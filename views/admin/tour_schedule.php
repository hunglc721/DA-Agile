<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Lịch Trình Tour & Lịch Làm Việc</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Lịch tổng quan</h6>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-3">
                    <input type="text" id="searchInput" class="form-control" placeholder="Tìm theo tên/mã tour...">
                </div>
                <div class="col-md-3">
                    <select class="form-control" id="statusFilter">
                        <option value="">-- Lọc theo trạng thái --</option>
                        <option value="scheduled">Sắp diễn ra</option>
                        <option value="ongoing">Đang chạy</option>
                        <option value="finished">Đã kết thúc</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-control" id="categoryFilter">
                        <option value="">-- Lọc theo loại tour --</option>
                        <option value="domestic">Tour trong nước</option>
                        <option value="international">Tour quốc tế</option>
                    </select>
                </div>
                <div class="col-md-3 text-right">
                    <button class="btn btn-success btn-sm"><i class="fas fa-file-excel mr-1"></i> Excel</button>
                    <button class="btn btn-danger btn-sm"><i class="fas fa-file-pdf mr-1"></i> PDF</button>
                </div>
            </div>

            <div id='calendar'></div>
        </div>
    </div>

</div>

<div class="modal fade" id="eventDetailModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chi Tiết Lịch Trình</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Đang chuyển hướng đến chi tiết đơn hàng...</p>
            </div>
        </div>
    </div>
</div>

<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />

<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/vi.js'></script>


<script>
    let calendar;
    let filterState = {
        status: '',
        category: '',
        search: ''
    };

    function getEventUrl() {
        let url = 'index.php?action=api_schedule_events';
        if (filterState.status) {
            url += '&status=' + encodeURIComponent(filterState.status);
        }
        if (filterState.category) {
            url += '&category=' + encodeURIComponent(filterState.category);
        }
        if (filterState.search) {
            url += '&search=' + encodeURIComponent(filterState.search);
        }
        return url;
    }

    function refreshCalendarEvents() {
        if (calendar) {
            calendar.refetchEvents();
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        
        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'vi', // Ngôn ngữ Tiếng Việt
            themeSystem: 'bootstrap', 
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listWeek'
            },
            buttonText: {
                today: 'Hôm nay',
                month: 'Tháng',
                week: 'Tuần',
                day: 'Ngày',
                list: 'Danh sách'
            },
            
            // Gọi API lấy dữ liệu JSON từ Controller - dùng function để cập nhật URL
            events: function(info, successCallback, failureCallback) {
                fetch(getEventUrl())
                    .then(response => response.json())
                    .then(data => {
                        // Nếu API trả về error object, trả về mảng rỗng
                        if (data.error) {
                            successCallback([]);
                        } else {
                            successCallback(Array.isArray(data) ? data : []);
                        }
                    })
                    .catch(error => {
                        console.error('Error loading events:', error);
                        failureCallback(error);
                    });
            },

            // Xử lý khi click vào sự kiện -> Chuyển trang xem chi tiết
            eventClick: function(info) {
                if (info.event.url) {
                    // Chuyển trang
                    window.location.href = info.event.url;
                    // Ngăn chặn hành vi mặc định (mở tab mới nếu có target=_blank)
                    info.jsEvent.preventDefault(); 
                }
            },

            // Hiệu ứng con trỏ chuột
            eventMouseEnter: function(info) {
                info.el.style.cursor = 'pointer';
            },

            // Xử lý lỗi nếu API trả về sai
            eventDidMount: function(info) {
                // Có thể thêm logic hiển thị tooltip tại đây nếu muốn
            }
        });
        
        calendar.render();

        // Thêm event listeners cho filters
        document.getElementById('statusFilter').addEventListener('change', function(e) {
            filterState.status = e.target.value;
            refreshCalendarEvents();
        });

        document.getElementById('categoryFilter').addEventListener('change', function(e) {
            filterState.category = e.target.value;
            refreshCalendarEvents();
        });

        document.getElementById('searchInput').addEventListener('keyup', function(e) {
            filterState.search = e.target.value;
            // Debounce search - chỉ refresh sau 1 giây không gõ
            clearTimeout(window.searchTimeout);
            window.searchTimeout = setTimeout(() => {
                refreshCalendarEvents();
            }, 1000);
        });
    });
</script>