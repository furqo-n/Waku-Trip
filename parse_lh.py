import json
import sys

file_path = "arealaptop.online-20260305T083106.json"
try:
    with open(file_path, 'r', encoding='utf-8') as f:
        data = json.load(f)
except Exception as e:
    print(f"Error reading {file_path}: {e}")
    sys.exit(1)

categories = data.get('categories', {})
audits = data.get('audits', {})

print("=== CATEGORIES SCORE ===")
for cat_id, cat_data in categories.items():
    score = cat_data.get('score', 0)
    score = int(score * 100) if score is not None else 'N/A'
    print(f"{cat_data.get('title')}: {score}")

print("\n=== ISSUES (Score < 1) ===")
for cat_id, cat_data in categories.items():
    print(f"\n--- Category: {cat_data.get('title')} ---")
    audit_refs = cat_data.get('auditRefs', [])
    for ref in audit_refs:
        if ref.get('weight', 0) > 0 or cat_id == 'accessibility':
            # Note: weight 0 often means it's manual or informative, but sometimes we want all failures
            # Need to get audit details
            audit_id = ref.get('id')
            audit = audits.get(audit_id, {})
            score = audit.get('score')
            if score is not None and score < 1:
                # print details
                title = audit.get('title')
                desc = audit.get('description')
                display_value = audit.get('displayValue', '')
                print(f"Audit: {audit_id}")
                print(f"Title: {title}")
                print(f"Score: {score} | Weight: {ref.get('weight')}")
                if display_value:
                    print(f"Display Value: {display_value}")
                
                # Check for specific severity in details (like axe for a11y)
                details = audit.get('details', {})
                if 'items' in details:
                    items = details['items']
                    if items and isinstance(items, list) and isinstance(items[0], dict):
                        # print first few issues
                        for item in items[:3]:
                            node_msg = item.get('node', {}).get('snippet', '') if isinstance(item.get('node'), dict) else ''
                            print(f" - {node_msg} | {item}")
                print()
