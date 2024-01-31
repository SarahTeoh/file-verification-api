<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\FileType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class FileVerificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // TODO: authorization
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
            'file' => [
                'required',
                File::types(FileType::values())
                    ->max('2mb'),
            ],
        ];
    }
}
