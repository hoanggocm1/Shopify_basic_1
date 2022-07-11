<?php

namespace App\Http\Controllers\Admin;

use App\Http\Services\Product\ProductService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\CreateFormRequestProduct;
use App\Jobs\addProduct;
use App\Models\Image;
use App\Models\imageFirst;
use App\Models\infoShop;
use App\Models\Option;
use App\Models\Product;
use App\Models\ValueOption;
use App\Models\Variant;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

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
        ]);
    }

    public function createproduct(Request $request)
    {
        $infoShop = Session::get('shop');
        $infoShop_ = infoShop::where('id', $infoShop->id)->first();

        $client = new Client();
        $url = 'https://' . $infoShop->domain . '/admin/api/2022-07/products.json';
        $hostNgrok = env('ngrok');
        $resShop = $client->request('POST', $url, [
            'headers' => [
                'X-Shopify-Access-Token' => $infoShop_->access_token,
            ],
            'form_params' => [
                'product' => [
                    'title' => $request->input('title'),
                    'body_html' => $request->input('body_html'),
                    'vendor' => $request->input('vendor'),
                    'status' => $request->input('status'),
                    'product_type' => 'Snowboard',
                ]
            ]
        ]);
        return redirect()->back();
    }

    public function deleteProductByApp($idProduct, infoShop $idShop)
    {
        $client = new Client();
        $url = 'https://' . $idShop->domain . '/admin/api/2022-07/products/' . $idProduct . '.json';
        $resShop = $client->request('DELETE', $url, [
            'headers' => [
                'X-Shopify-Access-Token' => $idShop->access_token,
            ],
        ]);
        Product::where('id', $idProduct)->delete();
        return redirect()->back();
    }
    public function editProductByApp($id)
    {
        $infoShop = Session::get('shop');
        $product = Product::where('id', $id)->first();
        return view('admin.product.editproduct', [
            'title' => 'Sửa thông tin sản phẩm' . $product->title,
            'product' => $product,
            'idShop' => $infoShop->id,
        ]);
    }
    public function updateProductByApp(Request $request, $idProduct, infoShop $idShop)
    {
        $client = new Client();
        $url = 'https://' . $idShop->domain . '/admin/api/2022-07/products/' . $idProduct . '.json';
        $resShop = $client->request('PUT', $url, [
            'headers' => [
                'X-Shopify-Access-Token' => $idShop->access_token,
            ],
            'form_params' => [
                'product' => [
                    'title' => $request->input('title'),
                    'body_html' => $request->input('body_html'),
                    'vendor' => $request->input('vendor'),
                    'status' => $request->input('status'),
                    'product_type' => 'Snowboard',
                    'image' => '123',
                ]
            ]
        ]);

        return redirect('admin/products/list/' . $idShop->id);
    }

    public function listProduct(infoShop $infoShop)
    {

        // shopify
        $client = new Client();
        $url = 'https://' . $infoShop->domain . '/admin/api/2022-07/products.json';

        $hostNgrok = env('ngrok');
        $resShop = $client->request('GET', 'https://hoang-store1234.myshopify.com/admin/api/2022-07/products.json', [
            'headers' => [
                'X-Shopify-Access-Token' => $infoShop->access_token,
            ],

        ]);
        $data = json_decode($resShop->getBody()->getContents());

        foreach ($data as $value) {
            for ($i = 0; $i < count($value); $i++) {

                if (empty(Product::find($value[$i]->id))) {
                    Product::create([
                        'domain_shop' => Session::get('shop')->domain,
                        'id' => $value[$i]->id,
                        'title' => $value[$i]->title,
                        'body_html' => $value[$i]->body_html,
                        'vendor' => $value[$i]->vendor,
                        'product_type' => $value[$i]->product_type,
                        'handle' => $value[$i]->handle,
                        'published_at' => $value[$i]->published_at,
                        'template_suffix' => $value[$i]->template_suffix,
                        'status' => $value[$i]->status,
                        'published_scope' => $value[$i]->published_scope,
                        'tags' => $value[$i]->tags,
                    ]);
                    if (count($value[$i]->images) > 0) {
                        imageFirst::create([
                            'id' => $value[$i]->images[0]->id,
                            'product_id' => $value[$i]->images[0]->product_id,
                            'alt' => $value[$i]->images[0]->alt,
                            'width' => $value[$i]->images[0]->width,
                            'height' => $value[$i]->images[0]->height,
                            'src' => $value[$i]->images[0]->src,
                        ]);
                    }

                    for ($j = 0; $j < count($value[$i]->variants); $j++) {
                        Variant::create([
                            'id' => $value[$i]->variants[$j]->id,
                            'product_id' => $value[$i]->variants[$j]->product_id,
                            'title' =>  $value[$i]->variants[$j]->title,
                            'price' =>  $value[$i]->variants[$j]->price,
                            'sku' =>  $value[$i]->variants[$j]->sku,
                            'position' =>  $value[$i]->variants[$j]->position,
                            'inventory_policy' =>  $value[$i]->variants[$j]->inventory_policy,
                            'compare_at_price' =>  $value[$i]->variants[$j]->compare_at_price,
                            'fulfillment_service' =>  $value[$i]->variants[$j]->fulfillment_service,
                            'inventory_management' =>  $value[$i]->variants[$j]->inventory_management,
                            'option1' =>  $value[$i]->variants[$j]->option1,
                            'option2' =>  $value[$i]->variants[$j]->option2,
                            'option3' =>  $value[$i]->variants[$j]->option3,
                            'taxable' =>  $value[$i]->variants[$j]->taxable,
                            'barcode' =>  $value[$i]->variants[$j]->barcode,
                            'image_id' =>  $value[$i]->variants[$j]->image_id,
                            'weight' =>  $value[$i]->variants[$j]->weight,
                            'weight_unit' =>  $value[$i]->variants[$j]->weight_unit,
                            'inventory_item_id' =>  $value[$i]->variants[$j]->inventory_item_id,
                            'inventory_quantity' =>  $value[$i]->variants[$j]->inventory_quantity,
                            'old_inventory_quantity' =>  $value[$i]->variants[$j]->old_inventory_quantity,
                            'requires_shipping' =>  $value[$i]->variants[$j]->requires_shipping,
                        ]);
                    }
                    for ($z = 0; $z < count($value[$i]->options); $z++) {
                        Option::create([
                            'id' => $value[$i]->options[$z]->id,
                            'product_id' => $value[$i]->options[$z]->product_id,
                            'name' => $value[$i]->options[$z]->name,
                            'position' => $value[$i]->options[$z]->position,
                        ]);
                        for ($y = 0; $y < count($value[$i]->options[$z]->values); $y++) {
                            ValueOption::create([
                                'product_id' => $value[$i]->options[$z]->product_id,
                                'id_options' => $value[$i]->options[$z]->id,
                                'stt' => $y,
                                'name' => $value[$i]->options[$z]->values[$y],
                            ]);
                        }
                    }
                    for ($x = 0; $x < count($value[$i]->images); $x++) {
                        Image::create([
                            'id' => $value[$i]->images[$x]->id,
                            'product_id' => $value[$i]->images[$x]->product_id,
                            'position' => $value[$i]->images[$x]->position,
                            'alt' => $value[$i]->images[$x]->alt,
                            'width' => $value[$i]->images[$x]->width,
                            'height' => $value[$i]->images[$x]->height,
                            'src' => $value[$i]->images[$x]->src,
                        ]);
                    }
                }
            }
        }
        $products = Product::where('domain_shop', Session::get('shop')->domain)->get();
        return view('admin.product.list', [
            'title' => ' Danh sách sản phẩm ',
            'products' => $products,
        ]);
    }

    // Nhận webhook kho tạo sản phẩm trên shopify => add vào DB
    public function createProductShopify(Request $request, $infoShop)
    {

        $createProduct = $request->input();


        $product =  Product::create([
            'id' => $createProduct['id'],
            'domain_shop' => $infoShop,
            'title' => $createProduct['title'],
            'body_html' => $createProduct['body_html'],
            'vendor' => $createProduct['vendor'],
            'product_type' => $createProduct['product_type'],
            'handle' => $createProduct['handle'],
            'published_at' => $createProduct['published_at'],
            'template_suffix' => $createProduct['template_suffix'],
            'status' => $createProduct['status'],
            'published_scope' => $createProduct['published_scope'],
            'tags' => $createProduct['tags'],
        ]);
        $job = (new addProduct($product))->delay(Carbon::now()->addSecond(3));
        dispatch($job);
        if ($createProduct['image'] !== null) {
            imageFirst::create([
                'id' => $createProduct['image']['id'],
                'product_id' => $createProduct['image']['product_id'],
                'alt' => $createProduct['image']['alt'],
                'width' => $createProduct['image']['width'],
                'height' => $createProduct['image']['height'],
                'src' => $createProduct['image']['src'],
            ]);
        }

        if ($createProduct['variants'] !== null) {
            for ($j = 0; $j < count($createProduct['variants']); $j++) {
                Variant::create([
                    'id' => $createProduct['variants'][$j]['id'],
                    'product_id' => $createProduct['variants'][$j]['product_id'],
                    'title' =>  $createProduct['variants'][$j]['title'],
                    'price' =>  $createProduct['variants'][$j]['price'],
                    'sku' =>  $createProduct['variants'][$j]['sku'],
                    'position' =>  $createProduct['variants'][$j]['position'],
                    'inventory_policy' =>  $createProduct['variants'][$j]['inventory_policy'],
                    'compare_at_price' =>  $createProduct['variants'][$j]['compare_at_price'],
                    'fulfillment_service' =>  $createProduct['variants'][$j]['fulfillment_service'],
                    'inventory_management' =>  $createProduct['variants'][$j]['inventory_management'],
                    'option1' =>  $createProduct['variants'][$j]['option1'],
                    'option2' =>  $createProduct['variants'][$j]['option2'],
                    'option3' =>  $createProduct['variants'][$j]['option3'],
                    'taxable' =>  $createProduct['variants'][$j]['taxable'],
                    'barcode' =>  $createProduct['variants'][$j]['barcode'],
                    'image_id' =>  $createProduct['variants'][$j]['image_id'],
                    'weight' =>  $createProduct['variants'][$j]['weight'],
                    'weight_unit' =>  $createProduct['variants'][$j]['weight_unit'],
                    'inventory_item_id' =>  $createProduct['variants'][$j]['inventory_item_id'],
                    'inventory_quantity' =>  $createProduct['variants'][$j]['inventory_quantity'],
                    'old_inventory_quantity' =>  $createProduct['variants'][$j]['old_inventory_quantity'],
                    'requires_shipping' =>  $createProduct['variants'][$j]['requires_shipping'],
                ]);
            }
        }

        for ($z = 0; $z < count($createProduct['options']); $z++) {
            Option::create([
                'id' => $createProduct['options'][$z]['id'],
                'product_id' => $createProduct['options'][$z]['product_id'],
                'name' => $createProduct['options'][$z]['name'],
                'position' => $createProduct['options'][$z]['position'],
            ]);
            for ($y = 0; $y < count($createProduct['options'][$z]['values']); $y++) {
                ValueOption::create([
                    'product_id' => $createProduct['options'][$z]['product_id'],
                    'id_options' => $createProduct['options'][$z]['id'],
                    'stt' => $y,
                    'name' => $createProduct['options'][$z]['values'][$y],
                ]);
            }
        }

        if ($createProduct['images'] !== null) {
            for ($x = 0; $x < count($createProduct['images']); $x++) {
                Image::create([
                    'id' => $createProduct['images'][$x]['id'],
                    'product_id' => $createProduct['id'],
                    'position' => $createProduct['images'][$x]['position'],
                    'alt' => $createProduct['images'][$x]['alt'],
                    'width' => $createProduct['images'][$x]['width'],
                    'height' => $createProduct['images'][$x]['height'],
                    'src' => $createProduct['images'][$x]['src'],
                ]);
            }
        }
    }

    //nhận webhook khi update sản phẩm trên shopify
    public function updateProductShopify(Request $request)
    {
        $update = $request->input();
        Product::where('id', $update['id'])->update([
            'title' => $update['title'],
            'body_html' => $update['body_html'],
            'vendor' => $update['vendor'],
            'product_type' => $update['product_type'],
            'handle' => $update['handle'],
            'published_at' => $update['published_at'],
            'template_suffix' => $update['template_suffix'],
            'status' => $update['status'],
            'published_scope' => $update['published_scope'],
            'tags' => $update['tags'],
        ]);
        if ($update['image'] == null) {
            imageFirst::where('product_id', $update['id'])->delete();
        } else {
            imageFirst::where('product_id', $update['id'])->update([
                'id' => $update['image']['id'],
                'product_id' => $update['image']['product_id'],
                'alt' => $update['image']['alt'],
                'width' => $update['image']['width'],
                'height' => $update['image']['height'],
                'src' => $update['image']['src'],
            ]);
        }

        if ($update['variants'] == null) {
            Variant::where('product_id', $update['id'])->delete();
        } else {


            //Kiểm tra: so sánh dữ liệu từ DB_app và dữ liệu từ shopify trả về.
            // Xóa những Variant trong DB_app không còn trên shopify  
            $variantOld = Variant::where('product_id', $update['id'])->get();
            foreach ($variantOld as $valueVariant) {
                $checkVariant = 0;
                for ($checkDeleteVariant = 0; $checkDeleteVariant < count($update['variants']); $checkDeleteVariant++) {
                    if ($valueVariant->id == $update['variants'][$checkDeleteVariant]['id']) {
                        $checkVariant += 1;
                    }
                }
                if ($checkVariant == 0) {
                    Variant::where('id', $valueVariant->id)->delete();
                }
            }
            for ($j = 0; $j < count($update['variants']); $j++) {

                $dataVariant =  Variant::find($update['variants'][$j]['id']);
                if ($dataVariant) {
                    Variant::where('id', $update['variants'][$j]['id'])->update([
                        'title' =>  $update['variants'][$j]['title'],
                        'price' =>  $update['variants'][$j]['price'],
                        'sku' =>  $update['variants'][$j]['sku'],
                        'position' =>  $update['variants'][$j]['position'],
                        'inventory_policy' =>  $update['variants'][$j]['inventory_policy'],
                        'compare_at_price' =>  $update['variants'][$j]['compare_at_price'],
                        'fulfillment_service' =>  $update['variants'][$j]['fulfillment_service'],
                        'inventory_management' =>  $update['variants'][$j]['inventory_management'],
                        'option1' =>  $update['variants'][$j]['option1'],
                        'option2' =>  $update['variants'][$j]['option2'],
                        'option3' =>  $update['variants'][$j]['option3'],
                        'taxable' =>  $update['variants'][$j]['taxable'],
                        'barcode' =>  $update['variants'][$j]['barcode'],
                        'image_id' =>  $update['variants'][$j]['image_id'],
                        'weight' =>  $update['variants'][$j]['weight'],
                        'weight_unit' =>  $update['variants'][$j]['weight_unit'],
                        'inventory_item_id' =>  $update['variants'][$j]['inventory_item_id'],
                        'inventory_quantity' =>  $update['variants'][$j]['inventory_quantity'],
                        'old_inventory_quantity' =>  $update['variants'][$j]['old_inventory_quantity'],
                        'requires_shipping' =>  $update['variants'][$j]['requires_shipping'],
                    ]);
                } else {
                    Variant::create([
                        'id' => $update['variants'][$j]['id'],
                        'product_id' => $update['variants'][$j]['product_id'],
                        'title' =>  $update['variants'][$j]['title'],
                        'price' =>  $update['variants'][$j]['price'],
                        'sku' =>  $update['variants'][$j]['sku'],
                        'position' =>  $update['variants'][$j]['position'],
                        'inventory_policy' =>  $update['variants'][$j]['inventory_policy'],
                        'compare_at_price' =>  $update['variants'][$j]['compare_at_price'],
                        'fulfillment_service' =>  $update['variants'][$j]['fulfillment_service'],
                        'inventory_management' =>  $update['variants'][$j]['inventory_management'],
                        'option1' =>  $update['variants'][$j]['option1'],
                        'option2' =>  $update['variants'][$j]['option2'],
                        'option3' =>  $update['variants'][$j]['option3'],
                        'taxable' =>  $update['variants'][$j]['taxable'],
                        'barcode' =>  $update['variants'][$j]['barcode'],
                        'image_id' =>  $update['variants'][$j]['image_id'],
                        'weight' =>  $update['variants'][$j]['weight'],
                        'weight_unit' =>  $update['variants'][$j]['weight_unit'],
                        'inventory_item_id' =>  $update['variants'][$j]['inventory_item_id'],
                        'inventory_quantity' =>  $update['variants'][$j]['inventory_quantity'],
                        'old_inventory_quantity' =>  $update['variants'][$j]['old_inventory_quantity'],
                        'requires_shipping' =>  $update['variants'][$j]['requires_shipping'],
                    ]);
                }
            }
        }
        if ($update['options'] == null) {
            Option::where('product_id', $update['id'])->delete();
        } else {

            //Kiểm tra: so sánh dữ liệu từ DB_app và dữ liệu từ shopify trả về.
            // Xóa những Option trong DB_app không còn trên shopify  
            $optionOld = Option::where('product_id', $update['id'])->get();
            foreach ($optionOld as $valueOption) {
                $checkOption = 0;
                for ($checkDeleteOption = 0; $checkDeleteOption < count($update['options']); $checkDeleteOption++) {
                    if ($valueOption->id == $update['options'][$checkDeleteOption]['id']) {
                        $checkOption += 1;
                    }
                }
                if ($checkOption == 0) {
                    Option::where('id', $valueOption->id)->delete();
                    ValueOption::where('id_options', $valueOption->id)->delete();
                }
            }

            for ($z = 0; $z < count($update['options']); $z++) {
                $dataOption =  Option::find($update['options'][$z]['id']);
                ValueOption::where('id_options', $update['options'][$z]['id'])->delete();
                if ($dataOption) {
                    Option::where('id', $update['options'][$z]['id'])->update([
                        'name' => $update['options'][$z]['name'],
                        'position' => $update['options'][$z]['position'],
                    ]);


                    for ($y = 0; $y < count($update['options'][$z]['values']); $y++) {
                        ValueOption::create([
                            'product_id' => $update['options'][$z]['product_id'],
                            'id_options' => $update['options'][$z]['id'],
                            'stt' => $y,
                            'name' => $update['options'][$z]['values'][$y],
                        ]);
                    }
                } else {
                    Option::create([
                        'id' => $update['options'][$z]['id'],
                        'product_id' => $update['options'][$z]['product_id'],
                        'name' => $update['options'][$z]['name'],
                        'position' => $update['options'][$z]['position'],
                    ]);
                    for ($y = 0; $y < count($update['options'][$z]['values']); $y++) {
                        ValueOption::create([
                            'product_id' => $update['options'][$z]['product_id'],
                            'id_options' => $update['options'][$z]['id'],
                            'stt' => $y,
                            'name' => $update['options'][$z]['values'][$y],
                        ]);
                    }
                }
            }
        }

        if ($update['images'] == null || count($update['images']) < 1) {
            Image::where('product_id', $update['id'])->delete();
        } else {

            //Kiểm tra: so sánh dữ liệu từ DB_app và dữ liệu từ shopify trả về.
            // Xóa những Image trong DB_app không còn trên shopify  
            $imageOld = Image::where('product_id', $update['id'])->get();
            foreach ($imageOld as $value_) {
                $check = 0;
                for ($checkDelete = 0; $checkDelete < count($update['images']); $checkDelete++) {
                    if ($value_->id == $update['images'][$checkDelete]['id']) {
                        $check += 1;
                    }
                }
                if ($check == 0) {
                    Image::where('id', $value_->id)->delete();
                }
            }

            for ($x = 0; $x < count($update['images']); $x++) {
                $data =  Image::find($update['images'][$x]['id']);
                if ($data) {
                    $data->position = $update['images'][$x]['position'];
                    $data->alt = $update['images'][$x]['alt'];
                    $data->width =  $update['images'][$x]['width'];
                    $data->height =  $update['images'][$x]['height'];
                    $data->src = $update['images'][$x]['src'];
                    $data->update();
                } else {
                    Image::create([
                        'id' => $update['images'][$x]['id'],
                        'product_id' => $update['id'],
                        'position' => $update['images'][$x]['position'],
                        'alt' => $update['images'][$x]['alt'],
                        'width' => $update['images'][$x]['width'],
                        'height' => $update['images'][$x]['height'],
                        'src' => $update['images'][$x]['src'],
                    ]);
                }
            }
        }
    }

    public function deleteProductShopify(Request $request)
    {
        $id = $request->input('id');
        Product::where('id', $id)->delete();
        Image::where('product_id', $id)->delete();
        imageFirst::where('product_id', $id)->delete();
        Variant::where('product_id', $id)->delete();
        Option::where('product_id', $id)->delete();
        ValueOption::where('product_id', $id)->delete();
    }
}
