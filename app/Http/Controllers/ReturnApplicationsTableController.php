<?php

namespace App\Http\Controllers;

use App\Models\ReturnApplication;
use Inertia\Inertia;

class ReturnApplicationsTableController extends Controller
{
    public function index()
    {
        $applications = ReturnApplication::select(['id', 'google_sheet_token', 'created_at'])
            ->where('google_sheet_token', '!=', null)
            ->get();
        $tableData = [];
        /** @var ReturnApplication $application */
        foreach ($applications as $application){
            $tableData[] = [
                'id' => $application->id,
                'link' => $application->getSpreadsheetLink(),
                'created_at' => $application->created_at
            ];
        }
        return Inertia::render('TablePage', [
            'tableData' => $tableData
        ]);
    }
}
