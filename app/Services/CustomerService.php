<?php

namespace App\Filament\Services;

use App\Models\Customer;
use App\Models\User;
use Filament\Notifications\Notification;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CustomerService
{

    public function createCustomer(array $data): User
    {
        // my logics you don't need it
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'status' => 'active',
        ]);
        $user->assignRole('Customer');
        $user->givePermissionTo('customer');

        $data['user_id'] = $user->id;

        $customer = User::create($data);
        // the line below is important
        $this->sendEmailReminder($customer);

        return $customer;
    }

    public function setValidatedEmail(Request $request): RedirectResponse
    {
        $user = User::findOrFail($request->id);
        if (is_null($user->email_verified_at)) {
            $user->email_verified_at = now();
            $user->save();
            $user->customer()->update([
                'state' => 1
            ]);
        }

        if (!Auth::loginUsingId($user->id)) {
            abort(403);
        }

        return redirect()->to('/admin/login');
    }

    public function sendEmailReminder(User $customer, $toNotify = false): void
    {
        try {
            // this is important!
            $customer->user->notify(new VerifyEmailNotification());
            // my custom logics
            if ($toNotify) {
                Notification::make()
                    ->title('Avviso')
                    ->body('Email di notifica Attivazione Account inviata')
                    ->success()
                    ->send();
            }
        } catch (\Exception $e) {
            Notification::make()
                ->title('Errore durante la richiesta')
                ->body($e->getMessage())
                ->danger()
                ->persistent()
                ->send();
        }
    }
}
