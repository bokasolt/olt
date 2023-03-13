<?php


namespace App\Services;

use App\Http\Requests\Backend\GoogleSheet\GoogleSheetRequest;
use App\Models\GoogleSheet;

class GoogleSheetEntityService
{
    public function save(GoogleSheetRequest $request, GoogleSheet $googleSheet): GoogleSheet
    {
        $googleSheet->update($request->validated());

        $googleSheet->associations()->delete();

        foreach ($request->associations as $dbColumn => $gsColumn) {
            if(!is_null($gsColumn) && $gsColumn !== '-- not use --') {
                $googleSheet->associations()->create([
                    'gs_column' => $gsColumn,
                    'db_column' => $dbColumn
                ]);
            }
        }

        return $googleSheet;
    }
}