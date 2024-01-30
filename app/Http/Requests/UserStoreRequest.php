<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        if ($this->isMethod('post')) {
            return [
                'name' => 'required|string|max:258',
                'email' => 'required|string',
                'password' => 'required|string'
            ];
        } elseif ($this->isMethod('put')) {
            // For the PUT method, exclude the 'password' field
            return [
                'name' => 'required|string|max:258',
                'email' => 'required|string',
                // 'password' is excluded for the update operation
            ];
        }
    }

    public function messages()
    {
        if ($this->isMethod('post')) {
            return [
                'name.required' => 'Name is required!',
                'email.required' => 'Email is required!',
                'password.required' => 'Password is required!'
            ];
        } elseif ($this->isMethod('put')) {
            // Adjust messages accordingly for the update operation
            return [
                'name.required' => 'Name is required!',
                'email.required' => 'Email is required!',
                // No password message for the update operation
            ];
        }
    }
}
