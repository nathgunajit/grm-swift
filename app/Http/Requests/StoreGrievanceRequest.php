<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGrievanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $anonymous = $this->boolean('is_anonymous');

        return [
            'mode_of_receipt' => ['required', 'in:online,verbal,written,phone,drop-box,meeting'],
            'category_id' => ['required', 'exists:grievance_categories,id'],
            'name' => [$anonymous ? 'nullable' : 'required', 'string', 'max:255'],
            'gender' => ['nullable', 'in:Male,Female,Other'],
            'age' => ['nullable', 'integer', 'min:1', 'max:120'],
            'caste' => ['nullable', 'string', 'max:100'],
            // Mobile mandatory unless anonymous; format-validated (Indian 10-digit), no OTP.
            'mobile' => [$anonymous ? 'nullable' : 'required', 'nullable', 'regex:/^[6-9]\d{9}$/'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => [$anonymous ? 'nullable' : 'required', 'string', 'max:1000'],
            'place_village' => ['required', 'string', 'max:255'],
            // Beel is no longer mandatory (Phase 2 requirement).
            'beel_id' => ['nullable', 'exists:beels,id'],
            'description' => ['required', 'string', 'min:5'],
            'documents.*' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,doc,docx', 'max:5120'],
            'is_anonymous' => ['nullable', 'boolean'],
            'is_confidential' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'mobile.regex' => 'Enter a valid 10-digit Indian mobile number.',
            'beel_id.required' => 'Please select a Beel.',
        ];
    }
}
