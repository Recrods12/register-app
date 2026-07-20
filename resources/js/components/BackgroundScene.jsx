import React, { useRef } from 'react';
import { Canvas, useFrame } from '@react-three/fiber';
import { MeshWobbleMaterial, OrbitControls } from '@react-three/drei';

function FloatingSphere({ pos, speed = 0.5, scale = 1, color = '#7dd3fc' }) {
  const ref = useRef();
  useFrame((state) => {
    const t = state.clock.getElapsedTime() * speed;
    ref.current.position.y = pos[1] + Math.sin(t) * 0.12;
    ref.current.rotation.y = t * 0.2;
  });
  return (
    <mesh ref={ref} position={pos} scale={scale}>
      <sphereGeometry args={[0.6, 32, 32]} />
      <MeshWobbleMaterial factor={0.6} speed={1.2} color={color} roughness={0.7} metalness={0.05} />
    </mesh>
  );
}

export default function BackgroundScene() {
  return (
    <Canvas
      camera={{ position: [0, 1.5, 6], fov: 50 }}
      gl={{ antialias: true }}
      style={{ width: '100%', height: '100%' }}
      pixelRatio={Math.min(1.2, window.devicePixelRatio || 1)}
    >
      <ambientLight intensity={0.6} />
      <directionalLight position={[5, 5, 5]} intensity={0.6} />

      <FloatingSphere pos={[-1.8, 0.6, -1]} scale={1.4} color="#60a5fa" />
      <FloatingSphere pos={[1.6, 0.8, -0.5]} scale={1.0} color="#7dd3fc" />
      <FloatingSphere pos={[0.2, 0.5, 0.8]} scale={0.9} color="#22c55e" />

      {/* subtle controls for debugging; pointer-events disabled by parent div */}
      <OrbitControls enablePan={false} enableZoom={false} enableRotate={false} />
    </Canvas>
  );
}
