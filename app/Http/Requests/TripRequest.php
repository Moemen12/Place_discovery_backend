<?php

namespace App\Http\Requests;

use App\Models\Trip;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class TripRequest extends FormRequest
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
            'title' => 'required|string|min:10|max:100',
            'description' => 'required|string|min:20|max:600',
            'trip_type' => ['required', 'string', Rule::in(Trip::$trip_place_type)],
            'images' => 'required|array|min:4|max:10',
            'images.*' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'address' => 'nullable|string|max:25',
        ];
    }

    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $response = new JsonResponse([
            'error' => true,
            'message' => $validator->errors()->first()
        ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);

        throw new ValidationException($validator, $response);
    }
}
