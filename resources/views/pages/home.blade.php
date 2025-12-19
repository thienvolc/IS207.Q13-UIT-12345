<?php

/**
 * @var array<App\Domains\Catalog\DTOs\Product\Responses\PublicProductDTO> heroProducts
 * @var array<App\Domains\Catalog\DTOs\Category\Responses\PublicCategoryDTO> bannerCategories
 * @var array<App\Domains\Catalog\DTOs\Product\Responses\PublicProductDTO> newProducts
 * @var array<App\Domains\Catalog\DTOs\Product\Responses\PublicProductDTO> featuredProducts
 * @var array<App\Domains\Catalog\DTOs\Product\Responses\PublicProductDTO> saleProducts
 * @var array<App\Domains\Catalog\DTOs\Product\Responses\PublicProductDTO> bestSellers
 * */
?>

@extends('layouts.app')
@section('title', 'PinkCapy - Home')

@section('content')
  <!-- Slide Section -->
  <section class="grid hero hero--anim mb-5 home-padding">
    <div class="hero__inner">
      <div class="hero__content">
        <h1 class="hero__title"></h1>
        <p class="hero__subtitle"></p>

        <div class="hero__meta">
          <span class="hero__from">TỪ</span>
          <span class="hero__price">
            <strong></strong>
            <sup></sup><span class="hero__price-currency">đ</span>
          </span>
        </div>

        <a href="/san-pham/" class="btn-cta">Mua ngay</a>

        <div class="hero__dots" aria-hidden="true">
          <span class="dot is-active" data-index="0"></span>
          <span class="dot" data-index="1"></span>
          <span class="dot" data-index="2"></span>
        </div>
      </div>

      <div class="hero__visual">
        <img src="/img/hero1.png" alt="" class="hero__img">
      </div>
    </div>
  </section>

  @push('scripts')
    <script src="{{ asset('js/home.js') }}"></script>
  @endpush

  <section class="grid home-padding">
    <!-- Banner recommends-->
    <div class="mb-5">
      <div class="grid-row">
        {{-- ============================================ --}}
        {{-- DATA THẬT - Categories từ Aiven Cloud DB --}}
        {{-- ============================================ --}}
        @php
          // HARDCODE TẠM: Banner images và titles
          $bannerData = [
            [
              'image' => '/img/hero3.png',
              'title' => 'ÂM THANH',
              'slug' => 'tai-nghe'
            ],
            [
              'image' => '/img/hero2.png',
              'title' => 'BÀN PHÍM',
              'slug' => 'banphim'
            ],
            [
              'image' => '/img/rcm3.jpg',
              'title' => 'PHỤ KIỆN',
              'slug' => 'phu-kien'
            ],
            [
              'image' => '/img/hero4.png',
              'title' => 'ĐỒNG HỒ THÔNG MINH',
              'slug' => 'dong-ho'
            ]
          ];
        @endphp

        @foreach($bannerData as $banner)
          <div class="grid__col-3">
            <a href="{{ route('products.index') }}?category={{ $banner['slug'] }}" class="recommends-item">
              <div class="recommends-item-wrap">
                <div class="grid__col-6">
                  <img src="{{ $banner['image'] }}" alt="{{ $banner['title'] }}" class="img-fluid">
                </div>
                <div class="grid__col-6">
                  <div class="recommends-item-title">
                    MUA NGAY
                    <strong>DEAL HOT</strong>
                    DÀNH CHO {{ $banner['title'] }}
                  </div>
                  <div class="recommends-item-link-show">Xem chi tiết
                    <span class="recommends-item-icon"><i class="bi bi-arrow-right-circle"></i></span>
                  </div>
                </div>
              </div>
            </a>
          </div>
        @endforeach
      </div>
    </div>
    <!-- Product Tabs Section (Restored) -->
    <div class="mb-5">
      <div class="grid-row">
        <!-- Tabs - Full Width -->
        <div class="grid__col-12 tabs-section">
          <!-- Nav Tabs -->
          <div class="tabs-nav-wrapper">
            <ul class="nav nav-pills nav-classic justify-content-center" id="productTabs" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link active" id="featured-tab" data-bs-toggle="pill" data-bs-target="#featured"
                  type="button" role="tab" aria-controls="featured" aria-selected="true">
                  SẢN PHẨM MỚI
                </button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="onsale-tab" data-bs-toggle="pill" data-bs-target="#onsale" type="button"
                  role="tab" aria-controls="onsale" aria-selected="false">
                  SẢN PHẨM NỔI BẬT
                </button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="toprated-tab" data-bs-toggle="pill" data-bs-target="#toprated" type="button"
                  role="tab" aria-controls="toprated" aria-selected="false">
                  SẢN PHẨM ĐANG GIẢM GIÁ
                </button>
              </li>
            </ul>
          </div>
          <!-- End Nav Tabs -->

          <!-- Tab Content -->
          <div class="tab-content" id="productTabsContent">
            <!-- Tab New -->
            <div class="tab-pane fade show active" id="featured" role="tabpanel" aria-labelledby="featured-tab">
              <div class="grid-row">
                @foreach($newProducts as $product)
                  <div class="grid__col-2-4 product-col mb-3" data-product-id="{{ $product->productId ?? '' }}">
                    <x-product-card :product="$product" />
                  </div>
                @endforeach
              </div>
            </div>
            <!-- End Tab New -->

            <!-- Tab nổi bật -->
            <div class="tab-pane fade" id="onsale" role="tabpanel" aria-labelledby="onsale-tab">
              <div class="grid-row">
                @foreach($featuredProducts as $product)
                  <div class="grid__col-2-4 product-col mb-3" data-product-id="{{ $product->productId ?? '' }}">
                    <x-product-card :product="$product" />
                  </div>
                @endforeach
              </div>
            </div>
            <!-- End Tab nổi bật -->

            <!-- Tab đang giảm giá -->
            <div class="tab-pane fade" id="toprated" role="tabpanel" aria-labelledby="toprated-tab">
              <div class="grid-row">
                @foreach($saleProducts as $product)
                  <div class="grid__col-2-4 product-col mb-3">
                    <x-product-card :product="$product" />
                  </div>
                @endforeach
              </div>
            </div>
            <!-- End Tab đang giảm giá -->
          </div>
          <!-- End Tab Content -->
        </div>
      </div>
    </div>

    <!-- Homepage Sections (Reorganized) -->
    @php
      $homeSections = [
        [
          'title' => 'TOP 10 BEST SELLER',
          'products' => $bestSellers,
          'link' => route('products.index') . '?sort=best_seller'
        ],
        [
          'title' => 'TAI NGHE',
          'products' => $headphoneProducts,
          'link' => route('products.index') . '?category=tai-nghe'
        ],
        [
          'title' => 'ĐỒNG HỒ',
          'products' => $watchProducts,
          'link' => route('products.index') . '?category=do-choi-cong-nghe'
        ],
        [
          'title' => 'CAMERA',
          'products' => $cameraProducts,
          'link' => route('products.index') . '?category=camera'
        ]
      ];
    @endphp

    @foreach($homeSections as $section)
      @if(count($section['products']) > 0)
        @php
          $isBestSeller = $section['title'] === 'TOP 10 BEST SELLER';
          // Add blue border #6ea3f9 to Best Seller frame (thinner: 1px)
          $bgStyle = $isBestSeller ? "background-image: url('/img/bg_vertex.jpg'); background-size: cover; border-radius: 20px; padding: 20px; border: 3px solid #6ea3f9;" : "";
          $titleClass = $isBestSeller ? "section-title-bestseller" : "section-title-glow";
          $titleColor = $isBestSeller ? "#d70018" : "#d70018"; // Keep consistent red
        @endphp

        <div class="mb-5" style="{{ $bgStyle }}">
          <!-- Section Header -->
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold text-uppercase mb-0 {{ $titleClass }}" style="color: {{ $titleColor }};">
              {{ $section['title'] }}
            </h3>

            @if(!$isBestSeller)
              <a href="{{ $section['link'] }}" class="link-see-all">
                Xem tất cả <i class="bi bi-chevron-right"></i>
              </a>
            @endif
          </div>

          <!-- Product Carousel -->
          <div class="product-carousel-section position-relative">
            <div class="product-carousel-viewport overflow-hidden">
              <!-- Added 'flex-nowrap' to ensure horizontal layout -->
              <div class="grid-row flex-nowrap product-carousel-track" style="transition: transform 0.5s ease-in-out;">
                @foreach($section['products'] as $product)
                  <div class="grid__col-2-4 product-col mb-3 flex-shrink-0" data-product-id="{{ $product->productId ?? '' }}">
                    <x-product-card :product="$product" />
                  </div>
                @endforeach
              </div>
            </div>

            <!-- Navigation Buttons -->
            <button class="carousel-btn-custom carousel-btn-prev js-carousel-prev">
              <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512"
                xmlns="http://www.w3.org/2000/svg">
                <path fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="48"
                  d="M328 112 184 256l144 144"></path>
              </svg>
            </button>
            <button class="carousel-btn-custom carousel-btn-next js-carousel-next">
              <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512"
                xmlns="http://www.w3.org/2000/svg">
                <path fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="48"
                  d="M184 112l144 144-144 144"></path>
              </svg>
            </button>
          </div>
        </div>
      @endif
    @endforeach
  </section>
@endsection