<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     @OA\Xml(name="UpdateTask"),
 *     @OA\Property(property="title", type="string",  example="Example task title"),
 *     @OA\Property(property="description", type="text",  example="Example task description", nullable=true),
 *     @OA\Property(property="due_date", type="string", format="date", example="2025-02-25"),
 *     @OA\Property(property="completed", type="boolean", example=true),
 * )
 */
class UpdateTaskRequest extends FormRequest
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
            'title' => 'min:3|max:100',
            'description' => 'max:1000',
            'due_date' => 'date|after_or_equal:today',
            'completed' => 'bool'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message'   => 'Validation errors',
            'data'      => $validator->errors()
        ])->setStatusCode(400));
    }
}