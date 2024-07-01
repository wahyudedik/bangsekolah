<?php

use yii\helpers\Url;

$baseUrl = Yii::$app->request->baseUrl;
?>

<!-- ======= Header ======= -->
<header id="header" class="header fixed-top">
    <div class="container-fluid container-xl d-flex align-items-center justify-content-between">
        <a href="#" class="logo d-flex align-items-center">
            <img src="<?= $baseUrl ?>/img/logo.png" alt="" />
            <span>BANK SEKOLAH</span>
        </a>

        <nav id="navbar" class="navbar">
            <ul>
                <li><a class="nav-link scrollto active" href="#hero">Home</a></li>
                <li><a class="nav-link scrollto" href="#about">Tentang Kami</a></li>
                <li><a class="nav-link scrollto" href="#post">Artikel</a></li>
                <li><a class="nav-link scrollto" href="#features">Fitur</a></li>
                <li><a class="nav-link scrollto" href="#pricing">Harga</a></li>
                <li><a class="nav-link" href="<?= Yii::getAlias('@fronturl') ?>/school-register">Daftar Sekolah</a></li>
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
                <h1 data-aos="fade-up">Pembayaran Sekolah Era Digital</h1>
                <h2 data-aos="fade-up" data-aos-delay="400">
                    Semua transaksi pembayaran online dituntut cepat, mudah dan flexibel. Bank Sekolah Hadir untuk memenuhi kebutuhan seperti iuran wajib tiap bulan maupun ber sifatnya tidak rutin (Non Bulanan), di samping itu bank sekolah
                    memiliki fitur untuk BANK MINI SEKOLAH berupa Tabungan dan Simpan Pinjam.
                </h2>
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
                <img src="<?= $baseUrl ?>/img/features-3.png" class="img-fluid" alt="" />
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
                            Pengelolaan pembayaran sekolah seperti uang masuk, SPP, Bulanan, Semester Angsuran dan Lain-lain yang dilengkapi dengan laporan yang sesuai kebutuhan sekolah, di samping itu bank sekolah memiliki fitur untuk BANK
                            MINI SEKOLAH berupa Tabungan dan Simpan Pinjam.
                        </p>
                        <div class="text-center text-lg-start">
                            <a href="#pricing" class="btn-read-more d-inline-flex align-items-center justify-content-center align-self-center">
                                <span>KLIK DEMO</span>
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 d-flex align-items-center" data-aos="zoom-out" data-aos-delay="200">
                    <img src="<?= $baseUrl ?>/img/about.jpg" class="img-fluid" alt="" />
                </div>
            </div>
        </div>
    </section>
    <!-- End About Section -->

    <!-- ======= Artikel Section ======= -->
<section id="post" class="post">
    <div class="container">
        <div class="row justify-content-center" data-aos="fade-up" data-aos-delay="100">
            <div class="col-lg-8">
                <div class="content text-center">
                    <h3>Artikel Terbaru</h3>
                    <h2><strong>Temukan Informasi Terkini Seputar Bank Sekolah</strong></h2>
                    <p>
                        Kami menyajikan artikel-artikel terbaru seputar solusi pembayaran sekolah dan pengelolaan keuangan institusi pendidikan. Simak artikel-artikel berikut untuk mengetahui lebih lanjut.
                    </p>
                    <div class="text-center">
                        <a href="<?= Yii::getAlias('@fronturl') ?>/post" class="btn-read-more d-inline-flex align-items-center justify-content-center" data-aos="fade-up" data-aos-delay="200">
                            <span>Baca Selengkapnya</span>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row gy-4 mt-4 justify-content-center" data-aos="fade-up" data-aos-delay="300">
            <?php foreach ($posts as $post) : ?>
                <div class="col-md-4 col-lg-4">
                    <div class="card">
                        <img src="<?= Yii::getAlias('@fronturl') ?>/uploads/posts/small-<?= $post->image ?>" class="img img-responsive post-image" onerror="this.onerror=null; this.src='<?= Yii::getAlias('@fronturl') ?>/images/default-post.webp';">
                        <div class="card-body">
                            <h5 class="card-title"><strong><?= $post->title ?></strong></h5>
                            <p class="card-text"><?= substr($post->content, 0, 100) . '...' ?></p>
                            <a href="<?= Yii::getAlias('@fronturl') ?>/post/<?= $post->slug ?>" class="btn btn-primary">Baca Selengkapnya</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<!-- End Artkel Section -->

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
                            <span data-purecounter-start="0" data-purecounter-end="4000" data-purecounter-duration="1" class="purecounter"></span>
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
                    <img src="<?= $baseUrl ?>/img/features.png" class="img-fluid" alt="" />
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
                        <p>Tidak perlu install aplikasi secara manual. Dapat diakses dimana saja asal terkoneksi dengan internet.</p>
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
                <h2>Biaya Berlangganan Bank Sekolah</h2>
                <p></p>
            </header>

            <div class="row gy-4" data-aos="fade-left">
                <div class="col-lg-6 col-md-6" data-aos="zoom-in" data-aos-delay="100">
                    <div class="box">
                        <h3 style="color: #07d5c0;">GRATIS</h3>
                        <div class="price"><sup>Rp.</sup>0<span> / Tahun</span></div>
                        <img src="<?= $baseUrl ?>/img/pricing-free.png" class="img-fluid" alt="" />
                        <ul>
                            <li>X</li>
                            <li>X</li>
                            <li>Managemen Data Siswa</li>
                            <li>Aplikasi Pembayaran</li>
                            <li>Laporan Pembayaran</li>
                            <li>Laporan Rekapitulasi</li>
                            <li>Laporan Tagihan</li>
                            <li>Siswa 300</li>
                            <li>X</li>
                            <li>X</li>
                        </ul>
                        <a href="https://api.whatsapp.com/send?phone=6285649400339&text=Hallo%20Bank%20Sekolah,%20Request%20Demo%20Paket%20Gratis
" target="_blank" class="btn-buy">
                            DAFTAR SEKARANG
                        </a>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6" data-aos="zoom-in" data-aos-delay="400">
                    <div class="box">
                        <span class="featured">Featured</span>
                        <h3 style="color: #ff0071;">Paket Update</h3>
                        <div class="price"><sup></sup>Hubungi<span> Kami</span></div>
                        <img src="<?= $baseUrl ?>/img/pricing-ultimate.png" class="img-fluid" alt="" />
                        <ul>
                            <li>Informasi Data Sekolah</li>
                            <li>Informasi Absensi Siswa</li>
                            <li>Managemen Data Siswa</li>
                            <li>Aplikasi Pembayaran</li>
                            <li>Laporan Pembayaran</li>
                            <li>Laporan Rekapitulasi</li>
                            <li>Laporan Tagihan</li>
                            <li>Siswa Lebih 1.000</li>
                            <li>Aplikasi Android</li>
                            <li>Gratis Update Aplikasi</li>
                        </ul>
                        <a href="https://api.whatsapp.com/send?phone=6285649400339&text=Hallo%20Bank%20Sekolah,%20Request%20Demo%20Paket%20Update" target="_blank" class="btn-buy">DAFTAR SEKARANG</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Pricing Section -->

    <!-- ======= Clients Section ======= -->
    <section id="clients" class="clients">
        <div class="container" data-aos="fade-up">
            <header class="section-header">
                <h2>Partner Kami</h2>
                <p></p>
            </header>

            <div class="clients-slider swiper-container">
                <div class="swiper-wrapper align-items-center">
                    <div class="swiper-slide"><img src="<?= $baseUrl ?>/img/clients/client-1.png" class="img-fluid" alt="" /></div>
                    <div class="swiper-slide"><img src="<?= $baseUrl ?>/img/clients/client-2.png" class="img-fluid" alt="" /></div>
                    <div class="swiper-slide"><img src="<?= $baseUrl ?>/img/clients/client-3.png" class="img-fluid" alt="" /></div>
                    <div class="swiper-slide"><img src="<?= $baseUrl ?>/img/clients/client-4.png" class="img-fluid" alt="" /></div>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </section>
    <!-- End Clients Section -->

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
                                    Apakah biaya perpanjangan untuk tahun berikutnya sama?
                                </button>
                            </h2>
                            <div id="faq-content-2" class="accordion-collapse collapse" data-bs-parent="#faqlist1">
                                <div class="accordion-body">
                                    Ya, biaya perpanjangan sama.
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
                                    Apakah ada login untuk DEMO Aplikasi ?
                                </button>
                            </h2>
                            <div id="faq2-content-1" class="accordion-collapse collapse" data-bs-parent="#faqlist2">
                                <div class="accordion-body">
                                    Silahkan Pilih Paket terlebih dahulu, klik tombol Daftar Sekarang
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2-content-2">
                                    Bagaimana Cara Daftarnya ?
                                </button>
                            </h2>
                            <div id="faq2-content-2" class="accordion-collapse collapse" data-bs-parent="#faqlist2">
                                <div class="accordion-body">
                                    Silahkan Pilih Paket terlebih dahulu, klik tombol Daftar Sekarang
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End F.A.Q Section -->

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
                                <p>
                                    Peterongan Jombang<br />
                                    Jawa Timur. 61481
                                </p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <i class="bi bi-telephone"></i>
                                <h3>Hubungi Kami</h3>
                                <p>085 649 400 339</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <i class="bi bi-envelope"></i>
                                <h3>Email</h3>
                                <p>infobanksekolah@gmail.com<br /></p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <i class="bi bi-clock"></i>
                                <h3>Jam Buka</h3>
                                <p>
                                    Senin - Sabtu<br />
                                    9:00 - 16.00
                                </p>
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