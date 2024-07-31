<x-default-layout>
    @section('title')
        Dashboard
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('dashboard') }}
    @endsection
    <div class="row">


        <!-- Dashboard Cards -->
        <div class="container mt-4">
            <div class="row">
                @can('department-show')
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <h2 class="section-title"><i class="fas fa-building"></i> Jumlah Department</h2>
                            <div id="spinner-department" style="display: none;">
                                <i class="fas fa-spinner fa-spin" style="font-size: 24px;"></i>
                            </div>
                            <div class="chart-container">
                                <i class="fas fa-chart-pie icon-animate" style="font-size: 48px; color: #3498db;"></i>
                                <span class="chart-number custom-font" id="department-content">0</span>
                            </div>
                            <a href="/departments" class="btn btn-sm btn-primary">
                                <i class="fas fa-building"></i> View Departments
                            </a>
                        </div>
                    </div>
                @endcan
                @can('kantorcabang-show')
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <h2 class="section-title"><i class="fas fa-code-branch"></i> Jumlah Cabang</h2>
                            <div id="spinner-cabang" style="display: none;">
                                <i class="fas fa-spinner fa-spin" style="font-size: 24px;"></i>
                            </div>
                            <div class="chart-container">
                                <i class="fas fa-chart-bar icon-animate" style="font-size: 48px; color: #e74c3c;"></i>
                                <span class="chart-number custom-font" id="cabang-content">0</span>
                            </div>
                            <a href="/branches" class="btn btn-sm btn-primary">
                                <i class="fas fa-code-branch"></i> View Branches
                            </a>
                        </div>
                    </div>
                @endcan
                @can('cuti-show')
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <h2 class="section-title"><i class="fas fa-calendar-day"></i> Jumlah Cuti</h2>
                            <div id="spinner-leave" style="display: none;">
                                <i class="fas fa-spinner fa-spin" style="font-size: 24px;"></i>
                            </div>
                            <div class="chart-container">
                                <i class="fas fa-calendar-check icon-animate" style="font-size: 48px; color: #2ecc71;"></i>
                                <span class="chart-number custom-font" id="leave-content">0</span>
                            </div>
                            <a href="/leaves" class="btn btn-sm btn-primary">
                                <i class="fas fa-calendar-check"></i> View Leaves
                            </a>
                        </div>
                    </div>
                @endcan
                @can('po-show')
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <h2 class="section-title"><i class="fas fa-file-alt"></i> Jumlah PO</h2>
                            <div id="spinner-po" style="display: none;">
                                <i class="fas fa-spinner fa-spin" style="font-size: 24px;"></i>
                            </div>
                            <div class="chart-container">
                                <i class="fas fa-chart-line icon-animate" style="font-size: 48px; color: #9b59b6;"></i>
                                <span class="chart-number custom-font" id="po-content">0</span>
                            </div>
                            <a href="/pos" class="btn btn-sm btn-primary">
                                <i class="fas fa-file-alt"></i> View POs
                            </a>
                        </div>
                    </div>
                @endcan
                @can('siswa-show')
                <div class="container mt-5">
                    <div class="row">
                        <div class="col-md-3 mb-4">
                            <div class="card equal-height">
                                <h2 class="section-title"><i class="fas fa-user-graduate"></i> Jumlah Siswa</h2>
                                <div id="spinner-student" style="display: none;">
                                    <i class="fas fa-spinner fa-spin" style="font-size: 24px;"></i>
                                </div>
                                <div class="chart-container text-center">
                                    <i class="fas fa-users icon-animate" style="font-size: 48px; color: #f39c12;"></i>
                                    <span class="chart-number custom-font" id="student-content">0</span>
                                    <div>
                                        <span id="male-count">Laki-laki: 0</span> |
                                        <span id="female-count">Perempuan: 0</span>
                                    </div>
                                </div>
                                <a href="/students" class="btn btn-sm btn-primary mt-3">
                                    <i class="fas fa-user-graduate"></i> View Students
                                </a>
                                <button id="show-more-button" class="btn btn-sm btn-secondary mt-2">
                                    <i class="fas fa-plus-circle"></i> Show More
                                </button>
                            </div>
                        </div>

                        <div class="col-md-3 mb-4">
                            <div class="card equal-height">
                                <h2 class="section-title"><i class="fas fa-chalkboard"></i> Jumlah Kelas</h2>
                                <div id="spinner-kelas" style="display: none;">
                                    <i class="fas fa-spinner fa-spin" style="font-size: 24px;"></i>
                                </div>
                                <div class="chart-container text-center">
                                    <i class="fas fa-chalkboard icon-animate" style="font-size: 48px; color: #007bff;"></i>
                                    <span class="chart-number custom-font" id="kelas-content">0</span>
                                </div>
                                <a href="/classes" class="btn btn-sm btn-primary mt-3">
                                    <i class="fas fa-chalkboard"></i> View Classes
                                </a>
                            </div>
                        </div>

                        <div class="col-md-3 mb-4">
                            <div class="card equal-height">
                                <h2 class="section-title"><i class="fas fa-chalkboard"></i> Rombongan Belajar</h2>
                                <div id="spinner-rombel" style="display: none;">
                                    <i class="fas fa-spinner fa-spin"></i>
                                </div>
                                <div class="chart-container text-center">
                                    <i class="fas fa-chalkboard icon-animate" style="font-size: 48px; color: #007bff;"></i>
                                    <span class="chart-number custom-font" id="rombel-content">0</span>
                                </div>
                                <a href="/classes" class="btn btn-sm btn-primary mt-3">
                                    <i class="fas fa-chalkboard"></i> View Rombel
                                </a>
                            </div>
                        </div>
                        <div class="col-md-3 mb-4">
                            <div class="card equal-height">
                                <h2 class="section-title"><i class="fas fa-book"></i> Mata Pelajaran</h2>
                                <div id="spinner-mata-pelajaran" style="display: none;">
                                    <i class="fas fa-spinner fa-spin"></i>
                                </div>
                                <div class="chart-container text-center">
                                    <i class="fas fa-book icon-animate" style="font-size: 48px; color: #28a745;"></i>
                                    <span class="chart-number custom-font" id="mata-pelajaran-content">0</span>
                                </div>
                                <a href="/subjects" class="btn btn-sm btn-primary mt-3">
                                    <i class="fas fa-book"></i> View Subjects
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Details Section -->
                    <div id="additional-details" class="col-md-12 mt-4" style="display: none;">
                        <div class="card shadow-sm border-light">
                            <div class="card-header">
                                <h2 class="section-title mb-0"><i class="fas fa-info-circle"></i> Detail Per Rombel</h2>
                            </div>
                            <div class="card-body">
                                <div id="spinner-detail" class="text-center mb-3" style="display: none;">
                                    <i class="fas fa-spinner fa-spin" style="font-size: 24px;"></i>
                                </div>
                                <div class="chart-container table-responsive">
                                    <table class="table align-middle table-row-dashed fs-6 gy-5">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>Rombel - Kelas</th>
                                                <th>Jumlah Siswa</th>
                                            </tr>
                                        </thead>
                                        <tbody id="rombel-table-body">
                                            <!-- Data will be populated here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endcan
            </div>
        </div>

        <!-- Rombel and Kelas Detail Card -->




        <!-- Grid Container -->
        <div class="grid-container">
            @can('jamkerja-show')
                <div class="jam-kerja-container">
                    <h2 class="section-title"><i class="fas fa-clock"></i> Jam Kerja</h2>
                    <div id="spinner" style="display: none; text-align: center;">
                        <i class="fas fa-spinner fa-spin" style="font-size: 24px;"></i>
                    </div>
                    <div class="jam-kerja-content" id="jam-kerja-content">
                        <!-- Content will be populated by AJAX -->
                    </div>
                </div>
            @endcan
            @can('cuti-show')
                <div class="jam-kerja-container">
                    <h2 class="section-title"><i class="fas fa-calendar-day"></i> Jumlah Cuti</h2>
                    <div id="spinner-leave" style="display: none;">
                        <i class="fas fa-spinner fa-spin" style="font-size: 24px;"></i>
                    </div>
                    <div class="jam-kerja-content" id="listleave-content">
                        <!-- Content will be populated by AJAX -->
                    </div>
                </div>
            @endcan
        </div>
        @can('po-show')
            <div class="row">
                <div class="col-xl-4">

                    <!--begin::List widget 16-->
                    <div class="card card-flush h-xl-100">
                        <!--begin::Header-->
                        <div class="card-header pt-7">
                            <!--begin::Title-->

                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold text-gray-800" id="delivery-tracking-title">Delivery
                                    Tracking</span>
                                <span class="text-gray-500 mt-1 fw-semibold fs-6" id="deliveries-in-progress">56
                                    deliveries in progress</span>
                            </h3>
                            <!--end::Title-->

                            <!--begin::Toolbar-->
                            {{-- <div class="card-toolbar">
                                <a href="#" class="btn btn-sm btn-light" data-bs-toggle="tooltip"
                                    data-bs-dismiss="click" data-bs-custom-class="tooltip-inverse"
                                    data-bs-original-title="Delivery App is coming soon" data-kt-initialized="1">View
                                    All</a>
                            </div> --}}
                            <!--end::Toolbar-->
                        </div>
                        <!--end::Header-->

                        <!--begin::Body-->
                        <div class="card-body pt-4 px-0">
                            <!--begin::Nav-->
                            <ul class="nav nav-pills nav-pills-custom item position-relative mx-9 mb-9" role="tablist">
                                <!--begin::Item-->
                                <li class="nav-item col-3 mx-0 p-0" role="presentation">
                                    <!--begin::Link-->
                                    <a class="nav-link active d-flex justify-content-center w-100 border-0 h-100"
                                        data-bs-toggle="pill" href="#kt_list_widget_16_tab_1" aria-selected="true"
                                        role="tab">
                                        <!--begin::Subtitle-->
                                        <span id="confirmed-count" class="nav-text text-gray-800 fw-bold fs-6 mb-3">
                                            Confirmed
                                        </span>
                                        <!--end::Subtitle-->
                                        <!--begin::Bullet-->
                                        <span
                                            class="bullet-custom position-absolute z-index-2 bottom-0 w-100 h-4px bg-primary rounded"></span>
                                        <!--end::Bullet-->
                                    </a>
                                    <!--end::Link-->
                                </li>
                                <!--end::Item-->

                                <!--begin::Item-->
                                <li class="nav-item col-3 mx-0 px-0" role="presentation">
                                    <!--begin::Link-->
                                    <a class="nav-link d-flex justify-content-center w-100 border-0 h-100"
                                        data-bs-toggle="pill" href="#kt_list_widget_16_tab_2" aria-selected="false"
                                        tabindex="-1" role="tab">
                                        <!--begin::Subtitle-->
                                        <span class="nav-text text-gray-800 fw-bold fs-6 mb-3">
                                            Preparing
                                        </span>
                                        <!--end::Subtitle-->
                                        <!--begin::Bullet-->
                                        <span
                                            class="bullet-custom position-absolute z-index-2 bottom-0 w-100 h-4px bg-primary rounded"></span>
                                        <!--end::Bullet-->
                                    </a>
                                    <!--end::Link-->
                                </li>
                                <!--end::Item-->

                                <!--begin::Item-->
                                <li class="nav-item col-3 mx-0 px-0" role="presentation">
                                    <!--begin::Link-->
                                    <a class="nav-link d-flex justify-content-center w-100 border-0 h-100"
                                        data-bs-toggle="pill" href="#kt_list_widget_16_tab_3" aria-selected="false"
                                        tabindex="-1" role="tab">
                                        <!--begin::Subtitle-->
                                        <span class="nav-text text-gray-800 fw-bold fs-6 mb-3">
                                            Delivering
                                        </span>
                                        <!--end::Subtitle-->
                                        <!--begin::Bullet-->
                                        <span
                                            class="bullet-custom position-absolute z-index-2 bottom-0 w-100 h-4px bg-primary rounded"></span>
                                        <!--end::Bullet-->
                                    </a>
                                    <!--end::Link-->
                                </li>
                                <!--end::Item-->

                                <!--begin::Item-->
                                <li class="nav-item col-3 mx-0 px-0" role="presentation">
                                    <!--begin::Link-->
                                    <a class="nav-link d-flex justify-content-center w-100 border-0 h-100"
                                        data-bs-toggle="pill" href="#kt_list_widget_16_tab_4" aria-selected="false"
                                        tabindex="-1" role="tab">
                                        <!--begin::Subtitle-->
                                        <span class="nav-text text-gray-800 fw-bold fs-6 mb-3">
                                            Receiving
                                        </span>
                                        <!--end::Subtitle-->
                                        <!--begin::Bullet-->
                                        <span
                                            class="bullet-custom position-absolute z-index-2 bottom-0 w-100 h-4px bg-primary rounded"></span>
                                        <!--end::Bullet-->
                                    </a>
                                    <!--end::Link-->
                                </li>
                                <!--end::Item-->

                                <!--begin::Bullet-->
                                <span class="position-absolute z-index-1 bottom-0 w-100 h-4px bg-light rounded"></span>
                                <!--end::Bullet-->
                            </ul>

                            <!--end::Nav-->

                            <!--begin::Tab Content-->
                            <div class="tab-content px-9 hover-scroll-overlay-y pe-7 me-3 mb-2" style="height: 454px">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-primary btn-sm" id="deliveryModalBtn">
                                        <i class="fas fa-calendar-days"></i> Show Delivery Dates
                                    </button>

                                    <!-- Alert message container below the button -->
                                    <div class="mt-2">
                                        <span id="deliveryAlert" class="text-danger d-none"></span>
                                        <!-- Alert message container -->
                                    </div>
                                </div>
                                <!--begin::Tab pane 1 (Confirmed)-->
                                <div class="tab-pane fade show active" id="kt_list_widget_16_tab_1" role="tabpanel">

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="timeline-container"></div>
                                        </div>
                                    </div>
                                </div>
                                <!--end::Tab pane 1-->

                                <!--begin::Tab pane 2 (Preparing)-->
                                <div class="tab-pane fade" id="kt_list_widget_16_tab_2" role="tabpanel">
                                    <!--begin::Item container-->
                                    <div class="timeline-container"></div>
                                    <!--end::Item container-->
                                </div>
                                <!--end::Tab pane 2-->

                                <!--begin::Tab pane 3 (Delivery)-->
                                <div class="tab-pane fade" id="kt_list_widget_16_tab_3" role="tabpanel">
                                    <!--begin::Item container-->
                                    <div class="timeline-container"></div>
                                    <!--end::Item container-->
                                </div>
                                <!--end::Tab pane 3-->

                                <!--begin::Tab pane 4 (Receiving)-->
                                <div class="tab-pane fade" id="kt_list_widget_16_tab_4" role="tabpanel">
                                    <!--begin::Item container-->
                                    <div class="timeline-container"></div>
                                    <!--end::Item container-->
                                </div>
                                <!--end::Tab pane 4-->
                            </div>

                            <!--end::Tab Content-->
                        </div>
                        <!--end: Card Body-->
                    </div>
                    <!--end::List widget 16-->
                </div>

                <div class="col-xl-8">
                    <div class="card card-bordered">
                        <div class="card-body">
                            <h5 class="card-title">PO Data by Month and Year</h5>
                            <div id="filter-container" class="mb-3">
                                <div class="form-container mb-3">
                                    <label for="filter-date" class="filter-label">Date:</label>
                                    <div class="input-wrapper mb-3">
                                        <input type="month" id="filter-date" class="form-control">
                                    </div>

                                    <label for="filter-select" class="filter-label">Filter:</label>
                                    <select id="filter-select" class="form-select">
                                        <option value="qty">Quantity</option>
                                        <option value="cost">Total Cost</option>
                                    </select>
                                </div>

                                <div id="dropdown-container" class="dropdown mb-3">
                                    <button class="btn btn-secondary dropdown-toggle" type="button" id="statusDropdown"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        Status
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="statusDropdown">
                                        <li class="dropdown-item">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="show-expired"
                                                    checked>
                                                <label class="form-check-label" for="show-expired">Expired</label>
                                            </div>
                                        </li>
                                        <li class="dropdown-item">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="show-completed"
                                                    checked>
                                                <label class="form-check-label" for="show-completed">Completed</label>
                                            </div>
                                        </li>
                                        <li class="dropdown-item">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="show-confirmed"
                                                    checked>
                                                <label class="form-check-label" for="show-confirmed">Confirmed</label>
                                            </div>
                                        </li>
                                        <li class="dropdown-item">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="show-in-progress"
                                                    checked>
                                                <label class="form-check-label" for="show-in-progress">In Progress</label>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div id="kt_apexcharts_1"></div>
                            <div id="spinner-po" class="spinner">Loading...</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- PO Data Card -->
        @endcan
    </div>





    {{-- <div class="notification-button-container" style="margin: 20px;">
            <button id="notify-btn" class="btn btn-primary">
                <i class="fas fa-bell"></i> Show Notification
            </button>
        </div> --}}

    @include('modals.modal')
    @push('scripts')
        <script src="{{ asset('js/home.js') }}"></script>
        <script src="{{ asset('js/formatRupiah.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (Notification.permission === 'default' || Notification.permission === 'denied') {
                    Notification.requestPermission().then(function(permission) {
                        if (permission === 'granted') {
                            showNotification();
                        }
                    });
                } else if (Notification.permission === 'granted') {
                    showNotification();
                }

                document.getElementById('notify-btn').addEventListener('click', function() {
                    if (Notification.permission === 'granted') {
                        showNotification();
                    } else {
                        Notification.requestPermission().then(function(permission) {
                            if (permission === 'granted') {
                                showNotification();
                            }
                        });
                    }
                });
            });

            function showNotification() {
                const notification = new Notification('Test Notification', {
                    body: 'This is a test notification',
                    icon: '/image/logo.png' // Replace with your icon URL
                });

                notification.onclick = function() {
                    window.focus();
                    notification.close();
                };
            }
        </script>
    @endpush
</x-default-layout>
