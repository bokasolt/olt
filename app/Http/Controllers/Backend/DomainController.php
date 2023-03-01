<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\Domain\DomainRequest;
use App\Http\Requests\Backend\Domain\DomainUpdateRequest;
use App\Models\Domain;
use App\Services\ConfigListService;

class DomainController
{
    public function edit(ConfigListService $listService, Domain $domain)
    {
        $lists = $listService->buildList($domain);

        return view('backend.domain.edit')
            ->withDomain($domain)
            ->withLists($lists);
    }

    public function update(DomainUpdateRequest $request, Domain $domain)
    {
        $vals = $request->validated();
        $vals = $this->normalizeDomain($vals);
        $domain->update($vals);

        return redirect()->route('admin.dashboard')
            ->withFlashSuccess(__('The domain was successfully updated.'));
    }

    public function create(ConfigListService $listService)
    {
        $lists = $listService->buildList();

        return view('backend.domain.create')
            ->withLists($lists);
    }

    public function store(DomainRequest $request)
    {
        Domain::create($request->validated());

        return redirect()->route('admin.dashboard')
            ->withFlashSuccess(__('The domain was successfully created.'));
    }

    public function destroy(Domain $domain)
    {
        $domain->delete();

        return redirect()->route('admin.dashboard')
            ->withFlashSuccess(__('The domain was successfully deleted.'));
    }

    protected function normalizeDomain(array $vals)
    {
        if (substr($vals['domain'], 0, 7) == 'http://') {
            $vals['domain'] = substr($vals['domain'], 7);
        }
        if (substr($vals['domain'], 0, 8) == 'https://') {
            $vals['domain'] = substr($vals['domain'], 8);
        }
        if (substr($vals['domain'], 0, 4) == 'www.') {
            $vals['domain'] = substr($vals['domain'], 4);
        }
        if (strpos($vals['domain'], '/') !== false) {
            $vals['domain'] = strstr($vals['domain'], '/', true);
        }
        if (strpos($vals['domain'], '?') !== false) {
            $vals['domain'] = strstr($vals['domain'], '?', true);
        }
        $vals['domain'] = rtrim($vals['domain']);

        return $vals;
    }
}
