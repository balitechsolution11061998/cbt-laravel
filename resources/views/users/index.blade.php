<x-default-layout>
    @section('title')
        User
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('users') }}
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
                        <input type="text" data-kt-user-table-filter="search" class="form-control form-control-solid form-control-sm w-250px ps-13" placeholder="Search user" />
                    </div>
                    <!--end::Search-->
                </div>
                <!--begin::Card title-->
                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                    <!--begin::Toolbar-->
                    <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">


                        <!--begin::Add user-->
                        <button type="button" class="btn btn-success btn-sm" onclick="filterUser()">
                            <i class="fas fa-filter"></i> Filter User
                        </button>
                        &nbsp;
                        <button type="button" class="btn btn-primary btn-sm" onclick="createUser()">
                            <i class="ki-duotone ki-plus fs-2"></i>Add User</button>
                        <!--end::Add user-->
                    </div>
                    <!--end::Toolbar-->



                </div>
                <!--end::Card toolbar-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body py-4">
                <!--begin::Table-->
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="users_table">
                    <thead>
                        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                            <th class="w-10px pe-2">
                                <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                    <input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="#po_table .form-check-input" value="1" />
                                </div>
                            </th>
                            <th class="min-w-125px">USERNAME</th>
                            <th class="min-w-125px">NAME</th>
                            <th class="min-w-125px">EMAIL</th>
                            <th class="min-w-125px">PASSWORD</th>
                            <th class="min-w-125px">JABATAN</th>
                            <th class="min-w-125px">NO HANDPHONE</th>
                            <th class="min-w-125px">FOTO</th>
                            <th class="min-w-125px">DEPARTEMEN</th>
                            <th class="min-w-125px">CABANG</th>

                            <th class="min-w-125px">STATUS</th>

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
    <!--end::Row-->
    @push('scripts')
        <script src="{{ asset('js/user.js') }}"></script>
        <script src="{{ asset('js/formatRupiah.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/mark.js/8.11.1/mark.min.js"></script>

    @endpush
</x-default-layout>
