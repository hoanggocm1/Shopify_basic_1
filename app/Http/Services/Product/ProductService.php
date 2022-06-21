<?php

namespace App\Http\Services\Product;

use Illuminate\Support\Facades\DB;


class ProductService
{

    public function getMenuloc()
    {
        return DB::table('menus')->where('active', 1)->get();
    }




    public function getProduct()
    {

        return DB::table('products')->orderByDesc('id')->paginate(10);
    }


    public function ValidatePrice($request)
    {
        if (
            $request->input('price') != 0 &&  $request->input('price_sale') != 0 &&
            $request->input('price_sale') > $request->input('price')
        ) {
            session()->flash('error', 'Giá giảm không được nhỏ hơn giá gốc');
            return false;
        }

        if ($request->input('price_sale') != 0 && $request->input('price') == 0) {
            session()->flash('error', 'Vui lòng nhập lại giá gốc');
            return false;
        }
        if ($request->input('price_sale') != 0 && $request->input('price') == 0) {
            session()->flash('error', 'Vui lòng nhập lại giá gốc');
            return false;
        }


        return true;
    }

    public function detailProduct($id)
    {
        return  DB::table('products')->where('id', $id)->first();
    }
}
