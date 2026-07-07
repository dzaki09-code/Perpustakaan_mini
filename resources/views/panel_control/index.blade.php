<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Perpustakaan Mini — Modern & Dinamis</title>
    <meta name="description" content="Landing page perpustakaan mini dengan pengalaman modern dan interaktif" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />

    <style>
      /* ===== RESET & VARIABLES ===== */
      :root {
        --primary: #4f46e5;
        --primary-dark: #4338ca;
        --primary-light: #818cf8;
        --primary-glow: rgba(79, 70, 229, 0.25);
        --bg-start: #f5f8ff;
        --bg-end: #eef7ff;
        --card-bg: rgba(255, 255, 255, 0.75);
        --glass-border: rgba(255, 255, 255, 0.3);
        --text-dark: #1e293b;
        --text-muted: #64748b;
        --shadow-sm: 0 8px 30px rgba(0, 0, 0, 0.05);
        --shadow-lg: 0 20px 60px rgba(79, 70, 229, 0.15);
        --radius-lg: 1.5rem;
        --radius-sm: 1rem;
        --transition: 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
      }

      * {
        font-family: 'Inter', sans-serif;
        box-sizing: border-box;
      }

      body {
        background: linear-gradient(135deg, var(--bg-start) 0%, var(--bg-end) 100%);
        color: var(--text-dark);
        min-height: 100vh;
        overflow-x: hidden;
      }

      /* ===== ANIMASI GLOBAL ===== */
      @keyframes float {
        0%,
        100% {
          transform: translateY(0px);
        }
        50% {
          transform: translateY(-12px);
        }
      }
      @keyframes pulse-glow {
        0%,
        100% {
          box-shadow: 0 0 20px var(--primary-glow);
        }
        50% {
          box-shadow: 0 0 40px var(--primary-glow), 0 0 80px rgba(79, 70, 229, 0.1);
        }
      }
      @keyframes slideUp {
        from {
          opacity: 0;
          transform: translateY(40px);
        }
        to {
          opacity: 1;
          transform: translateY(0);
        }
      }
      @keyframes fadeInScale {
        from {
          opacity: 0;
          transform: scale(0.92);
        }
        to {
          opacity: 1;
          transform: scale(1);
        }
      }
      @keyframes shimmer {
        0% {
          background-position: -200% 0;
        }
        100% {
          background-position: 200% 0;
        }
      }

      .animate-on-scroll {
        opacity: 0;
        transform: translateY(40px);
        transition: opacity 0.8s ease, transform 0.8s ease;
      }
      .animate-on-scroll.visible {
        opacity: 1;
        transform: translateY(0);
      }

      /* ===== NAVBAR ===== */
      .navbar {
        padding: 0.8rem 0;
        background: rgba(255, 255, 255, 0.7) !important;
        backdrop-filter: blur(16px) saturate(180%);
        -webkit-backdrop-filter: blur(16px) saturate(180%);
        border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.03);
        transition: var(--transition);
      }
      .navbar.scrolled {
        background: rgba(255, 255, 255, 0.92) !important;
        box-shadow: 0 8px 40px rgba(0, 0, 0, 0.06);
      }
      .brand {
        font-weight: 900;
        font-size: 1.35rem;
        color: var(--primary) !important;
        letter-spacing: -0.5px;
      }
      .brand i {
        font-size: 1.6rem;
        vertical-align: middle;
        margin-right: 6px;
      }
      .nav-btn {
        border-radius: 60px;
        padding: 0.45rem 1.4rem;
        font-weight: 600;
        transition: var(--transition);
        border: none;
      }
      .nav-btn-outline {
        background: transparent;
        color: var(--primary);
        border: 2px solid var(--primary);
      }
      .nav-btn-outline:hover {
        background: var(--primary);
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px var(--primary-glow);
      }
      .nav-btn-primary {
        background: var(--primary);
        color: #fff;
        border: 2px solid var(--primary);
      }
      .nav-btn-primary:hover {
        background: var(--primary-dark);
        border-color: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px var(--primary-glow);
      }

      /* ===== HERO ===== */
      .hero {
        padding: 5rem 0 4rem;
        position: relative;
        overflow: hidden;
      }
      .hero::before {
        content: '';
        position: absolute;
        top: -40%;
        right: -10%;
        width: 600px;
        height: 600px;
        background: radial-gradient(circle, rgba(79, 70, 229, 0.07) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
      }
      .hero::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -10%;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(129, 140, 248, 0.06) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
      }
      .hero h1 {
        font-size: clamp(2.2rem, 4.5vw, 3.6rem);
        font-weight: 900;
        line-height: 1.12;
        letter-spacing: -1px;
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
      }
      .hero h1 .highlight {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
      }
      .hero p {
        font-size: 1.1rem;
        color: var(--text-muted);
        max-width: 580px;
        line-height: 1.7;
      }
      .hero .badge-pill {
        background: rgba(79, 70, 229, 0.1);
        color: var(--primary);
        font-weight: 600;
        padding: 0.45rem 1rem;
        border-radius: 60px;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        backdrop-filter: blur(4px);
        border: 1px solid rgba(79, 70, 229, 0.08);
      }

      .hero-card {
        background: var(--card-bg);
        backdrop-filter: blur(12px) saturate(180%);
        -webkit-backdrop-filter: blur(12px) saturate(180%);
        border: 1px solid var(--glass-border);
        border-radius: var(--radius-lg);
        padding: 2rem 2rem 1.8rem;
        box-shadow: var(--shadow-lg);
        transition: var(--transition);
        animation: fadeInScale 0.8s ease;
      }
      .hero-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 30px 70px rgba(79, 70, 229, 0.18);
      }
      .hero-card ul {
        padding-left: 0;
        list-style: none;
        margin: 0;
      }
      .hero-card li {
        padding: 0.65rem 0;
        border-bottom: 1px solid rgba(0, 0, 0, 0.04);
        display: flex;
        align-items: center;
        gap: 0.7rem;
        color: var(--text-dark);
        font-weight: 500;
      }
      .hero-card li:last-child {
        border-bottom: 0;
      }
      .hero-card li i {
        color: var(--primary);
        font-size: 1.3rem;
        flex-shrink: 0;
      }
      .hero-float-icon {
        animation: float 4s ease-in-out infinite;
      }

      /* ===== STATS ===== */
      .stats-section {
        padding: 2rem 0;
      }
      .stat-card {
        background: var(--card-bg);
        backdrop-filter: blur(8px);
        border: 1px solid var(--glass-border);
        border-radius: var(--radius-sm);
        padding: 1.5rem 1.2rem;
        text-align: center;
        transition: var(--transition);
        box-shadow: var(--shadow-sm);
      }
      .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
      }
      .stat-number {
        font-size: 2.6rem;
        font-weight: 900;
        color: var(--primary);
        line-height: 1.1;
      }
      .stat-label {
        font-size: 0.9rem;
        color: var(--text-muted);
        font-weight: 500;
        margin-top: 0.2rem;
      }
      .stat-icon {
        font-size: 2rem;
        color: var(--primary-light);
        opacity: 0.3;
        margin-bottom: 0.2rem;
      }

      /* ===== FEATURES ===== */
      .feature-card {
        background: var(--card-bg);
        backdrop-filter: blur(8px);
        border: 1px solid var(--glass-border);
        border-radius: var(--radius-lg);
        padding: 2rem 1.8rem;
        height: 100%;
        transition: var(--transition);
        box-shadow: var(--shadow-sm);
        position: relative;
        overflow: hidden;
      }
      .feature-card::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(79, 70, 229, 0.02), transparent);
        pointer-events: none;
      }
      .feature-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-lg);
        border-color: var(--primary-light);
      }
      .feature-icon {
        width: 56px;
        height: 56px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 1rem;
        background: linear-gradient(135deg, rgba(79, 70, 229, 0.12), rgba(129, 140, 248, 0.08));
        color: var(--primary);
        font-size: 1.6rem;
        transition: var(--transition);
      }
      .feature-card:hover .feature-icon {
        background: var(--primary);
        color: #fff;
        transform: scale(1.05) rotate(-3deg);
      }
      .feature-card h5 {
        font-weight: 700;
        margin-top: 1rem;
      }
      .feature-card p {
        color: var(--text-muted);
        font-size: 0.95rem;
        line-height: 1.6;
      }

      /* ===== BOOK GALLERY ===== */
      .book-card {
        background: var(--card-bg);
        backdrop-filter: blur(8px);
        border: 1px solid var(--glass-border);
        border-radius: var(--radius-sm);
        overflow: hidden;
        transition: var(--transition);
        box-shadow: var(--shadow-sm);
        height: 100%;
      }
      .book-card:hover {
        transform: translateY(-8px) scale(1.01);
        box-shadow: var(--shadow-lg);
        border-color: var(--primary-light);
      }
      .book-cover {
        height: 190px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3.5rem;
        color: #fff;
        position: relative;
        background: linear-gradient(135deg, #4f46e5, #818cf8);
      }
      .book-cover .bx {
        filter: drop-shadow(0 8px 16px rgba(0, 0, 0, 0.15));
      }
      .book-cover .book-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(4px);
        padding: 0.2rem 0.7rem;
        border-radius: 60px;
        font-size: 0.7rem;
        font-weight: 600;
        color: #fff;
      }
      .book-body {
        padding: 1.2rem 1.2rem 1.4rem;
      }
      .book-body h6 {
        font-weight: 700;
        margin-bottom: 0.15rem;
      }
      .book-body .author {
        font-size: 0.85rem;
        color: var(--text-muted);
      }
      .book-body .meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 0.6rem;
      }
      .book-body .meta .tag {
        background: rgba(79, 70, 229, 0.08);
        color: var(--primary);
        padding: 0.2rem 0.8rem;
        border-radius: 60px;
        font-size: 0.7rem;
        font-weight: 600;
      }
      .book-body .meta .year {
        font-size: 0.8rem;
        color: var(--text-muted);
      }

      /* ===== TESTIMONI ===== */
      .testimoni-card {
        background: var(--card-bg);
        backdrop-filter: blur(8px);
        border: 1px solid var(--glass-border);
        border-radius: var(--radius-lg);
        padding: 2rem 1.8rem;
        height: 100%;
        transition: var(--transition);
        box-shadow: var(--shadow-sm);
      }
      .testimoni-card:hover {
        transform: translateY(-6px);
        box-shadow: var(--shadow-lg);
      }
      .testimoni-card .stars {
        color: #f59e0b;
        font-size: 1.1rem;
        letter-spacing: 2px;
      }
      .testimoni-card .quote {
        font-style: italic;
        font-size: 1rem;
        line-height: 1.7;
        color: var(--text-dark);
        margin: 0.8rem 0;
      }
      .testimoni-card .user {
        display: flex;
        align-items: center;
        gap: 0.8rem;
        margin-top: 0.5rem;
      }
      .testimoni-card .avatar {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        color: #fff;
        background: var(--primary);
        flex-shrink: 0;
      }
      .testimoni-card .user-info strong {
        display: block;
        font-weight: 700;
        line-height: 1.2;
      }
      .testimoni-card .user-info small {
        color: var(--text-muted);
        font-size: 0.8rem;
      }

      /* ===== CTA ===== */
      .cta-box {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        border-radius: var(--radius-lg);
        padding: 3rem 2.5rem;
        box-shadow: 0 24px 60px rgba(79, 70, 229, 0.3);
        position: relative;
        overflow: hidden;
      }
      .cta-box::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.08), transparent 70%);
        border-radius: 50%;
        pointer-events: none;
      }
      .cta-box h3 {
        font-weight: 800;
        letter-spacing: -0.5px;
      }
      .cta-box p {
        opacity: 0.85;
        font-weight: 400;
        max-width: 500px;
      }
      .cta-btn-light {
        background: #fff;
        color: var(--primary);
        border: none;
        padding: 0.6rem 2rem;
        border-radius: 60px;
        font-weight: 700;
        transition: var(--transition);
      }
      .cta-btn-light:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
        background: #f8fafc;
      }
      .cta-btn-outline-light {
        background: transparent;
        color: #fff;
        border: 2px solid rgba(255, 255, 255, 0.5);
        padding: 0.6rem 2rem;
        border-radius: 60px;
        font-weight: 700;
        transition: var(--transition);
      }
      .cta-btn-outline-light:hover {
        background: rgba(255, 255, 255, 0.1);
        border-color: #fff;
        transform: translateY(-3px);
      }

      /* ===== FOOTER ===== */
      footer {
        background: rgba(255, 255, 255, 0.5);
        backdrop-filter: blur(12px);
        border-top: 1px solid rgba(255, 255, 255, 0.3);
        padding: 2.5rem 0 1.5rem;
        margin-top: 2rem;
      }
      footer .brand-footer {
        font-weight: 800;
        color: var(--primary);
      }
      footer a {
        color: var(--text-muted);
        text-decoration: none;
        transition: var(--transition);
        font-weight: 500;
      }
      footer a:hover {
        color: var(--primary);
      }
      footer .social a {
        font-size: 1.4rem;
        margin-right: 1rem;
        color: var(--text-muted);
      }
      footer .social a:hover {
        color: var(--primary);
      }

      /* ===== RESPONSIVE ===== */
      @media (max-width: 768px) {
        .hero {
          padding: 3rem 0 2.5rem;
        }
        .hero-card {
          padding: 1.5rem;
        }
        .stat-number {
          font-size: 2rem;
        }
        .cta-box {
          padding: 2rem 1.5rem;
          text-align: center;
        }
        .cta-box p {
          max-width: 100%;
        }
        .book-cover {
          height: 150px;
        }
      }

      /* ===== SCROLLBAR ===== */
      ::-webkit-scrollbar {
        width: 8px;
      }
      ::-webkit-scrollbar-track {
        background: var(--bg-start);
      }
      ::-webkit-scrollbar-thumb {
        background: var(--primary-light);
        border-radius: 10px;
      }
      ::-webkit-scrollbar-thumb:hover {
        background: var(--primary);
      }
    </style>
  </head>
  <body>

    <!-- ============================================================ -->
    <!-- NAVBAR -->
    <!-- ============================================================ -->
    <nav class="navbar navbar-expand-lg fixed-top" id="navbar">
      <div class="container">
        <a class="navbar-brand brand" href="#">
          <i class='bx bx-book-open'></i>Perpustakaan Mini
        </a>
        <div class="ms-auto d-flex gap-2 align-items-center">
          <a href="#" class="btn nav-btn nav-btn-outline d-none d-sm-inline-block">Masuk</a>
          <a href="#" class="btn nav-btn nav-btn-primary">Daftar</a>
        </div>
      </div>
    </nav>

    <!-- ============================================================ -->
    <!-- HERO -->
    <!-- ============================================================ -->
    <section class="hero" id="hero">
      <div class="container position-relative">
        <div class="row align-items-center g-5">
          <div class="col-lg-7">
            <span class="badge-pill mb-3">
              <i class='bx bx-library'></i> Solusi nyaman untuk pelanggan & pengelola
            </span>
            <h1>
              Pinjam buku dengan cepat, <br>
              <span class="highlight">tanpa ribet</span>, dan selalu siap.
            </h1>
            <p>
              Perpustakaan Mini dibuat untuk pelanggan yang ingin mencari buku, meminjam, dan
              mengakses layanan perpustakaan dengan pengalaman yang lebih cepat, jelas, dan terpercaya.
            </p>
            <div class="d-flex flex-wrap gap-3 mt-4">
              <a href="#" class="btn nav-btn nav-btn-primary btn-lg">Daftar Sekarang</a>
              <a href="#" class="btn nav-btn nav-btn-outline btn-lg">Masuk ke Akun</a>
            </div>
            <div class="mt-4 d-flex flex-wrap gap-3">
              <span class="badge-pill"><i class='bx bx-time-five'></i> Proses cepat</span>
              <span class="badge-pill"><i class='bx bx-heart'></i> Nyaman dipakai</span>
              <span class="badge-pill"><i class='bx bx-lock-alt'></i> Terpercaya</span>
            </div>
          </div>
          <div class="col-lg-5">
            <div class="hero-card">
              <h4 class="fw-bold mb-3">Kenapa pelanggan suka?</h4>
              <ul>
                <li><i class='bx bx-check-circle'></i> Temukan buku favorit lebih cepat</li>
                <li><i class='bx bx-check-circle'></i> Pinjam dengan alur sederhana & jelas</li>
                <li><i class='bx bx-check-circle'></i> Monitor status peminjaman tanpa bertanya</li>
                <li><i class='bx bx-check-circle'></i> Pengalaman layanan modern & terarah</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ============================================================ -->
    <!-- STATISTIK (Dinamis dengan Counter) -->
    <!-- ============================================================ -->
    <section class="stats-section animate-on-scroll" id="stats">
      <div class="container">
        <div class="row g-4">
          <div class="col-6 col-md-3">
            <div class="stat-card">
              <div class="stat-icon"><i class='bx bx-book'></i></div>
              <div class="stat-number" data-target="12500">0</div>
              <div class="stat-label">Koleksi Buku</div>
            </div>
          </div>
          <div class="col-6 col-md-3">
            <div class="stat-card">
              <div class="stat-icon"><i class='bx bx-user'></i></div>
              <div class="stat-number" data-target="8400">0</div>
              <div class="stat-label">Pelanggan Aktif</div>
            </div>
          </div>
          <div class="col-6 col-md-3">
            <div class="stat-card">
              <div class="stat-icon"><i class='bx bx-calendar-check'></i></div>
              <div class="stat-number" data-target="15200">0</div>
              <div class="stat-label">Peminjaman Bulan Ini</div>
            </div>
          </div>
          <div class="col-6 col-md-3">
            <div class="stat-card">
              <div class="stat-icon"><i class='bx bx-star'></i></div>
              <div class="stat-number" data-target="4.9">0</div>
              <div class="stat-label">Rating Rata-rata</div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ============================================================ -->
    <!-- FITUR -->
    <!-- ============================================================ -->
    <section class="py-5 animate-on-scroll" id="features">
      <div class="container">
        <div class="text-center mb-5">
          <h2 class="fw-bold">Fitur <span style="color: var(--primary);">Unggulan</span></h2>
          <p class="text-muted" style="max-width: 500px; margin: 0.5rem auto 0;">Nikmati kemudahan akses dan pengalaman perpustakaan yang lebih baik.</p>
        </div>
        <div class="row g-4">
          <div class="col-md-4">
            <div class="feature-card">
              <div class="feature-icon"><i class='bx bx-search'></i></div>
              <h5>Temukan Buku yang Dibutuhkan</h5>
              <p>Pelanggan bisa cepat menemukan buku yang sesuai kebutuhan belajar, hiburan, atau referensi.</p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="feature-card">
              <div class="feature-icon"><i class='bx bx-book-reader'></i></div>
              <h5>Peminjaman yang Lebih Praktis</h5>
              <p>Alur peminjaman dibuat lebih ringkas sehingga pelanggan tidak perlu menunggu lama.</p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="feature-card">
              <div class="feature-icon"><i class='bx bx-shield-quarter'></i></div>
              <h5>Layanan yang Terarah</h5>
              <p>Setiap aktivitas peminjaman tersusun rapi sehingga pelanggan merasa lebih aman dan nyaman.</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ============================================================ -->
    <!-- GALERI BUKU (Dinamis) -->
    <!-- ============================================================ -->
    <section class="py-5 animate-on-scroll" id="books" style="background: rgba(255,255,255,0.3);">
      <div class="container">
        <div class="text-center mb-5">
          <h2 class="fw-bold">Koleksi <span style="color: var(--primary);">Populer</span></h2>
          <p class="text-muted" style="max-width: 500px; margin: 0.5rem auto 0;">Buku-buku favorit yang paling banyak dicari.</p>
        </div>
        <div class="row g-4">
          <div class="col-sm-6 col-lg-3">
            <div class="book-card">
              <div class="book-cover" style="background: linear-gradient(135deg, #4f46e5, #818cf8);">
                <i class='bx bx-book'></i>
                <span class="book-badge">Fiksi</span>
              </div>
              <div class="book-body">
                <h6>Bumi Manusia</h6>
                <div class="author">Pramoedya Ananta Toer</div>
                <div class="meta">
                  <span class="tag">Novel</span>
                  <span class="year">1980</span>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-lg-3">
            <div class="book-card">
              <div class="book-cover" style="background: linear-gradient(135deg, #2563eb, #60a5fa);">
                <i class='bx bx-book-open'></i>
                <span class="book-badge">Non-Fiksi</span>
              </div>
              <div class="book-body">
                <h6>Sapiens</h6>
                <div class="author">Yuval Noah Harari</div>
                <div class="meta">
                  <span class="tag">Sejarah</span>
                  <span class="year">2011</span>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-lg-3">
            <div class="book-card">
              <div class="book-cover" style="background: linear-gradient(135deg, #7c3aed, #a78bfa);">
                <i class='bx bx-book-content'></i>
                <span class="book-badge">Pengembangan</span>
              </div>
              <div class="book-body">
                <h6>Atomic Habits</h6>
                <div class="author">James Clear</div>
                <div class="meta">
                  <span class="tag">Self-Help</span>
                  <span class="year">2018</span>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-lg-3">
            <div class="book-card">
              <div class="book-cover" style="background: linear-gradient(135deg, #0891b2, #22d3ee);">
                <i class='bx bx-book-heart'></i>
                <span class="book-badge">Fiksi</span>
              </div>
              <div class="book-body">
                <h6>Laut Bercerita</h6>
                <div class="author">Leila S. Chudori</div>
                <div class="meta">
                  <span class="tag">Novel</span>
                  <span class="year">2017</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="text-center mt-5">
          <a href="#" class="btn nav-btn nav-btn-outline">Lihat Semua Koleksi</a>
        </div>
      </div>
    </section>

    <!-- ============================================================ -->
    <!-- TESTIMONI -->
    <!-- ============================================================ -->
    <section class="py-5 animate-on-scroll" id="testimonials">
      <div class="container">
        <div class="text-center mb-5">
          <h2 class="fw-bold">Apa Kata <span style="color: var(--primary);">Pelanggan</span></h2>
          <p class="text-muted" style="max-width: 500px; margin: 0.5rem auto 0;">Pengalaman nyata dari komunitas perpustakaan.</p>
        </div>
        <div class="row g-4">
          <div class="col-md-4">
            <div class="testimoni-card">
              <div class="stars"><i class='bx bxs-star'></i><i class='bx bxs-star'></i><i class='bx bxs-star'></i><i class='bx bxs-star'></i><i class='bx bxs-star'></i></div>
              <p class="quote">"Koleksinya lengkap dan suasana ruang baca sangat tenang. Saya sering menghabiskan akhir pekan di sini."</p>
              <div class="user">
                <div class="avatar">A</div>
                <div class="user-info">
                  <strong>Andini</strong>
                  <small>Mahasiswa</small>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="testimoni-card">
              <div class="stars"><i class='bx bxs-star'></i><i class='bx bxs-star'></i><i class='bx bxs-star'></i><i class='bx bxs-star'></i><i class='bx bxs-star'></i></div>
              <p class="quote">"Akses digitalnya memudahkan saya membaca jurnal dan referensi untuk penelitian. Sangat direkomendasikan!"</p>
              <div class="user">
                <div class="avatar" style="background: #2563eb;">B</div>
                <div class="user-info">
                  <strong>Bima</strong>
                  <small>Peneliti</small>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="testimoni-card">
              <div class="stars"><i class='bx bxs-star'></i><i class='bx bxs-star'></i><i class='bx bxs-star'></i><i class='bx bxs-star'></i><i class='bx bxs-star'></i></div>
              <p class="quote">"Anak-anak saya senang mengikuti program mendongeng dan workshop literasi. Tempat yang ramah keluarga."</p>
              <div class="user">
                <div class="avatar" style="background: #7c3aed;">C</div>
                <div class="user-info">
                  <strong>Citra</strong>
                  <small>Ibu rumah tangga</small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ============================================================ -->
    <!-- CTA -->
    <!-- ============================================================ -->
    <section class="py-5 animate-on-scroll" id="cta">
      <div class="container">
        <div class="cta-box">
          <div class="row align-items-center g-4">
            <div class="col-lg-8">
              <h3 class="fw-bold text-white">Siap menikmati layanan perpustakaan yang lebih cepat?</h3>
              <p class="text-white-50 mb-0">Daftar sekarang untuk mulai mengakses fitur yang memudahkan pelanggan dalam mencari dan meminjam buku.</p>
            </div>
            <div class="col-lg-4 text-lg-end">
              <a href="#" class="btn cta-btn-light me-2 mb-2">Daftar</a>
              <a href="#" class="btn cta-btn-outline-light mb-2">Masuk</a>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ============================================================ -->
    <!-- FOOTER -->
    <!-- ============================================================ -->
    <footer>
      <div class="container">
        <div class="row g-4 align-items-center">
          <div class="col-md-4">
            <span class="brand-footer fw-bold fs-5"><i class='bx bx-book-open me-2'></i>Perpustakaan Mini</span>
            <p class="text-muted small mt-2" style="max-width: 260px;">Perpustakaan modern yang terbuka untuk semua. Menumbuhkan minat baca dan akses pengetahuan.</p>
          </div>
          <div class="col-md-4 text-center">
            <div class="social">
              <a href="#"><i class='bx bxl-instagram'></i></a>
              <a href="#"><i class='bx bxl-twitter'></i></a>
              <a href="#"><i class='bx bxl-youtube'></i></a>
              <a href="#"><i class='bx bxl-facebook'></i></a>
            </div>
          </div>
          <div class="col-md-4 text-md-end">
            <a href="#" class="me-3">Tentang</a>
            <a href="#" class="me-3">Kebijakan</a>
            <a href="#">Kontak</a>
          </div>
        </div>
        <hr class="my-3" style="border-color: rgba(0,0,0,0.05);">
        <div class="text-center small text-muted">
          &copy; 2026 Perpustakaan Mini. Dibuat dengan <i class='bx bx-heart' style="color: var(--primary);"></i> untuk literasi.
        </div>
      </div>
    </footer>

    <!-- ============================================================ -->
    <!-- SCRIPTS -->
    <!-- ============================================================ -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
      (function() {
        'use strict';

        // ===== NAVBAR SCROLL EFFECT =====
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', function() {
          if (window.scrollY > 40) {
            navbar.classList.add('scrolled');
          } else {
            navbar.classList.remove('scrolled');
          }
        });

        // ===== SCROLL REVEAL (Intersection Observer) =====
        const animateElements = document.querySelectorAll('.animate-on-scroll');
        const observer = new IntersectionObserver((entries) => {
          entries.forEach(entry => {
            if (entry.isIntersecting) {
              entry.target.classList.add('visible');
            }
          });
        }, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });

        animateElements.forEach(el => observer.observe(el));

        // ===== COUNTER ANIMATION =====
        const statNumbers = document.querySelectorAll('.stat-number');
        let countersStarted = false;

        function startCounters() {
          if (countersStarted) return;
          countersStarted = true;

          statNumbers.forEach(el => {
            const target = parseFloat(el.dataset.target);
            const isFloat = target % 1 !== 0;
            const duration = 2000;
            const startTime = performance.now();

            function updateCounter(currentTime) {
              const elapsed = currentTime - startTime;
              const progress = Math.min(elapsed / duration, 1);
              // easeOutQuart
              const eased = 1 - Math.pow(1 - progress, 4);
              const current = eased * target;
              el.textContent = isFloat ? current.toFixed(1) : Math.floor(current);
              if (progress < 1) {
                requestAnimationFrame(updateCounter);
              } else {
                el.textContent = isFloat ? target.toFixed(1) : target;
              }
            }
            requestAnimationFrame(updateCounter);
          });
        }

        // Trigger counter when stats section becomes visible
        const statsSection = document.getElementById('stats');
        const statsObserver = new IntersectionObserver((entries) => {
          entries.forEach(entry => {
            if (entry.isIntersecting && !countersStarted) {
              startCounters();
            }
          });
        }, { threshold: 0.3 });

        statsObserver.observe(statsSection);

        // ===== SMOOTH SCROLL UNTUK ANCHOR (opsional) =====
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
          anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href === "#" || href === "") return;
            e.preventDefault();
            const target = document.querySelector(href);
            if (target) {
              target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
          });
        });

        // ===== PARALLAX SEDERHANA PADA HERO =====
        const hero = document.getElementById('hero');
        window.addEventListener('scroll', function() {
          const scrollY = window.scrollY;
          if (scrollY < 500) {
            hero.style.transform = `translateY(${scrollY * 0.03}px)`;
            hero.style.opacity = 1 - (scrollY / 700);
          }
        }, { passive: true });

      })();
    </script>
  </body>
</html>
