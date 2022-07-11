@extends('admin.main')
@section('content')
<h1 style="text-align: center;">Chào Admin</h1>
<?php


use Illuminate\Support\Facades\Session;

$data = Session::get('shop');
?>

<section style="margin: 20PX;">
    <div>
        <a href="api/admin/createWebHook_createCustomer/{{$data->id}}">nhấn vào để tạo webhook create customer </a>
    </div>
    <div>
        <a href="api/admin/createWebHook_createProduct/{{$data->id}}">nhấn vào để tạo webhook create product </a>
    </div>
    <div>
        <a href="api/admin/createWebHook_updateProduct/{{$data->id}}">nhấn vào để tạo webhook update product </a>
    </div>
    <div>
        <a href="api/admin/createWebHook_deleteProduct/{{$data->id}}">nhấn vào để tạo webhook delete product </a>
    </div>
</section>
@endsection