@extends('layouts.admin')

@section('title','Chi tiết đơn hàng')
@section('page-title','Chi tiết đơn hàng #'.$order->id)

@section('content')
<div class="container-fluid">

  <div class="card shadow-sm mb-3">
    <div class="card-body">
      <h5 class="mb-3">Thông tin khách hàng</h5>

      <div class="row g-3">
        <div class="col-md-4">
          <label class="text-muted small">Tên khách hàng</label>
          <div class="fw-semibold">{{ $order->customer_name }}</div>
        </div>

        <div class="col-md-4">
          <label class="text-muted small">Email</label>
          <div>{{ $order->customer_email }}</div>
        </div>

        <div class="col-md-4">
          <label class="text-muted small">Số điện thoại</label>
          <div>{{ $order->customer_phone }}</div>
        </div>

        <div class="col-md-12">
          <label class="text-muted small">Địa chỉ</label>
          <div>{{ $order->customer_address }}</div>
        </div>
      </div>
    </div>
  </div>

  {{-- Products list --}}
  <div class="card shadow-sm mb-3">
    <div class="card-body">
      <h5 class="mb-3">Sản phẩm trong đơn</h5>

      <div class="table-responsive">
        <table class="table table-bordered align-middle">
          <thead class="table-light">
          <tr>
            <th>Sản phẩm</th>
            <th width="120">Số lượng</th>
            <th width="120">Giá</th>
            <th width="140">Thành tiền</th>
          </tr>
          </thead>

          <tbody>
          @foreach($order->items as $item)
            <tr>
              <td>{{ $item->product_name }}</td>
              <td>{{ $item->quantity }}</td>
              <td>{{ number_format($item->price) }} ₫</td>
              <td class="fw-bold text-danger">{{ number_format($item->price * $item->quantity) }} ₫</td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>

      <div class="text-end fw-bold fs-5 text-danger">
        Tổng tiền: {{ number_format($order->total) }} ₫
      </div>
    </div>
  </div>

  {{-- Update status --}}
  <div class="card shadow-sm">
    <div class="card-body">
      <h5 class="mb-3">Cập nhật trạng thái</h5>

      <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-3">
          <div class="col-md-4">
            <select class="form-select" name="status">
              <option value="pending" @selected($order->status=='pending')>Chờ xử lý</option>
              <option value="processing" @selected($order->status=='processing')>Đang xử lý</option>
              <option value="completed" @selected($order->status=='completed')>Hoàn tất</option>
              <option value="cancelled" @selected($order->status=='cancelled')>Đã hủy</option>
            </select>
          </div>

          <div class="col-md-3 d-grid">
            <button class="btn btn-danger">
              <i class="fa fa-save me-1"></i> Cập nhật
            </button>
          </div>

          <div class="col-md-3 d-grid">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-light">
              <i class="fa fa-arrow-left me-1"></i> Quay lại
            </a>
          </div>
        </div>

      </form>
    </div>
  </div>

</div>
@endsection
