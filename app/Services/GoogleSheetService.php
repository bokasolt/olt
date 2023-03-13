<?php


namespace App\Services;

use App\Exceptions\GoogleSheet\AccessException;
use App\Exceptions\GoogleSheet\ValidationException;
use App\Models\Domain;
use App\Models\GoogleSheet;
use DB;
use Google_Client;
use Google_Service_Sheets;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GoogleSheetService
{
    private $sheets;
    private $spreadsheetId;
    private Google_Service_Sheets $service;

    /**
     * @throws ValidationException
     * @throws AccessException
     */
    public function load(string $url): GoogleSheetService
    {
        $this->setSheetId($url);

        if(!env('GOOGLE_APPLICATION_CREDENTIALS')) {
            throw new AccessException('GOOGLE_APPLICATION_CREDENTIALS does not exist in .env or is invalid');
        }

        // Create new client
        $client = new Google_Client();
        // Set credentials
        $client->useApplicationDefaultCredentials();

        // Adding an access area for reading, editing, creating and deleting tables
        $client->addScope('https://www.googleapis.com/auth/spreadsheets');

        $this->service = new Google_Service_Sheets($client);

        // get all sheets
        $response = $this->service->spreadsheets->get($this->spreadsheetId);

        $this->sheets = $response->getSheets();

        return $this;
    }

    /**
     * @throws ValidationException
     */
    private function setSheetId(string $url)
    {
        $this->spreadsheetId = stristr(str_replace(
            'spreadsheets/d/',
            '',
            stristr($url, 'spreadsheets/d/')), '/', true);

        if(!$this->spreadsheetId) {
            throw new ValidationException('Invalid url');
        }
    }

    public function getHeader(): array
    {
        return $this->service->spreadsheets_values->get($this->spreadsheetId, $this->getCurrentSheet())->values[0] ?? [];
    }

    public function getSheet(): \Google\Service\Sheets\ValueRange
    {
        return $this->service->spreadsheets_values->get($this->spreadsheetId, $this->getCurrentSheet());
    }

    public function getCurrentSheet()
    {
        return $this->sheets[0]['properties']['title'] ?? null;
    }

    public function import(GoogleSheet $googleSheet, Request $request)
    {
        $entities = $this->getEntities($googleSheet, $request);
        $existing = $this->checkExisting($entities, $request);

        if ($existing->count()) {
            return response()->json([
                'existing' => $existing
            ]);
        }

        DB::beginTransaction();

        try {

            foreach ($entities as $entity) {
                if (isset($request->overwrite)
                    && isset($request->overwrite[$entity['domain']])) {
                    if ($request->overwrite[$entity['domain']] === '1') {
                        Domain::updateOrCreate([
                            'domain' => $entity['domain']
                        ], $entity);
                    }
                } else {
                    Domain::create($entity);
                }
            }

            DB::commit();

            return response()->json([
                'redirect' => route('admin.google-sheet.index'),
                'message' => 'The google sheet was successfully imported.'
            ]);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'message' => 'Transaction error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function getEntities(GoogleSheet $googleSheet, Request $request): array
    {
        $entities = [];
        $googleSheetData = $this->load($googleSheet->url)->getSheet();

        foreach ($request->rows as $row) {
            foreach ($googleSheet->associations as $association) {
                if (isset($googleSheetData[$row][$association['gs_column']])) {
                    $entities[$row][$association['db_column']] = $googleSheetData[$row][$association['gs_column']];
                } else {
                    $this->log[] = 'Column ' . $association['gs_column'] . ' not found in Google Sheet';
                }
            }
        }

        return $entities;
    }

    private function checkExisting($entities, $request)
    {
        $domains = Domain::whereIn('domain', collect($entities)->pluck('domain'))
            ->select('domain');

        if (isset($request->overwrite)) {
            $domains->whereNotIn('domain', array_keys($request->overwrite));
        }

        return $domains->get() ?? false;
    }
}