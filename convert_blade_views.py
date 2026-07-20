import pathlib
import re

root = pathlib.Path('resources/views')
files = [p for p in root.rglob('*.blade.php') if 'layouts' not in p.parts]

for path in files:
    text = path.read_text('utf-8')
    top_php = ''
    m = re.match(r'^(@php[\s\S]*?@endphp)\s*\n', text)
    if m:
        top_php = m.group(1).strip() + '\n\n'
        text = text[m.end():]

    title_match = re.search(r'<title>(.*?)</title>', text, re.S)
    title = title_match.group(1).strip() if title_match else None

    body_class = None
    body_match = re.search(r'<body([^>]*)>', text, re.S)
    if body_match:
        m2 = re.search(r'class\s*=\s*"([^"]*)"', body_match.group(1))
        if m2:
            body_class = m2.group(1).strip()

    header_match = re.search(r'(<header[\s\S]*?</header>)', text, re.S)
    header = header_match.group(1).strip() if header_match else None

    scripts = re.findall(r'(<script[\s\S]*?</script>)', text, re.S)

    text = re.sub(r'^[\s\S]*?<body[^>]*>', '', text, count=1)
    text = re.sub(r'</body>\s*</html>\s*$', '', text, flags=re.S)

    if header:
        text = text.replace(header, '')
    for script in scripts:
        text = text.replace(script, '')

    content = text.strip()
    lines = []
    if top_php:
        lines.append(top_php)
    lines.append("@extends('layouts.app')")
    if title:
        lines.append("@section('title', '{}')".format(title.replace("'", "\\'")))
    if body_class:
        lines.append("@section('body-class', '{}')".format(body_class.replace("'", "\\'")))
    if header:
        lines.append("@section('header')")
        lines.append(header)
        lines.append("@endsection")
    lines.append("@section('page-content')")
    lines.append(content)
    lines.append("@endsection")
    if scripts:
        lines.append("@push('scripts')")
        lines.extend(scripts)
        lines.append("@endpush")

    path.write_text('\n\n'.join(lines).strip() + '\n', 'utf-8')
    print('updated', path)
