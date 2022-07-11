<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\infoShop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Session;

class MainController extends Controller
{
    public function shopify()
    {

        return view('admin.shopify');
    }
    public function homeAdmin(Request $request)
    {

        // return dd(1);
        $data = $request->input();

        return view('admin.home', [
            'title' => 'Shopify',
        ]);
    }



    //Hàm nhận name shop nhập vào, chuyển hướng tran với các dữ liệu đễ install app và trả về code.
    public function shopify_(Request $request)
    {
        $client_id = 'f32277594c000b55c71ef71e85d45e5b';
        $scope = 'write_orders,read_customers,read_products,write_products,read_product_listings,read_customers,write_customers';
        $redirect_uri = 'http://127.0.0.1:8000/admin';
        $nameStore = $request->nameShopify;

        return redirect('https://' . $nameStore . '.myshopify.com/admin/oauth/authorize?client_id=' . $client_id . '&scope=' . $scope . '&redirect_uri=' . $redirect_uri);
    }

    //Hàm đc redirect_uri từ function shopify_ . nhận đc code => nhận Access token
    public function index(Request $request)
    {
        // $url = 'https://Hoang-Store12345.myshopify.com/admin/oauth/authorize?client_id=f32277594c000b55c71ef71e85d45e5b&scope=write_orders,read_customers&redirect_uri=http://127.0.0.1:8000/';

        $shop = $request->shop;
        $code = $request->code;
        $access_token_url = "https://" . $shop . "/admin/oauth/access_token";
        $url = 'https://' . $shop . '/admin/api/2022-07/shop.json?';
        // $query = array(
        //     "client_id" => 'f32277594c000b55c71ef71e85d45e5b', // Your API key
        //     "client_secret" => '2312f43d320b8738c2fde886f1afd83d', // Your app credentials (secret key)
        //     "code" => $code // Grab the access key from the URL
        // );


        $client = new Client();
        $response = $client->post(
            $access_token_url,
            [
                'form_params' => [
                    'client_id' => "f32277594c000b55c71ef71e85d45e5b",
                    'client_secret' => "2312f43d320b8738c2fde886f1afd83d",
                    'code' => $code,
                ]
            ]
        );
        $data = json_decode($response->getBody()->getContents());

        $accessToken = $data->access_token;
        $resShop = $client->request('GET', $url, [
            'headers' => [
                'X-Shopify-Access-Token' => $accessToken,
            ]
        ]);

        $data = (array) json_decode($resShop->getBody());


        $getfilter = $data['shop'];
        Session::put('shop', $getfilter);

        //kiểm tra thông tin shop đã tồn tại hay chưa? chưa thì tạo mới thông tin vào DB
        if (!empty(infoShop::find($getfilter->id))) {
            infoShop::where('email', $getfilter->email)->update(['access_token' => $accessToken]);
        } else {
            infoShop::create([
                'id' => $getfilter->id,
                'name_shop' => $getfilter->name,
                'domain' => $getfilter->domain,
                'email' => $getfilter->email,
                'shopify_domain' => $getfilter->domain,
                'access_token' => $accessToken,
                'plan' => $getfilter->plan_name,
            ]);
        }
        return view('admin.home', [
            'title' => 'Trang quản trị Admin',
        ]);
    }

    public function createWebHook_createCustomer(infoShop $infoShop)
    {
        $client = new Client();
        $url = 'https://' . $infoShop->domain . '/admin/api/2022-07/webhooks.json';
        $hostNgrok = env('ngrok');
        $resShop = $client->request('POST', $url, [
            'headers' => [
                'X-Shopify-Access-Token' => $infoShop->access_token,
            ],
            'form_params' => [
                'webhook' => [
                    'topic' => 'customers/create',
                    'format' => 'json',
                    'address' => $hostNgrok . '/' . $infoShop->name_shop . '/api/111',
                ]
            ]
        ]);
    }

    public function createProduct()
    {
        echo 'hello';
    }

    public function createWebHook_createProduct(infoShop $infoShop)
    {
        $client = new Client();
        $url = 'https://' . $infoShop->domain . '/admin/api/2022-07/webhooks.json';
        $hostNgrok = env('ngrok');
        $resShop = $client->request('POST', $url, [
            'headers' => [
                'X-Shopify-Access-Token' => $infoShop->access_token,
            ],
            'form_params' => [
                'webhook' => [
                    'topic' => 'products/create',
                    'format' => 'json',
                    'address' => $hostNgrok . '/api/createProduct/' . $infoShop->domain,
                ]
            ]
        ]);
        dd($resShop);
    }

    public function createWebHook_updateProduct(infoShop $infoShop)
    {
        $client = new Client();
        $url = 'https://' . $infoShop->domain . '/admin/api/2022-07/webhooks.json';
        $hostNgrok = env('ngrok');
        $resShop = $client->request('POST', $url, [
            'headers' => [
                'X-Shopify-Access-Token' => $infoShop->access_token,
            ],
            'form_params' => [
                'webhook' => [
                    'topic' => 'products/update',
                    'format' => 'json',
                    'address' => $hostNgrok . '/api/updateProduct',
                ]
            ]
        ]);
        // dd($resShop);
    }
    public function aaaw(Request $request)
    {
        var_dump($request->input());
    }

    public function createWebHook_deleteProduct(infoShop $infoShop)
    {
        $client = new Client();
        $url = 'https://' . $infoShop->domain . '/admin/api/2022-07/webhooks.json';
        $hostNgrok = env('ngrok');
        $resShop = $client->request('POST', $url, [
            'headers' => [
                'X-Shopify-Access-Token' => $infoShop->access_token,
            ],
            'form_params' => [
                'webhook' => [
                    'topic' => 'products/delete',
                    'format' => 'json',
                    'address' => $hostNgrok . '/api/deleteProduct',
                ]
            ]
        ]);
        // dd($resShop);
    }
}
