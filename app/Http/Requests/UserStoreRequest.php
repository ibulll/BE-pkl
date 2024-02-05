<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|email' . $this->route('id'),
            'role_id' => 'required|exists:roles,id',
            'password' => 'nullable|string|min:6',
            // Add validation rules for other fields
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'Name is required!',
            'email.required' => 'Email is required!',
            'email.email' => 'Email must be a valid email address!',
            'role_id.required' => 'Role is required!',
            'role_id.exists' => 'Selected role does not exist!',
            'password.min' => 'Password must be at least 6 characters!',
            // Add other messages for validation rules
        ];
    }
}
