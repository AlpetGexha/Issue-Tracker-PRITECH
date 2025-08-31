<?php

declare(strict_types=1);

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login;
use Filament\Schemas\Schema;

final class LoginPage extends Login
{
    public function mount(): void
    {
        $this->form->fill([
            'email' => 'user@example.com',
            'password' => 'password',
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
            ]);
    }
}
