<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        $rules = [
            'name' => 'required|min:5|max:255',
            'category' => 'required|int|exists:categories,id',
        ];
        if ($this->isMethod('post')) {
            $rules['image'] = 'required|file|mimes:jpg,jpgeg,png,webp,svg|max:1024';
        } else {
            $rules['image'] = 'nullable|file|mimes:jpg,jpgeg,png,webp,svg|max:1024';
        }
        return $rules;
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes() {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages() {
        return [
            //
        ];
    }
}
