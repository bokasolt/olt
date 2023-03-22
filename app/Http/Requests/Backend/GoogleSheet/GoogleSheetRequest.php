<?php


namespace App\Http\Requests\Backend\GoogleSheet;

use App\Models\Domain;
use App\Models\GoogleSheet;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        if (isset($this->route()->parameters['google_sheet'])) {
            return [
                'id' => ['sometimes'],
                'name' => ['required', 'string'],
                'url' => ['required', 'string', Rule::unique('google_sheets', 'url')->ignore($this->route()->parameters['google_sheet']),],
                'associations' => ['required', 'array'],
                'associations.domain' => ['required', 'string', 'not_in:-- not use --'],
                'import' => ['sometimes']
            ];
        }
        return [
            'id' => ['sometimes'],
            'name' => ['required', 'string'],
            'url' => ['required', 'string', 'unique:google_sheets'],
            'associations' => ['required', 'array'],
            'associations.domain' => ['required', 'string', 'not_in:-- not use --'],
            'import' => ['sometimes']
        ];
    }
}
