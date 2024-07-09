<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReturnApplicationRequest;
use App\Models\ReturnApplication;
use Carbon\Carbon;
use Google\Service\Exception;
use Revolution\Google\Sheets\Facades\Sheets;

class ReturnApplicationController extends Controller
{
    public function store(ReturnApplicationRequest $request)
    {
        try {
            $returnApplication = ReturnApplication::create($request->validated());
        } catch (Exception|\Exception $e) {
            \Log::error($e->getMessage().' | '.$e->getFile().' | '.$e->getLine());
            //just example of handling error, use Logger Service
            return response('Error occurred when creating spreadsheet', 500);
        }
        return $returnApplication;
    }
}
