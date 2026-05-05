<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Data\Profile\ChangePasswordData;
use App\Data\Profile\ProfileUpdateData;
use App\Models\User;
use App\Services\ProfileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final class ProfileController extends Controller
{
    public function __construct(private readonly ProfileService $service) {}

    public function show(Request $request): Response
    {
        /** @var User $user */
        $user = $request->user();

        return Inertia::render('Profile/Show', [
            'user' => [
                'id' => $user->getKey(),
                'name' => $user->name,
                'email' => $user->email,
                'locale' => $user->locale,
            ],
        ]);
    }

    public function update(ProfileUpdateData $data, Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $this->service->updateProfile($user, $data);

        return back()->with('flash.success', __('app.profile_updated'));
    }

    public function changePassword(ChangePasswordData $data, Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $this->service->changePassword($user, $data);

        return back()->with('flash.success', __('app.password_changed'));
    }
}
