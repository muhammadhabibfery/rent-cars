<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
        $rules = [
            'name' => 'required|max:60',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->user)],
            'phone' => ['required', 'numeric', 'digits_between:10,12', Rule::unique('users', 'phone')->ignore($this->user)],
            'address' => 'required',
            'gambar' => 'nullable|mimes:png,jpg,jpeg|file|max:2500',
        ];

        if (request()->isMethod('patch') || request()->isMethod('put')) {
            $rules['email'] = ['required', 'email', Rule::unique('users', 'email')->ignore(auth()->id())];
            $rules['phone'] = ['required', 'numeric', 'digits_between:10,12', Rule::unique('users', 'phone')->ignore(auth()->id())];
        }

        return $rules;
    }
}
