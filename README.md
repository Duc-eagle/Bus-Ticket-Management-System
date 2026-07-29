# 🚌 Bus Ticket Management System (Hệ thống Quản lý và Đặt Vé Xe Khách)

Dự án quản lý bến xe và đặt vé xe khách trực tuyến được xây dựng bằng **Laravel** (Backend) kết hợp với **React** & **Tailwind CSS** (Frontend), sử dụng công cụ build **Vite**.

Dự án này được tối ưu hóa cấu hình môi trường và cơ sở dữ liệu mẫu để phục vụ quá trình chạy thử nghiệm nhanh chóng (chỉ mất vài phút thiết lập).

---

## 🛠️ Công nghệ sử dụng
- **Backend:** Laravel 11.x, PHP >= 8.2
- **Frontend:** React 19.x, Tailwind CSS v4
- **Database:** MySQL
- **Build Tool:** Vite

---

## 📋 Yêu cầu hệ thống (Prerequisites)
Đảm bảo máy tính của bạn đã được cài đặt các phần mềm sau:
- **PHP** >= 8.2
- **Composer** (Quản lý thư viện PHP)
- **Node.js** >= 18.x & **NPM**
- **MySQL Server** (Ví dụ: XAMPP, Laragon, hoặc Docker)

---

## 🚀 Hướng dẫn cài đặt và thiết lập nhanh (Setup Guide)

Hãy thực hiện tuần tự các bước dưới đây để khởi chạy dự án trên môi trường local:

### Bước 1: Clone dự án về máy
Mở terminal và chạy lệnh:
```bash
git clone <url-kho-luu-tru-cua-ban>
cd busTicket
```

### Bước 2: Cài đặt các thư viện PHP (Composer)
Cài đặt toàn bộ các packages phụ thuộc của Laravel:
```bash
composer install
```

### Bước 3: Cài đặt các thư viện Javascript (NPM)
Cài đặt các packages cần thiết cho React và Tailwind CSS:
```bash
npm install
```

### Bước 4: Thiết lập file cấu hình môi trường `.env`
1. Tạo file `.env` từ file mẫu `.env.example`:
   ```bash
   cp .env.example .env
   ```
   *(Nếu trên Windows PowerShell, sử dụng lệnh: `copy .env.example .env`)*

2. Mở file `.env` vừa tạo và cập nhật thông tin kết nối Cơ sở dữ liệu (Database) phù hợp với máy của bạn:
   ```ini
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=bus_reservation_db # Tên database của bạn
   DB_USERNAME=root               # Username DB (Mặc định XAMPP là root)
   DB_PASSWORD=                   # Password DB (Mặc định XAMPP để trống)
   ```

3. Khởi tạo khóa bảo mật của ứng dụng (`APP_KEY`):
   ```bash
   php artisan key_generate
   ```

### Bước 5: Tạo cơ sở dữ liệu & Sinh dữ liệu mẫu (Database Migration & Seeding)
Tạo một database trống có tên trùng khớp với `DB_DATABASE` trong file `.env` của bạn (ví dụ: `bus_reservation_db`). Sau đó chạy lệnh sau để thiết lập bảng và chèn dữ liệu mẫu (Admin, Nhân viên, Khách hàng, Tuyến đường, Xe khách, Chuyến đi):
```bash
php artisan migrate:fresh --seed
```

### Bước 6: Liên kết thư mục Storage để hiển thị hình ảnh
Tạo link liên kết giữa thư mục lưu trữ file và thư mục public để hiển thị ảnh xe khách/tuyến đường:
```bash
php artisan storage:link
```

---

## 🏃 Khởi chạy dự án (Running locally)

Bạn cần chạy đồng thời cả máy chủ Laravel (Backend) và server Vite (Frontend) để dự án hoạt động đầy đủ:

1. **Khởi chạy Laravel server (Terminal 1):**
   ```bash
   php artisan serve
   ```
   *Mặc định hệ thống sẽ chạy tại địa chỉ: [http://127.0.0.1:8000](http://127.0.0.1:8000)*

2. **Khởi chạy Vite dev server cho React (Terminal 2):**
   ```bash
   npm run dev
   ```

Mở trình duyệt và truy cập vào [http://127.0.0.1:8000](http://127.0.0.1:8000) để trải nghiệm ứng dụng.

---

## 🔐 Tài khoản dùng thử (Test Accounts)

Hệ thống đã được thiết lập sẵn 3 tài khoản tương ứng với 3 phân quyền (roles) sau khi bạn chạy lệnh `--seed` ở **Bước 5**:

| Vai trò (Role) | Email đăng nhập | Mật khẩu mặc định | Ghi chú |
| :--- | :--- | :--- | :--- |
| **Admin** | `admin@gmail.com` | `password` | Quản lý toàn bộ hệ thống (Bến xe, Tuyến đường, Xe khách, Chuyến đi, Vé) |
| **Staff** | `staff@gmail.com` | `password` | Nhân viên bán vé và quản lý nội dung |
| **Customer** | `customer@gmail.com` | `password` | Khách hàng thực hiện tìm kiếm và đặt vé trực tuyến |

---

## 🌟 Các chức năng cốt lõi của dự án
1. **Tìm kiếm chuyến xe trực quan:** Tìm kiếm chuyến xe theo điểm đi, điểm đến, ngày đi.
2. **Chọn ghế ngồi thời gian thực:** Hiển thị sơ đồ ghế trống/đã đặt trực quan bằng React.
3. **Quản trị hệ thống (Admin Dashboard):**
   - Quản lý tỉnh thành, bến xe, tuyến đường chạy.
   - Quản lý xe khách, thông tin số ghế và trạng thái hoạt động.
   - Thiết lập chuyến đi và theo dõi doanh thu bán vé.
4. **Hệ thống hóa đơn và lịch sử đặt vé:** Khách hàng có thể kiểm tra danh sách vé đã mua, trạng thái thanh toán và thông tin chi tiết.
