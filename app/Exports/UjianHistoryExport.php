<?php
namespace App\Exports;

use App\Models\UjianHistory;
use Maatwebsite\Excel\Concerns\FromCollection;

class UjianHistoryExport implements FromCollection
{
    public function collection()
    {
        return UjianHistory::all();
    }
}
