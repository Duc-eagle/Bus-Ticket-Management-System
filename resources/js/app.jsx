import './bootstrap';
import React from 'react';
import { createRoot } from 'react-dom/client';

// Tạo một Component cơ bản
function HelloWorld() {
    return (
        <div style={{ padding: '20px', backgroundColor: '#eef2f3', borderRadius: '8px' }}>
            <h3 style={{ color: '#ff4b4b' }}>🚀 Xin chào, React đã hoạt động bên trong Laravel!</h3>
            <p>Bây giờ bạn có thể viết code UI phức tạp ở đây.</p>
        </div>
    );
}

// Tìm thẻ div có id="react-app" trên giao diện và nhúng Component vào đó
const rootElement = document.getElementById('react-app');
if (rootElement) {
    const root = createRoot(rootElement);
    root.render(<HelloWorld />);
}

// ==========================================
// TÍCH HỢP CHARACTER COUNTER COMPONENT
// ==========================================
import CharacterCounterTextarea from './components/CharacterCounterTextarea';

// 1. Tìm thẻ div "chờ sẵn" có id là 'character-counter-root' trên giao diện HTML
const characterRootElement = document.getElementById('character-counter-root');

// 2. Nếu trang hiện tại (ví dụ trang Tạo Bến Xe) có chứa thẻ div đó, ta mới bơm React vào
if (characterRootElement) {
    const root = createRoot(characterRootElement);
    root.render(<CharacterCounterTextarea />);
}
