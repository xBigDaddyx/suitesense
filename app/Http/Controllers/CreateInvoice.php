<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Xendit\Configuration;
use Xendit\Invoice\CreateInvoiceRequest;
use Xendit\Invoice\InvoiceApi;

class CreateInvoice extends Controller
{
    public function __construct()
    {
        Configuration::setXenditKey(env('XENDIT_SECRET_KEY'));
    }
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $createInvoice = new CreateInvoiceRequest([
            'external_id' => $request->input('external_id'),
            'amount' => $request->input('amount')
        ]);
        $apiInstance = new InvoiceApi();
        $generateInvoice = $apiInstance->createInvoice($createInvoice);
        return dd($generateInvoice);
    }
}
