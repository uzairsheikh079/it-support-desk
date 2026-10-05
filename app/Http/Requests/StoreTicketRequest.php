<?php

namespace App\Http\Requests;

use App\Enums\TicketPriority;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTicketRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'subject' => ['required', 'string', 'max:120'],
            'description' => ['required', 'string', 'min:10', 'max:5000'],
            'requester_name' => ['required', 'string', 'max:100'],
            'requester_email' => ['required', 'email', 'max:255'],
            'priority' => ['required', Rule::enum(TicketPriority::class)],
            'assigned_to' => ['nullable', 'string', 'max:100'],
        ];
    }
}
