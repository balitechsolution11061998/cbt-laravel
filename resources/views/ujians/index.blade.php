<x-default-layout>
    @section('title')
        Ujian
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('ujian') }}
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
                    <input type="text" data-kt-user-table-filter="search" class="form-control form-control-solid form-control-sm w-250px ps-13" placeholder="Search ujian" />
                </div>
                <!--end::Search-->
            </div>
            <!--end::Card title-->
            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                    <button type="button" class="btn btn-primary btn-sm" onclick="createUjian(null)">
                        <i class="ki-duotone ki-plus fs-2"></i>Add Ujian
                    </button>
                </div>
            </div>
            <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->
        <!--begin::Card body-->
        <div class="card-body py-4">
            <!--begin::Table-->
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="ujian_table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Paket Soal</th>
                        <th>Kelas</th>
                        <th>Keterangan</th>
                        <th>Waktu Mulai</th>
                        <th>Durasi</th>
                        {{-- <th>Tampil Hasil</th>
                        <th>Detail Hasil</th>
                        <th>Token</th> --}}
                        <th width="150px">Action</th>
                    </tr>
                </thead>
                <tbody>
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
    <script src="{{ asset('js/ujian.js') }}"></script>
    @endpush
</x-default-layout>
