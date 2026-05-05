<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Data\Language\LanguageSwitchData;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

final class LanguageController extends Controller
{
    public function switch(Request $request, string $locale): RedirectResponse
    {
        $data = LanguageSwitchData::validateAndCreate(['locale' => $locale]);

        app()->setLocale($data->locale);
        $request->session()->put('locale', $data->locale);

        $user = $request->user();
        if ($user instanceof User) {
            $user->locale = $data->locale;
            $user->save();
        }

        Cookie::queue('locale', $data->locale, 60 * 24 * 30);

        return back()->with('flash.info', __('app.language_changed'));
    }
}
