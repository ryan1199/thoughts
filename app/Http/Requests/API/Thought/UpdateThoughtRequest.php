<?php

namespace App\Http\Requests\API\Thought;

use App\Models\Thought;
use Illuminate\Foundation\Http\FormRequest;

class UpdateThoughtRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', [Thought::class, $this->thought]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'topic' => ['sometimes', 'required', 'string', 'max:100'],
            'content' => ['sometimes', 'required', 'string', 'max:1000'],
            'tags' => ['sometimes', 'required', 'string', 'max:100'],
            'open' => ['sometimes', 'required', 'boolean']
        ];
    }
}
