<?php

namespace App\Filament\Facades;

use App\Filament\Services\CustomerService;
use Illuminate\Support\Facades\Facade;

class CustomerServiceFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return CustomerService::class;
    }
}
