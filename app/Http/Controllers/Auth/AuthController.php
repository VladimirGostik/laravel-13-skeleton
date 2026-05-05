<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Data\Auth\LoginData;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

final class AuthController extends Controller
{
    public function showLogin(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => true,
        ]);
    }

    public function login(LoginData $data): RedirectResponse
    {
        if (! Auth::attempt(['email' => $data->email, 'password' => $data->password, 'is_active' => true], $data->remember)) {
            throw ValidationException::withMessages([
                'email' => __('app.invalid_credentials'),
            ]);
        }

        request()->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
