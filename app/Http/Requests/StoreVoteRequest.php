<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVoteRequest extends FormRequest
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
            'voter_name' => ['required', 'string', 'max:255'],
            'votes' => ['required', 'array'],
            'votes.*' => ['required', 'string', 'in:yes,no,maybe'],
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
            'voter_name.required' => 'Please enter your name.',
            'voter_name.max' => 'Your name cannot exceed 255 characters.',
            'votes.required' => 'Please vote for at least one option.',
            'votes.*.in' => 'Invalid vote type. Must be yes, no, or maybe.',
        ];
    }
}
