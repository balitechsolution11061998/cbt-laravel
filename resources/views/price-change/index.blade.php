<x-default-layout>
    @section('title')
        Price change
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('price-change') }}
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
                        <button type="button" class="btn btn-success btn-sm" onclick="uploadPriceChange()">
                            <i class="fas fa-upload me-2"></i>Upload Price Change
                        </button>
                    </div> &nbsp;
                    <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                        <button type="button" class="btn btn-primary btn-sm" onclick="tambahPriceList()">
                            <i class="ki-duotone ki-plus fs-2"></i>Add Cost Change</button>
                    </div>
                </div>
                <!--end::Card toolbar-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body py-4">
                <!--begin::Table-->
                <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0" id="tablePriceChange">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                        <th>Price List No</th>
                        <th>Display Name</th>
                        <th>Active Date</th>
                        <th>Supplier</th>
                        <th>Supplier Name</th>
                        <th>Last Approval</th>
                        <th>Status</th>
                        <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                    </tbody>
                </table>

                <!--end::Table-->
            </div>
            <div class="card-footer">
                <b>Note Pricelist :</b>
                <p>1. Harga pricelist adalah harga diluar PPN</p>
                <p>2. Harga pricelist adalah harga per pcs</p>
                <p>3. Download format import dari excel atau csv, <a href="#" class="text-olive" onclick="showDownloadModal()"><b>klik disini</b></a></p>
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->
    @include('modals.modal')
    <!--end::Row-->
    @push('scripts')

        <script src="{{ asset('js/pricelist.js') }}"></script>
        <script src="{{ asset('js/formatRupiah.js') }}"></script>

    @endpush
</x-default-layout>
