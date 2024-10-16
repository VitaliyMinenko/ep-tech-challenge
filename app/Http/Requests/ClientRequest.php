<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:190',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|regex:/^[\d\s+]*$/',
            'address' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:50',
            'postcode' => 'nullable|string|max:6',
        ];
    }
}
