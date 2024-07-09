<?php

namespace App\Models;

use Carbon\Carbon;
use Google\Service\Exception;
use Illuminate\Database\Eloquent\Model;
use App\Services\SpreadsheetService\SpreadsheetService;

class ReturnApplication extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'text',
        'google_sheet_token',
    ];

    /**
     * @throws Exception|\Exception
     */
    public static function create(array $attributes = []): static{
        $attributes['google_sheet_token'] = self::createGoogleSpreadsheet($attributes);
        return tap(self::newModelInstance($attributes), function ($instance) {
            $instance->save();
        });
    }

    /**
     * @throws Exception|\Exception
     */
    protected static function createGoogleSpreadsheet(array $attributes = []): string{
        if (!key_exists('first_name', $attributes))
            $attributes['first_name'] = 'undefined';
        if (!key_exists('last_name', $attributes))
            $attributes['last_name'] = 'undefined';

        $tableName = $attributes['first_name'].'_'.$attributes['last_name'].'_'.Carbon::now()->toDateTimeString();

        $spreadsheet = SpreadsheetService::createSpreadsheet([
            'title' => $tableName
        ]);
        SpreadsheetService::simpleWriteData($spreadsheet, $attributes);
        SpreadsheetService::setPermission($spreadsheet, [
            'type' => 'anyone',
            'role' => 'reader'
        ]);
        return $spreadsheet->spreadsheetId;
    }

    public function getSpreadsheetLink(): string{
        return SpreadsheetService::getSpreadsheetLink($this->google_sheet_token);
    }
}
