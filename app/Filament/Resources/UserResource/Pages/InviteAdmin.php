<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Mail\InviteAdminMail;
use App\Models\User;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Filament\Notifications\Notification;
use Illuminate\Validation\ValidationException;

class InviteAdmin extends Page
{
    use InteractsWithForms;

    protected static string $resource = UserResource::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    protected static ?string $title = 'Nodig een admin uit';
    protected static string $view = 'filament.resources.user-resource.pages.invite-admin';

    public ?string $name = '';
    public ?string $email = '';

    public static function shouldRegisterNavigation(array $parameters = []): bool
    {
        return Auth::check() && Auth::user()->role === 'super_admin';
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Naam')
                    ->maxLength(255)
                    ->placeholder('Optioneel – enkel voor nieuwe gebruikers'),

                TextInput::make('email')
                    ->label('E-mailadres')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Voer het e-mailadres van de admin in'),
            ]);
    }

    public function submit(): void
    {
        $user = User::where('email', $this->email)->first();

        if ($user) {
            // ✅ Bestaande gebruiker: rol naar admin
            if ($user->role !== 'admin') {
                $user->update(['role' => 'admin']);

                Notification::make()
                    ->title('Gebruiker gepromoveerd')
                    ->body($user->email . ' is nu admin.')
                    ->success()
                    ->send();
            } else {
                Notification::make()
                    ->title('Reeds admin')
                    ->body($user->email . ' had al de rol "admin".')
                    ->info()
                    ->send();
            }
        } else {
            // ✅ Nieuwe gebruiker: naam is verplicht
            if (blank($this->name)) {
                throw ValidationException::withMessages([
                    'name' => 'Naam is verplicht voor een nieuwe gebruiker.',
                ]);
            }

            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'role' => 'admin',
                'password' => '', // Wordt gezet via registratieformulier
            ]);

            $url = URL::temporarySignedRoute(
                'admin.register',
                now()->addHours(24),
                ['user' => $user->id]
            );

            Mail::to($user->email)->send(new InviteAdminMail($url));

            Notification::make()
                ->title('Nieuwe admin uitgenodigd')
                ->body('Uitnodiging verstuurd naar ' . $user->email)
                ->success()
                ->send();
        }

        $this->reset(['name', 'email']);
    }
}
