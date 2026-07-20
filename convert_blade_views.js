const fs = require('fs').promises;
const path = require('path');

const root = path.join(process.cwd(), 'resources', 'views');
const layoutDir = path.join(root, 'layouts');

async function walk(dir) {
  const entries = await fs.readdir(dir, { withFileTypes: true });
  const files = [];
  for (const entry of entries) {
    const fullPath = path.join(dir, entry.name);
    if (entry.isDirectory()) {
      if (fullPath === layoutDir) continue;
      files.push(...await walk(fullPath));
    } else if (entry.isFile() && entry.name.endsWith('.blade.php')) {
      files.push(fullPath);
    }
  }
  return files;
}

function extractTopPhp(text) {
  const match = text.match(/^(@php[\s\S]*?@endphp)\s*\r?\n/);
  if (!match) return { topPhp: '', rest: text };
  return { topPhp: match[1].trim() + '\n\n', rest: text.slice(match[0].length) };
}

function extractTitle(text) {
  const match = text.match(/<title>([\s\S]*?)<\/title>/i);
  return match ? match[1].trim() : null;
}

function extractBodyClass(text) {
  const match = text.match(/<body([^>]*)>/i);
  if (!match) return null;
  const classMatch = match[1].match(/class\s*=\s*(["'])(.*?)\1/i);
  return classMatch ? classMatch[2].trim() : null;
}

function extractHeader(text) {
  const match = text.match(/<header[\s\S]*?<\/header>/i);
  return match ? match[0].trim() : null;
}

function extractScripts(text) {
  const scripts = [];
  const regex = /<script\b[^>]*>[\s\S]*?<\/script>/gi;
  let match;
  while ((match = regex.exec(text)) !== null) {
    scripts.push(match[0].trim());
  }
  return scripts;
}

function removeWrapper(text, header, scripts) {
  let result = text.replace(/^[\s\S]*?<body[^>]*>/i, '');
  result = result.replace(/<\/body>\s*<\/html>\s*$/i, '');
  if (header) {
    result = result.replace(header, '');
  }
  for (const script of scripts) {
    result = result.replace(script, '');
  }
  return result.trim();
}

function buildBlade({ topPhp, title, bodyClass, header, content, scripts }) {
  const lines = [];
  if (topPhp) lines.push(topPhp.trim());
  lines.push("@extends('layouts.app')");
  if (title) lines.push(`@section('title', '${title.replace(/'/g, "\\'")}')`);
  if (bodyClass) lines.push(`@section('body-class', '${bodyClass.replace(/'/g, "\\'")}')`);
  if (header) {
    lines.push('@section(\'header\')');
    lines.push(header);
    lines.push('@endsection');
  }
  lines.push('@section(\'page-content\')');
  lines.push(content);
  lines.push('@endsection');
  if (scripts.length > 0) {
    lines.push('@push(\'scripts\')');
    for (const script of scripts) {
      lines.push(script);
    }
    lines.push('@endpush');
  }
  return lines.join('\n\n').trim() + '\n';
}

(async function main() {
  const files = await walk(root);
  const updated = [];
  for (const file of files) {
    const relative = path.relative(process.cwd(), file);
    const text = await fs.readFile(file, 'utf-8');
    if (/extends\s*\(\s*['"]layouts\.app['"]\s*\)/.test(text)) {
      continue;
    }

    const { topPhp, rest } = extractTopPhp(text);
    const title = extractTitle(rest);
    const bodyClass = extractBodyClass(rest);
    const header = extractHeader(rest);
    const scripts = extractScripts(rest);
    const content = removeWrapper(rest, header, scripts);

    const newText = buildBlade({ topPhp, title, bodyClass, header, content, scripts });
    await fs.writeFile(file, newText, 'utf-8');
    updated.push(relative);
  }

  if (updated.length === 0) {
    console.log('No templates needed conversion.');
  } else {
    for (const file of updated) console.log('updated', file);
  }
})();
