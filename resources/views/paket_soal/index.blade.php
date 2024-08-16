<x-default-layout>
    @section('title')
        Paket Soal
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('paketsoal') }}
    @endsection

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
                    <input type="text" data-kt-user-table-filter="search" class="form-control form-control-solid form-control-sm w-250px ps-13" placeholder="Search Management Soal" />
                </div>
                <!--end::Search-->
            </div>
            <!--end::Card title-->
            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <!--begin::Toolbar-->
                <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                    <!--begin::Add Paket Soal-->
                    <button type="button" class="btn btn-primary btn-sm" onclick="createPaketSoal()">
                        <i class="ki-duotone ki-plus fs-2"></i> Add Paket Soal
                    </button>
                    <!--end::Add Paket Soal-->
                </div>
                <!--end::Toolbar-->
            </div>
            <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->
        <!--begin::Card body-->
        <div class="card-body py-4">
            <!--begin::Table-->
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="paket_soal_table">
                <thead>
                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                        <th class="w-10px pe-2">
                            <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                <input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="#paket_soal_table .form-check-input" value="1" />
                            </div>
                        </th>
                        <th class="min-w-125px">Kelas</th>
                        <th class="min-w-125px">Mata Pelajaran</th>
                        <th class="min-w-125px">Paket</th>
                        <th class="min-w-125px">Keterangan</th>
                        <th class="text-end min-w-100px">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 fw-semibold">

                </tbody>
            </table>
            <!--end::Table-->
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->

    @include('modals.modal')

    @push('scripts')
        <script src="{{ asset('js/paketsoal.js') }}"></script>
    @endpush
</x-default-layout>
