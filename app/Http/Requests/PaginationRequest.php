<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
class PaginationRequest extends FormRequest
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
            'page' => 'numeric',
            'item_per_page' => 'numeric'
        ];
    }
    public function messages()
    {
        return [
            'page.numeric' => 'page must be numeric!',
            'item_per_page.numeric' => 'item_per_page must be numeric!',
        ];
    }
   
}
