<x-default-layout>
    @section('title')
        Order
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('order') }}
    @endsection
        <!--begin::Card-->
        <div class="card">
            <!--begin::Card header-->
            <div class="card-header border-0 pt-6">
                <!--begin::Card title-->
                <div class="card-title">
                    <!--begin::Search-->
                    <label for="" style="color:black;font-weight:bold;">Form Users</label>
                </div>
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body py-4" id="formUsers">
                <!--begin::Table-->

                <!--end::Table-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->
    <!--end::Row-->
    @push('scripts')
        <script src="{{ asset('js/createuser.js') }}"></script>
        <script src="{{ asset('js/formatRupiah.js') }}"></script>

    @endpush
</x-default-layout>
