<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LocaleController extends Controller
{
    public function setLocale(Request $request): JsonResponse
    {
        // set locale to session
        $locale = $request->get('locale'); // TODO validation
        Session::put('locale', $locale);

        return response()->json([
            'locale' => $locale,
        ]);
    }

    public function getLocale(): JsonResponse
    {
        // get locale from session
        $locale = Session::get('locale', 'en');

        return response()->json([
            'locale' => $locale,
        ]);
    }
}
