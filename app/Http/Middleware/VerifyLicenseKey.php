<?php

namespace App\Http\Middleware;

use App\Models\Vendor\License;
use Closure;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VerifyLicenseKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $url = parse_url(url(''))['host'];

        // Ambil license key dari konfigurasi atau dari environment variable
        $licenseKey = auth()->user()->latestHotel->subscriptions->where('is_active', true)->first()->licenses?->where('is_active', true)->first()?->key;
        $loginRoute = '';
        switch ($url) {
            case 'manage.suitify.cloud':
                $loginRoute = 'filament.manage.auth.login';
                break;
            case 'admin.suitify.cloud':
                $loginRoute = 'filament.admin.auth.login';
                break;
            case '127.0.0.1':
                $loginRoute = 'filament.manage.auth.login';
                break;
        }
        if (!$licenseKey) {
            if (Auth::check()) {
                Auth::logout();
            }
            Notification::make()
                ->title('Missing license key')
                ->body('License key is required to access this panel.')
                ->danger()
                ->color('danger')
                ->send();
            return redirect()->route($loginRoute)->withErrors([
                'error' => 'License key is required to access this panel.',
            ]);
        }

        // Pisahkan key dan checksum
        $keyParts = explode('-', $licenseKey);
        if (count($keyParts) < 6) {
            if (Auth::check()) {
                Auth::logout();
            }
            Notification::make()
                ->title('Wrong License key')
                ->body('Invalid license key format.')
                ->danger()
                ->color('danger')
                ->send();
            return redirect()->route($loginRoute)->withErrors([
                'error' => 'Invalid license key format.',
            ]);
        }

        // Pisahkan checksum dari key
        $checksum = array_pop($keyParts);
        $keyWithoutChecksum = implode('-', $keyParts);

        // Validasi checksum
        $generatedChecksum = License::generateChecksum($keyWithoutChecksum);
        if ($generatedChecksum !== $checksum) {
            if (Auth::check()) {
                Auth::logout();
            }
            Notification::make()
                ->title('Wrong License key')
                ->body('Invalid license key checksum.')
                ->danger()
                ->color('danger')
                ->send();
            return redirect()->route($loginRoute)->withErrors([
                'error' => 'Invalid license key checksum.',
            ]);
        }

        // Cari lisensi di database
        $license = License::where('key', $licenseKey)->first();

        if (!$license || !$license->isValid()) {
            if (Auth::check()) {
                Auth::logout();
            }
            Notification::make()
                ->title('License key status')
                ->body('License key is expired or inactive.')
                ->danger()
                ->color('danger')
                ->send();
            return redirect()->route($loginRoute)->withErrors([
                'error' => 'License key is expired or inactive.',
            ]);
        }

        // Lisensi valid, lanjutkan ke request berikutnya
        return $next($request);
    }
}
