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
                @can('siswa-show')
                <div class="container mt-5">
                    <div class="row">
                        <div class="col-md-4 mb-4">
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


                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <div class="card equal-height">
                                <h2 class="section-title"><i class="fas fa-chalkboard"></i> Jumlah Kelas</h2>
                                <div id="spinner-kelas" style="display: none;">
                                    <i class="fas fa-spinner fa-spin" style="font-size: 24px;"></i>
                                </div>
                                <div class="chart-container text-center">
                                    <i class="fas fa-chalkboard icon-animate" style="font-size: 48px; color: #007bff;"></i>
                                    <span class="chart-number custom-font" id="kelas-content">0</span>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="card equal-height">
                                <h2 class="section-title"><i class="fas fa-book"></i> Mata Pelajaran</h2>
                                <div id="spinner-mata-pelajaran" style="display: none;">
                                    <i class="fas fa-spinner fa-spin"></i>
                                </div>
                                <div class="chart-container text-center">
                                    <i class="fas fa-book icon-animate" style="font-size: 48px; color: #28a745;"></i>
                                    <span class="chart-number custom-font" id="mata-pelajaran-content">0</span>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Additional Details Section -->

                </div>
                @endcan
            </div>
        </div>

        <!-- Rombel and Kelas Detail Card -->

        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm border-light">
                    <div class="card-header">
                        <h2 class="section-title mb-0"><i class="fas fa-info-circle"></i>History Ujian</h2>
                    </div>
                    <div class="card-body">
                        <div id="spinner-detail" class="text-center mb-3" style="display: none;">
                            <i class="fas fa-spinner fa-spin" style="font-size: 24px;"></i>
                        </div>
                        <div class="chart-container table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-5">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>NIS</th>
                                        <th>Siswa Name</th>
                                        <th>Kelas</th>
                                        <th>Tanggal Ujian</th>
                                        <th>Mata Pelajaran</th>
                                        <th>Jumlah Benar</th>
                                        <th>Jumlah Salah</th>
                                        <th>Total Nilai</th>
                                    </tr>
                                </thead>
                                <tbody id="historyUjian-table-body">
                                    <!-- Data will be populated here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div id="additional-details" class="col-md-12 mt-4">
                <div class="card shadow-sm border-light">
                    <div class="card-header">
                        <h2 class="section-title mb-0"><i class="fas fa-info-circle"></i> Detail Per Kelas</h2>
                    </div>
                    <div class="card-body">
                        <div id="spinner-detail" class="text-center mb-3" style="display: none;">
                            <i class="fas fa-spinner fa-spin" style="font-size: 24px;"></i>
                        </div>
                        <div class="chart-container table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-5">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Kelas</th>
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

    @endpush
</x-default-layout>
