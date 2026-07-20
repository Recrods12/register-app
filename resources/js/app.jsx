import React from 'react';
import { createRoot } from 'react-dom/client';
import ThreeDemo from './components/ThreeDemo';
import BackgroundScene from './components/BackgroundScene';

function mountPageDemo() {
  const el = document.getElementById('r3f-root');
  if (!el) return;
  const root = createRoot(el);
  root.render(<ThreeDemo />);
}

function mountGlobalBackground() {
  // create a global background mount if not present
  let bg = document.getElementById('r3f-bg-root');
  if (!bg) {
    bg = document.createElement('div');
    bg.id = 'r3f-bg-root';
    Object.assign(bg.style, {
      position: 'fixed',
      inset: '0',
      zIndex: '-1',
      width: '100%',
      height: '100%',
      pointerEvents: 'none',
      opacity: '0.85',
    });
    document.body.appendChild(bg);
  }

  const rootBg = createRoot(bg);
  rootBg.render(<BackgroundScene />);
}

// Mount on DOMContentLoaded so Blade can render server-side first
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', () => {
    mountGlobalBackground();
    mountPageDemo();
  });
} else {
  mountGlobalBackground();
  mountPageDemo();
}
