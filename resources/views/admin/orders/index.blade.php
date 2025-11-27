@extends('layouts.admin')

@section('title','Đơn hàng')
@section('page-title','Quản lý đơn hàng')

@section('content')
<div class="container-fluid">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Danh sách đơn hàng</h5>
  </div>

  {{-- Search filters --}}
  <form method="GET" class="row g-2 mb-3">
    <div class="col-md-3">
      <input type="text" name="q" class="form-control" placeholder="Tìm theo mã / tên"
             value="{{ request('q') }}">
    </div>

    <div class="col-md-3">
      <select name="status" class="form-select">
        <option value="">Tất cả trạng thái</option>
        <option value="pending" @selected(request('status')=='pending')>Chờ xử lý</option>
        <option value="processing" @selected(request('status')=='processing')>Đang xử lý</option>
        <option value="completed" @selected(request('status')=='completed')>Hoàn tất</option>
        <option value="cancelled" @selected(request('status')=='cancelled')>Đã hủy</option>
      </select>
    </div>

    <div class="col-md-2 d-grid">
      <button class="btn btn-outline-secondary">Lọc</button>
    </div>
  </form>

  {{-- Table --}}
  <div class="card shadow-sm">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th width="80">Mã</th>
            <th>Khách hàng</th>
            <th width="120">Tổng tiền</th>
            <th width="140">Ngày tạo</th>
            <th width="120">Trạng thái</th>
            <th width="120">Hành động</th>
          </tr>
        </thead>
        <tbody>
        @forelse($orders ?? [] as $item)
          <tr>
            <td class="fw-bold">#{{ $item->id }}</td>
            <td>{{ $item->customer_name ?? 'Không có tên' }}</td>
            <td class="fw-bold text-danger">{{ number_format($item->total) }} ₫</td>
            <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>

            <td>
              @include('admin.orders.partials.status-badge', ['status' => $item->status])
            </td>

            <td>
              <a href="{{ route('admin.orders.show', $item->id) }}" 
                 class="btn btn-sm btn-outline-primary">
                 <i class="fa fa-eye"></i>
              </a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="text-center py-4 text-muted">Không có đơn hàng</td>
          </tr>
        @endforelse
        </tbody>
      </table>
    </div>

    <div class="card-footer text-end small text-muted">
      Hiển thị {{ count($orders ?? []) }} đơn hàng
    </div>
  </div>

</div>
@endsection
