import json
import sys

file_path = "arealaptop.online-20260305T083106.json"
out_path = "lighthouse_analysis_raw.md"
try:
    with open(file_path, 'r', encoding='utf-8') as f:
        data = json.load(f)
except Exception as e:
    sys.exit(1)

categories = data.get('categories', {})
audits = data.get('audits', {})

with open(out_path, 'w', encoding='utf-8') as out:
    out.write("# Lighthouse Analysis Raw Data\n\n")
    out.write("## Categories\n")
    for cat_id, cat_data in categories.items():
        score = cat_data.get('score', 0)
        score = int(score * 100) if score is not None else 'N/A'
        out.write(f"- **{cat_data.get('title')}**: {score}\n")

    out.write("\n## Issues by Category\n")
    for cat_id, cat_data in categories.items():
        out.write(f"\n### {cat_data.get('title')}\n")
        audit_refs = cat_data.get('auditRefs', [])
        for ref in audit_refs:
            if ref.get('weight', 0) > 0 or cat_id == 'accessibility':
                audit_id = ref.get('id')
                audit = audits.get(audit_id, {})
                score = audit.get('score')
                
                # Check if it failed
                if score is not None and score < 1:
                    title = audit.get('title')
                    desc = audit.get('description')
                    display_value = audit.get('displayValue', '')
                    weight = ref.get('weight')
                    out.write(f"#### Audit: {title} ({audit_id})\n")
                    out.write(f"- **Score**: {score} | **Weight**: {weight}\n")
                    if display_value:
                        out.write(f"- **Value**: {display_value}\n")
                    out.write(f"- **Description**: {desc}\n\n")

    # Let's also grab some key performance metrics (even if score >= 1 or no score)
    out.write("\n## Key Metrics\n")
    metrics = ['first-contentful-paint', 'largest-contentful-paint', 'total-blocking-time', 'cumulative-layout-shift', 'speed-index', 'interactive']
    for m in metrics:
        audit = audits.get(m, {})
        title = audit.get('title')
        display_value = audit.get('displayValue', '')
        score = audit.get('score', 0)
        out.write(f"- **{title}**: {display_value} (Score: {score})\n")

