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
            'name' => ['required', 'max:60'],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'nik' => ['required', 'numeric', 'digits:12', Rule::unique('users', 'nik')],
            'phone' => ['required', 'numeric', 'digits_between:10,12', Rule::unique('users', 'phone')],
            'address' => 'required',
            'image' => 'nullable|mimes:png,jpg,jpeg|file|max:2500',
        ];

        if (request()->routeIs('profiles.update')) {
            $rules['email'] = ['required', 'email', Rule::unique('users', 'email')->ignore(auth()->id())];
            $rules['phone'] = ['required', 'numeric', 'digits_between:10,12', Rule::unique('users', 'phone')->ignore(auth()->id())];
            $rules['nik'] = [];
        }

        if (request()->routeIs('profiles.password.update'))
            return [
                'current_password' => ['required', 'string', 'password'],
                'new_password' => ['required', 'different:current_password', 'min:5', 'confirmed'],
            ];

        return $rules;
    }
}
