<?php

namespace App\Http\Controllers\Admin;

use App\Http\Services\Product\ProductService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\CreateFormRequestProduct;
use App\Http\Requests\Product\UpdateFormRequestProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ProductController extends Controller
{

    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function addProduct()
    {

        return view('admin.product.add', [
            'title' => 'Thêm sản phẩm',
            'menus' => $this->productService->getMenuloc(),
        ]);
    }

    public function createproduct(CreateFormRequestProduct $request)
    {
        $result = $this->productService->ValidatePrice($request);

        if ($result) {
            $image =  $request->input('file');

            if ($image) {
                $product = DB::table('products')
                    ->updateOrInsert([
                        'name' => (string) $request->input('name'),
                        'menu_id' => $request->input('menu_id'),
                        'description' => (string) $request->input('description'),
                        'content' => (string) $request->input('content'),
                        'qty' => (int)$request->input('qty'),
                        'price' => (int)$request->input('price'),
                        'price_sale' => (int)$request->input('price_sale'),
                        'image' => (string)$image,
                        'active' => (int)$request->input('active')
                    ]);
            }
            session()->flash('success', 'Thêm sản phẩm thành công.');
            return redirect()->back();
        }
        session()->flash('error', 'Thêm sản phẩm thất bại!');
        return redirect()->back();
    }

    public function listProduct()
    {
        $menus =  DB::table('menus')->get();
        $products = $this->productService->getProduct();
        $product_all = DB::table('products')->get();
        $Reject = 0;
        $Pending = 0;
        $Approve = 0;
        foreach ($product_all  as $product) {
            if ($product->active == 0) {
                $Reject++;
            } elseif ($product->active == 1) {
                $Pending++;
            } else {
                $Approve++;
            }
        }
        return view('admin.product.list', [
            'title' => ' Danh sách sản phẩm ',
            'products' => $products,
            'Reject' => $Reject,
            'Pending' => $Pending,
            'Approve' => $Approve,
            'menus' => $menus,
        ]);
    }

    public function editProduct($id)
    {
        $product_ =  DB::table('products')->where('id', $id)->get();
        $product = $product_[0];
        return view('admin.product.editproduct', [
            'title' => 'Sửa thông tin sản phẩm' . $product->name,
            'product' => $product_,
            'menus' => $this->productService->getMenuloc(),
        ]);
    }

    public function updateProduct(Request $request, $id)
    {
        $image = $request->input('file');
        $product = DB::table('products')->where('id', $id)->get();
        if ($image) {

            DB::table('products')->where('id', $id)
                ->update([
                    'name' => $request->input('name'),
                    'menu_id' => $request->input('menu_id'),
                    'description' => $request->input('description'),
                    'content' => $request->input('content'),
                    'price' => $request->input('price'),
                    'price_sale' => $request->input('price_sale'),
                    'qty' => $request->input('qty'),
                    'active' => $request->input('active'),
                    'image' => $image,

                ]);
        } else {
            DB::table('products')->where('id', $id)
                ->update([
                    'name' => $request->input('name'),
                    'menu_id' => $request->input('menu_id'),
                    'description' => $request->input('description'),
                    'content' => $request->input('content'),
                    'price' => $request->input('price'),
                    'price_sale' => $request->input('price_sale'),
                    'qty' => $request->input('qty'),
                    'active' => $request->input('active'),
                    'image' => $product[0]->image,
                ]);
        }

        return redirect('admin/products/list');
    }

    public function deleteProductAjax(Request $request)
    {

        $product_ =  DB::table('products')->where('id', $request->id)->get();
        $product = $product_[0];
        $path = 'uploads/products/' . $product->image;
        if ($product) {
            $status = $product->active;
            if (file_exists($path)) {
                unlink($path);
                DB::table('products')->where('id', $request->id)->delete();
                return response()->json([
                    'code' => 200,
                    'message' => 'Xóa sản phẩm thành công.',
                    'id' => $request->id,
                    'status' => $status
                ]);
            } else {
                DB::table('products')->where('id', $request->id)->delete();
                return response()->json([
                    'code' => 200,
                    'message' => 'Xóa sản phẩm thành công.',
                    'id' => $request->id,
                    'status' => $status
                ]);
            }
        }
        return response()->json([
            'code' => 500,
            'message' => 'Xóa sản phẩm thất bại!',
        ]);
    }
    public function editProductImage($id)
    {
        $product_ = DB::table('products')->where('id', $id)->get();
        $product = $product_[0];
        // $imageProduct = $product->image;
        $imageDetails = DB::table('image_products')->where('id_product', $product->id)->get();

        return view('admin.product.editimage', [
            'title' => 'Sửa hình ảnh sản phẩm',
            'product' => $product,
            'imageDetails' => $imageDetails

        ]);
    }
    public function deleteImageProductAjax($id)
    {
        DB::table('products')->where('id', $id)->delete();
        return response()->json([
            'id' => $id
        ]);
    }
    public function changeImageProductAjax(Request $request, $id)
    {
        $image = $request->input('nameImageProduct');

        DB::table('products')->where('id', $id)->update(['image' => $image]);
        return response()->json([
            'image' => $image
        ]);
    }


    public function add_images_product(Request $request, $id)
    {
        for ($i = 0; $i < $request->input('demImages'); $i++) {
            DB::table('image_products')
                ->updateOrInsert([
                    'id_product' => $id,
                    'image_product' => $request->input('files' . $i),
                ]);
        }
        return redirect()->back();
    }

    public function detailProduct($id)
    {

        $product = $this->productService->detailProduct($id);
        $menu =  DB::table('menus')->where('id', $product->menu_id)->first();
        $images = DB::table('image_products')->where('id_product', $product->id)->get();
        return view('admin.product.detail', [
            'title' => 'Chi tiết sản phẩm.',
            'images' => $images,
            'product' => $product,
            'menu' => $menu,
        ]);
    }
    public function search_product_byName(Request $request)
    {
        $products = DB::table('products')->where('name', 'LIKE', '%' . $request->keyword_name . '%')->get();

        $html = '';
        foreach ($products as $product) {
            if ($product->active == 0) {
                $htmlc = 'Reject';
            } else if ($product->active == 1) {
                $htmlc = 'Pending';
            } else {
                $htmlc = 'Approve';
            }


            $menu_name = DB::table('menus')->select('name')->where('id', $product->menu_id)->first();

            $html .= '<tr id="account_' . $product->id . '">';
            $html .= '<td>' . $product->name . '</td>';
            $html .= '<td>' . $menu_name->name . '</td>';
            $html .= '<td>' . $product->content . '</td>';
            $html .= '<td>' . number_format($product->price, 0, ',', '.') . 'VNĐ</td>';
            $html .= '<td>' . number_format($product->price_sale, 0, ',', '.') . 'VNĐ</td>';
            $html .= '<td>' . $product->qty . '</td>';
            $html .= '<td><img src="' . $product->image . '" width="100px" >
            <a href="/admin/products/editProductImage/' . $product->id . '">Ảnh</a></td>';
            $html .= '<td>' . $htmlc . '<a onclick="updateActive(' . $product->id . ',' . $product->active . ');">';
            $html .= ' <i class="fas fa-retweet" style="color:blue; cursor: pointer;  align-items: center;" alt="' . $product->id . '"></i></a></td>';
            $html .= '<td> <a class="btn btn-primary btn-sm" href="/admin/products/editProduct/' . $product->id . '"><i class="fas fa-edit"></i></a>';
            $html .= '<a class="btn btn-danger btn-sm" style="color:blue; cursor: pointer;" onclick="deleteProduct(' . $product->id . ')">';
            $html .= '<i id="hoverdi" class="fas fa-trash"></i>  </a>';
            $html .= '<a class="btn btn-danger btn-sm" style="color:green; margin-top: 2px;" href="detailProduct/' . $product->id . '">Xem</a></td></tr>';
        }
        return  response()->json([
            'html' => $html,
        ]);
    }
    public function search_product_byPrice(Request $request)
    {
        $products = DB::table('products')->where('price', 'LIKE', '%' . $request->keyword_price . '%')->get();
        $html = '';
        foreach ($products as $product) {
            if ($product->active == 0) {
                $htmlc = 'Reject';
            } else if ($product->active == 1) {
                $htmlc = 'Pending';
            } else {
                $htmlc = 'Approve';
            }

            $menu_name = DB::table('menus')->select('name')->where('id', $product->menu_id)->first();
            $html .= '<tr id="account_' . $product->id . '">';
            $html .= '<td>' . $product->name . '</td>';
            $html .= '<td>' . $menu_name->name . '</td>';
            $html .= '<td>' . $product->content . '</td>';
            $html .= '<td>' . number_format($product->price, 0, ',', '.') . 'VNĐ</td>';
            $html .= '<td>' . number_format($product->price_sale, 0, ',', '.') . 'VNĐ</td>';
            $html .= '<td>' . $product->qty . '</td>';
            $html .= '<td><img src="' . $product->image . '" width="100px" >
            <a href="/admin/products/editProductImage/' . $product->id . '">Ảnh</a></td>';
            $html .= '<td>' . $htmlc . '<a onclick="updateActive(' . $product->id . ',' . $product->active . ');">';
            $html .= ' <i class="fas fa-retweet" style="color:blue; cursor: pointer;  align-items: center;" alt="' . $product->id . '"></i></a></td>';
            $html .= '<td> <a class="btn btn-primary btn-sm" href="/admin/products/editProduct/' . $product->id . '"><i class="fas fa-edit"></i></a>';
            $html .= '<a class="btn btn-danger btn-sm" style="color:blue; cursor: pointer;" onclick="deleteProduct(' . $product->id . ')">';
            $html .= '<i id="hoverdi" class="fas fa-trash"></i>  </a>';
            $html .= '<a class="btn btn-danger btn-sm" style="color:green; margin-top: 2px;" href="detailProduct/' . $product->id . '">Xem</a></td></tr>';
        }
        return  response()->json([
            'html' => $html,
        ]);
    }
    public function search_product_byNameAndPrice(Request $request)
    {
        $products = DB::table('products')->where('name', 'LIKE', '%' . $request->keyword_name . '%')
            ->where('price', 'LIKE', '%' . $request->keyword_price . '%')
            ->get();

        $html = '';
        foreach ($products as $product) {
            if ($product->active == 0) {
                $htmlc = 'Reject';
            } else if ($product->active == 1) {
                $htmlc = 'Pending';
            } else {
                $htmlc = 'Approve';
            }

            $menu_name = DB::table('menus')->select('name')->where('id', $product->menu_id)->first();
            $html .= '<tr id="account_' . $product->id . '">';
            $html .= '<td>' . $product->name . '</td>';
            $html .= '<td>' . $menu_name->name . '</td>';
            $html .= '<td>' . $product->content . '</td>';
            $html .= '<td>' . number_format($product->price, 0, ',', '.') . 'VNĐ</td>';
            $html .= '<td>' . number_format($product->price_sale, 0, ',', '.') . 'VNĐ</td>';
            $html .= '<td>' . $product->qty . '</td>';
            $html .= '<td><img src="' . $product->image . '" width="100px" >
            <a href="/admin/products/editProductImage/' . $product->id . '">Ảnh</a></td>';
            $html .= '<td>' . $htmlc . '<a onclick="updateActive(' . $product->id . ',' . $product->active . ');">';
            $html .= ' <i class="fas fa-retweet" style="color:blue; cursor: pointer;  align-items: center;" alt="' . $product->id . '"></i></a></td>';
            $html .= '<td> <a class="btn btn-primary btn-sm" href="/admin/products/editProduct/' . $product->id . '"><i class="fas fa-edit"></i></a>';
            $html .= '<a class="btn btn-danger btn-sm" style="color:blue; cursor: pointer;" onclick="deleteProduct(' . $product->id . ')">';
            $html .= '<i id="hoverdi" class="fas fa-trash"></i>  </a>';
            $html .= '<a class="btn btn-danger btn-sm" style="color:green; margin-top: 2px;" href="detailProduct/' . $product->id . '">Xem</a></td></tr>';
        }
        return  response()->json([
            'html' => $html,
        ]);
    }

    public function refresh_listProduct()
    {
        $products = DB::table('products')->orderBy('id', 'desc')->paginate(5);
        $html = '';
        foreach ($products as $product) {
            if ($product->active == 0) {
                $htmlc = 'Reject';
            } else if ($product->active == 1) {
                $htmlc = 'Pending';
            } else {
                $htmlc = 'Approve';
            }
            $menu_name = DB::table('menus')->select('name')->where('id', $product->menu_id)->first();
            $html .= '<tr id="account_' . $product->id . '">';
            $html .= '<td>' . $product->name . '</td>';
            $html .= '<td>' . $menu_name->name . '</td>';
            $html .= '<td>' . $product->content . '</td>';
            $html .= '<td>' . number_format($product->price, 0, ',', '.') . ' VNĐ</td>';
            $html .= '<td>' . number_format($product->price_sale, 0, ',', '.') . ' VNĐ</td>';
            $html .= '<td>' . $product->qty . '</td>';
            $html .= '<td><img src="' . $product->image . '" width="100px" >
            <a href="/admin/products/editProductImage/' . $product->id . '">Ảnh</a></td>';
            $html .= '<td>' . $htmlc . '</td>';
            $html .= '<td> <a class="btn btn-primary btn-sm" href="/admin/products/editProduct/' . $product->id . '"><i class="fas fa-edit"></i></a>';
            $html .= '<a class="btn btn-danger btn-sm" style="color:blue; cursor: pointer;" onclick="deleteProduct(' . $product->id . ')">';
            $html .= '<i id="hoverdi" class="fas fa-trash"></i>  </a>';
            $html .= '<a class="btn btn-danger btn-sm" style="color:green; margin-top: 2px;" href="detailProduct/' . $product->id . '">Xem</a></td></tr>';
        }
        return  response()->json([
            'html' => $html,
        ]);
    }
    public function filter(Request $request)
    {


        $products = DB::table('products')->where('active', $request->keyword)->paginate(5);
        $html = '';
        foreach ($products as $product) {
            if ($product->active == 0) {
                $htmlc = 'Reject';
            } else if ($product->active == 1) {
                $htmlc = 'Pending';
            } else {
                $htmlc = 'Approve';
            }
            $menu_name = DB::table('menus')->select('name')->where('id', $product->menu_id)->first();
            $html .= '<tr id="listProducts_' . $product->id . '">';
            $html .= '<td>' . $product->name . '</td>';
            $html .= '<td>' . $menu_name->name . '</td>';
            $html .= '<td>' . $product->content . '</td>';
            $html .= '<td>' . number_format($product->price, 0, ',', '.') . ' VNĐ</td>';
            $html .= '<td>' . number_format($product->price_sale, 0, ',', '.') . ' VNĐ</td>';
            $html .= '<td>' . $product->qty . '</td>';
            $html .= '<td><img src="' . $product->image . '" width="100px" >
            <a href="/admin/products/editProductImage/' . $product->id . '">Ảnh</a></td>';
            $html .= '<td>' . $htmlc . '</td>';
            $html .= '<td> <a class="btn btn-primary btn-sm" href="/admin/products/editProduct/' . $product->id . '"><i class="fas fa-edit"></i></a>';
            $html .= '<a class="btn btn-danger btn-sm" style="color:blue; cursor: pointer;" onclick="deleteProduct(' . $product->id . ')">';
            $html .= '<i id="hoverdi" class="fas fa-trash"></i>  </a>';
            $html .= '<a class="btn btn-danger btn-sm" style="color:green; margin-top: 2px;" href="detailProduct/' . $product->id . '">Xem</a></td></tr>';
        }
        return  response()->json([
            'html' => $html,
        ]);
    }
}
