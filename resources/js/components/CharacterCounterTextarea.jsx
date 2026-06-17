import React, { useState } from 'react';

export default function CharacterCounterTextarea() {
    // 1. Khai báo state 'text' để lưu nội dung người dùng gõ.
    // 'setText' là hàm dùng để cập nhật giá trị cho 'text'.
    // Giá trị khởi tạo ban đầu là chuỗi rỗng ''.
    const [text, setText] = useState('');

    // Đặt giới hạn ký tự tối đa
    const MAX_LENGTH = 200;

    // 2. Hàm xử lý sự kiện khi người dùng gõ phím
    const handleChange = (event) => {
        // Lấy giá trị hiện tại trong ô input
        const value = event.target.value;
        
        // Gọi hàm setText để báo cho React biết dữ liệu đã thay đổi.
        // Ngay khi setText chạy, React sẽ tự động vẽ lại (re-render) phần HTML bên dưới.
        setText(value);
    };

    // 3. Tính toán trạng thái: Độ dài hiện tại có vượt giới hạn không? (Trả về true/false)
    const isOverLimit = text.length > MAX_LENGTH;

    return (
        <div>
            {/* Ghi chú: Vẫn giữ name="address" để khi bấm "Lưu", Laravel Controller vẫn nhận được biến $request->address bình thường */}
            <textarea 
                name="address" 
                className={`form-control bg-light ${isOverLimit ? 'is-invalid' : ''}`} 
                rows="3" 
                placeholder="Nhập địa chỉ bến xe..."
                value={text}            // Giá trị của ô text được trói chặt (bind) vào biến state
                onChange={handleChange} // Bắt sự kiện mỗi khi người dùng gõ phím
                required
            ></textarea>
            
            {/* Hiển thị số đếm. Toán tử {} dùng để in biến Javascript ra HTML */}
            {/* Nếu isOverLimit là true, thêm class 'text-danger' của Bootstrap để chữ hóa đỏ */}
            <div className={`mt-1 text-end small ${isOverLimit ? 'text-danger fw-bold' : 'text-muted'}`}>
                {text.length} / {MAX_LENGTH} ký tự
            </div>
        </div>
    );
}
