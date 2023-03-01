<?php


namespace App\Services;

use App\Imports\DomainsImport;
use App\Models\Domain;
use App\Models\DomainTempImport;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class DomainImportService
{
    public function import($file): void
    {
        DomainTempImport::truncate();

        DB::beginTransaction();

        Excel::import(new DomainsImport(), $file);

        DB::commit();
    }

    public function commit(): int
    {
        $total = DomainTempImport::whereDoesntHave('counterpartDomain')->count();



        $fields = (new DomainTempImport)->getFillable();
        $table = (new Domain)->getTable();
        $select_q = DomainTempImport::query()->select($fields)->toSql();
        $insert_fields = DB::getQueryGrammar()->columnize($fields);

        DB::insert("INSERT IGNORE {$table}({$insert_fields}) {$select_q}");

        //Restore removed domains
        $domainsToRestore = Domain::onlyTrashed()->whereIn('domain', function ($query){
            $query->select('domain')
                ->from('domain_temp_imports');
        })->get();
        foreach ($domainsToRestore as $domain){
            $data = DomainTempImport::whereDomain($domain->domain)->select($fields)->first()->toArray();
            $domain->update($data);
            $domain->restore();
        }
        DomainTempImport::truncate();

        return $total;
    }
}
