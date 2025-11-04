@extends('layouts.app')
@section('title','PinkCapy - Home')

@section('content')
<!-- Slide Section -->
<section class="grid hero hero--anim mb-5">
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

      <a href="/" class="btn-cta">Mua ngay</a>

      <div class="hero__dots" aria-hidden="true">
        <span class="dot is-active" data-index="0"></span>
        <span class="dot" data-index="1"></span>
        <span class="dot" data-index="2"></span>
      </div>
    </div>

    <div class="hero__visual">
      <img src="" alt="" class="hero__img">
    </div>
  </div>
</section>

<section class="grid">
  <!-- Banner recommends-->
  <div class="mb-5">
    <div class="grid-row">
      @php
      $banners = [
      ['img' => '/img/rcm1.png', 'title' => 'CAMERA'],
      ['img' => '/img/rcm2.jpg', 'title' => 'LAPTOP'],
      ['img' => '/img/rcm3.jpg', 'title' => 'GAMING'],
      ['img' => '/img/rcm4.png', 'title' => 'AUDIO'],
      ];
      @endphp
      @foreach($banners as $banner)
      <div class="grid__col-3">
        <a href="{{ route('products.index') }}" class="recommends-item">
          <div class="recommends-item-wrap">
            <div class="grid__col-6">
              <img src="{{ $banner['img'] }}" alt="{{ $banner['title'] }}" class="img-fluid">
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
  <!-- Deal and tabs -->
  <div class="mb-5">
    <div class="grid-row">
      <!-- Deal -->
      @php
      $dealProduct = $newProducts->first();
      @endphp
      <div class="grid__col-3 deal">
        <div class="deal-wrap">
          <div class="deal-header">
            <h3 class="deal-header-title">
              ƯU ĐÃI ĐẶC BIỆT
            </h3>
            <div class="deal-header-coupon">
              <span>Tiết kiệm</span>
              <div class="deal-header-coupon_price">
                {{ number_format(($dealProduct['price'] - $dealProduct['price_sale']) / 1000) }}K
              </div>
            </div>
          </div>
          <div class="deal-img">
            <a href="{{ route('products.show', $dealProduct['slug']) }}">
              <img src="{{ $dealProduct['thumbnail'] }}" alt="{{ $dealProduct['name'] }}" class="img-fluid">
            </a>
          </div>
          <h5 class="deal-name-produce">
            <a href="{{ route('products.show', $dealProduct['slug']) }}">
              {{ $dealProduct['name'] }}
            </a>
          </h5>
          <div class="deal-price">
            <del class="deal-price-main">{{ number_format($dealProduct['price']) }}đ</del>
            <ins class="deal-price-reduce">{{ number_format($dealProduct['price_sale']) }}đ</ins>
          </div>
          <div class="deal-hang">
            <div class="deal-soluong">
              <span class="">Available: <strong>3</strong></span>
              <span class="">Already Sold: <strong>36</strong></span>
            </div>
          </div>
          <div class="deal-offer-end">
            <h6>Ưu đãi kết thúc trong:</h6>
            <div class="js-countdown d-flex justify-content-center" data-end-date="2025/12/31" data-hours-format="%H" data-minutes-format="%M" data-seconds-format="%S">
              <div class="countdown-item">
                <div class="countdown-number">
                  <span class="js-cd-hours">00</span>
                </div>
                <div class="countdown-label">GIỜ</div>
              </div>
              <div class="countdown-separator">:</div>
              <div class="countdown-item">
                <div class="countdown-number">
                  <span class="js-cd-minutes">00</span>
                </div>
                <div class="countdown-label">PHÚT</div>
              </div>
              <div class="countdown-separator">:</div>
              <div class="countdown-item">
                <div class="countdown-number">
                  <span class="js-cd-seconds">00</span>
                </div>
                <div class="countdown-label">GIÂY</div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Tabs -->
      <div class="grid__col-9 tabs-section">
        <!-- Nav Tabs -->
        <div class="tabs-nav-wrapper">
          <ul class="nav nav-pills nav-classic justify-content-center" id="productTabs" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="featured-tab" data-bs-toggle="pill" data-bs-target="#featured" type="button" role="tab" aria-controls="featured" aria-selected="true">
                SẢN PHẨM MỚI
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="onsale-tab" data-bs-toggle="pill" data-bs-target="#onsale" type="button" role="tab" aria-controls="onsale" aria-selected="false">
                SẢN PHẨM NỔI BẬT
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="toprated-tab" data-bs-toggle="pill" data-bs-target="#toprated" type="button" role="tab" aria-controls="toprated" aria-selected="false">
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
              <div class="grid__col-3 product-col">
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
              <div class="grid__col-3 product-col">
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
              <div class="grid__col-3 product-col">
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
  <!-- Product carousel -->
  <div class="product__carousel-wrap">
    <div class="product__carousel">
      <h3>Best Sellers</h3>
      <ul class="nav nav-pills product__carousel-list">
        <li class="nav-item">
          <a href="{{ route('products.index') }}" class="nav-link product__carousel-item-top">Top 10</a>
        </li>
        <li class="nav-item">
          <a href="{{ route('products.index') }}" class="nav-link">Tai nghe</a>
        </li>
        <li class="nav-item">
          <a href="{{ route('products.index') }}" class="nav-link">Phụ kiện</a>
        </li>
        <li class="nav-item">
          <a href="{{ route('products.index') }}" class="nav-link">Camera</a>
        </li>
      </ul>
    </div>

    <div class="container">
      <div class="product-carousel-wrapper">
        <!-- Tab Navigation Buttons -->
        <button class="scroll-btn scroll-btn-left" data-tab-action="prev">
          <i class="bi bi-chevron-left"></i>
        </button>
        <button class="scroll-btn scroll-btn-right" data-tab-action="next">
          <i class="bi bi-chevron-right"></i>
        </button>

        @php
        $tab1 = $bestSellers->take(8);
        $tab2 = $bestSellers->skip(8)->take(8);
        @endphp

        <!-- Tab 1 - Products 1-8 -->
        <div class="grid-row product-tab active" id="tab-page-1">
          @foreach($tab1 as $product)
          <div class="grid__col-3 product-col">
            <div class="product-item">
              <div class="product-item__image">
                <a href="{{ route('products.show', $product['slug']) }}">
                  <img src="{{ $product['thumbnail'] }}" alt="{{ $product['name'] }}" class="img-fluid" loading="lazy">
                </a>
              </div>
              <div class="product-item__content">
                <div class="product-item__category">
                  <a href="{{ route('products.index') }}">{{ $product['category'] }}</a>
                </div>
                <h5 class="product-item__title">
                  <a href="{{ route('products.show', $product['slug']) }}">{{ $product['name'] }}</a>
                </h5>
                <div class="product-item__footer">
                  <div class="product-item__price">{{ number_format($product['price_sale']) }}đ</div>
                  <div class="product-item__btn-list">
                    <button class="product-item__btn-item btn-cart">
                      <i class="bi bi-cart-plus"></i>
                    </button>
                  </div>
                  <div class="product-item__hover-actions">
                    <button class="product-item__btn-item btn-compare">
                      <i class="bi bi-arrow-left-right"></i>
                    </button>
                    <button class="product-item__btn-item btn-wishlist">
                      <i class="bi bi-heart"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          @endforeach
        </div>
        <!-- End Tab 1 -->

        <!-- Tab 2 - Products 9-16 -->
        <div class="grid-row product-tab" id="tab-page-2">
          @foreach($tab2 as $product)
          <div class="grid__col-3 product-col">
            <div class="product-item">
              <div class="product-item__image">
                <a href="{{ route('products.show', $product['slug']) }}">
                  <img src="{{ $product['thumbnail'] }}" alt="{{ $product['name'] }}" class="img-fluid" loading="lazy">
                </a>
              </div>
              <div class="product-item__content">
                <div class="product-item__category">
                  <a href="{{ route('products.index') }}">{{ $product['category'] }}</a>
                </div>
                <h5 class="product-item__title">
                  <a href="{{ route('products.show', $product['slug']) }}">{{ $product['name'] }}</a>
                </h5>
                <div class="product-item__footer">
                  <div class="product-item__price">{{ number_format($product['price_sale']) }}đ</div>
                  <div class="product-item__btn-list">
                    <button class="product-item__btn-item btn-cart">
                      <i class="bi bi-cart-plus"></i>
                    </button>
                  </div>
                  <div class="product-item__hover-actions">
                    <button class="product-item__btn-item btn-compare">
                      <i class="bi bi-arrow-left-right"></i>
                    </button>
                    <button class="product-item__btn-item btn-wishlist">
                      <i class="bi bi-heart"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          @endforeach
        </div>
        <!-- End Tab 2 -->

        <!-- Tab Indicators -->
        <div class="carousel-progress">
          <div class="tab-indicators">
            <span class="tab-dot active" data-tab-number="1"></span>
            <span class="tab-dot" data-tab-number="2"></span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection