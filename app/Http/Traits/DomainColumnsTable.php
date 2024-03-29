<?php


namespace App\Http\Traits;

use App\Models\Domain;
use App\Export\Column;

trait DomainColumnsTable
{
    public function domainColumns($actions = false): array
    {
        return [
            Column::make(__('DOMAIN'), 'domain')
                ->exportOnly(),
            Column::make(__('DOMAIN'), 'domain')
                ->format(function (Domain $row) use ($actions) {
                    return view('backend.domain.includes.domain', ['domain' => $row, 'actions' => $actions]);
                })
                ->excludeFromExport()
                ->searchable()
                ->sortable(),
        ];
    }

    /**
     * @return array
     */
    public function commonColumns($actions = false): array
    {
        return [
//            Column::make(__('NICHE'), 'niche')
//                ->sortable(),
            Column::make(__('Language'), 'lang', 'LAN')
                ->sortable(),
            Column::make(__('Title of home page'), 'title', 'Title')
                ->sortable(),
            Column::make(__('Ahrefs DR'), 'ahrefs_dr', 'DR')
                ->sortable(),
            Column::make(__('Ahrefs Traffic'), 'ahrefs_traffic', 'Traffic')
                ->sortable(),
            Column::make(__('Linked domains'), 'linked_domains', 'LD')
                ->sortable(),
            Column::make(__('Ref. domains'), 'ref_domains', 'RD')
                ->sortable(),
            Column::make(__('Keywords TOP 10'), 'num_organic_keywords_top_10', 'KW TOP 10')
                ->sortable(),
//            Column::make(__('Article provides by'), 'article_by')
//                ->sortable(),
            Column::make(__('Price'), 'price', 'Price ($)')
                ->sortable(),
            Column::make(__('Sponsored label'), 'sponsored_label', 'SL')
                ->sortable(),
//            Column::make(__('Type of publication'), 'type_of_publication')
//                ->sortable(),
            Column::make(__('Link type'), 'type_of_link', 'LT')
                ->sortable(),
            Column::make(__('Contact email'), 'contact_email', 'Email')
                ->sortable(),
//            Column::make(__('Contact form'), 'contact_form_link', 'CF')
//                ->sortable(),
            Column::make(__('Contact name'), 'contact_name', 'Name')
                ->sortable(),
            Column::make(__('Additional notes'), 'additional_notes', 'Notes')
                ->sortable(),
        ];
    }
}
