<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ViewController;

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

Route::get('/', [ViewController::class,'index'])->name('persons.index');
Route::get('/persona',[ViewController::class,'show']);
Route::post('/persona',[ViewController::class,'store']);
Route::get('/edit/{id}',[ViewController::class,'edit'])->name('persons.edit');
Route::put('/edit/{id}',[ViewController::class,'update'])->name('persons.update');

Route::delete('/delete/{id}',[ViewController::class,'delete']);