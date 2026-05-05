<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Data\Auth\PasswordResetLinkData;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

final class PasswordResetLinkController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Auth/ForgotPassword');
    }

    public function store(PasswordResetLinkData $data): RedirectResponse
    {
        $status = Password::sendResetLink(['email' => $data->email]);

        if ($status !== Password::ResetLinkSent) {
            throw ValidationException::withMessages(['email' => __($status)]);
        }

        return back()->with('status', __($status));
    }
}
