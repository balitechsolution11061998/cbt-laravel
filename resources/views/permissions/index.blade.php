<x-default-layout>
    @section('title')
        Permissions
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('permissions') }}
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
                        <input type="text" data-kt-user-table-filter="search" id="frmSearchPermissions" class="form-control form-control-solid w-250px ps-13" placeholder="Search Permissions" />
                    </div>
                    <!--end::Search-->
                </div>
                <!--begin::Card title-->
                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                    <!--begin::Toolbar-->
                    @can('permissions-create')
                    <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                        <button type="button" class="btn btn-primary" onclick="tambahPermissions()">
                            <i class="ki-duotone ki-plus fs-2"></i>Add Permissions</button>
                    </div>
                    @endcan
                </div>
                <!--end::Card toolbar-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body py-4">
                <!--begin::Table-->
                <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0" id="tablePermissions" style="border: 1px solid #dee2e6;">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th style="border: 1px solid #dee2e6;">Permission Name</th>
                            <th style="border: 1px solid #dee2e6;">Display Name</th>
                            <th style="border: 1px solid #dee2e6;">Description</th>
                            <th style="border: 1px solid #dee2e6;">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                        <!-- Table body content -->
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
        <script src="{{ asset('js/permissions.js') }}"></script>
        <script src="{{ asset('js/formatRupiah.js') }}"></script>

    @endpush
</x-default-layout>
