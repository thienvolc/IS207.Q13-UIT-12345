{{-- resources/views/components/pagination.blade.php --}}
@if ($paginator->hasPages())
<nav aria-label="Phân trang sản phẩm" class="mt-5 mb-5">
    <ul class="pagination justify-content-center">
        {{-- Previous --}}
        @if ($paginator->onFirstPage())
        <li class="page-item disabled"><span class="page-link">Trước</span></li>
        @else
        <li class="page-item"><a class="page-link" href="{{ $paginator->previousPageUrl() }}">Trước</a></li>
        @endif

        {{-- Page Numbers --}}
        @foreach ($elements as $element)
        @if (is_string($element))
        <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
        @endif
        @if (is_array($element))
        @foreach ($element as $page => $url)
        @if ($page == $paginator->currentPage())
        <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
        @else
        <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
        @endif
        @endforeach
        @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
        <li class="page-item"><a class="page-link" href="{{ $paginator->nextPageUrl() }}">Tiếp</a></li>
        @else
        <li class="page-item disabled"><span class="page-link">Tiếp</span></li>
        @endif
    </ul>
</nav>
@endif