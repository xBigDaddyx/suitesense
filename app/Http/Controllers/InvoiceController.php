<?php

namespace App\Http\Controllers;

use App\Models\Vendor\Invoice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\Browsershot\Browsershot;
use Spatie\LaravelPdf\Enums\Format;
use Spatie\LaravelPdf\Enums\Unit;

use function Spatie\LaravelPdf\Support\pdf;

class InvoiceController extends Controller
{
    public function view($record)
    {
        $record = Invoice::findOrFail($record);
        return pdf()
            ->format(Format::A4)
            ->view('filament.front-office.pages.view-invoice', compact('record'))
            ->name($record->number . Carbon::now()->format('dmyHis') . '.pdf');


        // return view('filament.front-office.pages.view-invoice')->with('record', $record);
    }
}
