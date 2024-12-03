<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Login as AuthLogin;
use Filament\Pages\Page;
use Filament\Pages\SimplePage;

class Login extends AuthLogin
{
    protected static string $view = 'filament.manage.pages.login';
}
