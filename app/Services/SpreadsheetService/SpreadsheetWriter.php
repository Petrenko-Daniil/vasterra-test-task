<?php
namespace App\Services\SpreadsheetService;
use Google\Service\Sheets\Spreadsheet;
use Revolution\Google\Sheets\Facades\Sheets;

class SpreadsheetWriter
{
    public static function simpleWriteArrayData(string|Spreadsheet $spreadsheet, array $data): void{
        $spreadsheet = SpreadsheetService::getSpreadsheetId($spreadsheet);
        $values = collect($data)->map(function ($value, $key) {
            return [$key, $value];
        })->values()->toArray();
        $range = 'Sheet1!A1:B' . count($values);
        Sheets::spreadsheet($spreadsheet)
            ->range($range)
            ->update($values);
    }
    public static function simpleWriteStringData(string|Spreadsheet $spreadsheet, string $data): void{
        //TODO: implement writing string to spreadsheet
        throw new \Exception('Not implemented method');
    }
}
