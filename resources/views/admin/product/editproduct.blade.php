@extends('admin.main')
@section('content')

<form action="/api/editProductByApp/{{$product->id}}/{{$idShop}}" method="POST" enctype="multipart/form-data">
    <div class="card-body">


        <div class="form-group">
            <label for="menu">Tiêu đề</label>
            <input type="text" name="title" class="form-control" value="{{$product->title}}" placeholder="Nhập sản phẩm">
        </div>
        <div class="form-group">
            <label>Mô tả </label>
            <textarea name="body_html" class="form-control" placeholder="điền mô tả">{{$product->body_html}}</textarea>
        </div>

        <div class="form-group">
            <label>Nhà cung cấp</label>
            <textarea name="vendor" class="form-control" placeholder="Nhà cung cấp">{{$product->vendor}}</textarea>
        </div>
        <div class="form-group">
            <label for="menu">Giá </label>
            <input type="number" name="compare_at_price" value="{{$product->compare_at_price}}" required class="form-control" placeholder="Nhập giá">
        </div>
        <div class="form-group">
            <label for="menu">Giá giảm </label>
            <input type="number" name="price" value="{{$product->price}}" required class="form-control" placeholder="Nhập giá giảm">
        </div>
        <div class="form-group">
            <label for="menu">Tồn kho</label>
            <input type="number" name="inventory_quantity" value="{{$product->inventory_quantity}}" required class=" form-control" placeholder="Nhập số lượng">
        </div>

        <div class="form-group">
            <label for="menu">File input</label>


            <input type="file" name="file1" value="{{old('file1')}}" class="form-control" id="uploadImageProduct">
            <div id="image_change_name">
                <p style="float: left;">Đường đẫn của hình ảnh:&ensp;
                <p style="color:green; "></p>
                </p>
            </div>
            <div id="image_show">
                <a href="" target="_blank">
                    <img src="" value="" width="100">
                </a>
            </div>
            <input type="hidden" name="file" id="file" value="">

        </div>

    </div>

    <div class="form-group m-4">
        <label> Kích hoạt</label>
        <div class="form-group">
            <div class="custom-control custom-radio">
                <input class="custom-control-input" value="active" type="radio" id="active" name="status" {{$product->status == 'active' ? 'checked' : ''}}>
                <label for="active" class="custom-control-label">Active</label>
            </div>
            <div class="custom-control custom-radio">
                <input class="custom-control-input" value="draft" type="radio" id="no_active" name="status" {{$product->status == 'draft' ? 'checked' : ''}}>
                <label for="no_active" class="custom-control-label">Draft</label>
            </div>
        </div>

    </div>



    <div class="card-footer">
        <button type="submit" class="btn btn-primary">Lưu chỉnh sửa</button>
    </div>
    @csrf
</form>
@endsection