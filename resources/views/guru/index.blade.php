<x-default-layout>
    @section('title')
        Manajemen Guru
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('manajementguru') }}
    @endsection

    <!-- Include Toastify CSS and JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <!--begin::Card-->
    <div class="card">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <!--begin::Card title-->
            <div class="card-title">
                <!--begin::Search-->
                <div class="d-flex align-items-center position-relative my-1">
                    <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    <input type="text" data-kt-user-table-filter="search" class="form-control form-control-solid form-control-sm w-250px ps-13" placeholder="Search Guru" />
                </div>
                <!--end::Search-->
            </div>
            <!--end::Card title-->
            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <!--begin::Toolbar-->
                <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                    <!--begin::Add Guru-->
                    <button type="button" class="btn btn-primary btn-sm" id="createGuruBtn">
                        <i class="ki-duotone ki-plus fs-2"></i> Add Guru
                    </button>
                    <!--end::Add Guru-->
                </div>
                <!--end::Toolbar-->
            </div>
            <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->
        <!--begin::Card body-->
        <div class="card-body py-4">
            <!--begin::Table-->
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="guru_table">
                <thead>
                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                        <th class="min-w-125px">NIK</th>
                        <th class="min-w-125px">Name</th>
                        <th class="min-w-125px">Kelas</th>
                        <th class="min-w-125px">Email</th>
                        <th class="text-end min-w-100px">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 fw-semibold">
                    <!-- Table content will be dynamically populated by DataTables -->
                </tbody>
            </table>
            <!--end::Table-->
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->

    <!-- Modal for Create/Edit Guru -->
    @include('modals.guru_modal') <!-- Make sure this file exists and is properly set up -->

    <!-- Toastify Notifications -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                Toastify({
                    text: "{{ session('success') }}",
                    backgroundColor: "green",
                    duration: 3000
                }).showToast();
            @endif

            @if(session('error'))
                Toastify({
                    text: "{{ session('error') }}",
                    backgroundColor: "red",
                    duration: 3000
                }).showToast();
            @endif
        });
    </script>

    @push('scripts')
        <script src="{{ asset('js/guru.js') }}"></script>
    @endpush
</x-default-layout>
