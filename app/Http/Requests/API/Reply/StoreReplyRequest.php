<?php

namespace App\Http\Requests\API\Reply;

use App\Models\Reply;
use Illuminate\Foundation\Http\FormRequest;

class StoreReplyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth('sanctum')->user()->can('create', [Reply::class]);
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
            'replied_id' => ['sometimes', 'required', 'numeric', 'exists:replies,id'],
        ];
    }
}
