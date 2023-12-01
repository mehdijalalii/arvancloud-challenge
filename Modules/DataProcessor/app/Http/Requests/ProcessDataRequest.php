<?php

namespace Modules\DataProcessor\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\DataProcessor\app\Rules\NoRecentProcessRule;

class ProcessDataRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        if ($this->method() === 'POST') {
            return $this->validatePostMethod();
        }

        return [];
    }

    public function validatePostMethod(): array
    {
        return [
            'user_id' => 'required',
            'object_uuid' => ['required', new NoRecentProcessRule],
            'object_volume' => 'required'
        ];
    }
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
