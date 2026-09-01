<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreCopilotRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Sanitize input before validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('message') && is_string($this->input('message'))) {
            $cleaned = $this->sanitizeMessage($this->input('message'));
            $this->merge(['message' => $cleaned]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'message' => 'nullable|string|max:1000',
            'files' => 'nullable|array|max:2',
            'files.*' => 'file|mimes:pdf,png,jpg,jpeg,webp|max:10240',
        ];
    }

    /**
     * Custom validation error messages.
     */
    public function messages(): array
    {
        return [
            'files.max' => 'Solo puedes adjuntar un máximo de 2 archivos por mensaje.',
            'files.*.mimes' => 'Formato no válido. Solo se permiten archivos PDF o imágenes (PNG, JPG, WEBP).',
            'files.*.max' => 'El archivo es demasiado grande (máximo 10MB).',
            'message.max' => 'El mensaje no puede superar los 1000 caracteres.',
        ];
    }

    /**
     * Handle a failed validation attempt with a clean, friendly JSON response.
     */
    protected function failedValidation(Validator $validator)
    {
        $firstError = $validator->errors()->first();

        $response = response()->json([
            'success' => false,
            'error' => $firstError ?: 'El archivo es demasiado grande (máximo 10MB) o el formato no es válido (solo PDF o imágenes).',
            'message' => $firstError ?: 'El archivo es demasiado grande (máximo 10MB) o el formato no es válido (solo PDF o imágenes).',
            'errors' => $validator->errors()
        ], 422);

        throw new HttpResponseException($response);
    }

    /**
     * Sanitize user input against XSS and common Prompt Injections.
     */
    protected function sanitizeMessage(string $input): string
    {
        // 1. Strip all HTML/script tags and null bytes
        $clean = strip_tags($input);
        $clean = str_replace(["\0", "\x0B"], '', $clean);

        // 2. Remove common prompt injection phrases
        $injectionPatterns = [
            '/\bignore\s+(all\s+)?(previous|prior|above)\s+instructions\b/i',
            '/\bdisregard\s+(all\s+)?(previous|prior|above)\s+instructions\b/i',
            '/\bforget\s+(all\s+)?(previous|prior|above)\s+instructions\b/i',
            '/\breveal\s+(your\s+)?(system\s+prompt|instructions)\b/i',
            '/\bprint\s+(your\s+)?(system\s+prompt|initial\s+prompt)\b/i',
            '/\byou\s+are\s+now\s+(in\s+developer\s+mode|unrestricted|DAN)\b/i',
            '/\bact\s+as\s+(an\s+unrestricted|a\s+hacker|DAN)\b/i',
        ];

        $clean = preg_replace($injectionPatterns, '[Filtro de seguridad aplicado]', $clean);

        return trim($clean);
    }
}
