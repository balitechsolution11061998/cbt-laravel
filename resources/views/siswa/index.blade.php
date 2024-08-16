<!-- resources/views/siswa/index.blade.php -->
<x-default-layout>
    @section('title', 'Siswa')
    @section('breadcrumbs')
        {{ Breadcrumbs::render('siswa') }}
    @endsection

    <div class="card">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <div class="d-flex align-items-center position-relative my-1">
                    <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    <input type="text" data-kt-user-table-filter="search" class="form-control form-control-solid form-control-sm w-250px ps-13" placeholder="Search siswa" />
                </div>
            </div>
            <div class="card-toolbar">
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-primary btn-sm" onclick="createSiswa()">
                        <i class="fas fa-plus"></i> Add Siswa
                    </button>
                    <button type="button" class="btn btn-success btn-sm ms-2" onclick="$('#importModal').modal('show')">
                        <i class="fas fa-file-excel"></i> Import Excel
                    </button>
                </div>
            </div>

        </div>
        <div class="card-body py-4">
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="siswa_table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIS</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>Jenis Kelamin</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    @include('modals.modal')
    @include('modals.modalsiswa')

    @push('scripts')
        <script src="{{ asset('js/siswa.js') }}"></script>
    @endpush
</x-default-layout>
