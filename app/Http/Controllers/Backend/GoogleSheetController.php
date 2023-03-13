<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\GoogleSheet\GoogleSheetRequest;
use App\Models\Domain;
use App\Models\GoogleSheet;
use App\Services\GoogleSheetEntityService;
use App\Services\GoogleSheetService;
use DB;
use Illuminate\Http\Request;

class GoogleSheetController
{
    public function __construct(GoogleSheetService $listService)
    {
    }

    public function index()
    {
        return view('backend.google-sheet.index');
    }

    public function edit(GoogleSheet $googleSheet)
    {
        return view('backend.google-sheet.edit')
            ->withGoogleSheet($googleSheet->load('associations'));
    }

    public function update(GoogleSheetRequest $request, GoogleSheet $googleSheet, GoogleSheetEntityService $service)
    {
        $service->save($request, $googleSheet);

        if ($request->import){
            return redirect()->route('admin.google-sheet.import', $googleSheet)
                ->withFlashSuccess(__('The google sheet was successfully updated.'));
        }
        return redirect()->route('admin.google-sheet.index')
            ->withFlashSuccess(__('The google sheet was successfully updated.'));
    }

    public function create()
    {
        return view('backend.google-sheet.create');
    }

    public function store(GoogleSheetRequest $request)
    {
        GoogleSheet::create($request->validated());

        return redirect()->route('admin.google-sheet.index')
            ->withFlashSuccess(__('The google sheet was successfully created.'));
    }

    public function destroy(GoogleSheet $googleSheet)
    {
        $googleSheet->delete();

        return redirect()->route('admin.google-sheet.index')
            ->withFlashSuccess(__('The google sheet was successfully deleted.'));
    }

    public function loadHeaderGoogleSheet(Request $request, GoogleSheetService $googleSheetService)
    {
        return response()->json([
            'data' => $googleSheetService->load($request->url)->getHeader()
        ]);
    }

    public function importPreview(GoogleSheet $googleSheet, GoogleSheetService $googleSheetService)
    {
        return view('backend.google-sheet.import', [
            'googleSheet' => $googleSheet->load('associations'),
            'googleSheetData' => $googleSheetService->load($googleSheet->url)->getSheet()
        ]);
    }

    public function import(GoogleSheet $googleSheet, Request $request, GoogleSheetService $googleSheetService)
    {
        return $googleSheetService->import($googleSheet, $request);
    }
}
