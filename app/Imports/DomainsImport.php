<?php

namespace App\Imports;

use App\Models\DomainTempImport;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;

class DomainsImport implements ToModel, WithHeadingRow, WithUpserts, SkipsEmptyRows
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $fields = (new DomainTempImport)->getFillable();

        $map = array_combine($fields, $fields);
//        print_r(array_diff_key($map, $row));
        $map += [
            'language' => 'lang',
            'title_of_home_page_url_parametr_ahrefs' => 'title',
            'title_of_home_page' => 'title',
            'number_of_organic_keywords_top_10' => 'num_organic_keywords_top_10',
            'keywords_top_10'=> 'num_organic_keywords_top_10',
            'article_provides_by' => 'article_by',
            'price_usd' => 'price',
            'link_type' => 'type_of_link',
            'link_to_contact_form' => 'contact_form_link',
            'contact_form' => 'contact_form_link',
            'ahrfes_dr' => 'ahrefs_dr',
        ];

        $vals = [];
        foreach ($map as $k => $f) {
            if (isset($row[$k])) {
                $vals[$f] = trim($row[$k]);
            }
        }
        if (substr($vals['domain'], 0, 7) == 'http://') {
            $vals['domain'] = substr($vals['domain'], 7);
        }
        if (substr($vals['domain'], 0, 8) == 'https://') {
            $vals['domain'] = substr($vals['domain'], 8);
        }
        if (substr($vals['domain'], 0, 4) == 'www.') {
            $vals['domain'] = substr($vals['domain'], 4);
        }
        $vals['domain'] = rtrim($vals['domain']);

        if (empty($vals) || empty($vals['domain'])) {
            return null;
        }

        $vals['price'] = str_replace(',', '.', $vals['price']);
        if (!is_numeric($vals['price'])) {
            return null;
        }

        foreach (['lang', 'niche', 'article_by', 'sponsored_label', 'type_of_publication', 'type_of_link'] as $item) {
            $vals[$item] = strtoupper($vals[$item]);
        }

        return new DomainTempImport($vals);
    }

    public function uniqueBy()
    {
        return 'domain';
    }
}
