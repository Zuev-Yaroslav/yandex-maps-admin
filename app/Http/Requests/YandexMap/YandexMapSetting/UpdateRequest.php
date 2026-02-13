<?php

namespace App\Http\Requests\YandexMap\YandexMapSetting;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'org_reviews_url' => [
                'required',
                "regex:/^https:\/\/yandex\.ru\/maps\/org\/[a-z0-9_.-]+\/\d+\/reviews\/?$/u",
                'max:255',
            ],
        ];
    }
}
