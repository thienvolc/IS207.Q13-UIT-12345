@extends('layouts.admin')

@section('title','Chỉnh sửa sản phẩm')
@section('page-title','Chỉnh sửa sản phẩm')

@section('content')
<div class="container-fluid">
  <div class="card shadow-sm">
    <div class="card-body">

      <h5 class="mb-3">Sửa sản phẩm: <span class="text-primary">{{ $product->name }}</span></h5>

      <form action="{{ route('admin.products.update',$product->id) }}" 
            method="POST" 
            enctype="multipart/form-data"
            class="row g-3">
        @csrf
        @method('PUT')

        {{-- Tên --}}
        <div class="col-md-6">
          <label class="form-label fw-semibold">Tên sản phẩm</label>
          <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
        </div>

        {{-- Giá --}}
        <div class="col-md-3">
          <label class="form-label fw-semibold">Giá</label>
          <input type="number" name="price" class="form-control" value="{{ $product->price }}" required>
        </div>

        {{-- Trạng thái --}}
        <div class="col-md-3">
          <label class="form-label fw-semibold">Trạng thái</label>
          <select name="status" class="form-select">
            <option value="1" @selected($product->status == 1)>Hiển thị</option>
            <option value="0" @selected($product->status == 0)>Ẩn</option>
          </select>
        </div>

        {{-- Ảnh --}}
        <div class="col-md-6">
          <label class="form-label fw-semibold">Ảnh sản phẩm</label>
          <input type="file" name="image" class="form-control">
          <div class="mt-2">
            <img src="{{ $product->image_url ?? '/images/no-image.png' }}" 
                 style="width:120px;height:90px;object-fit:cover;border-radius:8px">
          </div>
        </div>

        {{-- Mô tả --}}
        <div class="col-md-12">
          <label class="form-label fw-semibold">Mô tả</label>
          <textarea name="description" class="form-control" rows="4">{{ $product->description }}</textarea>
        </div>

        <div class="col-12 d-flex justify-content-between">
          <a href="{{ route('admin.products.index') }}" class="btn btn-light">
            <i class="fa fa-arrow-left me-1"></i> Quay lại
          </a>

          <button class="btn btn-primary">
            <i class="fa fa-save me-1"></i> Cập nhật
          </button>
        </div>

      </form>

    </div>
  </div>
</div>
@endsection
