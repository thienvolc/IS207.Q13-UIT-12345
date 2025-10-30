@extends('layouts.app')
@section('title','PinkCapy - Home')

@section('content')
<section class="grid hero hero--anim">
  <div class="hero__inner">
    <div class="hero__content">
      <h1 class="hero__title"></h1>
      <p class="hero__subtitle"></p>

      <div class="hero__meta">
        <span class="hero__from">TỪ</span>
        <span class="hero__price">
          <span class="hero__price-currency"></span>
          <strong></strong>
          <sup></sup>
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
      <img src="" alt="Smartwatch" class="hero__img">
    </div>
  </div>
</section>
@endsection
