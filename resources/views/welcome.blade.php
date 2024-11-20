<x-app-layout>

    <!-- start banner -->
    <section class="p-0 full-screen ipad-top-space-margin position-relative overflow-hidden md-h-auto">
        <div class="container-fluid p-0 h-100 position-relative">
            <div class="row h-100 g-0">
                <div
                    class="col-xl-5 col-lg-6 d-flex justify-content-center flex-column ps-10 xxl-ps-5 xl-ps-2 md-ps-0 position-relative order-2 order-lg-1">
                    <div
                        class="vertical-title-center align-items-center w-75px justify-content-center position-absolute h-auto d-none d-md-flex">
                        <div class="title fs-16 alt-font text-dark-gray fw-700 text-uppercase ls-05px text-uppercase"
                            data-fancy-text='{ "opacity": [0, 1], "translateY": [50, 0], "filter": ["blur(20px)", "blur(0px)"], "string": ["Solusi Terintegrasi untuk Manajemen Perhotelan"], "duration": 400, "delay": 0, "speed": 50, "easing": "easeOutQuad" }'>
                        </div>
                    </div>
                    <div class="border-start border-color-extra-medium-gray ps-60px ms-100px lg-ps-30px lg-ms-70px position-relative z-index-9 sm-ps-30px sm-pe-30px sm-ms-0 border-0"
                        data-anime='{ "el": "childs", "translateY": [30, 0], "opacity": [0,1], "duration": 600, "delay":0, "staggervalue": 300, "easing": "easeOutQuad" }'>
                        <h1
                            class="text-dark-gray fw-600 alt-font outside-box-right-10 xl-outside-box-right-15 ls-minus-4px sm-ls-minus-2px md-me-0">
                            Suitify
                            Solusi Terintegrasi untuk Manajemen Perhotelan</h1>
                        <p class="w-75 mb-35px lg-w-90 sm-w-100">Tingkatkan efisiensi operasional hotel Anda dengan
                            teknologi modern dan kemudahan penggunaan. Kelola reservasi, kamar, dan layanan tamu dalam
                            satu platform cerdas.</p>
                        <a href="#" class="btn btn-extra-large btn-gradient-fuel-yellow-blue fw-400">Let's talk -
                            Send a message</a>
                    </div>
                </div>
                <div
                    class="col-xl-7 col-lg-6 position-relative swiper-number-pagination-progress md-h-500px order-1 order-lg-2 md-mb-50px">
                    <div class="swiper h-100 banner-slider magic-cursor drag-cursor"
                        data-slider-options='{ "slidesPerView": 1, "loop": true, "pagination": { "el": ".swiper-number-line-pagination", "clickable": true }, "navigation": { "nextEl": ".slider-one-slide-next-1", "prevEl": ".slider-one-slide-prev-1" }, "autoplay": { "delay": 4000, "stopOnLastSlide": true, "disableOnInteraction": false },"keyboard": { "enabled": true, "onlyInViewport": true }, "effect": "fade" }'
                        data-swiper-number-pagination-progress="true">
                        <div class="swiper-wrapper">
                            <!-- start slider item -->
                            <div class="swiper-slide">
                                <div class="position-absolute left-0px top-0px w-100 h-100 cover-background background-position-center-top"
                                    style="background-image:url('{{ asset('storage/images/illustration/suitify_illustration_photo_01.jpg') }}');">
                                </div>
                            </div>
                            <!-- end slider item -->
                            <!-- start slider item -->
                            <div class="swiper-slide">
                                <div class="position-absolute left-0px top-0px w-100 h-100 cover-background background-position-center-top"
                                    style="background-image:url('https://127.0.0.1:8000/storage/images/illustration/suitify_illustration_photo_01.jpg');">
                                </div>
                            </div>
                            <!-- end slider item -->
                            <!-- start slider item -->
                            <div class="swiper-slide">
                                <div class="position-absolute left-0px top-0px w-100 h-100 cover-background background-position-center-top"
                                    style="background-image:url('https://127.0.0.1:8000/storage/images/illustration/suitify_illustration_photo_01.jpg');">
                                </div>
                            </div>
                            <!-- end slider item -->
                        </div>
                        <!-- start slider pagination -->
                        <div
                            class="swiper-pagination swiper-pagination-clickable swiper-pagination-bullets d-block d-sm-none">
                        </div>
                        <!-- end slider pagination -->
                        <!-- start slider navigation -->
                        <!-- <div class="slider-one-slide-prev-1 icon-very-small text-white swiper-button-prev slider-navigation-style-06 d-none d-sm-inline-block"><i class="line-icon-Arrow-OutLeft icon-extra-large"></i></div>
                            <div class="slider-one-slide-next-1 icon-very-small text-white swiper-button-next slider-navigation-style-06 d-none d-sm-inline-block"><i class="line-icon-Arrow-OutRight icon-extra-large"></i></div> -->
                        <!-- end slider navigation -->
                    </div>
                    <!-- start slider pagination -->
                    <div
                        class="swiper-pagination-wrapper d-none d-lg-flex align-items-center justify-content-center position-absolute bottom-40px md-bottom-30px sm-bottom-20px left-minus-45 md-left-30px sm-left-20px z-index-9">
                        <div class="number-prev fs-14 fw-600 text-dark-gray"></div>
                        <div class="swiper-pagination-progress bg-extra-medium-gray">
                            <span class="swiper-progress"></span>
                        </div>
                        <div class="number-next fs-14 fw-600 text-dark-gray"></div>
                    </div>
                    <!-- end slider pagination -->
                </div>
            </div>
        </div>
    </section>
    <!-- end banner -->
    <!-- start section -->
    <section>
        <div class="container">
            <div class="row row-cols-1 row-cols-lg-5 row-cols-md-3 row-cols-sm-2 clients-style-06 justify-content-center"
                data-anime='{ "el": "childs", "translateY": [30, 0], "opacity": [0,1], "duration": 600, "delay":0, "staggervalue": 300, "easing": "easeOutQuad" }'>
                {{-- <!-- start client item -->
                <div class="col client-box text-center md-mb-35px">
                    <a href="#"><img src="images/logo-walmart.svg" class="h-35px" alt=""></a>
                </div>
                <!-- end client item -->
                <!-- start client item -->
                <div class="col client-box text-center md-mb-35px">
                    <a href="#"><img src="images/logo-logitech.svg" class="h-35px" alt=""></a>
                </div>
                <!-- end client item -->
                <!-- start client item -->
                <div class="col client-box text-center md-mb-35px">
                    <a href="#"><img src="images/logo-monday.svg" class="h-35px" alt=""></a>
                </div>
                <!-- end client item -->
                <!-- start client item -->
                <div class="col client-box text-center sm-mb-35px">
                    <a href="#"><img src="images/logo-google.svg" class="h-35px" alt=""></a>
                </div>
                <!-- end client item -->
                <!-- start client item -->
                <div class="col client-box text-center">
                    <a href="#"><img src="images/logo-paypal.svg" class="h-35px" alt=""></a>
                </div>
                <!-- end client item --> --}}
            </div>
        </div>
    </section>
    <!-- end section -->
    <!-- start section -->
    <section class="pt-0">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 position-relative">
                    <div class="row align-items-center position-relative md-mb-15"
                        data-anime='{ "opacity": [0,1], "duration": 600, "delay":0, "staggervalue": 300, "easing": "easeOutQuad" }'>
                        <div class="col-md-5 sm-mb-30px" data-bottom-top="transform: translateY(50px)"
                            data-top-bottom="transform: translateY(-50px)">
                            <img class="w-100" src="https://via.placeholder.com/421x524" alt="" />
                        </div>
                        <div class="col-lg-7 col-md-7 sm-mb-30px text-end"
                            data-bottom-top="transform: translateY(-30px)"
                            data-top-bottom="transform: translateY(30px)">
                            <img src="https://via.placeholder.com/261x313" alt=""
                                class="box-shadow-quadruple-large md-w-100" />
                        </div>
                        <div class="w-50 sm-w-100 overflow-hidden position-absolute sm-position-relative left-140px bottom-minus-200px sm-bottom-0px sm-left-0px p-0 sm-ps-15px sm-pe-15px"
                            data-shadow-animation="true" data-animation-delay="100"
                            data-bottom-top="transform: translateY(20px)"
                            data-top-bottom="transform: translateY(-20px)">
                            <img src="https://via.placeholder.com/500x614" alt=""
                                class="box-shadow-quadruple-large w-100" />
                        </div>
                    </div>
                </div>
                <div class="col-xl-5 col-lg-6 offset-xl-1 md-mt-20 sm-mt-0"
                    data-anime='{ "el": "childs", "translateY": [30, 0], "opacity": [0,1], "duration": 600, "delay":0, "staggervalue": 300, "easing": "easeOutQuad" }'>
                    <div class="mb-10px">
                        <span class="w-25px h-1px d-inline-block bg-base-color me-5px align-middle"></span>
                        <span
                            class="text-gradient-base-color fs-15 alt-font fw-700 ls-05px text-uppercase d-inline-block align-middle">Mengapa
                            Memilih Suitify?</span>
                    </div>
                    <h4 class="text-dark-gray alt-font fw-600 ls-minus-2px mb-20px">Mitra Terbaik untuk Pengelolaan
                        Hotel Anda</h4>
                    <p class="w-90 md-w-100 mb-35px sm-mb-20px">Dengan Suitify, Anda dapat mengelola operasional hotel
                        secara efisien dan profesional. Platform kami dirancang untuk memenuhi kebutuhan perhotelan
                        dengan fitur yang mudah digunakan dan hasil yang maksimal.</p>
                    <a href="demo-branding-agency-about.html"
                        class="btn btn-large btn-dark-gray btn-switch-text btn-box-shadow border-1 left-icon me-10px sm-mb-15px sm-mt-15px">
                        <span>
                            <span><i class="feather icon-feather-edit"></i></span>
                            <span class="btn-double-text" data-text="Explore details">Explore details</span>
                        </span>
                    </a>
                    <a href="demo-branding-agency-services.html"
                        class="btn btn-large btn-transparent-light-gray border-1 btn-switch-text left-icon sm-mb-15px sm-mt-15px">
                        <span>
                            <span><i class="feather icon-feather-briefcase"></i></span>
                            <span class="btn-double-text" data-text="More services">More services</span>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </section>
    <!-- end section -->
    <!-- start section -->
    <section class="pt-0 border-top border-color-extra-medium-gray mt-6 md-mt-0 sm-border-top-0">
        <div class="container">
            <div class="row row-cols-1 row-cols-md-3 row-cols-sm-2 g-0"
                data-anime='{ "el": "childs", "translateX": [30, 0], "opacity": [0,1], "duration": 600, "delay":0, "staggervalue": 300, "easing": "easeOutQuad" }'>
                <!-- start process step item -->
                <div class="col process-step-style-06 last-paragraph-no-margin hover-box sm-mb-50px">
                    <div class="process-step-icon-box position-relative top-minus-14px">
                        <span class="progress-step-separator bg-light-gray w-100 separator-line-1px opacity-1"></span>
                        <div
                            class="step-box d-flex align-items-center justify-content-center bg-white border-radius-100 w-25px h-25px position-relative border border-color-extra-medium-gray box-shadow-large">
                            <span class="w-7px h-7px bg-dark-gray border-radius-100"></span>
                        </div>
                    </div>
                    <span
                        class="d-block alt-font text-dark-gray fw-600 mb-10px mt-15px fs-22 lh-28 ls-minus-05px w-60 lg-w-75 md-w-100">Step
                        1: Mulai dengan Mudah</span>
                    <p class="w-60 lg-w-75 md-w-100 sm-w-100">Daftarkan hotel Anda dalam beberapa langkah sederhana.
                        Suitify siap membantu Anda memulai perjalanan digitalisasi perhotelan.</p>
                </div>
                <!-- end process step item -->
                <!-- start process step item -->
                <div class="col process-step-style-06 last-paragraph-no-margin hover-box sm-mb-50px">
                    <div class="process-step-icon-box position-relative top-minus-14px">
                        <span class="progress-step-separator bg-light-gray w-100 separator-line-1px opacity-1"></span>
                        <div
                            class="step-box d-flex align-items-center justify-content-center bg-white border-radius-100 w-25px h-25px position-relative border border-color-extra-medium-gray box-shadow-large">
                            <span class="w-7px h-7px bg-dark-gray border-radius-100"></span>
                        </div>
                    </div>
                    <span
                        class="d-block alt-font text-dark-gray fw-600 mb-10px mt-15px fs-22 lh-28 ls-minus-05px w-60 lg-w-75 md-w-100">Step
                        2: Kelola dengan Efisiensi</span>
                    <p class="w-60 lg-w-75 md-w-100 sm-w-100">Pantau reservasi, kelola kamar, dan atur layanan tamu
                        dari satu dashboard yang terintegrasi dan mudah digunakan.</p>
                </div>
                <!-- end process step item -->
                <!-- start process step item -->
                <div class="col process-step-style-06 last-paragraph-no-margin hover-box">
                    <div class="process-step-icon-box position-relative top-minus-14px">
                        <span class="progress-step-separator bg-light-gray w-100 separator-line-1px opacity-1"></span>
                        <div
                            class="step-box d-flex align-items-center justify-content-center bg-white border-radius-100 w-25px h-25px position-relative border border-color-extra-medium-gray box-shadow-large">
                            <span class="w-7px h-7px bg-dark-gray border-radius-100"></span>
                        </div>
                    </div>
                    <span
                        class="d-block alt-font text-dark-gray fw-600 mb-10px mt-15px fs-22 lh-28 ls-minus-05px w-60 lg-w-75 md-w-100">Step
                        3: Tingkatkan Kepuasan Tamu</span>
                    <p class="w-60 lg-w-75 md-w-100 sm-w-100">Optimalkan pengalaman tamu Anda dengan fitur otomatisasi
                        dan laporan yang akurat untuk meningkatkan pelayanan dan kepercayaan pelanggan.</p>
                </div>
                <!-- end process step item -->
            </div>
        </div>
    </section>
    <!-- end section -->
    <!-- start section -->
    {{-- <section class="overflow-hidden position-relative pt-0 lg-pb-0">
        <div class="container-fluid">
            <div class="row position-relative">
                <div class="col swiper swiper-width-auto feather-shadow text-center"
                    data-slider-options='{ "slidesPerView": "auto", "spaceBetween":40, "speed": 20000, "loop": true, "allowTouchMove": false, "autoplay": { "delay":0, "disableOnInteraction": false, "reverseDirection": true }, "effect": "slide" }'>
                    <div class="swiper-wrapper pb-30px marquee-slide">
                        <!-- start client item -->
                        <div class="swiper-slide">
                            <div
                                class="fs-130 md-fs-90 sm-fs-70 alt-font text-dark-gray fw-600 ls-minus-6px sm-ls-minus-2px word-break-normal">
                                Transformasi Digital untuk Hotel Anda <span class="ms-20px">-</span></div>
                        </div>
                        <!-- end client item -->
                        <!-- start client item -->
                        <div class="swiper-slide">
                            <div
                                class="fs-130 md-fs-90 sm-fs-70 alt-font text-dark-gray fw-600 ls-minus-6px sm-ls-minus-2px word-break-normal">
                                Mengelola Hotel Jadi Lebih Mudah <span class="ms-20px">-</span></div>
                        </div>
                        <!-- end client item -->
                    </div>
                </div>
            </div>
        </div>
    </section> --}}
    <!-- end section -->
    <!-- start section -->
    <section class="stack-box py-0 z-index-99">
        <div class="stack-box-contain">
            <!-- start stack item -->
            <div class="stack-item stack-item-01 bg-white lg-pt-8 lg-pb-8 md-pb-0">
                <div class="stack-item-wrapper">
                    <div class="container-fluid">
                        <div class="row align-items-center full-screen md-h-auto">
                            <div class="col-lg-6 cover-background overflow-visible h-100 md-h-500px"
                                style="background-image: url('{{ asset('storage/images/illustration/suitify_illustration_photo_04.jpg') }}');">
                                <div
                                    class="position-absolute right-minus-130px top-60px md-top-auto md-bottom-minus-50px fs-170 lg-fs-120 lg-right-minus-80px md-right-0px md-left-0px text-center text-lg-start alt-font z-index-9 fw-600 text-dark-gray opacity-3">
                                    01</div>
                                <div class="position-absolute right-0px bottom-minus-1px">
                                    <div class="vertical-title-center">
                                        <div
                                            class="title fw-700 fs-15 alt-font text-uppercase text-dark-gray bg-white pt-30px pb-30px ps-10px pe-10px">
                                            <span class="d-inline-block">Fitur Teratas</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="col-lg-6 ps-12 pe-14 xxl-ps-10 xxl-pe-10 xl-pe-8 lg-ps-6 lg-pe-4 md-p-50px sm-ps-30px sm-pe-30px position-relative align-self-center text-md-start text-center">
                                <div class="mb-15px">
                                    <span class="w-25px h-1px d-inline-block bg-base-color me-5px align-middle"></span>
                                    <span
                                        class="text-gradient-base-color fs-15 alt-font fw-700 ls-05px text-uppercase d-inline-block align-middle">Pemesanan</span>
                                </div>
                                <h1 class="text-dark-gray alt-font fw-600 ls-minus-4px mb-25px">Sistem Pemesanan
                                    Real-Time.
                                </h1>
                                <p class="w-95 md-w-100 mb-35px">Fitur pemesanan langsung memungkinkan tamu untuk
                                    melakukan reservasi kapan saja, tanpa batasan. Semua data tersinkronisasi otomatis
                                    untuk memastikan pengalaman tanpa hambatan.
                                </p>
                                <a href="demo-branding-agency-single-project-slider.html"
                                    class="btn btn-large btn-dark-gray btn-switch-text btn-box-shadow fw-400">
                                    <span>
                                        <span class="btn-double-text" data-text="Explore project">Explore
                                            project</span>
                                        <span><i class="feather icon-feather-arrow-right"></i></span>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end stack item -->
            <!-- start stack item -->
            <div class="stack-item stack-item-02 bg-linen md-pt-0 md-pb-0">
                <div class="stack-item-wrapper">
                    <div class="container-fluid">
                        <div class="row align-items-center full-screen md-h-auto">
                            <div class="col-lg-6 cover-background overflow-visible h-100 md-h-500px"
                                style="background-image: url('{{ asset('storage/images/illustration/suitify_illustration_photo_03.jpg') }}');">
                                <div
                                    class="position-absolute right-minus-130px top-60px md-top-auto md-bottom-minus-50px fs-170 lg-fs-120 lg-right-minus-80px md-right-0px md-left-0px text-center text-lg-start alt-font z-index-9 fw-600 text-dark-gray opacity-3">
                                    02</div>
                                <div class="position-absolute right-0px bottom-minus-1px">
                                    <div class="vertical-title-center">
                                        <div
                                            class="title fw-700 fs-15 alt-font text-uppercase text-dark-gray bg-linen pt-30px pb-30px ps-10px pe-10px">
                                            <span class="d-inline-block">Fitur Teratas</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="col-lg-6 ps-12 pe-14 xxl-ps-10 xxl-pe-10 xl-pe-8 lg-ps-6 lg-pe-4 md-p-50px sm-ps-30px sm-pe-30px position-relative align-self-center text-md-start text-center">
                                <div class="mb-15px">
                                    <span class="w-25px h-1px d-inline-block bg-base-color me-5px align-middle"></span>
                                    <span
                                        class="text-gradient-base-color fs-15 alt-font fw-700 ls-05px text-uppercase d-inline-block align-middle">Monitoring
                                        Ketersediaan Kamar</span>
                                </div>
                                <h1 class="text-dark-gray alt-font fw-600 ls-minus-4px mb-25px">Pemantauan Ketersediaan
                                    Kamar.
                                </h1>
                                <p class="w-95 md-w-100 mb-35px">Pantau ketersediaan kamar hotel Anda secara langsung.
                                    Dengan pembaruan otomatis, Suitify memastikan tidak ada double booking dan semua
                                    kamar dikelola dengan efisien.</p>
                                <a href="demo-branding-agency-single-project-slider.html"
                                    class="btn btn-large btn-dark-gray btn-switch-text btn-box-shadow fw-400">
                                    <span>
                                        <span class="btn-double-text" data-text="Explore project">Explore
                                            project</span>
                                        <span><i class="feather icon-feather-arrow-right"></i></span>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end stack item -->
            <!-- start stack item -->
            <div class="stack-item stack-item-03 bg-white lg-pt-8 md-pb-0 md-pt-0">
                <div class="stack-item-wrapper">
                    <div class="container-fluid">
                        <div class="row align-items-center full-screen md-h-auto">
                            <div class="col-lg-6 cover-background overflow-visible h-100 md-h-500px"
                                style="background-image: url('{{ asset('storage/images/illustration/suitify_illustration_photo_02.jpg') }}');">
                                <div
                                    class="position-absolute right-minus-130px top-60px md-top-auto md-bottom-minus-50px fs-170 lg-fs-120 lg-right-minus-80px md-right-0px md-left-0px text-center text-lg-start alt-font z-index-9 fw-600 text-dark-gray opacity-3">
                                    03</div>
                                <div class="position-absolute right-0px bottom-minus-1px">
                                    <div class="vertical-title-center">
                                        <div
                                            class="title fw-700 fs-15 alt-font text-uppercase text-dark-gray bg-white pt-30px pb-30px ps-10px pe-10px">
                                            <span class="d-inline-block">Fitur Teratas</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="col-lg-6 ps-12 pe-14 xxl-ps-10 xxl-pe-10 xl-pe-8 lg-ps-6 lg-pe-4 md-p-50px sm-ps-30px sm-pe-30px sm-pb-0 position-relative align-self-center text-md-start text-center">
                                <div class="mb-15px">
                                    <span class="w-25px h-1px d-inline-block bg-base-color me-5px align-middle"></span>
                                    <span
                                        class="text-gradient-base-color fs-15 alt-font fw-700 ls-05px text-uppercase d-inline-block align-middle">Analisis
                                        Data</span>
                                </div>
                                <h1 class="text-dark-gray alt-font fw-600 ls-minus-4px mb-25px">Laporan dan Analisis.
                                </h1>
                                <p class="w-95 md-w-100 mb-35px">Dapatkan wawasan yang mendalam dengan laporan yang
                                    terperinci. Suitify menyediakan analisis kinerja hotel, membantu Anda membuat
                                    keputusan yang lebih baik dan meningkatkan profitabilitas.

                                </p>
                                <a href="demo-branding-agency-single-project-slider.html"
                                    class="btn btn-large btn-dark-gray btn-switch-text btn-box-shadow fw-400">
                                    <span>
                                        <span class="btn-double-text" data-text="Explore project">Explore
                                            project</span>
                                        <span><i class="feather icon-feather-arrow-right"></i></span>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end stack item -->
        </div>
    </section>
    <!-- end section -->
    <!-- start section -->
    {{-- <section class="pb-0">
        <div class="container">
            <div class="row mb-6">
                <div class="col-xl-5 col-md-6"
                    data-anime='{ "el": "childs", "translateY": [30, 0], "opacity": [0,1], "duration": 600, "delay":0, "staggervalue": 300, "easing": "easeOutQuad" }'>
                    <div class="mb-10px">
                        <span class="w-25px h-1px d-inline-block bg-base-color me-5px align-middle"></span>
                        <span
                            class="text-gradient-base-color fs-15 alt-font fw-700 ls-05px text-uppercase d-inline-block align-middle">Layanan
                            Suitify</span>
                    </div>
                    <h4 class="text-dark-gray alt-font fw-600 w-90 ls-minus-2px">Layanan Suitify
                        Dukungan Lengkap untuk Operasional Hotel Anda.</h4>
                </div>
                <div class="offset-xl-1 col-xl-5 col-md-6">
                    <div class="accordion accordion-style-02" id="accordion-style-02"
                        data-active-icon="icon-feather-minus" data-inactive-icon="icon-feather-plus"
                        data-anime='{ "el": "childs", "translateY": [30, 0], "opacity": [0,1], "duration": 600, "delay":0, "staggervalue": 300, "easing": "easeOutQuad" }'>
                        <!-- start accordion item -->
                        <div class="accordion-item active-accordion">
                            <div class="accordion-header border-bottom border-color-dark-gray">
                                <a href="#" data-bs-toggle="collapse" data-bs-target="#accordion-style-02-01"
                                    aria-expanded="true" data-bs-parent="#accordion-style-02">
                                    <div class="accordion-title mb-0 position-relative text-dark-gray">
                                        <i class="feather icon-feather-minus"></i><span
                                            class="fw-600 alt-font fs-18">Research and strategy</span>
                                    </div>
                                </a>
                            </div>
                            <div id="accordion-style-02-01" class="accordion-collapse collapse show"
                                data-bs-parent="#accordion-style-02">
                                <div
                                    class="accordion-body last-paragraph-no-margin border-bottom border-color-dark-gray">
                                    <p>We strive to develop real-world web solutions that are ideal for small to large
                                        project.</p>
                                </div>
                            </div>
                        </div>
                        <!-- end accordion item -->
                        <!-- start accordion item -->
                        <div class="accordion-item">
                            <div class="accordion-header border-bottom border-color-dark-gray">
                                <a href="#" data-bs-toggle="collapse" data-bs-target="#accordion-style-02-02"
                                    aria-expanded="false" data-bs-parent="#accordion-style-02">
                                    <div class="accordion-title mb-0 position-relative text-dark-gray">
                                        <i class="feather icon-feather-plus"></i><span
                                            class="fw-600 alt-font fs-18">Wireframes and design</span>
                                    </div>
                                </a>
                            </div>
                            <div id="accordion-style-02-02" class="accordion-collapse collapse"
                                data-bs-parent="#accordion-style-02">
                                <div
                                    class="accordion-body last-paragraph-no-margin border-bottom border-color-dark-gray">
                                    <p>We strive to develop real-world web solutions that are ideal for small to large
                                        project.</p>
                                </div>
                            </div>
                        </div>
                        <!-- end accordion item -->
                        <!-- start accordion item -->
                        <div class="accordion-item">
                            <div class="accordion-header border-bottom border-color-transparent">
                                <a href="#" data-bs-toggle="collapse" data-bs-target="#accordion-style-02-03"
                                    aria-expanded="false" data-bs-parent="#accordion-style-02">
                                    <div class="accordion-title mb-0 position-relative text-dark-gray">
                                        <i class="feather icon-feather-plus"></i><span
                                            class="fw-600 alt-font fs-18">Maintenance and support</span>
                                    </div>
                                </a>
                            </div>
                            <div id="accordion-style-02-03" class="accordion-collapse collapse"
                                data-bs-parent="#accordion-style-02">
                                <div
                                    class="accordion-body last-paragraph-no-margin border-bottom border-color-transparent">
                                    <p>We strive to develop real-world web solutions that are ideal for small to large
                                        project.</p>
                                </div>
                            </div>
                        </div>
                        <!-- end accordion item -->
                    </div>
                </div>
            </div>
            <div class="row row-cols-1 row-cols-lg-4 row-cols-md-2 counter-style-04"
                data-anime='{ "el": "childs", "translateX": [30, 0], "opacity": [0,1], "duration": 600, "delay":0, "staggervalue": 300, "easing": "easeOutQuad" }'>
                <!-- start counter item -->
                <div class="col last-paragraph-no-margin md-mb-30px text-center text-md-start">
                    <span class="alt-font fw-600 d-block mb-5px text-dark-gray fs-18">Days of experience.</span>
                    <p class="w-90 sm-w-100 sm-ps-15 sm-pe-15">We have crafted beautiful and engaging web solutions.
                    </p>
                    <div class="separator-line-1px bg-extra-medium-gray w-90 mt-25px mb-25px sm-w-100"></div>
                    <h3 class="vertical-counter d-inline-flex alt-font text-dark-gray fw-700 ls-minus-2px sm-ls-minus-1px sm-mb-0"
                        data-text="+" data-to="3053"></h3>
                </div>
                <!-- end counter item -->
                <!-- start counter item -->
                <div class="col last-paragraph-no-margin md-mb-30px text-center text-md-start">
                    <span class="alt-font fw-600 d-block mb-5px text-dark-gray fs-18">Valuable happy clients.</span>
                    <p class="w-90 sm-w-100 sm-ps-15 sm-pe-15">We have crafted beautiful and engaging web solutions.
                    </p>
                    <div class="separator-line-1px bg-extra-medium-gray w-90 mt-25px mb-25px sm-w-100"></div>
                    <h3 class="vertical-counter d-inline-flex alt-font text-dark-gray fw-700 ls-minus-2px sm-ls-minus-1px sm-mb-0"
                        data-text="+" data-to="1750"></h3>
                </div>
                <!-- end counter item -->
                <!-- start counter item -->
                <div class="col last-paragraph-no-margin sm-mb-30px text-center text-md-start">
                    <span class="alt-font fw-600 d-block mb-5px text-dark-gray fs-18">Presence in countries.</span>
                    <p class="w-90 sm-w-100 sm-ps-15 sm-pe-15">We have crafted beautiful and engaging web solutions.
                    </p>
                    <div class="separator-line-1px bg-extra-medium-gray w-90 mt-25px mb-25px sm-w-100"></div>
                    <h3 class="vertical-counter d-inline-flex alt-font text-dark-gray fw-700 ls-minus-2px sm-ls-minus-1px sm-mb-0"
                        data-text="+" data-to="50"></h3>
                </div>
                <!-- end counter item -->
                <!-- start counter item -->
                <div class="col last-paragraph-no-margin text-center text-md-start">
                    <span class="alt-font fw-600 d-block mb-5px text-dark-gray fs-18">Worldwide projects.</span>
                    <p class="w-90 sm-w-100 sm-ps-15 sm-pe-15">We have crafted beautiful and engaging web solutions.
                    </p>
                    <div class="separator-line-1px bg-extra-medium-gray w-90 mt-25px mb-25px sm-w-100"></div>
                    <h3 class="vertical-counter d-inline-flex alt-font text-dark-gray fw-700 ls-minus-2px sm-ls-minus-1px sm-mb-0"
                        data-text="+" data-to="856"></h3>
                </div>
                <!-- end counter item -->
            </div>
        </div>
    </section> --}}
    <!-- end section -->
    <!-- start section -->
    <section class="pb-0">
        <div class="container-fluid p-0">
            <div class="row row-cols-1 row-cols-lg-4 row-cols-sm-2 g-0"
                data-anime='{ "el": "childs", "translateY": [30, 0], "rotateX":[30, 0], "opacity": [0,1], "duration": 600, "delay":0, "staggervalue": 300, "easing": "easeOutQuad" }'>
                <!-- start team member item -->
                <div class="col team-style-10 md-ps-15px md-pe-15px md-mb-30px">
                    <figure class="mb-0 position-relative overflow-hidden">
                        <img src="https://via.placeholder.com/480x605" class="w-100" alt="" />
                        <img src="https://via.placeholder.com/480x605" class="hover-switch-image" alt="" />
                        <figcaption class="w-100 h-100 d-flex flex-wrap">
                            <div class="social-icon d-flex flex-column flex-shrink-1 mb-auto p-30px ms-auto">
                                <a href="https://www.twitter.com/" target="_blank" class="text-white bg-dark-gray"><i
                                        class="fa-brands fa-twitter icon-small"></i></a>
                            </div>
                            <div
                                class="team-member-strip w-100 mt-auto d-flex align-items-center pt-15px pb-15px ps-30px pe-30px bg-white">
                                <span
                                    class="team-member-name fw-600 alt-font text-dark-gray fs-18 ls-minus-05px">Jeremy
                                    dupont</span>
                                <span class="member-designation fs-15 lh-20 ms-auto alt-font">Designer</span>
                            </div>
                        </figcaption>
                    </figure>
                </div>
                <!-- end team member item -->
                <!-- start team member item -->
                <div class="col team-style-10 mt-20px md-mt-0 md-ps-15px md-pe-15px md-mb-30px">
                    <figure class="mb-0 position-relative overflow-hidden">
                        <img src="https://via.placeholder.com/480x605" class="w-100" alt="" />
                        <img src="https://via.placeholder.com/480x605" class="hover-switch-image" alt="" />
                        <figcaption class="w-100 h-100 d-flex flex-wrap">
                            <div class="social-icon d-flex flex-column flex-shrink-1 mb-auto p-30px ms-auto">
                                <a href="https://www.facebook.com/" target="_blank"
                                    class="text-white bg-dark-gray"><i
                                        class="fa-brands fa-facebook-f icon-small"></i></a>
                            </div>
                            <div
                                class="team-member-strip w-100 mt-auto d-flex align-items-center pt-15px pb-15px ps-30px pe-30px bg-white">
                                <span
                                    class="team-member-name fw-600 alt-font text-dark-gray fs-18 ls-minus-05px">Matthew
                                    taylor</span>
                                <span class="member-designation fs-15 lh-20 ms-auto alt-font">Writer</span>
                            </div>
                        </figcaption>
                    </figure>
                </div>
                <!-- end team member item -->
                <!-- start team member item -->
                <div class="col team-style-10 mt-40px md-mt-0 md-ps-15px md-pe-15px sm-mb-30px">
                    <figure class="mb-0 position-relative overflow-hidden">
                        <img src="https://via.placeholder.com/480x605" class="w-100" alt="" />
                        <img src="https://via.placeholder.com/480x605" class="hover-switch-image" alt="" />
                        <figcaption class="w-100 h-100 d-flex flex-wrap">
                            <div class="social-icon d-flex flex-column flex-shrink-1 mb-auto p-30px ms-auto">
                                <a href="https://www.linkedin.com/" target="_blank"
                                    class="text-white bg-dark-gray"><i
                                        class="fa-brands fa-linkedin-in icon-small"></i></a>
                            </div>
                            <div
                                class="team-member-strip w-100 mt-auto d-flex align-items-center pt-15px pb-15px ps-30px pe-30px bg-white">
                                <span
                                    class="team-member-name fw-600 alt-font text-dark-gray fs-18 ls-minus-05px">Herman
                                    miller</span>
                                <span class="member-designation fs-15 lh-20 ms-auto alt-font">Manager</span>
                            </div>
                        </figcaption>
                    </figure>
                </div>
                <!-- end team member item -->
                <!-- start team member item -->
                <div class="col team-style-10 mt-60px md-mt-0 md-ps-15px md-pe-15px">
                    <figure class="mb-0 position-relative overflow-hidden">
                        <img src="https://via.placeholder.com/480x605" class="w-100" alt="" />
                        <img src="https://via.placeholder.com/480x605" class="hover-switch-image" alt="" />
                        <figcaption class="w-100 h-100 d-flex flex-wrap">
                            <div class="social-icon d-flex flex-column flex-shrink-1 mb-auto p-30px ms-auto">
                                <a href="https://www.instagram.com/" target="_blank"
                                    class="text-white bg-dark-gray"><i
                                        class="fa-brands fa-instagram icon-small"></i></a>
                            </div>
                            <div
                                class="team-member-strip w-100 mt-auto d-flex align-items-center pt-15px pb-15px ps-30px pe-30px bg-white">
                                <span
                                    class="team-member-name fw-600 alt-font text-dark-gray fs-18 ls-minus-05px">Jessica
                                    dover</span>
                                <span class="member-designation fs-15 lh-20 ms-auto alt-font">Designer</span>
                            </div>
                        </figcaption>
                    </figure>
                </div>
                <!-- end team member item -->
            </div>
        </div>
    </section>
    <!-- end section -->
    <!-- start section -->
    <section class="big-section position-relative">
        <div class="background-position-center-top background-no-repeat position-absolute h-100 w-100 left-0px top-0px"
            style="background-image: url('https://via.placeholder.com/1126x630')"></div>
        <div class="container position-relative">
            <div class="row justify-content-center mb-8"
                data-anime='{ "opacity": [0,1], "duration": 600, "delay":0, "staggervalue": 300, "easing": "easeOutQuad" }'>
                <div class="col-xl-4 col-md-5">
                    <h2 class="alt-font text-dark-gray mb-30px fw-600 ls-minus-3px">We are trusted by our clients<i
                            class="bi bi-heart-fill d-inline-block align-top ms-10px animation-zoom icon-very-medium text-red"></i>
                    </h2>
                    <div class="d-flex md-mb-25px">
                        <!-- start slider navigation -->
                        <div
                            class="slider-one-slide-prev-1 text-dark-gray swiper-button-prev slider-navigation-style-04 border border-1 border-color-extra-medium-gray bg-white">
                            <i class="fa-solid fa-arrow-left"></i>
                        </div>
                        <div
                            class="slider-one-slide-next-1 text-dark-gray swiper-button-next slider-navigation-style-04 border border-1 border-color-extra-medium-gray bg-white">
                            <i class="fa-solid fa-arrow-right"></i>
                        </div>
                        <!-- end slider navigation -->
                    </div>
                </div>
                <div class="col-xl-6 col-md-7 overflow-hidden offset-xl-2">
                    <div class="swiper"
                        data-slider-options='{ "slidesPerView": 1, "spaceBetween": 40, "loop": true, "autoplay": { "delay": 8000, "disableOnInteraction": false }, "navigation": { "nextEl": ".slider-one-slide-next-1", "prevEl": ".slider-one-slide-prev-1" }, "keyboard": { "enabled": true, "onlyInViewport": true }, "breakpoints": { "992": { "slidesPerView": 1 }, "768": { "slidesPerView":1 }, "320": { "slidesPerView": 1 } }, "effect": "slide" }'>
                        <div class="swiper-wrapper">
                            <!-- start review item -->
                            <div class="swiper-slide">
                                <h5 class="text-dark-gray mb-15px w-95 xl-w-100 lh-40 ls-minus-1px alt-font">The <span
                                        class="text-decoration-line-bottom-medium fw-700">wonderful</span> services you
                                    offer locally are great for our community. People are tired of having to travel out
                                    of town for things.</h5>
                                <span class="text-gradient-base-color fw-700 text-uppercase ls-1px">@Herman
                                    miller</span>
                            </div>
                            <!-- end review item -->
                            <!-- start review item -->
                            <div class="swiper-slide">
                                <h5 class="text-dark-gray mb-15px w-95 xl-w-100 lh-40 ls-minus-1px alt-font">Absolutely
                                    amazing theme and <span
                                        class="text-decoration-line-bottom-medium fw-700">awesome</span> design with
                                    possibilities. It's so very easy to use and to customize everything.</h5>
                                <span class="text-gradient-base-color fw-700 text-uppercase ls-1px">@Alexander
                                    Harad</span>
                            </div>
                            <!-- end review item -->
                            <!-- start review item -->
                            <div class="swiper-slide">
                                <h5 class="text-dark-gray mb-15px w-95 xl-w-100 lh-40 ls-minus-1px alt-font">There are
                                    design companies and then there are user <span
                                        class="text-decoration-line-bottom-medium fw-700">experience.</span> Simply the
                                    great designs and best theme for fast loading website.</h5>
                                <span class="text-gradient-base-color fw-700 text-uppercase ls-1px">@Jacob
                                    Kalling</span>
                            </div>
                            <!-- end review item -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="row row-cols-1 row-cols-lg-5 row-cols-md-3 row-cols-sm-2 clients-style-06 justify-content-center"
                data-anime='{ "el": "childs", "translateY": [30, 0], "opacity": [0,1], "skewX":[20, 0], "duration": 300, "delay":0, "staggervalue": 200, "easing": "easeOutQuad" }'>
                {{-- <!-- start client item -->
                <div class="col client-box text-center md-mb-35px">
                    <a href="#"><img src="images/logo-walmart.svg" class="h-35px" alt=""></a>
                </div>
                <!-- end client item -->
                <!-- start client item -->
                <div class="col client-box text-center md-mb-35px">
                    <a href="#"><img src="images/logo-logitech.svg" class="h-35px" alt=""></a>
                </div>
                <!-- end client item -->
                <!-- start client item -->
                <div class="col client-box text-center md-mb-35px">
                    <a href="#"><img src="images/logo-monday.svg" class="h-35px" alt=""></a>
                </div>
                <!-- end client item -->
                <!-- start client item -->
                <div class="col client-box text-center sm-mb-35px">
                    <a href="#"><img src="images/logo-google.svg" class="h-35px" alt=""></a>
                </div>
                <!-- end client item -->
                <!-- start client item -->
                <div class="col client-box text-center">
                    <a href="#"><img src="images/logo-paypal.svg" class="h-35px" alt=""></a>
                </div>
                <!-- end client item --> --}}
            </div>
        </div>
    </section>
    <!-- end section -->
    <!-- start footer -->
    <footer class="bg-charcoal-blue pb-4 sm-pb-50px"
        style="background-image: url(images/demo-branding-agency-pattern.svg)">
        <div class="container">
            <div class="row mb-6">
                <div class="col-lg-5 col-md-6 sm-mb-30px order-2 order-md-1">
                    <h3 class="text-white fw-500 alt-font mb-50px ls-minus-1px sm-mb-30px">Siap Mengelola Hotel dengan
                        Lebih Mudah? Mulai dengan Suitify!</h3>
                    {{-- <div class="row">
                        <div class="col-lg-5 col-6">
                            <span class="alt-font fs-14 text-uppercase d-block text-white ls-1px lh-24">Call our
                                office</span>
                            <a href="tel:12345678910">+1 234 567 8910</a>
                        </div>
                        <div class="col-lg-5 col-6">
                            <span class="alt-font fs-14 text-uppercase d-block text-white ls-1px lh-24">Send a
                                message</span>
                            <a href="mailto:info@domain.com">info@domain.com</a>
                        </div>
                    </div> --}}
                </div>
                <div class="col-md-2 offset-lg-1 col-6 order-3 order-md-2">
                    <span class="alt-font fs-14 text-uppercase mb-5px d-block text-white ls-1px">Company</span>
                    <ul>
                        <li><a href="demo-branding-agency.html">Home</a></li>
                        <li><a href="demo-branding-agency-about.html">Agency</a></li>
                        <li><a href="demo-branding-agency-services.html">Services</a></li>
                        <li><a href="demo-branding-agency-portfolio.html">Portfolio</a></li>
                        <li><a href="demo-branding-agency-contact.html">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-2 col-6 order-3 order-md-3">
                    <span class="alt-font fs-14 text-uppercase mb-5px d-block text-white ls-1px">Follow Us</span>
                    <ul>
                        <li><a href="https://www.pinterest.com/" target="_blank">Pinterest</a></li>
                        <li><a href="http://www.twitter.com" target="_blank">Twitter</a></li>
                        <li><a href="http://www.dribbble.com" target="_blank">Dribbble</a></li>
                        <li><a href="http://www.instagram.com" target="_blank">Instagram</a></li>
                        <li><a href="http://www.behance.com" target="_blank">Behance</a></li>
                    </ul>
                </div>
                <div class="col-md-2 order-1 order-md-4 sm-mb-30px">
                    <a href="demo-branding-agency.html" class="footer-logo"><img
                            src="images/demo-branding-agency-white-logo.png"
                            data-at2x="images/demo-branding-agency-white-logo@2x.png" alt=""></a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-7 last-paragraph-no-margin">
                    <p class="fs-13 lh-22 w-95 sm-w-100">This site is protected by reCAPTCHA and the Google privacy
                        policy and terms of service apply. You must not use this website if you disagree with any of
                        these website standard terms and conditions.</p>
                </div>
                {{-- <div class="col-md-5 text-md-end sm-mt-15px last-paragraph-no-margin">
                    <p class="fs-13 lh-22">&copy; 2024 Crafto is Powered by <a href="https://www.themezaa.com/"
                            target="_blank" class="text-decoration-line-bottom text-white">ThemeZaa</a></p>
                </div> --}}
            </div>
        </div>
    </footer>
    <!-- end footer -->
    <!-- start scroll progress -->
    <div class="scroll-progress d-none d-xxl-block">
        <a href="#" class="scroll-top" aria-label="scroll">
            <span class="scroll-text">Scroll</span><span class="scroll-line"><span
                    class="scroll-point"></span></span>
        </a>
    </div>
    <!-- end scroll progress -->
</x-app-layout>
