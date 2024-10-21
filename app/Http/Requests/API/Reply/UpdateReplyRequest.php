<?php

namespace App\Http\Requests\API\Reply;

use App\Models\Reply;
use Illuminate\Foundation\Http\FormRequest;

class UpdateReplyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth('sanctum')->user()->can('update', [Reply::class, $this->reply]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'content' => ['required', 'string', 'max:1000'],
        ];
    }
}