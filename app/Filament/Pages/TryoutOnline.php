<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class TryoutOnline extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.tryout-online';

    public $id;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public function mouunt($id)
    {
        $this->id = $id;
    }
}
