f = open('resources/views/index.blade.php', 'r', encoding='utf-8')
c = f.read()
f.close()

old = '<!--===============Header Menu Area =================-->'
new = old + '\r\n\r\n    <main id="main-content">'
c2 = c.replace(old, new, 1)

f = open('resources/views/index.blade.php', 'w', encoding='utf-8')
f.write(c2)
f.close()

print('Done' if old in c else 'NOT FOUND')
