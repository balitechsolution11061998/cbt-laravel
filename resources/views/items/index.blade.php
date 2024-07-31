<x-default-layout>
    @section('title')
        Items
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('items') }}
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
                        <input type="text" data-kt-user-table-filter="search" id="items" class="form-control form-control-solid form-control-sm w-250px ps-13" placeholder="Search items" />
                    </div>
                    <!--end::Search-->
                </div>

            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body py-4">
                <!--begin::Table-->
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="items_table">
                    <thead>
                        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                            <th class="w-10px pe-2">
                                <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                    <input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="#po_table .form-check-input" value="1" />
                                </div>
                            </th>
                            <th class="min-w-125px">SUPPLIER</th>
                            <th class="min-w-125px">SKU</th>
                            <th class="min-w-125px">SKU</th>
                            <th class="min-w-125px">UPC</th>
                            <th class="min-w-125px">UNIT COST</th>
                            <th class="min-w-125px">CREATE BY</th>
                            <th class="text-end min-w-100px">UPDATED BY</th>
                        </tr>
                    </thead>
                    <tbody class="text-black fw-semibold">

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
        <script src="{{ asset('js/items.js') }}"></script>
        <script src="{{ asset('js/formatRupiah.js') }}"></script>

    @endpush
</x-default-layout>
