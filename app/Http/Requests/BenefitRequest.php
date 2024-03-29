<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BenefitRequest extends FormRequest
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
        $image = request()->hasFile('image') ? 'required|image|mimes:jpeg,jpg,png|max:2048' : 'required|string|ends_with:.jpg,.jpeg,.png';
        return [
            'title' => ['required', 'string', 'max:255'],
            'details' => ['required', 'string'],
            'promo_code' => ['required', 'string'],
            'redemption_link' => ['nullable', 'string'],
            'image' => $image,
            'send_notification' => ['boolean', 'required']
        ];
    }
}
