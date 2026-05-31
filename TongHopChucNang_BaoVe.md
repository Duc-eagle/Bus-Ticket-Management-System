# Tổng Hợp Chức Năng Hệ Thống My Bus (Chuẩn Bị Bảo Vệ)

Hệ thống quản lý đặt vé xe My Bus được thiết kế với kiến trúc phân quyền chặt chẽ, tách biệt rõ ràng giữa quản trị viên, nhân viên và khách hàng. Dưới đây là bảng tổng hợp chi tiết toàn bộ chức năng của hệ thống.

## 1. Quản Trị Viên (Admin)
*Guard: `admin` | Role: `admin`*

### Basic CRUD (Chức năng cơ bản)
- Quản lý Tỉnh/Thành Phố (Provinces)
- Quản lý Bến Xe (Bus Stations)
- Quản lý Tuyến Đường (Routes)
- Quản lý Xe Khách (Buses)
- Quản lý Chuyến Đi (Trips)
- Quản lý Ghế Ngồi (Seats)
- Quản lý Người Dùng (Users)
- Quản lý Phương Thức Thanh Toán (Payment Methods)
- Quản lý Đơn Vé (Tickets)

### Advanced Logic (Chức năng nâng cao)
- **Session Isolation (Cách ly phiên đăng nhập):** Xây dựng riêng biệt Guard `admin` trong `config/auth.php` sử dụng chung bảng `users` để ngăn chặn triệt để tình trạng chồng chéo session giữa khách hàng và quản trị viên.
- **Role-Based Access Control (RBAC):** Xây dựng Middleware `CheckRole` để kiểm tra phân quyền động, giới hạn quyền truy cập ở cấp độ Route đối với từng chức năng cụ thể của hệ thống.
- **UI Sequence Numbering & Auto-Increment DB Reset:** Xử lý triệt để tình trạng đứt gãy số thứ tự bằng cách dùng `$loop->iteration` kết hợp thuật toán phân trang trên Blade, đồng thời chèn lệnh `DB::statement('ALTER TABLE... AUTO_INCREMENT=1')` vào Controller để reset tự động ID database khi xóa bản ghi.
- **Revenue Drill-Down Analytics:** Biểu đồ doanh thu tương tác cao bằng cách bắt sự kiện `onClick` của thư viện Chart.js trong `dashboard.blade.php`, truyền tham số ngày/tháng qua URL để Query Builder trong `TicketController` tự động lọc ra danh sách vé tương ứng.
- **Secure Profile Update:** Chức năng đổi mật khẩu an toàn sử dụng `Hash::check()` để xác thực mật khẩu cũ trước khi cập nhật mã băm mật khẩu mới vào cơ sở dữ liệu.

---

## 2. Nhân Viên Bán Vé (Ticket Staff)
*Guard: `admin` | Role: `author`*

### Basic CRUD (Chức năng cơ bản)
- Quản lý Đơn Vé (Xem, tạo, cập nhật trạng thái, hủy vé).
- Xem thống kê doanh thu cơ bản trên Dashboard.

### Advanced Logic (Chức năng nâng cao)
- **Restricted Routing (Giới hạn truy cập):** Middleware `CheckRole` tự động chặn và trả về lỗi 403 (Unauthorized) nếu nhân viên cố tình truy cập vào các module nhạy cảm (như Quản lý người dùng, Tuyến xe) bằng cách nhập trực tiếp URL.

---

## 3. Khách Hàng (Customer)
*Guard: `web` | Role: `customer`*

### Basic CRUD (Chức năng cơ bản)
- Đăng ký và Đăng nhập (Auth).
- Xem lịch sử mua vé và trạng thái đơn hàng.
- Hủy vé (đối với vé chưa thanh toán).

### Advanced Logic (Chức năng nâng cao)
- **Email-parsing Username Generation:** Logic tự động cắt chuỗi email trước ký tự `@` để tạo ra `username` duy nhất trong quá trình khách hàng đăng ký tài khoản.
- **Dynamic Booking Flow:** Quy trình đặt vé mượt mà sử dụng AJAX `fetch()` API để tải danh sách ghế trống theo chuyến đi, kết hợp JavaScript xử lý DOM để tự động hiển thị giá vé tức thời mà không cần reload trang.
- **Inventory Management (Quản lý kho ghế):** Backend xác minh tính khả dụng của ghế bằng cách truy vấn bảng `tickets` với điều kiện các trạng thái `confirmed, paid, pending_payment`, đảm bảo ghế đã đặt không bao giờ bị hiển thị cho khách khác.
- **The "Lazy Expiration" Trap (Bẫy hết hạn tự động):** Xử lý luồng giải phóng ghế bằng thuật toán Lazy-delete, tự động quét và hard-delete các vé `pending_payment` sử dụng phương thức thanh toán ZaloPay nếu thời gian tạo (`created_at`) đã vượt quá 20 phút mỗi khi khách hàng truy cập vào lịch sử vé.
