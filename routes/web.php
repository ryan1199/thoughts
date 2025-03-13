<?php

use App\Livewire\Thought\Index;
use App\Livewire\Thought\Show;
use App\Livewire\User\Show as UserShow;
use App\Livewire\Welcome;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', Index::class);
Route::get('/{thought:slug}', Show::class)->name('thoughts.show');
Route::get('/users/{user:slug}', UserShow::class)->name('users.show');
