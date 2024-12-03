<?php

namespace App\Filament\FrontOffice\Resources\InvoiceResource\Pages;

use App\Filament\FrontOffice\Resources\InvoiceResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateInvoice extends CreateRecord
{
    protected static string $resource = InvoiceResource::class;
}
