<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PorterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

// ================================================================ Public
Route::get('/', [HomeController::class, 'index'])->name('home');

// ================================================================ Auth
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ================================================================ Dashboard router (all roles)
Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard')->middleware('auth');

// ================================================================ Profile (all authenticated users)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.password');
});

// ================================================================ Student
Route::middleware(['auth', 'role:STUDENT'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
    Route::get('/submit-complaint', [StudentController::class, 'showSubmit'])->name('submit');
    Route::post('/submit-complaint', [StudentController::class, 'store'])->name('store');
    Route::get('/complaint/{id}', [StudentController::class, 'show'])->name('complaint');
    Route::get('/complaint/{id}/rate', [StudentController::class, 'showRate'])->name('rate');
    Route::post('/complaint/{id}/rate', [StudentController::class, 'rate']);
});

// ================================================================ Porter
Route::middleware(['auth', 'role:PORTER'])->prefix('porter')->name('porter.')->group(function () {
    Route::get('/dashboard', [PorterController::class, 'dashboard'])->name('dashboard');
    Route::match(['GET', 'POST'], '/complaint/{id}', [PorterController::class, 'show'])->name('complaint');
});

// ================================================================ Administrator
Route::middleware(['auth', 'role:ADMIN'])->prefix('control')->name('admin.')->group(function () {
    // Dashboard & complaints
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::match(['GET', 'POST'], '/complaint/{id}', [AdminController::class, 'showComplaint'])->name('complaint');

    // Users
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/add', [AdminController::class, 'showUserForm'])->name('users.add');
    Route::post('/users/add', [AdminController::class, 'saveUser'])->name('users.store');
    Route::get('/users/{id}/edit', [AdminController::class, 'showUserForm'])->name('users.edit');
    Route::post('/users/{id}/edit', [AdminController::class, 'saveUser'])->name('users.update');
    Route::post('/users/{id}/action', [AdminController::class, 'userAction'])->name('users.action');

    // Hostels
    Route::get('/hostels', [AdminController::class, 'hostels'])->name('hostels');
    Route::get('/hostels/add', [AdminController::class, 'showHostelForm'])->name('hostels.add');
    Route::post('/hostels/add', [AdminController::class, 'saveHostel'])->name('hostels.store');
    Route::get('/hostels/{id}/edit', [AdminController::class, 'showHostelForm'])->name('hostels.edit');
    Route::post('/hostels/{id}/edit', [AdminController::class, 'saveHostel'])->name('hostels.update');
    Route::post('/hostels/{id}/toggle', [AdminController::class, 'toggleHostel'])->name('hostels.toggle');

    // Categories
    Route::get('/categories', [AdminController::class, 'categories'])->name('categories');
    Route::get('/categories/add', [AdminController::class, 'showCategoryForm'])->name('categories.add');
    Route::post('/categories/add', [AdminController::class, 'saveCategory'])->name('categories.store');
    Route::get('/categories/{id}/edit', [AdminController::class, 'showCategoryForm'])->name('categories.edit');
    Route::post('/categories/{id}/edit', [AdminController::class, 'saveCategory'])->name('categories.update');
    Route::post('/categories/{id}/toggle', [AdminController::class, 'toggleCategory'])->name('categories.toggle');
});
