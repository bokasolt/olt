<?php


namespace App\Http\Requests\Backend\Domain;

use App\Models\Domain;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class DomainRequest extends FormRequest
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
            'domain' => ['required', 'string', 'max:255', 'unique:'. Domain::class],
            'niche' => ['nullable', 'string', 'max:255'],
            'lang' => ['nullable', 'string', 'max:32'],
            'title' => ['nullable', 'string', 'max:1023'],
            'ahrefs_dr' => ['nullable', 'integer', 'min:0'],
            'ahrefs_traffic' => ['nullable', 'integer', 'min:0'],
            'linked_domains' => ['nullable', 'integer', 'min:0'],
            'ref_domains' => ['nullable', 'integer', 'min:0'],
            'num_organic_keywords_top_10' => ['nullable', 'integer', 'min:0'],
            'article_by' => ['nullable', 'string', 'max:255'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'sponsored_label' => ['nullable', 'string', 'max:255'],
            'type_of_publication' => ['nullable', 'string', 'max:255'],
            'type_of_link' => ['nullable', 'string', 'max:255'],
            'contact_email' => ['nullable', 'string', 'max:255'],
            'contact_form_link' => ['nullable', 'string'],
            'contact_name' => ['nullable', 'string', 'max:255'],
            'additional_notes' => ['nullable', 'string'],
        ];
    }

    /**
     * Handle a failed authorization attempt.
     *
     * @return void
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    protected function failedAuthorization()
    {
        throw new AuthorizationException(__('You can not edit the Administrator role.'));
    }
}
