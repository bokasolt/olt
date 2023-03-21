<?php


namespace App\Http\Requests\Backend\GoogleSheet;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class GoogleSheetRequest extends FormRequest
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
            'name' => ['required', 'string'],
            'url' => ['required', 'string', 'unique:google_sheets'],
            'associations' => ['required', 'array'],
            'associations.domain' => ['required', 'string', 'not_in:-- not use --'],
            'import' => ['sometimes']
        ];
    }
}
