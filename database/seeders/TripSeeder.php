<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Province;
use App\Models\BusStation;
use App\Models\Bus;
use App\Models\Seat;
use App\Models\Route;
use App\Models\Trip;
use App\Models\PaymentMethod;
use Carbon\Carbon;

class TripSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Sinh các Tỉnh/Thành phố (Provinces)
        $hanoi = Province::firstOrCreate(['province_name' => 'Hà Nội']);
        $haiphong = Province::firstOrCreate(['province_name' => 'Hải Phòng']);
        $danang = Province::firstOrCreate(['province_name' => 'Đà Nẵng']);

        // 2. Sinh các Bến Xe (Bus Stations)
        $mydinh = BusStation::firstOrCreate(
            ['station_name' => 'Bến Xe Mỹ Đình'],
            [
                'province_id' => $hanoi->id,
                'address' => '20 Phạm Hùng, Mỹ Đình, Nam Từ Liêm, Hà Nội',
                'phone' => '0123456789'
            ]
        );

        $bachaiphong = BusStation::firstOrCreate(
            ['station_name' => 'Bến Xe Bắc Hải Phòng'],
            [
                'province_id' => $haiphong->id,
                'address' => 'Tỉnh Lộ 359C, Kênh Giang, Thủy Nguyên, Hải Phòng',
                'phone' => '0772369769'
            ]
        );

        // 3. Sinh các Xe Khách (Buses) và danh sách Ghế (Seats) tương ứng
        $bus1 = Bus::firstOrCreate(
            ['license_plate' => '29B-999.99'],
            [
                'bus_name' => 'Limousine Vip',
                'total_seats' => 30,
                'has_beds' => false
            ]
        );

        // Đảm bảo sinh đầy đủ 30 ghế cho bus1 (A1 - A30)
        for ($i = 1; $i <= 30; $i++) {
            Seat::firstOrCreate(
                [
                    'bus_id' => $bus1->id,
                    'seat_code' => 'A' . $i
                ],
                [
                    'status' => 'available'
                ]
            );
        }

        $bus2 = Bus::firstOrCreate(
            ['license_plate' => '15B-888.88'],
            [
                'bus_name' => 'Hai Phong Express Bed',
                'total_seats' => 20,
                'has_beds' => true
            ]
        );

        // Đảm bảo sinh đầy đủ 20 ghế cho bus2 (B1 - B20)
        for ($i = 1; $i <= 20; $i++) {
            Seat::firstOrCreate(
                [
                    'bus_id' => $bus2->id,
                    'seat_code' => 'B' . $i
                ],
                [
                    'status' => 'available'
                ]
            );
        }

        // 4. Sinh Tuyến Đường (Routes)
        $route1 = Route::firstOrCreate(
            [
                'departure_location' => $mydinh->id,
                'arrival_location' => $bachaiphong->id
            ],
            [
                'route_name' => 'Hà Nội - Hải Phòng',
                'distance' => 120,
                'estimate_time' => '3 giờ'
            ]
        );

        // 5. Sinh Phương thức Thanh toán (Payment Methods)
        $paymentMethods = [
            ['method_name' => 'ZaloPay', 'method_description' => 'Thanh toán nhanh qua ứng dụng ZaloPay. Miễn phí chuyển tiền, an toàn 100%'],
            ['method_name' => 'VNPay', 'method_description' => 'Thanh toán qua VNPay. Hỗ trợ thẻ tín dụng, thẻ ghi nợ, tài khoản ngân hàng'],
            ['method_name' => 'Momo', 'method_description' => 'Thanh toán qua ứng dụng Momo. Nhanh chóng, bảo mật và tiện lợi'],
            ['method_name' => 'Bank Transfer', 'method_description' => 'Chuyển khoản trực tiếp. Vui lòng liên hệ với chúng tôi để nhận thông tin tài khoản'],
            ['method_name' => 'Credit Card', 'method_description' => 'Thanh toán bằng thẻ tín dụng quốc tế (Visa, Mastercard). An toàn với mã hóa SSL'],
            ['method_name' => 'PayPal', 'method_description' => 'Thanh toán quốc tế an toàn qua PayPal'],
        ];

        foreach ($paymentMethods as $pm) {
            PaymentMethod::firstOrCreate(
                ['method_name' => $pm['method_name']],
                ['method_description' => $pm['method_description']]
            );
        }

        // 6. Sinh Chuyến Đi mẫu (Trips)
        // Chuyến đi hôm nay
        Trip::updateOrCreate(
            [
                'bus_id' => $bus1->id,
                'route_id' => $route1->id,
                'trip_date' => Carbon::today()->toDateString(),
                'departure_time' => '08:00:00'
            ],
            [
                'trip_name' => 'Hà Nội - Hải Phòng (Sáng)',
                'arrival_time' => '11:00:00',
                'base_price' => 200000.00,
                'status' => 'scheduled'
            ]
        );

        // Chuyến đi ngày mai
        Trip::updateOrCreate(
            [
                'bus_id' => $bus2->id,
                'route_id' => $route1->id,
                'trip_date' => Carbon::tomorrow()->toDateString(),
                'departure_time' => '14:30:00'
            ],
            [
                'trip_name' => 'Hà Nội - Hải Phòng (Chiều giường nằm)',
                'arrival_time' => '17:30:00',
                'base_price' => 250000.00,
                'status' => 'scheduled'
            ]
        );

        // Chuyến đi ngày hôm qua (dữ liệu lịch sử)
        Trip::updateOrCreate(
            [
                'bus_id' => $bus1->id,
                'route_id' => $route1->id,
                'trip_date' => Carbon::yesterday()->toDateString(),
                'departure_time' => '09:00:00'
            ],
            [
                'trip_name' => 'Hà Nội - Hải Phòng (Hôm qua)',
                'arrival_time' => '12:00:00',
                'base_price' => 200000.00,
                'status' => 'completed'
            ]
        );
    }
}
