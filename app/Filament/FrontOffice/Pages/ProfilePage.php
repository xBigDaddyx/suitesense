<?php

namespace App\Filament\FrontOffice\Pages;

use Filament\Pages\Auth\EditProfile;
use Filament\Pages\Page;

class ProfilePage extends Page
{
    protected static string $view = 'filament.front-office.pages.profile-page';
    protected static ?string $navigationIcon = 'heroicon-o-document-text';


    public function getTitle(): string
    {
        return __('Profile Page');
    }

    public function getHeading(): string
    {
        return __('My Profile');
    }

    public function getSubheading(): ?string
    {
        return null;
    }

    public static function getSlug(): string
    {
        return 'profile';
    }

    public static function getNavigationLabel(): string
    {
        return __('Profile');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function getNavigationGroup(): ?string
    {
        return '';
    }
}
