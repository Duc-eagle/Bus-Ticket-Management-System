# CLAUDE.md - My Bus Ticket Booking System Project Overview

## 1. Project Overview

**Project Name:** My Bus - Bus Ticket Booking System  
**Technology Stack:** Laravel (Blade) + Bootstrap 5.1.3 + Vanilla JavaScript  
**Database:** MySQL  
**Local Environment:** XAMPP (e:\xampp\htdocs\busTicket)  
**Project Status:** In Development - Booking System Phase  

**Primary Purpose:**
A comprehensive bus ticket booking platform allowing customers to search routes, view available trips (within 7-day window), select seats, manage their account, and complete transactions through multiple payment gateways.

---

## 2. Current Project Architecture

### Database Structure
- **Users Table:** Stores customer and admin accounts with authentication
- **Routes Table:** Bus routes with departure/arrival stations and trip relationships
- **Trips Table:** Individual trips with date, time, bus assignment, and pricing
- **Seats Table:** Seats assigned to buses with status tracking (available/booked)
- **Tickets Table:** Customer bookings linking users to seats and trips
- **PaymentMethods Table:** Payment gateway integration points
- **BusStations Table:** Bus terminal locations with province associations
- **Buses Table:** Fleet vehicles with capacity and bed configuration
- **Provinces Table:** Geographic regions for routing

### Models & Relationships
```
User → Tickets (hasMany)
Route → Trips (hasMany), Trips → Bus (belongsTo), Trips → Seats via Tickets
Bus → Seats (hasMany), Bus → Trips (hasMany)
PaymentMethod → Tickets (hasMany)
Province → BusStations (hasMany)
```

### Controllers (9 CRUD Controllers)
All controllers implement RESTful patterns with consistent auto-increment reset on delete:
- ProvinceController
- BusStationController  
- BusController
- RouteController (+ customerTrips method for route filtering)
- TripController (+ date validation)
- SeatController
- TicketController
- PaymentMethodController
- UserController (+ authentication methods)

---

## 3. Session Work Summary - What Was Completed This Session

### A. Route Navigation & Trip Filtering ✅ COMPLETE

**Implementation:**
- Created `RouteController::customerTrips()` method
- Query filters trips within 7-day window: `whereBetween('trip_date', [today, today+6days])`
- View: `resources/views/customer/route_trips.blade.php`
- Displays trips sorted by date and departure time
- Route parameter binding: `/customer/routes/{route}/trips`

**Result:** Clicking a route card now displays available trips for next 7 days

---

### B. Customer Account Management ✅ COMPLETE

**Features Implemented:**
1. **Account Information Page**
   - View: `resources/views/customer/account.blade.php`
   - Displays user details (name, email, phone, DOB, address)
   - Sidebar navigation with links to account sections

2. **Password Change Functionality**
   - View: `resources/views/customer/change-password.blade.php`
   - Routes:
     - `GET /customer/change-password` → `changePasswordView()`
     - `POST /customer/change-password` → `changePasswordProcess()`
   - Validation: min 8 characters
   - Security: Requires current password verification
   - Hash: Uses Laravel Hash::make() for storage

**Code Implementation (UserController):**
```php
public function changePasswordProcess(Request $request)
{
    $request->validate([
        'current_password' => 'required|current_password',
        'password' => 'required|min:8|confirmed',
    ]);
    
    auth()->user()->update(['password' => Hash::make($request->password)]);
    return redirect()->route('customer.account')->with('success', 'Mật khẩu đã được thay đổi');
}
```

**Result:** Customers can securely change passwords with validation

---

### C. Payment Methods Database Design ✅ STRUCTURE CONFIRMED

**Current Structure:**
- `method_name`: Unique identifier (ZaloPay, VNPay, Momo, etc.)
- `method_description`: User-friendly description

**Recommended Data (For Future Implementation):**
```
ZaloPay - "Thanh toán nhanh qua ứng dụng ZaloPay. Miễn phí chuyển tiền, an toàn 100%"
VNPay - "Thanh toán qua VNPay. Hỗ trợ thẻ tín dụng, thẻ ghi nợ, tài khoản ngân hàng"
Momo - "Thanh toán qua ứng dụng Momo. Nhanh chóng, bảo mật và tiện lợi"
Bank Transfer - "Chuyển khoản trực tiếp. Vui lòng liên hệ với chúng tôi để nhận thông tin tài khoản"
```

---

### D. Admin CRUD Enhancements - Auto-Increment Reset ✅ COMPLETE

**Implementation:**
- Applied to all 9 controllers
- Added DB facade import: `use Illuminate\Support\Facades\DB;`
- Logic: After deleting record, if table becomes empty, reset auto_increment to 1

**Pattern Applied:**
```php
public function destroy(ModelClass $model)
{
    $model->delete();
    
    // Reset auto_increment nếu không còn dữ liệu
    if (ModelClass::count() == 0) {
        DB::statement('ALTER TABLE table_name AUTO_INCREMENT = 1;');
    }
    
    return Redirect::route('index');
}
```

**Result:** Deleting all records and adding new ones starts ID from 1 (not from old max ID + 1)

---

### E. Trip Date Validation Refinement ✅ COMPLETE

**Changes:**
1. **Create (Adding New Trips):**
   - HTML: `input[type="date"]` with `min="{{ Carbon::today()->toDateString() }}"`
   - Controller: Validates `after_or_equal` today
   - **Result:** Can only add trips from today onwards

2. **Edit (Modifying Existing Trips):**
   - HTML: No min attribute
   - Controller: Only validates date format
   - **Result:** Can edit trips to any date (useful for fixing past bookings)

---

### F. Trip Display Redesign ✅ COMPLETE

**File:** `resources/views/customer/route_trips.blade.php`

**Layout Changes:**
- **3-Column Card Layout:** Image | Info | Price & Buttons
- **Time Display (Horizontal):** `09:40 ── 02h00 ── 11:40` (departure | duration | arrival)
- **Station Display (Side by Side):** Departure and arrival stations on same line
- **Buttons Section:**
  - "Thông tin chi tiết ▼" text with dropdown arrow
  - "Chọn chuyến" button (amber #fbbf24)
  - Single-line text (no wrapping)

**Responsive Design:**
- Desktop (>992px): 3-column layout maintained
- Tablet (768-992px): Proportional resizing
- Mobile (<768px): Card stacks vertically

---

### G. Seat Selection Modal UI ✅ UI COMPLETE (Backend Pending)

**File:** `resources/views/customer/route_trips.blade.php` (modal section added)

**Modal Structure:**
```
┌─────────────────────────────────────────┐
│ [Trip Name] [X Close]                   │
├──────────────┬──────────────────────────┤
│              │ Chuyến Xe: [Bus Info]    │
│  Seat Grid   │ Tuyến: [Route]           │
│  (30 seats)  │ Thời gian: [Time]        │
│              │ Giá: [Price]đ            │
│              ├──────────────────────────┤
│              │ Số vé: [−] [Input] [+]   │
│              │ Tổng cộng: [Total]đ      │
│              ├──────────────────────────┤
│              │ Legend:                  │
│              │ 🟢 Trống                 │
│              │ 🟠 Đang chọn              │
│              │ ⚫ Đã chọn                │
│              │ ⚪ Đã đặt                 │
├──────────────┴──────────────────────────┤
│ [Hủy bỏ] [ĐẶT VÉ]                      │
└─────────────────────────────────────────┘
```

**Features Implemented:**

1. **Seat Grid (30 seats):**
   - 2 rows of 15 seats each (H01-H15, H16-H30)
   - Color-coded states (green, red, gray)
   - Currently hardcoded: H05, H06 marked as booked

2. **Quantity Selector:**
   - Buttons: Decrement (−) and Increment (+) with bounds checking
   - Input field: Manual entry with min="1" max="8"

3. **Dynamic Price Calculation:**
   - Formula: `Total Price = Quantity × Base Price`
   - Updates in real-time
   - Format: Vietnamese currency (e.g., "310,000đ")

4. **JavaScript Functions:**
   - `openSeatModal(tripId)` - Opens modal, loads trip data
   - `closeSeatModal()` - Closes modal safely
   - `incrementQuantity()` - Increase tickets (max 8)
   - `decrementQuantity()` - Decrease tickets (min 1)
   - `updateTotalPrice()` - Recalculate based on quantity

5. **Trip Information Panel:**
   - Bus/Vehicle name
   - Route (from → to)
   - Departure time
   - Base price per seat
   - Available seats count

---

## 4. Project File Structure

### Views
```
resources/views/customer/
├── index.blade.php                    ← Homepage with routes list
├── route_trips.blade.php              ← Trip listing + seat modal (UPDATED)
├── change-password.blade.php          ← Password change form (NEW)
├── account.blade.php                  ← User account info
├── login.blade.php
├── register.blade.php
├── dashboard.blade.php

resources/views/admins/
├── [CRUD views for all models]
```

### Controllers
```
app/Http/Controllers/
├── UserController.php                 ← +changePassword methods (UPDATED)
├── TripController.php                 ← +date validation & auto-increment (UPDATED)
├── RouteController.php                ← +customerTrips method
├── ProvinceController.php             ← +auto-increment reset (UPDATED)
├── BusStationController.php           ← +auto-increment reset (UPDATED)
├── BusController.php                  ← +auto-increment reset (UPDATED)
├── SeatController.php                 ← +auto-increment reset (UPDATED)
├── TicketController.php               ← +auto-increment reset (UPDATED)
├── PaymentMethodController.php        ← +auto-increment reset (UPDATED)
```

### Routes
```
routes/web.php
├── GET  /customer/account                    → UserController::customerAccount()
├── GET  /customer/change-password            → UserController::changePasswordView()
├── POST /customer/change-password            → UserController::changePasswordProcess()
├── GET  /customer/routes/{route}/trips       → RouteController::customerTrips()
├── [Admin routes for all CRUD operations]
```

---

## 5. Technology Stack

### Frontend
- **Bootstrap 5.1.3:** Modal framework, responsive grid, components
- **Font Awesome 6.4.0:** Icons for UI elements
- **Vanilla JavaScript:** Modal control, quantity calculation, dynamic pricing
- **CSS Grid & Flexbox:** Layout for cards, seat grid, modal sections

### Backend
- **Laravel Framework:** Blade templating, Eloquent ORM, routing
- **PHP 8.x:** Request validation, data processing
- **Middleware:** Authentication checks on protected routes
- **Carbon:** Date manipulation for trip filtering

### Database
- **MySQL:** Relational data storage
- **Migrations:** Version-controlled schema (11 migration files)
- **Eloquent Models:** Type-hinted relationships
- **Timestamps:** created_at, updated_at tracking

### Security
- **Password Hashing:** Laravel Hash::make()
- **Current Password Validation:** `current_password` rule
- **CSRF Protection:** @csrf in forms
- **Input Validation:** Server-side rules in controllers
- **Role-Based Access:** customer, admin, staff roles

---

## 6. Pending Work / Next Steps

### Immediate Next (Phase 2)
- [ ] **Backend Integration for Seats:**
  - Query actual seats from database for selected trip
  - Replace hardcoded H05, H06 with real booked seat data
  - Show accurate available/booked count

- [ ] **Ticket Booking API:**
  - Create route: `POST /customer/book-ticket`
  - Accept: trip_id, selected_seats[], user_id
  - Validate: Check seat availability, prevent double-booking
  - Create ticket records and update seat status

- [ ] **Add Payment Method Data:**
  - Insert gateway data into payment_methods table
  - Add: ZaloPay, VNPay, Momo, Bank Transfer

### Medium-Term (Phase 3)
- [ ] **Payment Gateway Integration:**
  - ZaloPay API integration
  - VNPay API integration
  - Momo API integration
  - Payment callback handling

- [ ] **Ticket Confirmation:**
  - Confirmation email with booking details
  - PDF ticket generation
  - QR code for verification

### Long-Term (Phase 4+)
- [ ] **Advanced Features:**
  - Search form for finding trips (date range)
  - Return journey booking
  - Passenger information collection
  - Booking history and management
  - Cancellation and refund workflow

---

## 7. Key Code Patterns Used

### CRUD Pattern (All Controllers)
```php
public function index() { /* List with pagination */ }
public function create() { /* Show form */ }
public function store(Request $request) { /* Validate & Save */ }
public function edit(Model $model) { /* Show edit form */ }
public function update(Request $request, Model $model) { /* Validate & Update */ }
public function destroy(Model $model) 
{ 
    $model->delete();
    if (ModelClass::count() == 0) {
        DB::statement('ALTER TABLE table_name AUTO_INCREMENT = 1;');
    }
}
```

### Authentication Pattern
```php
if (!Auth::check() || Auth::user()->role !== 'customer') {
    return Redirect::route('customer.login');
}

if (Auth::attempt($request->only('email', 'password'))) {
    $user = Auth::user();
    if ($user->role === 'customer') {
        return Redirect::route('home');
    }
}
```

### Date Validation Pattern
```php
// Create: Enforce future dates
'trip_date' => 'required|date|after_or_equal:' . Carbon::today()->toDateString()

// Edit: Allow any valid date
'trip_date' => 'required|date'
```

### Modal JavaScript Pattern
```javascript
function openSeatModal(tripId) {
    document.getElementById('seatModal').style.display = 'block';
    // Load trip data...
}

function closeSeatModal() {
    document.getElementById('seatModal').style.display = 'none';
    // Reset form...
}

function updateTotalPrice() {
    const quantity = document.getElementById('quantityInput').value;
    const total = quantity * basePrice;
    document.getElementById('totalPrice').textContent = formatCurrency(total);
}
```

---

## 8. Known Issues & Resolutions

| Issue | Status | Solution |
|-------|--------|----------|
| Auto-increment not resetting on delete | ✅ RESOLVED | Added DB::statement() to reset AUTO_INCREMENT |
| Trip date allowing past dates | ✅ RESOLVED | Added min attribute + validation rules |
| Modal seat data hardcoded | ⚠️ IN PROGRESS | Query real seat data from database |
| Price currency formatting | ✅ RESOLVED | JavaScript `.toLocaleString('vi-VN')` + 'đ' |

---

## 9. Testing Checklist

- [x] Route detail page displays 7 days of trips
- [x] Trip filtering works correctly (today to +6 days)
- [x] Clicking route card navigates to trip list
- [x] Password change validates minimum 8 characters
- [x] Password change requires current password
- [x] Auto-increment resets when all records deleted
- [x] Trip date input prevents past dates on create
- [x] Trip date allows any date on edit
- [x] Trip cards display in 3-column layout
- [x] Time displays horizontally (departure ── duration ── arrival)
- [x] Stations display side-by-side
- [x] Modal opens when "Chọn chuyến" clicked
- [x] Seat grid renders 30 seats (2 rows × 15)
- [x] Quantity +/- buttons work correctly
- [x] Total price updates in real-time
- [x] Modal closes on X, Cancel, or outside click

---

## 10. Environment Configuration

**Local Setup:**
- **XAMPP Location:** `e:\xampp\htdocs\busTicket`
- **Database:** MySQL (via XAMPP)
- **Web Server:** Apache (via XAMPP)
- **PHP Version:** 8.x
- **Laravel Version:** 11.x

**Database Connection:**
- Host: localhost
- Username: root (default XAMPP)
- Password: (typically empty)
- Database Name: bus_ticket (assumed)

---

## 11. Design System

### Color Palette

| Element | Color | Hex | Usage |
|---------|-------|-----|-------|
| Primary | Blue | #0099ff | Links, highlights, primary actions |
| Accent | Orange | #ff5b2e | CTAs, search bar, warnings |
| Success | Amber | #fbbf24 | Book button, positive actions |
| Text Dark | Navy | #1f2430 | Primary text |
| Text Light | Gray | #667085 | Secondary text |
| Border | Light | #e3e8f0 | Card borders, dividers |
| Seat Available | Green | CSS custom | Available seats |
| Seat Selected | Red | CSS custom | User-selected seats |
| Seat Booked | Gray | CSS custom | Already booked seats |

### Typography
- **Font:** Bootstrap defaults (system fonts)
- **Sizes:** Bootstrap scale (h1-h6, body, small, etc.)
- **Weight:** Regular (400), Bold (700) for emphasis

### Components
- **Buttons:** Bootstrap btn classes with custom colors
- **Cards:** Bootstrap card component for trip display
- **Modal:** Bootstrap modal with custom content sections
- **Forms:** Bootstrap form-control with validation

---

## 12. Database Migrations Summary

**Total Migrations:** 11

1. `0001_01_01_000000_create_users_table.php` - User accounts
2. `0001_01_01_000001_create_cache_table.php` - Cache storage
3. `0001_01_01_000002_create_jobs_table.php` - Queue jobs
4. `0001_01_01_000003_create_provinces_table.php` - Provinces
5. `0001_01_01_000004_create_bus_stations_table.php` - Bus stations
6. `0001_01_01_000005_create_routes_table.php` - Routes
7. `0001_01_01_000007_create_buses_table.php` - Buses
8. `0001_01_01_000008_create_seats_table.php` - Seats
9. `0001_01_01_000009_create_trips_table.php` - Trips
10. `0001_01_01_000010_create_payment_methods_table.php` - Payment methods
11. `0001_01_01_000011_create_tickets_table.php` - Tickets

**Note:** Migration 6 is skipped (numbered as 0001_01_01_000007)

---

## 13. Document Information

**Created:** May 9, 2026 (Current Session)  
**Last Updated:** May 9, 2026  
**Created By:** AI Assistant (Claude Haiku 4.5)  
**Development Status:** 🟡 **In Active Development** - Booking System Phase  

**Session Accomplishments:**
- ✅ 7 major features implemented/enhanced
- ✅ 9 CRUD controllers updated
- ✅ 3 new customer-facing pages
- ✅ Seat selection UI fully functional
- ✅ Project documentation created

---

**For questions or clarifications about this document, refer to the conversation transcript or contact the development team.**
