@extends('layouts.app')

@section('title', 'PinkCapy - Khuyến mãi')

@section('content')
<div class="grid">
    <!-- Breadcrumb -->
    <div class="mb-4">
        @include('partials.breadcrumb', [
        'items' => [],
        'current' => 'Khuyến mãi'
        ])
    </div>

    <!-- Header -->
    <div class="text-center mb-5">
        <h1 class="title-lg fw-bold">
            <i class="fa-solid fa-gift text-danger"></i>
            Khuyến mãi đặc biệt
        </h1>
        <p class="text-muted">Săn ngay các deal hot, giảm giá cực sốc!</p>
    </div>

    <!-- Empty State -->
    <div class="empty-state-container">
        <div class="empty-state-card text-center py-5">
            <h3 class="empty-state-title">Chưa có chương trình khuyến mãi...</h3>
        </div>
    </div>
</div>
@endsection