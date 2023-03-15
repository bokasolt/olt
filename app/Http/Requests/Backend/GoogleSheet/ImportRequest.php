<?php


namespace App\Http\Requests\Backend\GoogleSheet;

use Illuminate\Foundation\Http\FormRequest;

class ImportRequest extends FormRequest
{
    /**
    * Determine if the user is authorized to make this request.
    *
    * @return bool
    */

    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'rows' => ['required'],
            'overwrite' => ['sometimes'],
        ];
    }

    public function messages()
    {
        return [
            'rows.required' => 'Select the domains you want to import',
        ];
    }
}
