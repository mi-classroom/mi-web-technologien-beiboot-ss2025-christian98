<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class EditorController extends Controller
{
    public function show(): Response
    {
        return Inertia::render('Editor', []);
    }
}
