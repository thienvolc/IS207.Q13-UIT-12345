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
    <div class="grid__col-3">
      <a href="/" class="recommends-item ">
        <div class="recommends-item-wrap">
          <div class="grid__col-6">
            <img src="/img/rcm1.png" alt="" class="img-fluid">
          </div>
          <div class="grid__col-6">
            <div class="recommends-item-title">
              MUA NGAY
              <strong>DEAL HOT</strong>
              DÀNH CHO CAMERA
            </div>
            <div class="recommends-item-link-show" href="/"
            >Xem chi tiết
            <span class="recommends-item-icon"><i class="bi bi-arrow-right-circle"></i></span>
            </div>
          </div>
        </div>
      </a>
    </div>
    <div class="grid__col-3">
      <a href="/" class="recommends-item">
        <div class="recommends-item-wrap">
          <div class="grid__col-6">
            <img src="/img/rcm2.jpg" alt="" class="img-fluid">
          </div>

          <div class="grid__col-6">
              <div class="recommends-item-title">
                MUA NGAY
                <strong>DEAL HOT</strong>
                DÀNH CHO CAMERA
              </div>
              <div class="recommends-item-link-show" href="/"
              >Xem chi tiết
              <span class="recommends-item-icon"><i class="bi bi-arrow-right-circle"></i></span>
            </div>
          </div>
        </div>
      </a>
        </div>
    <div class="grid__col-3">
      <a href="/" class="recommends-item">
        <div class="recommends-item-wrap">
          <div class="grid__col-6">
            <img src="/img/rcm3.jpg" alt="" class="img-fluid">
          </div>
          <div class="grid__col-6">
            <div class="recommends-item-title">
              MUA NGAY
              <strong>DEAL HOT</strong>
              DÀNH CHO CAMERA
            </div>
            <div class="recommends-item-link-show" href="/"
            >Xem chi tiết
            <span class="recommends-item-icon"><i class="bi bi-arrow-right-circle"></i></span>
            </div>
          </div>
        </div>
      </a>
    </div>
    <div class="grid__col-3">
      <a href="/" class="recommends-item">
        <div class="recommends-item-wrap">
          <div class="grid__col-6">
            <img src="/img/rcm4.png" alt="" class="img-fluid">
          </div>
        <div class="grid__col-6">
            <div class="recommends-item-title">
              MUA NGAY
              <strong>DEAL HOT</strong>
              DÀNH CHO CAMERA
            </div>
          <div class="recommends-item-link-show" href="/"
          >Xem chi tiết
          <span class="recommends-item-icon"><i class="bi bi-arrow-right-circle"></i></span>
            </div>
        </div>
        </div>
      </a>
    </div>
  </div>
</div>
<!-- Deal and tabs -->
  <div class="mb-5">
    <div class="grid-row">
      <!-- Deal -->
      <div class="grid__col-3 deal">
        <div class="deal-wrap">
          <div class="deal-header">
            <h3 class="deal-header-title">
              ƯU ĐÃI ĐẶC BIỆT
            </h3>
            <div class="deal-header-coupon">
              <span>Tiết kiệm</span>
              <div class="deal-header-coupon_price">300K</div>
            </div>
          </div>
          <div class="deal-img">
            <a href="/">
              <img src="/img/sp1.jpg" alt="" class="img-fluid">
            </a>
          </div>
          <h5 class="deal-name-produce"><a href="/">
            Tay cầm PS5 + USB 3.0 Cable
          </a></h5>
          <div class="deal-price">
            <del class="deal-price-main">1.234.567đ</del> <!-- Thẻ del - đánh dấu văn bản bị xóa -->
            <ins class="deal-price-reduce">1.000.000đ</ins> <!-- Thẻ ins - đánh dấu một phần của văn bản đã được thêm vào văn bản gốc ban đầu -->
          </div>
          <div class="deal-hang">
            <div class="deal-soluong" >
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
          <!-- Tab Nổi bật -->
          <div class="tab-pane fade show active" id="featured" role="tabpanel" aria-labelledby="featured-tab">
            <div class="grid-row">
              <!-- Product 1 -->
              <div class="grid__col-3 product-col">
                <div class="product-item">
                  <div class="product-item__category">
                    <a href="/">Loa</a>
                  </div>
                  <h5 class="product-item__title">
                    <a href="/">Wireless Audio System Multiroom 360</a>
                  </h5>
                  <div class="product-item__image">
                    <a href="/">
                      <img src="/img/sp1.jpg" alt="Loa Wireless" class="img-fluid">
                    </a>
                  </div>
                  <div class="product-item__footer">
                    <div class="product-item__price">
                      <span class="price">685.000đ</span>
                    </div>
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
              <!-- Product 2 -->
              <div class="grid__col-3 product-col">
                <div class="product-item">
                  <div class="product-item__category">
                    <a href="/">Tablet</a>
                  </div>
                  <h5 class="product-item__title">
                    <a href="/">Tablet White EliteBook Revolve</a>
                  </h5>
                  <div class="product-item__image">
                    <a href="/">
                      <img src="/img/sp2.jpg" alt="Tablet" class="img-fluid">
                    </a>
                  </div>
                  <div class="product-item__footer">
                    <div class="product-item__price">
                      <span class="price">1.299.000đ</span>
                    </div>
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
              <!-- Product 3 -->
              <div class="grid__col-3 product-col">
                <div class="product-item">
                  <div class="product-item__category">
                    <a href="/">Tai nghe</a>
                  </div>
                  <h5 class="product-item__title">
                    <a href="/">Purple Solo 2 Wireless</a>
                  </h5>
                  <div class="product-item__image">
                    <a href="/">
                      <img src="/img/sp3.jpg" alt="Tai nghe" class="img-fluid">
                    </a>
                  </div>
                  <div class="product-item__footer">
                    <div class="product-item__price">
                      <del class="price-old">800.000đ</del>
                      <ins class="price-sale">685.000đ</ins>
                    </div>
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
              <!-- Product 4 -->
              <div class="grid__col-3 product-col">
                <div class="product-item">
                  <div class="product-item__category">
                    <a href="/">Điện thoại</a>
                  </div>
                  <h5 class="product-item__title">
                    <a href="/">Smartphone 6S 32GB LTE</a>
                  </h5>
                  <div class="product-item__image">
                    <a href="/">
                      <img src="/img/sp4.jpg" alt="Smartphone" class="img-fluid">
                    </a>
                  </div>
                  <div class="product-item__footer">
                    <div class="product-item__price">
                      <del class="price-old">850.000đ</del>
                      <ins class="price-sale">750.000đ</ins>
                    </div>
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

              <!-- Product 5 -->
              <div class="grid__col-3 product-col">
                <div class="product-item">
                  <div class="product-item__category">
                    <a href="/">Camera</a>
                  </div>
                  <h5 class="product-item__title">
                    <a href="/">Camera C430W 4K Waterproof</a>
                  </h5>
                  <div class="product-item__image">
                    <a href="/">
                      <img src="/img/sp5.jpg" alt="Camera" class="img-fluid">
                    </a>
                  </div>
                  <div class="product-item__footer">
                    <div class="product-item__price">
                      <span class="price">899.000đ</span>
                    </div>
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
              <!-- Product 6 -->
              <div class="grid__col-3 product-col">
                <div class="product-item">
                  <div class="product-item__category">
                    <a href="/">Smartwatch</a>
                  </div>
                  <h5 class="product-item__title">
                    <a href="/">Smartwatch 2.0 LTE WiFi</a>
                  </h5>
                  <div class="product-item__image">
                    <a href="/">
                      <img src="/img/sp6.jpg" alt="Smartwatch" class="img-fluid">
                    </a>
                  </div>
                  <div class="product-item__footer">
                    <div class="product-item__price">
                      <span class="price">799.000đ</span>
                    </div>
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
              <!-- Product 7 -->
              <div class="grid__col-3 product-col">
                <div class="product-item">
                  <div class="product-item__category">
                    <a href="/">Phụ kiện</a>
                  </div>
                  <h5 class="product-item__title">
                    <a href="/">External SSD USB 3.1 750GB</a>
                  </h5>
                  <div class="product-item__image">
                    <a href="/">
                      <img src="/img/sp7.jpg" alt="SSD" class="img-fluid">
                    </a>
                  </div>
                  <div class="product-item__footer">
                    <div class="product-item__price">
                      <del class="price-old">900.000đ</del>
                      <ins class="price-sale">799.000đ</ins>
                    </div>
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
              <!-- Product 8 -->
              <div class="grid__col-3 product-col">
                <div class="product-item">
                  <div class="product-item__category">
                    <a href="/">Camera</a>
                  </div>
                  <h5 class="product-item__title">
                    <a href="/">Purple NX Mini F1 SMART NX</a>
                  </h5>
                  <div class="product-item__image">
                    <a href="/">
                      <img src="/img/bestseler1.jpg" alt="Camera NX" class="img-fluid">
                    </a>
                  </div>
                  <div class="product-item__footer">
                    <div class="product-item__price">
                      <del class="price-old">1.000.000đ</del>
                      <ins class="price-sale">890.000đ</ins>
                    </div>
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
              <!-- More products... -->
            </div>
          </div>
          <!-- End Tab Nổi bật -->

          <!-- Tab Giảm giá -->
          <div class="tab-pane fade" id="onsale" role="tabpanel" aria-labelledby="onsale-tab">
            <div class="grid-row">
              <!-- Product 1 -->
              <div class="grid__col-3 product-col">
                <div class="product-item">
                  <div class="product-item__category">
                    <a href="/">Máy tính bảng</a>
                  </div>
                  <h5 class="product-item__title">
                    <a href="/">Tablet White EliteBook Revolve 810 G2</a>
                  </h5>
                  <div class="product-item__image">
                    <a href="/">
                      <img src="/img/sp2.jpg" alt="Product" class="img-fluid">
                    </a>
                  </div>
                  <div class="product-item__footer">
                    <div class="product-item__price">
                      <del class="price-old">2.299.000đ</del>
                      <ins class="price-sale">1.999.000đ</ins>
                    </div>
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
              <!-- Product 2 -->
              <div class="grid__col-3 product-col">
                <div class="product-item">
                  <div class="product-item__category">
                    <a href="/">Tai nghe</a>
                  </div>
                  <h5 class="product-item__title">
                    <a href="/">Purple Solo 2 Wireless</a>
                  </h5>
                  <div class="product-item__image">
                    <a href="/">
                      <img src="/img/sp3.jpg" alt="Product" class="img-fluid">
                    </a>
                  </div>
                  <div class="product-item__footer">
                    <div class="product-item__price">
                      <del class="price-old">800.000đ</del>
                      <ins class="price-sale">685.000đ</ins>
                    </div>
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
              <!-- Product 3 -->
              <div class="grid__col-3 product-col">
                <div class="product-item">
                  <div class="product-item__category">
                    <a href="/">Điện thoại</a>
                  </div>
                  <h5 class="product-item__title">
                    <a href="/">Smartphone 6S 32GB LTE</a>
                  </h5>
                  <div class="product-item__image">
                    <a href="/">
                      <img src="/img/sp4.jpg" alt="Product" class="img-fluid">
                    </a>
                  </div>
                  <div class="product-item__footer">
                    <div class="product-item__price">
                      <del class="price-old">750.000đ</del>
                      <ins class="price-sale">685.000đ</ins>
                    </div>
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
              <!-- Product 4 -->
              <div class="grid__col-3 product-col">
                <div class="product-item">
                  <div class="product-item__category">
                    <a href="/">Camera</a>
                  </div>
                  <h5 class="product-item__title">
                    <a href="/">Camera C430W 4k Waterproof</a>
                  </h5>
                  <div class="product-item__image">
                    <a href="/">
                      <img src="/img/sp5.jpg" alt="Product" class="img-fluid">
                    </a>
                  </div>
                  <div class="product-item__footer">
                    <div class="product-item__price">
                      <del class="price-old">900.000đ</del>
                      <ins class="price-sale">685.000đ</ins>
                    </div>
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
          </div>
          <!-- End Tab Giảm giá -->

          <!-- Tab Đánh giá cao -->
          <div class="tab-pane fade" id="toprated" role="tabpanel" aria-labelledby="toprated-tab">
            <div class="grid-row">
              <!-- Product 1 -->
              <div class="grid__col-3 product-col">
                <div class="product-item">
                  <div class="product-item__category">
                    <a href="/">Loa</a>
                  </div>
                  <h5 class="product-item__title">
                    <a href="/">Wireless Audio System Multiroom 360</a>
                  </h5>
                  <div class="product-item__image">
                    <a href="/">
                      <img src="/img/sp1.jpg" alt="Product" class="img-fluid">
                    </a>
                  </div>
                  <div class="product-item__footer">
                    <div class="product-item__price">
                      <span class="price">685.000đ</span>
                    </div>
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
              <!-- Product 2 -->
              <div class="grid__col-3 product-col">
                <div class="product-item">
                  <div class="product-item__category">
                    <a href="/">Máy tính bảng</a>
                  </div>
                  <h5 class="product-item__title">
                    <a href="/">Tablet White EliteBook Revolve 810 G2</a>
                  </h5>
                  <div class="product-item__image">
                    <a href="/">
                      <img src="/img/sp6.jpg" alt="Product" class="img-fluid">
                    </a>
                  </div>
                  <div class="product-item__footer">
                    <div class="product-item__price">
                      <del class="price-old">2.299.000đ</del>
                      <ins class="price-sale">1.999.000đ</ins>
                    </div>
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
              <!-- Product 3 -->
              <div class="grid__col-3 product-col">
                <div class="product-item">
                  <div class="product-item__category">
                    <a href="/">Tai nghe</a>
                  </div>
                  <h5 class="product-item__title">
                    <a href="/">Purple Solo 2 Wireless</a>
                  </h5>
                  <div class="product-item__image">
                    <a href="/">
                      <img src="/img/sp7.jpg" alt="Product" class="img-fluid">
                    </a>
                  </div>
                  <div class="product-item__footer">
                    <div class="product-item__price">
                      <span class="price">685.000đ</span>
                    </div>
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
              <!-- Product 4 -->
              <div class="grid__col-3 product-col">
                <div class="product-item">
                  <div class="product-item__category">
                    <a href="/">Điện thoại</a>
                  </div>
                  <h5 class="product-item__title">
                    <a href="/">Smartphone 6S 32GB LTE</a>
                  </h5>
                  <div class="product-item__image">
                    <a href="/">
                      <img src="/img/sp4.jpg" alt="Product" class="img-fluid">
                    </a>
                  </div>
                  <div class="product-item__footer">
                    <div class="product-item__price">
                      <span class="price">685.000đ</span>
                    </div>
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
          </div>
          <!-- End Tab Đánh giá cao -->
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
          <a href="/" class="nav-link product__carousel-item-top">Top 10</a>
        </li>
        <li class="nav-item">
          <a href="/" class="nav-link">Tai nghe</a>
        </li>
        <li class="nav-item">
          <a href="/" class="nav-link">Phụ kiện</a>
        </li>
        <li class="nav-item">
          <a href="/" class="nav-link">Camera</a>
        </li>
      </ul>
   </div>
   
   <div class="container">
        <div class="product-carousel-wrapper">
            <!-- Tab Navigation Buttons -->
            <button class="scroll-btn scroll-btn-left" onclick="changeTab(-1)">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="scroll-btn scroll-btn-right" onclick="changeTab(1)">
                <i class="fas fa-chevron-right"></i>
            </button>
            
            <!-- Tab 1 - Products 1-8 -->
            <div class="grid-row product-tab active" id="tab-page-1">
                <div class="grid__col-3 product-col">
                    <div class="product-item">
                        <div class="product-item__image">
                            <a href="#">
                                <img src="{{ asset('img/sp1.jpg') }}" alt="Tablet Air 3 WiFi 64GB Gold" class="img-fluid" loading="lazy">
                            </a>
                        </div>
                        <div class="product-item__content">
                            <div class="product-item__category">
                                <a href="#">TABLETS</a>
                            </div>
                            <h5 class="product-item__title">
                                <a href="#">Tablet Air 3 WiFi 64GB Gold</a>
                            </h5>
                            <div class="product-item__footer">
                                <div class="product-item__price">$629,00</div>
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
                
                <div class="grid__col-3 product-col">
                    <div class="product-item">
                        <div class="product-item__image">
                            <a href="#">
                                <img src="{{ asset('img/sp2.jpg') }}" alt="Tablet White EliteBook Revolve 810 G2" class="img-fluid" loading="lazy">
                            </a>
                        </div>
                        <div class="product-item__content">
                            <div class="product-item__category">
                                <a href="#">LAPTOPS &amp; COMPUTERS</a>
                            </div>
                            <h5 class="product-item__title">
                                <a href="#">Tablet White EliteBook Revolve...</a>
                            </h5>
                            <div class="product-item__footer">
                                <div class="product-item__price">$1 299,00</div>
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
                
                <div class="grid__col-3 product-col">
                    <div class="product-item">
                        <div class="product-item__image">
                            <img src="{{ asset('img/sp3.jpg') }}" alt="Pendrive USB 3.0 Flash 64 GB" loading="lazy">
                        </div>
                        <div class="product-item__content">
                            <div class="product-item__category">
                                <a href="#">ACCESSORIES</a>
                            </div>
                            <h5 class="product-item__title">
                                <a href="#">Pendrive USB 3.0 Flash 64 GB</a>
                            </h5>
                            <div class="product-item__footer">
                                <div class="product-item__price">$110,00</div>
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
                
                <div class="grid__col-3 product-col">
                    <div class="product-item">
                        <div class="product-item__image">
                            <img src="{{ asset('img/sp4.jpg') }}" alt="White Solo 2 Wireless" loading="lazy">
                        </div>
                        <div class="product-item__content">
                            <div class="product-item__category">
                                <a href="#">HEADPHONES</a>
                            </div>
                            <h5 class="product-item__title">
                                <a href="#">White Solo 2 Wireless</a>
                            </h5>
                            <div class="product-item__footer">
                                <div class="product-item__price">$110,00</div>
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
                
                <div class="grid__col-3 product-col">
                    <div class="product-item">
                        <div class="product-item__image">
                            <img src="{{ asset('img/sp5.jpg') }}" alt="Smartwatch 2.0 LTE Wifi" loading="lazy">
                        </div>
                        <div class="product-item__content">
                            <div class="product-item__category">
                                <a href="#">SMARTWATCHES</a>
                            </div>
                            <h5 class="product-item__title">
                                <a href="#">Smartwatch 2.0 LTE Wifi</a>
                            </h5>
                            <div class="product-item__footer">
                                <div class="product-item__price">$110,00</div>
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
                
                <div class="grid__col-3 product-col">
                    <div class="product-item">
                        <div class="product-item__image">
                            <img src="{{ asset('img/sp6.jpg') }}" alt="Gear Virtual Reality" loading="lazy">
                        </div>
                        <div class="product-item__content">
                            <div class="product-item__category">
                                <a href="#">SMARTWATCHES</a>
                            </div>
                            <h5 class="product-item__title">
                                <a href="#">Gear Virtual Reality</a>
                            </h5>
                            <div class="product-item__footer">
                                <div class="product-item__price">$799,00</div>
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
                
                <div class="grid__col-3 product-col">
                    <div class="product-item">
                        <div class="product-item__image">
                            <img src="{{ asset('img/sp7.jpg') }}" alt="External SSD USB 3.1 750 GB" loading="lazy">
                        </div>
                        <div class="product-item__content">
                            <div class="product-item__category">
                                <a href="#">GADGETS</a>
                            </div>
                            <h5 class="product-item__title">
                                <a href="#">External SSD USB 3.1 750 GB</a>
                            </h5>
                            <div class="product-item__footer">
                                <div class="product-item__price">$799,00</div>
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
                
                <div class="grid__col-3 product-col">
                    <div class="product-item">
                        <div class="product-item__image">
                            <img src="{{ asset('img/bestseler1.jpg') }}" alt="Purple NX Mini F1 SMART NX" loading="lazy">
                        </div>
                        <div class="product-item__content">
                            <div class="product-item__category">
                                <a href="#">CAMERAS</a>
                            </div>
                            <h5 class="product-item__title">
                                <a href="#">Purple NX Mini F1 aparat SMART NX</a>
                            </h5>
                            <div class="product-item__footer">
                                <div class="product-item__price">$559.00</div>
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
            </div>
            <!-- End Tab 1 -->
                
            <!-- Tab 2 - Products 9-16 -->
            <div class="grid-row product-tab" id="tab-page-2">
                <div class="grid__col-3 product-col">
                    <div class="product-item">
                        <div class="product-item__image">
                            <img src="{{ asset('img/rcm1.png') }}" alt="Recommended Product 1" loading="lazy">
                        </div>
                        <div class="product-item__content">
                            <div class="product-item__category">
                                <a href="#">RECOMMENDED</a>
                            </div>
                            <h5 class="product-item__title">
                                <a href="#">Recommended Product 1</a>
                            </h5>
                            <div class="product-item__footer">
                                <div class="product-item__price">$629,00</div>
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
                
                <div class="grid__col-3 product-col">
                    <div class="product-item">
                        <div class="product-item__image">
                            <img src="{{ asset('img/rcm2.jpg') }}" alt="Recommended Product 2" loading="lazy">
                        </div>
                        <div class="product-item__content">
                            <div class="product-item__category">
                                <a href="#">RECOMMENDED</a>
                            </div>
                            <h5 class="product-item__title">
                                <a href="#">Recommended Product 2</a>
                            </h5>
                            <div class="product-item__footer">
                                <div class="product-item__price">$1 299,00</div>
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
                
                <div class="grid__col-3 product-col">
                    <div class="product-item">
                        <div class="product-item__image">
                            <img src="{{ asset('img/rcm3.jpg') }}" alt="Recommended Product 3" loading="lazy">
                        </div>
                        <div class="product-item__content">
                            <div class="product-item__category">
                                <a href="#">RECOMMENDED</a>
                            </div>
                            <h5 class="product-item__title">
                                <a href="#">Recommended Product 3</a>
                            </h5>
                            <div class="product-item__footer">
                                <div class="product-item__price">$110,00</div>
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
                
                <div class="grid__col-3 product-col">
                    <div class="product-item">
                        <div class="product-item__image">
                            <img src="{{ asset('img/rcm4.png') }}" alt="Recommended Product 4" loading="lazy">
                        </div>
                        <div class="product-item__content">
                            <div class="product-item__category">
                                <a href="#">RECOMMENDED</a>
                            </div>
                            <h5 class="product-item__title">
                                <a href="#">Recommended Product 4</a>
                            </h5>
                            <div class="product-item__footer">
                                <div class="product-item__price">$110,00</div>
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
                
                <div class="grid__col-3 product-col">
                    <div class="product-item">
                        <div class="product-item__image">
                            <img src="{{ asset('img/sp1.jpg') }}" alt="Tablet Pro 256GB" loading="lazy">
                        </div>
                        <div class="product-item__content">
                            <div class="product-item__category">
                                <a href="#">TABLETS</a>
                            </div>
                            <h5 class="product-item__title">
                                <a href="#">Tablet Pro 256GB</a>
                            </h5>
                            <div class="product-item__footer">
                                <div class="product-item__price">$110,00</div>
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
                
                <div class="grid__col-3 product-col">
                    <div class="product-item">
                        <div class="product-item__image">
                            <img src="{{ asset('img/sp2.jpg') }}" alt="Gaming Laptop Pro" loading="lazy">
                        </div>
                        <div class="product-item__content">
                            <div class="product-item__category">
                                <a href="#">LAPTOPS</a>
                            </div>
                            <h5 class="product-item__title">
                                <a href="#">Gaming Laptop Pro</a>
                            </h5>
                            <div class="product-item__footer">
                                <div class="product-item__price">$799,00</div>
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
                
                <div class="grid__col-3 product-col">
                    <div class="product-item">
                        <div class="product-item__image">
                            <img src="{{ asset('img/sp3.jpg') }}" alt="Wireless Keyboard & Mouse" loading="lazy">
                        </div>
                        <div class="product-item__content">
                            <div class="product-item__category">
                                <a href="#">ACCESSORIES</a>
                            </div>
                            <h5 class="product-item__title">
                                <a href="#">Wireless Keyboard & Mouse</a>
                            </h5>
                            <div class="product-item__footer">
                                <div class="product-item__price">$799,00</div>
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
                
                <div class="grid__col-3 product-col">
                    <div class="product-item">
                        <div class="product-item__image">
                            <img src="{{ asset('img/sp4.jpg') }}" alt="Premium Headphones" loading="lazy">
                        </div>
                        <div class="product-item__content">
                            <div class="product-item__category">
                                <a href="#">HEADPHONES</a>
                            </div>
                            <h5 class="product-item__title">
                                <a href="#">Premium Headphones</a>
                            </h5>
                            <div class="product-item__footer">
                                <div class="product-item__price">$559.00</div>
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
            </div>
            <!-- End Tab 2 -->
            
            <!-- Tab Indicators -->
            <div class="carousel-progress">
                <div class="tab-indicators">
                    <span class="tab-dot active" onclick="goToTab(1)"></span>
                    <span class="tab-dot" onclick="goToTab(2)"></span>
                </div>
            </div>
        </div>
   </div>
   
   <!-- JavaScript for Tab Navigation -->
   <script>
   let currentTab = 1;
   const totalTabs = 2;
   
   // Change Tab Function
   function changeTab(direction) {
       const oldTab = currentTab;
       currentTab += direction;
       
       // Loop around
       if (currentTab > totalTabs) currentTab = 1;
       if (currentTab < 1) currentTab = totalTabs;
       
       showTab(currentTab, direction);
   }
   
   // Go to specific tab
   function goToTab(tabNumber) {
       const direction = tabNumber > currentTab ? 1 : -1;
       currentTab = tabNumber;
       showTab(currentTab, direction);
   }
   
   // Show Tab with animation
   function showTab(tabNumber, direction = 1) {
       const tabs = document.querySelectorAll('.product-tab');
       
       // Add slide-out animation to current tab
       tabs.forEach((tab, index) => {
           if (tab.classList.contains('active')) {
               // Slide out in opposite direction
               if (direction > 0) {
                   tab.classList.add('slide-out-left');
               } else {
                   tab.classList.add('slide-out-right');
               }
               
               // Remove active after animation starts
               setTimeout(() => {
                   tab.classList.remove('active', 'slide-out-left', 'slide-out-right');
               }, 100);
           }
       });
       
       // Show selected tab with slide-in animation
       setTimeout(() => {
           const selectedTab = document.getElementById(`tab-page-${tabNumber}`);
           if (selectedTab) {
               selectedTab.classList.add('active');
           }
       }, 100);
       
       // Update indicators
       const dots = document.querySelectorAll('.tab-dot');
       dots.forEach((dot, index) => {
           if (index + 1 === tabNumber) {
               dot.classList.add('active');
           } else {
               dot.classList.remove('active');
           }
       });
   }
   </script>
</section>
@endsection