<!-- Seat Selection Modal -->
<div class="modal fade" id="seatSelectionModal" tabindex="-1" aria-labelledby="seatSelectionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="seatSelectionModalLabel">Chọn chỗ ngồi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="seatMapLoading" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Đang tải sơ đồ ghế...</p>
                </div>
                
                <div id="seatMapContainer" style="display: none;">
                    <div class="d-flex justify-content-center mb-3">
                        <span class="me-3"><button class="btn btn-outline-primary btn-sm me-1" style="width:30px;height:30px;" disabled></button> Trống</span>
                        <span class="me-3"><button class="btn btn-secondary btn-sm me-1" style="width:30px;height:30px;" disabled></button> Đã đặt</span>
                        <span><button class="btn btn-success btn-sm me-1" style="width:30px;height:30px;" disabled></button> Đang chọn</span>
                    </div>
                    
                    <!-- Seat map grid rendered by JS -->
                    <div id="seatGrid" class="d-flex flex-wrap justify-content-center gap-2 mb-4" style="max-width: 400px; margin: 0 auto;"></div>
                    
                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Số ghế đã chọn:</strong> <span id="selectedSeatsCount">0</span><br>
                            <strong>Ghế:</strong> <span id="selectedSeatsList" class="text-muted">Chưa chọn</span>
                        </div>
                        <div class="text-end">
                            <strong>Tổng tiền:</strong>
                            <h4 id="totalPriceDisplay" class="text-danger mb-0">0đ</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <!-- Form to submit to checkout -->
                <form id="checkoutForm" action="{{ route('customer.checkout') }}" method="POST">
                    @csrf
                    <!-- Currently routing to home as a placeholder until checkout phase -->
                    <input type="hidden" name="trip_id" id="checkoutTripId">
                    <input type="hidden" name="selected_seats" id="checkoutSelectedSeats">
                    <button type="submit" id="btnContinueCheckout" class="btn btn-primary" disabled>Tiếp tục</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Only initialize if Bootstrap is loaded
        if (typeof bootstrap !== 'undefined') {
            const modalEl = document.getElementById('seatSelectionModal');
            // Check if modal exists to prevent errors if partial is included but not rendered correctly
            if (!modalEl) return;
            
            const modal = new bootstrap.Modal(modalEl);
            const openButtons = document.querySelectorAll('.btn-open-seat-modal');
            
            let currentTripId = null;
            let currentBasePrice = 0;
            let selectedSeats = [];
            
            openButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    currentTripId = this.dataset.tripId;
                    currentBasePrice = parseFloat(this.dataset.price);
                    selectedSeats = []; // Reset selected seats
                    
                    updateSummary();
                    
                    // Show loading
                    document.getElementById('seatMapLoading').style.display = 'block';
                    document.getElementById('seatMapContainer').style.display = 'none';
                    
                    // Set hidden form value
                    document.getElementById('checkoutTripId').value = currentTripId;
                    
                    modal.show();
                    
                    // Fetch seats
                    fetch(`/api/trips/${currentTripId}/seats`)
                        .then(response => response.json())
                        .then(data => {
                            renderSeatMap(data.seats, data.booked_seat_ids);
                            document.getElementById('seatMapLoading').style.display = 'none';
                            document.getElementById('seatMapContainer').style.display = 'block';
                        })
                        .catch(error => {
                            console.error('Error fetching seats:', error);
                            document.getElementById('seatMapLoading').innerHTML = '<p class="text-danger text-center">Lỗi khi tải sơ đồ ghế. Vui lòng thử lại.</p>';
                        });
                });
            });
            
            function renderSeatMap(seats, bookedIds) {
                const grid = document.getElementById('seatGrid');
                grid.innerHTML = '';
                
                seats.forEach(seat => {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'btn seat-btn';
                    btn.style.width = '60px';
                    btn.style.height = '60px';
                    btn.textContent = seat.seat_code;
                    btn.dataset.seatId = seat.id;
                    btn.dataset.seatCode = seat.seat_code;
                    
                    if (bookedIds.includes(seat.id)) {
                        btn.classList.add('btn-secondary', 'disabled');
                        btn.disabled = true;
                    } else {
                        btn.classList.add('btn-outline-primary');
                        btn.addEventListener('click', function() {
                            toggleSeat(this, seat.id, seat.seat_code);
                        });
                    }
                    
                    grid.appendChild(btn);
                });
            }
            
            function toggleSeat(btnElement, seatId, seatCode) {
                const index = selectedSeats.findIndex(s => s.id === seatId);
                
                if (index > -1) {
                    // Deselect
                    selectedSeats.splice(index, 1);
                    btnElement.classList.remove('btn-success');
                    btnElement.classList.add('btn-outline-primary');
                } else {
                    // Select
                    selectedSeats.push({ id: seatId, code: seatCode });
                    btnElement.classList.remove('btn-outline-primary');
                    btnElement.classList.add('btn-success');
                }
                
                updateSummary();
            }
            
            function updateSummary() {
                const count = selectedSeats.length;
                const totalPrice = count * currentBasePrice;
                
                document.getElementById('selectedSeatsCount').textContent = count;
                
                if (count > 0) {
                    const codes = selectedSeats.map(s => s.code).join(', ');
                    document.getElementById('selectedSeatsList').textContent = codes;
                    document.getElementById('checkoutSelectedSeats').value = selectedSeats.map(s => s.id).join(',');
                    document.getElementById('btnContinueCheckout').disabled = false;
                } else {
                    document.getElementById('selectedSeatsList').textContent = 'Chưa chọn';
                    document.getElementById('checkoutSelectedSeats').value = '';
                    document.getElementById('btnContinueCheckout').disabled = true;
                }
                
                const formattedPrice = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(totalPrice);
                document.getElementById('totalPriceDisplay').textContent = formattedPrice.replace('₫', 'đ');
            }
            
            // Prevent form submission if no seats
            const form = document.getElementById('checkoutForm');
            if(form) {
                form.addEventListener('submit', function(e) {
                    if (selectedSeats.length === 0) {
                        e.preventDefault();
                        alert('Vui lòng chọn ít nhất một ghế để tiếp tục.');
                    }
                });
            }
        } else {
            console.error('Bootstrap JS is not loaded!');
        }
    });
</script>
@endpush
