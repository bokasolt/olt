<?php


namespace App\Http\Requests\Backend\GoogleSheet;

use App\Models\GoogleSheet;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LoadHeaderRequest extends FormRequest
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
        if (isset($this->id)) {
            return [
                'url' => ['required', 'string', Rule::unique('google_sheets', 'url')->ignore($this->id),],
            ];
        }
        return [
            'url' => ['required', 'string', 'unique:google_sheets'],
        ];
    }
}
