<?php

namespace App\Exports;

use App\Exports\LanguageFilePerSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class LanguageDataSampleSheet implements WithMultipleSheets
{
    use Exportable;


    /**
     * @return array
     */
    public function sheets(): array
    {

        $sheets = [];
        $languages = getAllLanguages(1);
        foreach (getAllLanguagesFiles() as $key => $val) {
            $sheets[] = new LanguageFilePerSheet($key, $val, $languages);
        }
        return $sheets;
    }
}
