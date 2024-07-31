<x-auth-layout>

    @section('title')
    Coming Soon
    @endsection

    <!--begin::Root-->
    <div class="d-flex flex-column flex-root" id="kt_app_root">
        <!--begin::Page bg image-->
        <style>
            body {
                background-image: url('{{ asset('assets/media/auth/bg3.jpg') }}');
            }

            [data-bs-theme="dark"] body {
                background-image: url('{{ asset('assets/media/auth/bg3-dark.jpg') }}');
            }

        </style>
        <!--end::Page bg image-->
        <!--begin::Authentication - Signup Welcome Message -->
        <div class="d-flex flex-column flex-center flex-column-fluid">
            <!--begin::Content-->
            <div class="d-flex flex-column flex-center text-center p-10">
                <!--begin::Wrapper-->
                <div class="card card-flush w-lg-650px py-5">
                    <div class="card-body py-15 py-lg-20">
                        <!--begin::Logo-->
                        <div class="mb-13">
                            <a href="index.html" class="">
                                <img alt="Logo" src="{{ asset('image/logo.png') }}" class="h-40px" />
                            </a>
                        </div>
                        <!--end::Logo-->
                        <!--begin::Title-->
                        <h1 class="fw-bolder text-gray-900 mb-7">We're Launching Soon</h1>
                        <!--end::Title-->
                        <!--begin::Counter-->
                        {{-- <div class="d-flex flex-center pb-10 pt-lg-5 pb-lg-12">
                            <div class="w-65px rounded-3 bg-body shadow-sm py-4 px-5 mx-3">
                                <div class="fs-2 fw-bold text-gray-800" id="kt_coming_soon_counter_days"></div>
                                <div class="fs-7 fw-semibold text-muted">days</div>
                            </div>

                            <div class="w-65px rounded-3 bg-body shadow-sm py-4 px-5 mx-3">
                                <div class="fs-2 fw-bold text-gray-800" id="kt_coming_soon_counter_hours"></div>
                                <div class="fs-7 text-muted">hrs</div>
                            </div>

                            <div class="w-65px rounded-3 bg-body shadow-sm py-4 px-5 mx-3">
                                <div class="fs-2 fw-bold text-gray-800" id="kt_coming_soon_counter_minutes"></div>
                                <div class="fs-7 text-muted">min</div>
                            </div>

                            <div class="w-65px rounded-3 bg-body shadow-sm py-4 px-5 mx-3">
                                <div class="fs-2 fw-bold text-gray-800" id="kt_coming_soon_counter_seconds"></div>
                                <div class="fs-7 text-muted">sec</div>
                            </div>
                        </div> --}}
                        <!--end::Counter-->
                        <!--begin::Text-->
                        <div class="fw-semibold fs-6 text-gray-500 mb-7">This is your opportunity to get creative amazing opportunaties
                            <br />that gives readers an idea</div>
                        <!--end::Text-->
                        <!--begin::Form-->
                        <form class="w-md-350px mb-2 mx-auto" action="#" id="kt_coming_soon_form">
                            <div class="fv-row text-start">
                                <div class="d-flex flex-column flex-md-row justify-content-center gap-3">
                                    <!--end::Input=-->
                                    <input type="text" placeholder="Email" name="email" autocomplete="off" class="form-control" />
                                    <!--end::Input=-->
                                    <!--begin::Submit-->
                                    <button class="btn btn-primary text-nowrap" id="kt_coming_soon_submit">
                                        <!--begin::Indicator label-->
                                        <span class="indicator-label">Notify Me</span>
                                        <!--end::Indicator label-->
                                        <!--begin::Indicator progress-->
                                        <span class="indicator-progress">Please wait...
                                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                        <!--end::Indicator progress-->
                                    </button>
                                    <!--end::Submit-->
                                </div>
                            </div>
                        </form>
                        <!--end::Form-->
                        <!--begin::Illustration-->
                        <div class="mb-n5">
                            <img src="{{ asset('assets/media/auth/chart-graph.png') }}" class="mw-100 mh-300px theme-light-show" alt="" />
                            <img src="{{ asset('assets/media/auth/chart-graph-dark.png') }}" class="mw-100 mh-300px theme-dark-show" alt="" />
                        </div>
                        <!--end::Illustration-->
                    </div>
                </div>
                <!--end::Wrapper-->
            </div>
            <!--end::Content-->
        </div>
        <!--end::Authentication - Signup Welcome Message-->
    </div>
    <!--end::Root-->
</x-auth-layout>
