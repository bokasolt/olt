<?php


namespace App\Services;

use App\Models\ConfigList;
use App\Models\Domain;

class ConfigListService
{
    public function buildList(?Domain $domain = null): array
    {
        $lists = ConfigList::all()
            ->groupBy('list')
            ->map(function ($item) {
                return $item->pluck('value', 'option');
            })
            ->toArray();

        if ($domain) {
            foreach (['lang', 'niche', 'article_by', 'sponsored_label', 'type_of_publication', 'type_of_link'] as $item) {
                if (!isset($lists[$item][strtoupper($domain->{$item})])) {
                    $lists[$item] = array_merge([strtoupper($domain->{$item}) => $domain->{$item}], $lists[$item]);
                }
            }
        } else {
           $this->addDefault($lists);
        }

        return $lists;
    }

    protected function addDefault(array &$lists)
    {
        $lists['lang'] = array_merge(['' => ''], $lists['lang']);
        $lists['niche'] = array_merge(['' => ''], $lists['niche']);
//        $lists['article_by'] = array_merge(['' => ''], $lists['article_by']);
//        $lists['sponsored_label'] = array_merge(['' => ''], $lists['sponsored_label']);
//        $lists['type_of_publication'] = array_merge(['' => ''], $lists['type_of_publication']);
    }
}