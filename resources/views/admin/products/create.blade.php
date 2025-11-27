@extends('layouts.admin')

@section('title','Thêm sản phẩm')
@section('page-title','Thêm sản phẩm')

@section('content')
<div class="container-fluid">
  <div class="card shadow-sm">
    <div class="card-body">

      <h5 class="mb-3">Tạo sản phẩm mới</h5>

      <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="row g-3">
        @csrf

        {{-- Tên sản phẩm --}}
        <div class="col-md-6">
          <label class="form-label fw-semibold">Tên sản phẩm</label>
          <input type="text" name="name" class="form-control" required>
        </div>

        {{-- Giá --}}
        <div class="col-md-3">
          <label class="form-label fw-semibold">Giá</label>
          <input type="number" name="price" class="form-control" required>
        </div>

        {{-- Trạng thái --}}
        <div class="col-md-3">
          <label class="form-label fw-semibold">Trạng thái</label>
          <select name="status" class="form-select">
            <option value="1">Hiển thị</option>
            <option value="0">Ẩn</option>
          </select>
        </div>

        {{-- Ảnh --}}
        <div class="col-md-6">
          <label class="form-label fw-semibold">Ảnh sản phẩm</label>
          <input type="file" name="image" class="form-control">
        </div>

        {{-- Mô tả --}}
        <div class="col-md-12">
          <label class="form-label fw-semibold">Mô tả</label>
          <textarea name="description" class="form-control" rows="4"></textarea>
        </div>

        <div class="col-12 d-flex justify-content-between">
          <a href="{{ route('admin.products.index') }}" class="btn btn-light">
            <i class="fa fa-arrow-left me-1"></i> Quay lại
          </a>

          <button class="btn btn-primary">
            <i class="fa fa-save me-1"></i> Lưu sản phẩm
          </button>
        </div>
      </form>

    </div>
  </div>
</div>
@endsection
