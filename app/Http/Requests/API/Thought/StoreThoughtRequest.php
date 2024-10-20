<?php

namespace App\Http\Requests\API\Thought;

use App\Models\Thought;
use Illuminate\Foundation\Http\FormRequest;

class StoreThoughtRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', [Thought::class]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'topic' => ['required', 'string', 'max:100'],
            'content' => ['required', 'string', 'max:1000'],
            'tags' => ['required', 'string', 'max:100'],
            'open' => ['required', 'boolean']
        ];
    }
}
