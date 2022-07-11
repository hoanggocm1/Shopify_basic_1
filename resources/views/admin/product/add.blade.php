@extends('admin.main')
@section('content')


<?php

use Illuminate\Support\Facades\Session;

$data = Session::get('shop');

?>
<form action="" method="POST" enctype="multipart/form-data">
    <div class="card-body">

        <div class="form-group">
            <label for="menu">Tiêu đề</label>
            <input type="text" name="title" class="form-control" value="{{old('title')}}" required autofocus placeholder="Nhập tên sản phẩm">
        </div>

        <div class="form-group">
            <label>Mô tả </label>
            <textarea name="body_html" class="form-control" required placeholder="Điền mô tả">{{old('body_html')}}</textarea>
        </div>

        <div class="form-group">
            <label>Nhà cung cấp</label>
            <textarea name="vendor" class="form-control" required placeholder="Chi tiết">{{old('vendor')}}</textarea>
        </div>
        <div class="form-group">
            <label for="menu">Giá </label>
            <input type="number" name="compare_at_price" value="{{old('compare_at_price')}}" required class="form-control" placeholder="Nhập giá">
        </div>
        <div class="form-group">
            <label for="menu">Giá giảm </label>
            <input type="number" name="price" value="{{old('price')}}" required class="form-control" placeholder="Nhập giá giảm">
        </div>
        <div class="form-group">
            <label for="menu">Quantity</label>
            <input type="number" name="inventory_quantity" value="{{old('inventory_quantity')}}" required class="form-control" placeholder="Nhập số lượng">
        </div>
        <div class="form-group">
            <label for="menu">Hình ảnh đại diện</label>


            <input type="file" name="file1" value="{{old('file')}}" class="form-control" id="uploadImageProduct">
            <div id="image_change_name">

            </div>
            <div id="image_show">

            </div>
            <input type="hidden" name="file" id="file">

        </div>

        <div class="form-group m-4">
            <label>Trạng thái</label>
            <div class="form-group">
                <div class="custom-control custom-radio">
                    <input class="custom-control-input" value="active" type="radio" id="active" name="status" checked="">
                    <label for="active" class="custom-control-label">Active</label>
                </div>
                <div class="custom-control custom-radio">
                    <input class="custom-control-input" value="draft" type="radio" id="no_active" name="status">
                    <label for="no_active" class="custom-control-label">Draft</label>
                </div>
            </div>
        </div>
    </div>
    <!-- /.card-body -->

    <div class="card-footer">
        <button type="submit" class="btn btn-primary">Thêm sản phẩm</button>
    </div>
    @csrf
</form>

@endsection