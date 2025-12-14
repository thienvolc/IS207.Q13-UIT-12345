@extends('layouts.admin')

@section('title','Sản phẩm - Admin')
@section('page-title','Sản phẩm')

@section('content')
<div class="container-fluid">

  {{-- Header row --}}
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Danh sách sản phẩm</h5>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
      <i class="fa fa-plus me-1"></i> Thêm sản phẩm
    </a>
  </div>

  {{-- Search + filters --}}
  <form method="GET" class="row g-2 mb-3">
    <div class="col-md-4">
      <input type="text" name="q" class="form-control" placeholder="Tìm theo tên, mô tả..."
             value="{{ request('q') }}">
    </div>

    <div class="col-md-3">
      <select class="form-select" name="status">
        <option value="">Tất cả trạng thái</option>
        <option value="1" @selected(request('status') == '1')>Hiển thị</option>
        <option value="0" @selected(request('status') == '0')>Ẩn</option>
      </select>
    </div>

    <div class="col-md-2 d-grid">
      <button class="btn btn-outline-secondary">Lọc</button>
    </div>
  </form>

  {{-- Table --}}
  <div class="card">
    <div class="card-body table-responsive p-0">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th width="50">#</th>
            <th width="80">Ảnh</th>
            <th>Tên sản phẩm</th>
            <th width="120">Giá</th>
            <th width="90">Trạng thái</th>
            <th width="150">Hành động</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($products ?? [] as $p)
            <tr>
              <td>{{ $loop->iteration }}</td>

              <td>
                <img src="{{ $p->image_url ?? '/images/no-image.png' }}" 
                     class="rounded" style="width:60px;height:45px;object-fit:cover;">
              </td>

              <td class="fw-medium">{{ $p->name }}</td>

              <td>{{ number_format($p->price) }} ₫</td>

              <td>
                @if($p->status)
                  <span class="badge bg-success">Hiện</span>
                @else
                  <span class="badge bg-secondary">Ẩn</span>
                @endif
              </td>

              <td>
                <a href="{{ route('admin.products.edit',$p->id) }}" class="btn btn-sm btn-outline-primary">
                  <i class="fa fa-edit"></i>
                </a>

                <form action="{{ route('admin.products.destroy',$p->id) }}" method="POST"
                      class="d-inline"
                      onsubmit="return confirmDelete('Bạn chắc chắn muốn xóa sản phẩm này?')">
                  @csrf  
                  @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger">
                    <i class="fa fa-trash"></i>
                  </button>
                </form>
              </td>
            </tr>

          @empty
            <tr>
              <td colspan="6" class="text-center py-4 text-muted">
                Không có sản phẩm nào
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Pagination placeholder --}}
    <div class="card-footer text-end">
      {{-- $products->links() nếu backend có pagination --}}
      <span class="text-muted small">Hiển thị {{ count($products ?? []) }} mục</span>
    </div>
  </div>

</div>
@endsection
