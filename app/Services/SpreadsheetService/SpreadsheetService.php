<?php

namespace App\Services\SpreadsheetService;

use Revolution\Google\Sheets\Facades\Sheets;
use Google\Service\Sheets\Spreadsheet;

class SpreadsheetService
{
    public static function getSpreadsheetId(string|Spreadsheet $spreadsheet): string{
        if ($spreadsheet instanceof Spreadsheet)
            $spreadsheet = $spreadsheet->spreadsheetId;
        return $spreadsheet;
    }
    /**
     * @throws \Google\Service\Exception
     */
    public static function createSpreadsheet(array $properties = []): Spreadsheet{
        $client = Sheets::getClient();
        $service = new \Google\Service\Sheets($client);
        $spreadsheet = new \Google\Service\Sheets\Spreadsheet([
            'properties' => $properties
        ]);
        return $service->spreadsheets->create($spreadsheet);
    }

    /**
     * @throws \Google\Service\Exception
     */
    public static function setPermission(string|Spreadsheet $spreadsheet, array $permissionProperties = []): void{
        $spreadsheet = self::getSpreadsheetId($spreadsheet);
        $client = Sheets::getClient();
        $driveService = new \Google\Service\Drive($client);
        $permission = new \Google\Service\Drive\Permission($permissionProperties);
        $driveService->permissions->create($spreadsheet, $permission);
    }

    /**
     * @throws \Exception
     */
    public static function simpleWriteData(string|Spreadsheet $spreadsheet, array|string $data): void{
        $spreadsheet = self::getSpreadsheetId($spreadsheet);
        switch (gettype($data)){
            case 'array':
                SpreadsheetWriter::simpleWriteArrayData($spreadsheet, $data);
                break;
            case 'string':
                /**
                 * Just to show how we can handle different types
                 * There can be some object with handling it`s generic class, such as Model,Collection,etc.
                 */
                break;
            default:
                throw new \Exception('Type of $data is not supported');
        }
    }

    public static function getSpreadsheetLink(string|Spreadsheet $spreadsheet): string{
        $spreadsheet = self::getSpreadsheetId($spreadsheet);
        return "https://docs.google.com/spreadsheets/d/$spreadsheet";
    }
}
