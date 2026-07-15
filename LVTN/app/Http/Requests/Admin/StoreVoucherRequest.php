<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreVoucherRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|min:1|max:100',
            'quantity' => 'required|integer|min:1',
            'value' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'code' => 'required|string|unique:vouchers,code',
            'used' => 'nullable|integer|min:0',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => __('message.required', ['attribute' => 'Tên Voucher']),
            'name.min' => __('message.min', ['min' => 1, 'attribute' => 'Tên Voucher']),
            'name.max' => __('message.max', ['max' => 100, 'attribute' => 'Tên Voucher']),
            'quantity.required' => __('message.required', ['attribute' => 'Số lượng']),
            'quantity.min' => __('message.min', ['min' => 1, 'attribute' => 'Số lượng']),
            'value.required' => __('message.required', ['attribute' => 'Giá trị']),
            'value.numeric' => __('message.integer', ['attribute' => 'Giá trị']),
            'code.required' => __('message.required', ['attribute' => 'Code Voucher']),
            'code.unique' => __('message.unique', ['attribute' => 'Code Voucher']),
            'start_date.required' => __('message.required', ['attribute' => 'Ngày bắt đầu']),
            'start_date.date' => __('message.date', ['attribute' => 'Ngày bắt đầu']),
            'end_date.required' => __('message.required', ['attribute' => 'Ngày kết thúc']),
            'end_date.date' => __('message.date', ['attribute' => 'Ngày kết thúc']),
        ];
    }
}
