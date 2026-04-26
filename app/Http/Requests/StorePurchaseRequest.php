<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePurchaseRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'purchasable_type' => 'required|in:component,template',
            'purchasable_id' => 'required|integer',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
        ];
    }
}