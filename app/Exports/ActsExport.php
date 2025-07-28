<?php

namespace App\Exports;

use App\Models\Act;
use Maatwebsite\Excel\Concerns\FromCollection;

class ActsExport implements FromCollection
{
    public function collection()
    {
        return Act::with('center.department.region')->get();
    }
}
