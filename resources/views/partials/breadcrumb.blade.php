{{-- resources/views/partials/breadcrumb.blade.php --}}
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0 bg-transparent small">
        @if(!isset($hideHome) || !$hideHome)
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
        @endif
        @foreach($items ?? [] as $item)
            <li class="breadcrumb-item">
                <a href="{{ $item['url'] }}">{{ $item['name'] }}</a>
            </li>
        @endforeach
        <li class="breadcrumb-item active" aria-current="page">{{ $current }}</li>
    </ol>
</nav>