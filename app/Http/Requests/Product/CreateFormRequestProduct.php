<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class CreateFormRequestProduct extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'file' => 'required',
            'file' => 'mimes:jpeg,jpg,png',
            'menu_id' => 'required',
            'description' => 'required',
            'price' => 'required',
            'price_sale' => 'required',
            'qty' => 'required',
            'content' => 'required'
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập tên sản phẩm',
            'file.required' => 'Bắc buộc phải nhập hình ảnh',
            'file.mimes' => 'File không đúng định dạng hình ảnh, Vui lòng thử lại!',
            'menu_id.required' => 'Hiện tại không có danh mục nào hoạt động. Không thể thêm sản phẩm!',
            'qty.required' => 'Vui lòng nhập tên sản phẩm',
            'description.required' => 'Vui lòng nhập chi tiết ',
            'price.required' => 'vui lòng nhập giá',
            'price_sale.required' => 'vui lòng nhập giá',
            'content.required' => 'vui lòng nhập mô tả',
        ];
    }
}
