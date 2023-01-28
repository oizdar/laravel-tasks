<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     @OA\Xml(name="StoreTask"),
 *     @OA\Property(property="title", type="string",  example="Example task title"),
 *     @OA\Property(property="description", type="text",  example="Example task description", nullable=true),
 *     @OA\Property(property="due_date", type="string", format="date", example="2019-02-25"),
 * )
 */
class StoreTaskRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'required|min:3|max:100',
            'description' => 'max:1000',
            'due_date' => 'date|after_or_equal:today'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'message'   => 'Validation errors',
            'data'      => $validator->errors()
        ]));
    }
}
