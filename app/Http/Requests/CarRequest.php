<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CarRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:60',
            'merk' => 'required|max:60',
            'years' => 'required|numeric|min:1]digits:4',
            'plat_number' => ['required', 'alpha_num', 'size:8', Rule::unique('cars', 'plat_number')->ignore($this->car)],
            'color' => 'required|alpha|max:20',
            'price' => 'required|numeric',
            'image' => 'nullable|mimes:png,jpg,jpeg|file|image|max:2500',
        ];
    }
}
