<x-default-layout>
    @section('title')
        Jam Kerja
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('jam-kerja') }}
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
                    <input type="text" data-kt-user-table-filter="search" id="frmSearchRoles" class="form-control form-control-solid form-control-sm w-250px ps-13" placeholder="Search Roles" />
                </div>
                <!--end::Search-->
            </div>
            <!--begin::Card title-->
            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <!--begin::Toolbar-->
                <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                    <button type="button" class="btn btn-primary btn-sm" onclick="tambahJamKerja()">
                        <i class="ki-duotone ki-plus fs-2"></i>Tambah Data</button>
                </div>
            </div>
            <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->
        <!--begin::Card body-->
        <div class="card-body py-4">
            <!--begin::Table-->
            <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0" id="tableJamKerja">
                <thead>
                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                        <th width="5%">NO</th>
                        <th width="15%">KODE JK</th>
                        <th width="15%">NAMA JK</th>
                        <th width="15%">AWAL JAM MASUK</th>
                        <th width="15%">JAM MASUK</th>
                        <th width="15%">AKHIR JAM MASUK</th>
                        <th width="15%">JAM PULANG</th>
                        <th width="10%">LINTAS HARI</th>
                        <th width="10%">AKSI</th>
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">
                    {{-- Data Jam Kerja akan di load di sini --}}
                </tbody>
            </table>

            <!--end::Table-->
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->
    @include('modals.modal')
    <!--end::Row-->
    @push('scripts')

        <script src="{{ asset('js/jam_kerja.js') }}"></script>
        <script src="{{ asset('js/formatRupiah.js') }}"></script>

    @endpush
</x-default-layout>
