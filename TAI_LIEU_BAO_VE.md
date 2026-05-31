# TÀI LIỆU CHUẨN BỊ BẢO VỆ ĐỒ ÁN (PROJECT DEFENSE GUIDE)

> **Lời khuyên từ Tech Lead:** Hãy đọc kỹ và hiểu rõ bản chất của các mục "Kiến thức cốt lõi". Giảng viên không chỉ quan tâm hệ thống của bạn làm được gì, mà họ quan tâm **tại sao** bạn lại giải quyết vấn đề theo cách đó. Hãy trả lời một cách tự tin, ngắn gọn và đi thẳng vào vấn đề kỹ thuật!

## 1. Tổng quan hệ thống (System Overview & Tech Stack)

**Giới thiệu dự án:**
Đây là một **Hệ thống Đặt vé Xe khách Toàn diện (Comprehensive Bus Ticket Reservation System)**. Hệ thống cho phép hành khách tìm kiếm chuyến đi, chọn ghế trực quan, và thanh toán vé an toàn. Đồng thời, hệ thống cung cấp một trang quản trị (Admin Dashboard) hiện đại để nhà xe theo dõi doanh thu, thống kê hiệu suất tuyến đường và quản lý vận hành.

**Tech Stack (Công nghệ sử dụng):**
*   **Backend Framework:** Laravel 11 (PHP) - Xử lý routing, business logic, ORM (Eloquent), và bảo mật.
*   **Cơ sở dữ liệu (Database):** MySQL - Cơ sở dữ liệu quan hệ lưu trữ dữ liệu người dùng, chuyến xe, vé.
*   **Frontend UI:** HTML5, CSS3, Bootstrap 5 - Đảm bảo giao diện hiện đại (Modern UI), Responsive (tương thích mọi thiết bị di động/desktop).
*   **Frontend Logic:** Vanilla JavaScript & Fetch API - Xử lý tương tác bất đồng bộ (chọn ghế động) mà không cần tải lại trang.

---

## 2. Danh sách Chức năng (Feature Matrix)

### Dành cho Khách hàng (Customer Role):
1.  **Tìm kiếm chuyến xe:** Tìm kiếm lộ trình linh hoạt dựa trên điểm đi, điểm đến và ngày khởi hành.
2.  **Chọn ghế trực quan (Seat Map):** Hiển thị sơ đồ ghế theo thời gian thực bằng Modal; vô hiệu hóa các ghế đã có người đặt.
3.  **Thanh toán an toàn (Secure Checkout):** Quy trình đặt vé được bảo vệ nghiêm ngặt ở backend.
4.  **Lịch sử đặt vé (Booking History):** Quản lý hồ sơ cá nhân và theo dõi trạng thái các vé đã mua thông qua Customer Portal.

### Dành cho Quản trị viên (Admin Role):
1.  **Dashboard Analytics (Thống kê theo thời gian thực):** Báo cáo doanh thu theo ngày/tháng, và thống kê các tuyến xe chạy nhiều/ít nhất thông qua giao diện trực quan.
2.  **Quản lý Vé (Ticket Management):** Quản lý trạng thái vé (Chờ thanh toán, Đã thanh toán, Hoàn thành, Đã hủy) nhanh chóng thông qua form Dropdown tích hợp.
3.  **Tạo Vé thủ công (Manual Booking):** Đặt vé hộ khách qua điện thoại với luồng chọn ghế thông minh (AJAX) giống hệt frontend.
4.  **Quản lý danh mục:** CRUD Tuyến xe (Routes), Chuyến xe (Trips), Xe (Buses) và Bến xe (Stations).

---

## 3. Kiến thức cốt lõi & Điểm nhấn Kỹ thuật (Key Technical Highlights)
*(Đây là phần quan trọng nhất để lấy điểm cao)*

**1. Database Transactions & Pessimistic Locking (`lockForUpdate`)**
*   **Giải thích:** Khi khách hàng bấm "Thanh toán", hệ thống sử dụng `DB::transaction()` kết hợp với khóa bi quan `lockForUpdate()`.
*   **Tại sao sử dụng?** Nhằm giải quyết bài toán **Race Condition (Xung đột đồng thời)**. Nếu có 2 người cùng click chọn mua ghế A1 ở đúng một phần nghìn giây, `lockForUpdate()` sẽ khóa dòng dữ liệu của ghế đó lại trong Database. Người click trước sẽ được hệ thống xử lý, người thứ hai sẽ phải đợi và nhận được thông báo "Ghế đã được đặt" thay vì hệ thống bán nhầm 1 ghế cho 2 người (Double-booking).

**2. Eager Loading (`with()`, `withCount()`) để giải quyết N+1 Query Problem**
*   **Giải thích:** Trong trang Lịch sử đặt vé hoặc Admin Dashboard, nếu dùng vòng lặp `foreach` để gọi dữ liệu liên quan (VD: Lấy thông tin Tuyến đường của từng Chuyến xe), ORM mặc định sẽ gọi cơ sở dữ liệu N lần. Bằng cách dùng `with('user', 'trip')`, Eloquent sẽ tải trước (Eager Load) toàn bộ dữ liệu liên quan chỉ bằng 2 câu truy vấn duy nhất.
*   **Tại sao quan trọng?** Nó giúp tối ưu hóa hiệu năng (Performance Optimization) cực kỳ lớn. Nếu có 100 vé, thay vì chạy 101 câu query làm sập server, hệ thống chỉ chạy đúng 2 câu.

**3. Tương tác bất đồng bộ với AJAX & Fetch API**
*   **Giải thích:** Ở chức năng Tạo Vé của Admin, khi chọn "Chuyến đi", một đoạn mã JavaScript (`fetch`) sẽ ngầm gửi request đến API `/api/trips/{id}/seats`. Hệ thống trả về danh sách ghế trống định dạng JSON, sau đó JS cập nhật ngay lập tức thẻ `<select>` chứa các ghế.
*   **Tại sao sử dụng?** Tăng trải nghiệm người dùng (UX). Người dùng không phải chờ tải lại toàn bộ trang web chỉ để lấy dữ liệu mới.

**4. Bảo mật toàn vẹn dữ liệu (Data Integrity & Backend Validation)**
*   **Giải thích:** Tổng số tiền (Total Price) không bao giờ được tin tưởng gửi từ Frontend (`<input>`). Thay vào đó, Backend luôn tự lấy giá trị `$trip->base_price` từ Database để xử lý thanh toán.
*   **Tại sao sử dụng?** Đề phòng Hacker hoặc người dùng F12 (Inspect Element) sửa giá vé trên HTML từ `500,000đ` thành `0đ`. Mọi logic liên quan đến tiền bạc **bắt buộc** phải được chốt ở Backend.

---

## 4. Cấu trúc Database (Database Schema Highlights)

Kiến trúc cơ sở dữ liệu được thiết kế chuẩn hóa để đảm bảo tính nhất quán:
*   **User (1 - N) Ticket:** Một khách hàng có thể sở hữu nhiều vé.
*   **Route (1 - N) Trip:** Một tuyến đường (VD: Hà Nội - Sapa) sẽ có nhiều Chuyến đi ở các khung giờ khác nhau.
*   **Trip (1 - N) Ticket:** Một chuyến đi sẽ chứa nhiều vé (tương ứng với số hành khách).
*   **Seat (1 - N) Ticket:** Một ghế cụ thể thuộc về một chiếc xe (`bus_id`). Ghế đó được map với Ticket để định danh vị trí khách ngồi.
*   **Quy trình tìm ghế trống:** Hệ thống kiểm tra bằng logic query: Tìm tất cả các ghế thuộc về `bus_id` của chuyến đi, sau đó loại trừ (`whereNotIn`) các ghế đã nằm trong bảng `tickets` có trạng thái là `paid`, `confirmed`, hoặc `pending_payment`.

---

## 5. Câu hỏi Phản biện (Mock Defense Q&A)

**Hỏi 1: Tại sao em lại dùng `lockForUpdate()` thay vì chỉ dùng `DB::transaction()`?**
> **Trả lời:** Dạ, `DB::transaction()` chỉ đảm bảo tính toàn vẹn dữ liệu (tất cả cùng thành công hoặc cùng thất bại). Nó không ngăn được việc 2 request đọc dữ liệu cùng một lúc. `lockForUpdate()` là Pessimistic Locking (Khóa bi quan), nó khóa cứng bản ghi đó ở mức Database (Row-level lock) cho đến khi transaction hoàn thành. Điều này ngăn chặn triệt để lỗi bán trùng 1 ghế cho 2 người (Double-booking).

**Hỏi 2: Hàm `withCount()` trong Controller có tác dụng gì? Em không dùng vòng lặp đếm được à?**
> **Trả lời:** Nếu em dùng vòng lặp PHP để đếm, em sẽ phải kéo toàn bộ hàng ngàn bản ghi từ database về RAM máy chủ, gây chậm hệ thống. Dùng `withCount()` sẽ đẩy việc đếm xuống SQL Server. DB xử lý cực kỳ nhanh và chỉ trả về 1 con số duy nhất cho ứng dụng, giúp tối ưu hiệu năng tối đa.

**Hỏi 3: Trong chức năng tạo vé, điều gì đảm bảo mã vé (Ticket Code) như `MB-A7X9B2` là duy nhất và không bị trùng lặp?**
> **Trả lời:** Dạ em sử dụng một vòng lặp `do { ... } while ($exists)` ở Backend. Hệ thống sinh ngẫu nhiên một mã 6 ký tự. Trước khi lưu, nó sẽ query database xem mã này tồn tại chưa. Chỉ khi mã đó hoàn toàn "sạch", vòng lặp mới dừng và cho phép chèn dữ liệu.

**Hỏi 4: Lỡ đang trong quá trình ghi dữ liệu vé mà mạng bị rớt hoặc mất điện server, database có bị rác không?**
> **Trả lời:** Dạ không ạ. Nhờ em bao bọc toàn bộ luồng tạo vé bên trong `DB::transaction()`. Nếu có bất kỳ lỗi nào xảy ra giữa chừng, lệnh `DB::rollBack()` sẽ được kích hoạt. Mọi lệnh `insert` trước đó sẽ bị xóa sạch, trả database về trạng thái nguyên vẹn.

**Hỏi 5: Tại sao mật khẩu lại hiển thị trong database là một chuỗi mã hóa dài thay vì mật khẩu thực?**
> **Trả lời:** Dạ đó là nguyên tắc bảo mật cơ bản. Em sử dụng thuật toán mã hóa BCRYPT của Laravel. Đây là hàm băm một chiều (One-way hashing). Kể cả khi hacker đánh cắp được database thì cũng không thể dịch ngược chuỗi đó ra mật khẩu gốc để đánh cắp tài khoản của khách hàng.
