<?php

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\SprintController;
use App\Http\Controllers\EpicController;
use App\Http\Controllers\ProjectMemberController;

//email de verif
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Notifications\VerifyEmail;
use App\Models\User;

// Ces routes doivent être AVANT require __DIR__.'/auth.php';
Route::get('/connexion', function () {
    return redirect()->route('login');
})->name('connexion');

Route::get('/inscription', function () {
    return redirect()->route('register');
})->name('inscription');

// Page pour vérifier le mail
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

// Lien de vérification envoyé par email
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');

// Relancer l'email de vérification
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('status', 'verification-link-sent');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::get('/simulate-mail', function () {
    $user = auth()->user();

    if (!$user) {
        return redirect()->route('login')->with('message', 'Connectez-vous pour simuler le mail.');
    }

    $notification = new \Illuminate\Auth\Notifications\VerifyEmail();
    $mail = $notification->toMail($user);

    return $mail->render();
})->middleware('auth');

// TOUTES les routes protégées par auth ET verified
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Gestion des membres
    Route::post('/projects/{project}/members', [ProjectMemberController::class, 'store'])
        ->name('projects.members.store');
    Route::patch('/projects/{project}/members/{user}', [ProjectMemberController::class, 'update'])
        ->name('projects.members.update');
    Route::delete('/projects/{project}/members/{user}', [ProjectMemberController::class, 'destroy'])
        ->name('projects.members.destroy');

    // Projets et ressources liées
    Route::resource('projects', ProjectController::class);
    Route::get('projects/{project}/roadmap', [ProjectController::class, 'roadmap'])->name('projects.roadmap');

    Route::scopeBindings()->group(function () {
        Route::resource('projects.sprints.epics', EpicController::class);
        Route::resource('projects.sprints', SprintController::class);
        Route::resource('projects.sprints.epics.tasks', TaskController::class);
    });

    // Recherche de tâches
    Route::get('/tasks/search', function () {
        return view('tasks.search');
    })->name('tasks.search');

    Route::patch('/tasks/{task}/update-status', [TaskController::class, 'updateStatus']);

    // Notifications
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
    Route::delete('/notifications/{id}', [App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');
});

require __DIR__.'/auth.php';
