<?php

use App\Http\Controllers\AiSearchController;
use Illuminate\Support\Facades\Route;

Route::post('/ai/search', [AiSearchController::class, 'search']);