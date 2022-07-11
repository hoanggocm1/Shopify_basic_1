<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\UploadService;

class UploadController extends Controller
{
    protected $uploadService;

    public function __construct(UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    public function store(Request $request)
    {
        $url = $this->uploadService->store($request);

        if ($url !== false) {
            return response()->json([
                'error' => false,
                'url' => $url,
            ]);
        }
        return response()->json([
            'error' => true
        ]);
    }

    public function stores(Request $request)
    {

        $url = $this->uploadService->stores($request);

        if ($url !== false) {
            return response()->json([
                'error' => false,
                'url' => $url,
            ]);
        }
        return response()->json([
            'error' => true
        ]);
    }

    public function store_slider(Request $request)
    {
        $url = $this->uploadService->store_slider($request);
        if ($url !== false) {
            return response()->json([
                'error' => false,
                'url' => $url,
            ]);
        }
        return response()->json([
            'error' => true
        ]);
    }

    public function store_avatarAdmin(Request $request)
    {
        $url = $this->uploadService->store_avatarAdmin($request);
        if ($url !== false) {
            return response()->json([
                'error' => false,
                'url' => $url,
            ]);
        }
        return response()->json([
            'error' => true
        ]);
    }

    public function testimage()
    {
        return view('test');
    }
    public function testimagestore(Request $request)
    {
        return dd($request->input('ok'));
    }

    public function shopify()
    {
        $url1 = 'localhost/laravel_basic_2/hoang-store1234.myshopify.com/admin/oauth/access_token';
        $url = 'https://localhost/laravel_basic_2/hoang-store1234.myshopify.com/admin/oauth/authorize?client_id=f32277594c000b55c71ef71e85d45e5b&&scope=scope=write_orders,read_customers&redirect_uri=http://127.0.0.1:8000';
        return redirect($url);
    }
}
