<?php

namespace App\Http\Controllers\Backend;

use App\Models\DomainTempImport;
use App\Services\DomainImportService;
use Illuminate\Http\Request;

class DomainImportController
{
    public function index()
    {
        $total = DomainTempImport::count();
        $toImportCount = DomainTempImport::whereDoesntHave('counterpartDomain')->count();
        return view('backend.import')
            ->withTotal($total)
            ->withToImportCount($toImportCount);
    }

    public function import(Request $request, DomainImportService $domain_service)
    {
        $request->validate([
            'file' => 'required',
        ]);

        $domain_service->import($request->file('file'));

        return back();
    }

    public function commit(Request $request, DomainImportService $domain_service)
    {
        $total = DomainTempImport::count();
        $importedCount = $domain_service->commit();

        return redirect(route('admin.dashboard'))
            ->withFlashSuccess('Imported ' . $importedCount . ' domains. Skipped ' . ($total - $importedCount).'.');
    }
}
