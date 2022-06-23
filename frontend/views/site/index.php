<?php
$baseUrl = Yii::$app->request->baseUrl;
?>

<!-- ======= Header ======= -->
<header id="header" class="header fixed-top">
    <div class="container-fluid container-xl d-flex align-items-center justify-content-between">
        <a href="index.html" class="logo d-flex align-items-center">
        <img src="<?= $baseUrl ?>/theme/assets/img/logo.png" alt="">
        <span>BANK SEKOLAH</span>
        </a>
        <nav id="navbar" class="navbar">
            <ul>
                <li><a class="nav-link scrollto active" href="#hero">Home</a></li>
                <li><a class="nav-link scrollto" href="#about">Tentang Kami</a></li>
                <li><a class="nav-link scrollto" href="#features">Fitur</a></li>
                <li><a class="nav-link scrollto" href="#pricing">Harga</a></li>
                <li><a class="nav-link scrollto" href="#contact">Hubungi Kami</a></li>
                <li><a class="getstarted scrollto" href="https://banksekolah.com/backend/web">LOGIN</a></li>
            </ul>
            <i class="bi bi-list mobile-nav-toggle"></i>
        </nav>
        <!-- .navbar -->
    </div>
</header>
<!-- End Header -->
<!-- ======= Hero Section ======= -->
<section id="hero" class="hero d-flex align-items-center">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 d-flex flex-column justify-content-center">
                <h1 data-aos="fade-up">Pembayaran Sekolah Era Digital </h1>
                <h2 data-aos="fade-up" data-aos-delay="400">Era digital semua transaksi pembayaran online dituntut cepat, mudah dan flexibel. Bank Sekolah adalah Solusi Pembayaran Keuangan yang tidak terbatas pada uang spp namun seluruh keuangan lainnya.</h2>
                <div data-aos="fade-up" data-aos-delay="600">
                    <div class="text-center text-lg-start">
                        <a href="#pricing" class="btn-get-started scrollto d-inline-flex align-items-center justify-content-center align-self-center">
                        <span>COBA SEKARANG</span>
                        <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 hero-img" data-aos="zoom-out" data-aos-delay="200">
                <img src="<?= $baseUrl ?>/theme/assets/img/hero-img.png" class="img-fluid" alt="">
            </div>
        </div>
    </div>
</section>
<!-- End Hero -->
<main id="main">
    <!-- ======= About Section ======= -->
    <section id="about" class="about">
        <div class="container" data-aos="fade-up">
            <div class="row gx-0">
                <div class="col-lg-6 d-flex flex-column justify-content-center" data-aos="fade-up" data-aos-delay="200">
                    <div class="content">
                        <h3>Apa Itu Bank Sekolah ?</h3>
                        <h2>Bank Sekolah adalah aplikasi pembayaran sekolah berbasis web dan mobile</h2>
                        <p>
                            Pengelolaan pembayaran sekolah seperti uang masuk, SPP, Bulanan, Semester Angsuran dan Lain-lain yang dilengkapi dengan laporan yang sesuai kebutuhan sekolah
                        </p>
                        <div class="text-center text-lg-start">
                            <a href="#" class="btn-read-more d-inline-flex align-items-center justify-content-center align-self-center">
                            <span>KLIK DEMO</span>
                            <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 d-flex align-items-center" data-aos="zoom-out" data-aos-delay="200">
                    <img src="<?= $baseUrl ?>/theme/assets/img/about.jpg" class="img-fluid" alt="">
                </div>
            </div>
        </div>
    </section>
    <!-- End About Section -->
    <!-- ======= Counts Section ======= -->
    <section id="counts" class="counts">
        <div class="container" data-aos="fade-up">
            <div class="row gy-4">
                <div class="col-lg-3 col-md-6">
                    <div class="count-box">
                        <i class="bi bi-emoji-smile"></i>
                        <div>
                            <span data-purecounter-start="0" data-purecounter-end="10" data-purecounter-duration="1" class="purecounter"></span>
                            <p>MITRA SEKOLAH</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="count-box">
                        <i class="bi bi-journal-richtext" style="color: #ee6c20;"></i>
                        <div>
                            <span data-purecounter-start="0" data-purecounter-end="5" data-purecounter-duration="1" class="purecounter"></span>
                            <p>Our Projects</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="count-box">
                        <i class="bi bi-headset" style="color: #15be56;"></i>
                        <div>
                            <span data-purecounter-start="0" data-purecounter-end="200" data-purecounter-duration="1" class="purecounter"></span>
                            <p>Hours Of Support</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="count-box">
                        <i class="bi bi-people" style="color: #bb0852;"></i>
                        <div>
                            <span data-purecounter-start="0" data-purecounter-end="1000" data-purecounter-duration="1" class="purecounter"></span>
                            <p>JUMLAH SISWA</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Counts Section -->
    <!-- ======= Features Section ======= -->
    <section id="features" class="features">
        <div class="container" data-aos="fade-up">
            <header class="section-header">
                <h2>FITUR BANK SEKOLAH</h2>
                <p>Keunggulan Bank Sekolah untuk mempermudah pekerjaan keuangan sekolah anda</p>
            </header>
            <div class="row">
                <div class="col-lg-6">
                    <img src="<?= $baseUrl ?>/theme/assets/img/features.png" class="img-fluid" alt="">
                </div>
                <div class="col-lg-6 mt-5 mt-lg-0 d-flex">
                    <div class="row align-self-center gy-4">
                        <div class="col-md-6" data-aos="zoom-out" data-aos-delay="200">
                            <div class="feature-box d-flex align-items-center">
                                <i class="bi bi-check"></i>
                                <h3>KEAMANAN DATA</h3>
                            </div>
                        </div>
                        <div class="col-md-6" data-aos="zoom-out" data-aos-delay="300">
                            <div class="feature-box d-flex align-items-center">
                                <i class="bi bi-check"></i>
                                <h3>REALTIME ONLINE</h3>
                            </div>
                        </div>
                        <div class="col-md-6" data-aos="zoom-out" data-aos-delay="400">
                            <div class="feature-box d-flex align-items-center">
                                <i class="bi bi-check"></i>
                                <h3>TANPA INSTALASI</h3>
                            </div>
                        </div>
                        <div class="col-md-6" data-aos="zoom-out" data-aos-delay="500">
                            <div class="feature-box d-flex align-items-center">
                                <i class="bi bi-check"></i>
                                <h3>MANAJEMEN DATA</h3>
                            </div>
                        </div>
                        <div class="col-md-6" data-aos="zoom-out" data-aos-delay="600">
                            <div class="feature-box d-flex align-items-center">
                                <i class="bi bi-check"></i>
                                <h3>KEUANGAN TERPUSAT</h3>
                            </div>
                        </div>
                        <div class="col-md-6" data-aos="zoom-out" data-aos-delay="700">
                            <div class="feature-box d-flex align-items-center">
                                <i class="bi bi-check"></i>
                                <h3>LAPORAN KEUANGAN</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- / row -->
        </div>
    </section>
    <!-- End Features Section -->
    <!-- ======= Services Section ======= -->
    <section id="services" class="services">
        <div class="container" data-aos="fade-up">
            <header class="section-header">
                <h2></h2>
                <p>PENJELASAN FITUR BANK SEKOLAH</p>
            </header>
            <div class="row gy-4">
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="service-box blue">
                        <i class="ri-discuss-line icon"></i>
                        <h3>KEAMANAN DATA</h3>
                        <p>Tidak perlu khawatir data hilang jika install ulang pc. Keamanan data kami dilengkapi dengan auto backup</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="service-box orange">
                        <i class="ri-discuss-line icon"></i>
                        <h3>TANPA INSTALASI</h3>
                        <p>Tidak perlu install aplikasi secara manual. Dapat diakses dimana saja asal terkoneksi dengan internet. </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="service-box green">
                        <i class="ri-discuss-line icon"></i>
                        <h3>KEUANGAN TERPUSAT</h3>
                        <p>Aktifikas pembayaran tercatat dengan baik.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="500">
                    <div class="service-box red">
                        <i class="ri-discuss-line icon"></i>
                        <h3>REALTIME ONLINE</h3>
                        <p>Pengguna dapat mengakses data secara langsung dimanapun & kapanpun secara online.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="600">
                    <div class="service-box purple">
                        <i class="ri-discuss-line icon"></i>
                        <h3>MANAJEMEN DATA</h3>
                        <p>Manajemen data secara terperinci, mulai kelas, jurusan/romble sampai data orang tua</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="700">
                    <div class="service-box pink">
                        <i class="ri-discuss-line icon"></i>
                        <h3>LAPORAN KEUANGAN</h3>
                        <p>Memudahkan anda dalam laporan kuangan dilengkapi dengan laporan rekapitulasi, laporan pembayaran serta laporan tagihan pembayaran</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Services Section -->
    <!-- ======= Pricing Section ======= -->
    <section id="pricing" class="pricing">
        <div class="container" data-aos="fade-up">
            <header class="section-header">
                <h2>Harga Bank Sekolah</h2>
                <p></p>
            </header>
            <div class="row gy-4" data-aos="fade-left">
                <div class="col-lg-3 col-md-6" data-aos="zoom-in" data-aos-delay="100">
                    <div class="box">
                        <h3 style="color: #07d5c0;">Free Plan</h3>
                        <div class="price"><sup>Rp.</sup>0<span> / Tahun</span></div>
                        <img src="<?= $baseUrl ?>/theme/assets/img/pricing-free.png" class="img-fluid" alt="">
                        <ul>
                            <li>Managemen Data</li>
                            <li>Aplikasi Pembayaran</li>
                            <li>Laporan Pembayaran</li>
                            <li>Laporan Rekapitulasi</li>
                            <li>Laporan Tagihan</li>
                            <li>Siswa 100</li>
                            <li>Aplikasi Android</li>
                        </ul>
                        <a href="#" class="btn-buy">DAFTAR SEKARANG</a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="zoom-in" data-aos-delay="200">
                    <div class="box">
                        <span class="featured">Featured</span>
                        <h3 style="color: #65c600;">Starter Plan</h3>
                        <div class="price"><sup>Rp.</sup>500.000<span> / Tahun</span></div>
                        <img src="<?= $baseUrl ?>/theme/assets/img/pricing-starter.png" class="img-fluid" alt="">
                        <ul>
                            <li>Managemen Data</li>
                            <li>Aplikasi Pembayaran</li>
                            <li>Laporan Pembayaran</li>
                            <li>Laporan Rekapitulasi</li>
                            <li>Laporan Tagihan</li>
                            <li>Siswa 500</li>
                            <li>Aplikasi Android</li>
                        </ul>
                        <a href="#" class="btn-buy">DAFTAR SEKARANG</a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="zoom-in" data-aos-delay="300">
                    <div class="box">
                        <h3 style="color: #ff901c;">Business Plan</h3>
                        <div class="price"><sup></sup>Call<span> me</span></div>
                        <img src="<?= $baseUrl ?>/theme/assets/img/pricing-business.png" class="img-fluid" alt="">
                        <ul>
                            <li>Managemen Data</li>
                            <li>Aplikasi Pembayaran</li>
                            <li>Laporan Pembayaran</li>
                            <li>Laporan Rekapitulasi</li>
                            <li>Laporan Tagihan</li>
                            <li>Siswa 1.000</li>
                            <li>Aplikasi Android</li>
                        </ul>
                        <a href="#" class="btn-buy">DAFTAR SEKARANG</a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="zoom-in" data-aos-delay="400">
                    <div class="box">
                        <h3 style="color: #ff0071;">Ultimate Plan</h3>
                        <div class="price"><sup></sup>Call<span> me</span></div>
                        <img src="<?= $baseUrl ?>/theme/assets/img/pricing-ultimate.png" class="img-fluid" alt="">
                        <ul>
                            <li>Managemen Data</li>
                            <li>Aplikasi Pembayaran</li>
                            <li>Laporan Pembayaran</li>
                            <li>Laporan Rekapitulasi</li>
                            <li>Laporan Tagihan</li>
                            <li>Siswa Lebih 1.000</li>
                            <li>Aplikasi Android</li>
                        </ul>
                        <a href="#" class="btn-buy">DAFTAR SEKARANG</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Pricing Section -->
    <!-- ======= F.A.Q Section ======= -->
    <section id="faq" class="faq">
        <div class="container" data-aos="fade-up">
            <header class="section-header">
                <h2>F.A.Q</h2>
                <p>Frequently Asked Questions</p>
            </header>
            <div class="row">
                <div class="col-lg-6">
                    <!-- F.A.Q List 1-->
                    <div class="accordion accordion-flush" id="faqlist1">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-content-1">
                                Apakah Biaya banksekolah.com pertahun ?
                                </button>
                            </h2>
                            <div id="faq-content-1" class="accordion-collapse collapse" data-bs-parent="#faqlist1">
                                <div class="accordion-body">
                                    Iyes. Biaya pertahun sesuai paket yang di pilih.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-content-2">
                                Feugiat scelerisque varius morbi enim nunc faucibus a pellentesque?
                                </button>
                            </h2>
                            <div id="faq-content-2" class="accordion-collapse collapse" data-bs-parent="#faqlist1">
                                <div class="accordion-body">
                                    Dolor sit amet consectetur adipiscing elit pellentesque habitant morbi. Id interdum velit laoreet id donec ultrices. Fringilla phasellus faucibus scelerisque eleifend donec pretium. Est pellentesque elit ullamcorper dignissim. Mauris ultrices eros in cursus turpis massa tincidunt dui.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-content-3">
                                Dolor sit amet consectetur adipiscing elit pellentesque habitant morbi?
                                </button>
                            </h2>
                            <div id="faq-content-3" class="accordion-collapse collapse" data-bs-parent="#faqlist1">
                                <div class="accordion-body">
                                    Eleifend mi in nulla posuere sollicitudin aliquam ultrices sagittis orci. Faucibus pulvinar elementum integer enim. Sem nulla pharetra diam sit amet nisl suscipit. Rutrum tellus pellentesque eu tincidunt. Lectus urna duis convallis convallis tellus. Urna molestie at elementum eu facilisis sed odio morbi quis
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <!-- F.A.Q List 2-->
                    <div class="accordion accordion-flush" id="faqlist2">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2-content-1">
                                Ac odio tempor orci dapibus. Aliquam eleifend mi in nulla?
                                </button>
                            </h2>
                            <div id="faq2-content-1" class="accordion-collapse collapse" data-bs-parent="#faqlist2">
                                <div class="accordion-body">
                                    Dolor sit amet consectetur adipiscing elit pellentesque habitant morbi. Id interdum velit laoreet id donec ultrices. Fringilla phasellus faucibus scelerisque eleifend donec pretium. Est pellentesque elit ullamcorper dignissim. Mauris ultrices eros in cursus turpis massa tincidunt dui.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2-content-2">
                                Tempus quam pellentesque nec nam aliquam sem et tortor consequat?
                                </button>
                            </h2>
                            <div id="faq2-content-2" class="accordion-collapse collapse" data-bs-parent="#faqlist2">
                                <div class="accordion-body">
                                    Molestie a iaculis at erat pellentesque adipiscing commodo. Dignissim suspendisse in est ante in. Nunc vel risus commodo viverra maecenas accumsan. Sit amet nisl suscipit adipiscing bibendum est. Purus gravida quis blandit turpis cursus in
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2-content-3">
                                Varius vel pharetra vel turpis nunc eget lorem dolor?
                                </button>
                            </h2>
                            <div id="faq2-content-3" class="accordion-collapse collapse" data-bs-parent="#faqlist2">
                                <div class="accordion-body">
                                    Laoreet sit amet cursus sit amet dictum sit amet justo. Mauris vitae ultricies leo integer malesuada nunc vel. Tincidunt eget nullam non nisi est sit amet. Turpis nunc eget lorem dolor sed. Ut venenatis tellus in metus vulputate eu scelerisque. Pellentesque diam volutpat commodo sed egestas egestas fringilla phasellus faucibus. Nibh tellus molestie nunc non blandit massa enim nec.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End F.A.Q Section -->
    <!-- ======= Testimonials Section ======= -->
    <section id="testimonials" class="testimonials">
        <div class="container" data-aos="fade-up">
            <header class="section-header">
                <h2>Testimonials</h2>
                <p>What they are saying about us</p>
            </header>
            <div class="testimonials-slider swiper-container" data-aos="fade-up" data-aos-delay="200">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class="testimonial-item">
                            <div class="stars">
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                            </div>
                            <p>
                                Proin iaculis purus consequat sem cure digni ssim donec porttitora entum suscipit rhoncus. Accusantium quam, ultricies eget id, aliquam eget nibh et. Maecen aliquam, risus at semper.
                            </p>
                            <div class="profile mt-auto">
                                <img src="<?= $baseUrl ?>/theme/assets/img/testimonials/testimonials-1.jpg" class="testimonial-img" alt="">
                                <h3>Saul Goodman</h3>
                                <h4>Ceo &amp; Founder</h4>
                            </div>
                        </div>
                    </div>
                    <!-- End testimonial item -->
                    <div class="swiper-slide">
                        <div class="testimonial-item">
                            <div class="stars">
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                            </div>
                            <p>
                                Export tempor illum tamen malis malis eram quae irure esse labore quem cillum quid cillum eram malis quorum velit fore eram velit sunt aliqua noster fugiat irure amet legam anim culpa.
                            </p>
                            <div class="profile mt-auto">
                                <img src="<?= $baseUrl ?>/theme/assets/img/testimonials/testimonials-2.jpg" class="testimonial-img" alt="">
                                <h3>Sara Wilsson</h3>
                                <h4>Designer</h4>
                            </div>
                        </div>
                    </div>
                    <!-- End testimonial item -->
                    <div class="swiper-slide">
                        <div class="testimonial-item">
                            <div class="stars">
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                            </div>
                            <p>
                                Enim nisi quem export duis labore cillum quae magna enim sint quorum nulla quem veniam duis minim tempor labore quem eram duis noster aute amet eram fore quis sint minim.
                            </p>
                            <div class="profile mt-auto">
                                <img src="<?= $baseUrl ?>/theme/assets/img/testimonials/testimonials-3.jpg" class="testimonial-img" alt="">
                                <h3>Jena Karlis</h3>
                                <h4>Store Owner</h4>
                            </div>
                        </div>
                    </div>
                    <!-- End testimonial item -->
                    <div class="swiper-slide">
                        <div class="testimonial-item">
                            <div class="stars">
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                            </div>
                            <p>
                                Fugiat enim eram quae cillum dolore dolor amet nulla culpa multos export minim fugiat minim velit minim dolor enim duis veniam ipsum anim magna sunt elit fore quem dolore labore illum veniam.
                            </p>
                            <div class="profile mt-auto">
                                <img src="<?= $baseUrl ?>/theme/assets/img/testimonials/testimonials-4.jpg" class="testimonial-img" alt="">
                                <h3>Matt Brandon</h3>
                                <h4>Freelancer</h4>
                            </div>
                        </div>
                    </div>
                    <!-- End testimonial item -->
                    <div class="swiper-slide">
                        <div class="testimonial-item">
                            <div class="stars">
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                            </div>
                            <p>
                                Quis quorum aliqua sint quem legam fore sunt eram irure aliqua veniam tempor noster veniam enim culpa labore duis sunt culpa nulla illum cillum fugiat legam esse veniam culpa fore nisi cillum quid.
                            </p>
                            <div class="profile mt-auto">
                                <img src="<?= $baseUrl ?>/theme/assets/img/testimonials/testimonials-5.jpg" class="testimonial-img" alt="">
                                <h3>John Larson</h3>
                                <h4>Entrepreneur</h4>
                            </div>
                        </div>
                    </div>
                    <!-- End testimonial item -->
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </section>
    <!-- End Testimonials Section -->
    <!-- ======= Clients Section ======= -->
    <section id="clients" class="clients">
        <div class="container" data-aos="fade-up">
            <header class="section-header">
                <h2>Partner Kami</h2>
                <p>Kami Bergerak dibidang Digital Solution</p>
            </header>
            <div class="clients-slider swiper-container">
                <div class="swiper-wrapper align-items-center">
                    <div class="swiper-slide"><img src="<?= $baseUrl ?>/theme/assets/img/clients/client-1.png" class="img-fluid" alt=""></div>
                    <div class="swiper-slide"><img src="<?= $baseUrl ?>/theme/assets/img/clients/client-2.png" class="img-fluid" alt=""></div>
                    <div class="swiper-slide"><img src="<?= $baseUrl ?>/theme/assets/img/clients/client-3.png" class="img-fluid" alt=""></div>
                    <div class="swiper-slide"><img src="<?= $baseUrl ?>/theme/assets/img/clients/client-4.png" class="img-fluid" alt=""></div>
                    <div class="swiper-slide"><img src="<?= $baseUrl ?>/theme/assets/img/clients/client-5.png" class="img-fluid" alt=""></div>
                    <div class="swiper-slide"><img src="<?= $baseUrl ?>/theme/assets/img/clients/client-6.png" class="img-fluid" alt=""></div>
                    <div class="swiper-slide"><img src="<?= $baseUrl ?>/theme/assets/img/clients/client-7.png" class="img-fluid" alt=""></div>
                    <div class="swiper-slide"><img src="<?= $baseUrl ?>/theme/assets/img/clients/client-8.png" class="img-fluid" alt=""></div>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </section>
    <!-- End Clients Section -->
    <!-- ======= Contact Section ======= -->
    <section id="contact" class="contact">
        <div class="container" data-aos="fade-up">
            <header class="section-header">
                <h2>Hubungi Kami</h2>
                <p></p>
            </header>
            <div class="row gy-12">
                <div class="col-lg-12">
                    <div class="row gy-3">
                        <div class="col-md-3">
                            <div class="info-box">
                                <i class="bi bi-geo-alt"></i>
                                <h3>Alamat</h3>
                                <p>Peterongan Jombang<br>Jawa Timur. 61481</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <i class="bi bi-telephone"></i>
                                <h3>Hubungi Kami</h3>
                                <p>081 515 876 755<br>0321 - </p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <i class="bi bi-envelope"></i>
                                <h3>Email </h3>
                                <p>info@banksekolah.com<br></p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <i class="bi bi-clock"></i>
                                <h3>Jam Buka</h3>
                                <p>Senin - Sabtu<br>9:00 - 16.00</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Contact Section -->
</main>
<!-- End #main -->