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
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . request()->user()->id],
            'phone_number' => 'required|string',
            'profile_picture' => request()->hasFile('profile_picture') ? 'nullable|image|mimes:jpeg,jpg,png' : 'nullable|string|ends_with:.jpg,.jpeg,.png',
            'user_name' => 'required|string',
            'address' => 'required|string',
            'date_of_birth' => 'required|date',
        ];
    }
}
