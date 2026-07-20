import React, { useRef, useState } from 'react';
import { Canvas, useFrame } from '@react-three/fiber';
import { OrbitControls, Text } from '@react-three/drei';
import { a, useSpring } from '@react-spring/three';

function Chair({ x, z, rotation, occupied }) {
  const ref = useRef();
  const props = useSpring({
    scale: occupied ? 1.05 : 1,
    color: occupied ? '#f97316' : '#334155',
    config: { mass: 1, tension: 180, friction: 18 },
  });

  useFrame(() => {
    if (ref.current) ref.current.rotation.y = rotation;
  });

  return (
    <a.mesh ref={ref} position={[x, 0.25, z]} scale={props.scale} castShadow>
      <boxGeometry args={[0.6, 0.5, 0.6]} />
      <a.meshStandardMaterial color={props.color} metalness={0.2} roughness={0.6} />
    </a.mesh>
  );
}

function Table({ onPointerOver, onPointerOut, onClick }) {
  const ref = useRef();
  const [hover, setHover] = useState(false);
  const props = useSpring({ scale: hover ? 1.02 : 1, color: hover ? '#06b6d4' : '#0ea5e9' });

  return (
    <a.mesh
      ref={ref}
      position={[0, 0.3, 0]}
      scale={props.scale}
      onPointerOver={(e) => { e.stopPropagation(); setHover(true); onPointerOver && onPointerOver(); }}
      onPointerOut={(e) => { e.stopPropagation(); setHover(false); onPointerOut && onPointerOut(); }}
      onClick={(e) => { e.stopPropagation(); onClick && onClick(); }}
      castShadow
    >
      <cylinderGeometry args={[0.9, 0.9, 0.2, 32]} />
      <a.meshStandardMaterial color={props.color} metalness={0.3} roughness={0.4} />
    </a.mesh>
  );
}

export default function ThreeDemo() {
  // Simulate occupancy to reflect bookings (could be wired to real data later)
  const occupied = [true, false, false, true];

  return (
    <div style={{ width: '100%', height: '460px', borderRadius: 12, overflow: 'hidden' }}>
      <Canvas camera={{ position: [0, 2.5, 3.5], fov: 50 }} shadows>
        <ambientLight intensity={0.5} />
        <directionalLight position={[5, 5, 5]} intensity={0.9} castShadow />

        {/* floor */}
        <mesh rotation={[-Math.PI / 2, 0, 0]} receiveShadow>
          <planeGeometry args={[10, 10]} />
          <meshStandardMaterial color="#f8fafc" />
        </mesh>

        {/* table */}
        <Table />

        {/* four chairs around table */}
        <Chair x={-1.1} z={0} rotation={0} occupied={occupied[0]} />
        <Chair x={1.1} z={0} rotation={Math.PI} occupied={occupied[1]} />
        <Chair x={0} z={-1.1} rotation={Math.PI / 2} occupied={occupied[2]} />
        <Chair x={0} z={1.1} rotation={-Math.PI / 2} occupied={occupied[3]} />

        {/* room label */}
        <Text position={[0, 1.2, 0]} fontSize={0.18} color="#0f172a" anchorX="center" anchorY="middle">
          Ruang Rapat A
        </Text>

        <OrbitControls enablePan={false} autoRotate autoRotateSpeed={0.25} />
      </Canvas>
    </div>
  );
}
