<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class SettingRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'country_name' => 'nullable|string|max:25',
        ];
    }

    protected function failedValidation(Validator $validator)
    {

        $response = new JsonResponse([
            'error' => true,
            'message' => $validator->errors()->first(),
        ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);

        throw new \Illuminate\Validation\ValidationException($validator, $response);
    }
}
