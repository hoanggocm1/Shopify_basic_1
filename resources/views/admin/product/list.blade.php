@extends('admin.main')
@section('content')
<?php


use Illuminate\Support\Facades\Session;

$data = Session::get('shop');
?>
<style>
  #hoverdi:hover {
    color: red;
  }
</style>

<nav class="navbar navbar-light bg-light">
  <div class="form-inline">
    <input class="mtext-107 cl2 size-114 plh2 p-r-15 m-r-2" style="margin-right: 10px;" type="text" name="keyword" id="search_product_byName" placeholder="Tìm theo tên sản phẩm">
    <input class="mtext-107 cl2 size-114 plh2 p-r-15 m-r-2" style="margin-right: 20px;" type="number" name="keyword" id="search_product_byPrice" placeholder="Tìm theo giá sản phẩm">
    <span style="margin-right: 20px;"> Hoặc</span>

    <a onclick="filter(2)" class="btn btn-success" id="statusApprove" style="margin-right: 10px;"><samp>Approve</samp>1</a>
    <a onclick="filter(1)" class="btn btn-warning" id="statusPending" style="margin-right: 10px;"><samp>Pending</samp>2</a>
    <a onclick="filter(0)" class="btn btn-danger" id="statusReject" style="margin-right: 30px;"><samp>Reject</samp>3</a>
    <a onclick="refresh()" class="btn btn-secondary">Đặt lại</a>
  </div>
</nav>
<div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table m-0">
        <thead>
          <tr>
            <th>Ô chọn</th>
            <th>Hình ảnh</th>
            <th>Sản phẩm</th>
            <th>Trạng thái</th>
            <th>Kho hàng</th>
            <th>Loại</th>
            <th>Nhà cung cấp</th>
            <th style="width: 75px;"> </th>
          </tr>
        </thead>
        <tbody id="bodyListProduct">
          <?php if (!empty($products)) { ?>
            @foreach($products as $value)
            <tr>
              <td>Chọn</td>
              <td>
                <img src="<?php if (isset($value->productImageFirst[0])) {
                            echo $value->productImageFirst[0]->src;
                          } else {
                            echo '';
                          } ?>" width="100px">
              </td>
              <td>
                {{$value->title}}
              </td>
              <td>
                {{$value->status}}
              </td>
              <td>
                <?php $dem = 0; ?>
                @foreach($value->productVariant as $value_)
                <?php $dem += $value_->inventory_quantity; ?>
                @endforeach
                <?php echo $dem; ?>
              </td>
              <td>
                {{$value->product_type}}
              </td>
              <td>
                {{$value->vendor}}
              </td>
              <td><a href="/api/deleteProductByApp/{{$value->id}}/{{$data->id}}">delete</a>---<a href="/admin/products/editProductByApp/{{$value->id}}">edit</a></td>
            </tr>
            @endforeach
          <?php } ?>
        </tbody>
      </table>
      <div class="card-tools">
        <ul class="pagination pagination-sm" id="paginate_list_product">

        </ul>
      </div>
      <style>

      </style>
    </div>


    @endsection