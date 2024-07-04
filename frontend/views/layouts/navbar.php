<?php

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
                <li><a class="nav-link" href="school-register">Daftar Sekolah</a></li>
                <li><a class="nav-link scrollto" href="#contact">Hubungi Kami</a></li>
                <li><a class="getstarted scrollto" href="https://banksekolah.com/backend/web">LOGIN</a></li>
            </ul>
            <i class="bi bi-list mobile-nav-toggle"></i>
        </nav>
        <!-- .navbar -->
    </div>
</header>
<!-- End Header -->