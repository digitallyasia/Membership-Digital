<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone_number' => 'required|string',
            'profile_picture' => request()->hasFile('profile_picture') ? 'required|image|mimes:jpeg,jpg,png' : 'active_url',,
            'user_name' => 'required|string',
            'fcm_token' => 'required|string',
            'address' => 'required|string',
            'date_of_birth' => 'required',
        ];
    }
}
