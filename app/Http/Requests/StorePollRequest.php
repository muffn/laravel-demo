<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePollRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'options' => ['required', 'array', 'min:1'],
            'options.*' => ['required', 'string', 'max:255'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Please provide a title for your poll.',
            'title.max' => 'The title cannot exceed 255 characters.',
            'description.max' => 'The description cannot exceed 1000 characters.',
            'options.required' => 'Please add at least one option.',
            'options.min' => 'Please add at least one option.',
            'options.*.required' => 'Each option must have text.',
            'options.*.max' => 'Each option cannot exceed 255 characters.',
        ];
    }

    /**
     * Get the validated options, filtered and trimmed.
     *
     * @return array<int, string>
     */
    public function validatedOptions(): array
    {
        $options = $this->validated('options', []);

        return array_values(array_filter(
            array_map('trim', $options),
            fn (string $opt): bool => $opt !== ''
        ));
    }
}
