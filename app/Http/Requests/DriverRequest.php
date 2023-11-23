<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DriverRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'years'=>['required','numeric','between:2010,2023'],
            'make'=>['required'],
            'model'=>['required'],
            'color'=>['required'],
            'license_plate'=>['required'],
            'name'=>['required'],
        ];
    }
}
