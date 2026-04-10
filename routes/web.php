<?php

use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BonusController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminBonusController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\PreservationController;
use App\Http\Controllers\MesombWebhookController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\WithdrawalInfoController;
use App\Http\Controllers\AdminTransactionController;

/*
|--------------------------------------------------------------------------
| Routes publiques (sans authentification)
|--------------------------------------------------------------------------
*/
Route::get('/', [AuthController::class, 'index'])->name('index');

// Inscription
Route::get('/register', [AuthController::class, 'index'])
    ->name('register.view')
    ->middleware('guest');

Route::post('/register', [AuthController::class, 'register'])
    ->name('register')
    ->middleware('guest');

// Connexion
Route::get('/login', function () {
    return view('login');
})->name('login')->middleware('guest');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.post')
    ->middleware('guest');

/*
|--------------------------------------------------------------------------
| Routes protégées (authentification requise)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Tableau de bord
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Produits
    Route::get('/products', [ProduitController::class, 'index'])
        ->name('products');
    Route::post('/products/{id}', [ProduitController::class, 'acheter'])
        ->name('produits.acheter');
    Route::get('/mes-produits', [ProduitController::class, 'MesProduits'])
        ->name('Mesproduits');

    // Partage / lien d’invitation
    Route::get('/share', [UserController::class, 'monlien'])
        ->name('share');

    // Compte utilisateur
    Route::get('/account', [DashboardController::class, 'account'])
        ->name('account');
    Route::put('/mon-compte/preferences-email', [ProfileController::class, 'updateEmailPreferences'])->name('profile.updateEmailPreferences');
    // Déconnexion
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    Route::get('/team', [UserController::class, 'team'])
    ->name('team');
    Route::get('/presentation', function () {
    return view('presentation');
})->name('presentation');
    Route::get('/contact',function(){
        return view('contact');
    })->name('contact');

Route::get('/deposit', [TransactionController::class, 'deposit'])->name('deposit');
Route::post('/depot', [TransactionController::class, 'storeDepot'])->name('depot.store');

Route::get('/retrait', [TransactionController::class, 'retrait'])->name('retrait');
Route::post('/withdrawal', [TransactionController::class, 'storeRetrait'])->name('retrait.store');
Route::post('/withdrawal/preview',[TransactionController::class,'preview'])->name('retrait.preview');

// Page Fond de Préservation (liste des produits)
Route::get('/fond-preservation', [PreservationController::class, 'index'])
    ->name('fond.index');

// Action : épargner dans un produit
Route::post('/fond-preservation/{id}/epagner', [PreservationController::class, 'epagner'])
    ->name('fond.epagner');
Route::get('/fond-preservation/mesepargnes',[PreservationController::class,'mesEpargnes'])->name('mes.epargnes');

    // Compte utilisateur
Route::get('/account',[ProfileController::class,'index'])->name('profile');
Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');


Route::get('/transactions',[TransactionController::class,"index"])->name('transaction');

Route::get('/messages',[MessageController::class,'index'])->name('messages');
Route::post('/messages/vue',[MessageController::class,'markAllAsRead'])->name('messages.markAllAsRead');
Route::get('/retrait/info',[WithdrawalInfoController::class,"index"])->name('withdraw_info');
Route::put('/retrait/info/update',[WithdrawalInfoController::class,'update'])->name('withdrawal.update');

// Reclamation du bonus
Route::get('/bonus', [BonusController::class, 'index'])->name('bonus.code');
Route::post('/bonus/reclamer', [BonusController::class, 'reclamer'])->name('bonus.reclamer');

Route::prefix('admin')->middleware(['admin' => \App\Http\Middleware\IsAdmin::class])->group(function () {
    Route::get('/bonus', [AdminBonusController::class, 'index'])->name('admin.bonus.index');
    Route::get('/bonus/create', [AdminBonusController::class, 'create'])->name('admin.bonus.create');
    Route::post('/bonus/store', [AdminBonusController::class, 'store'])->name('admin.bonus.store');
    Route::post('/bonus/toggle/{id}', [AdminBonusController::class, 'toggle'])->name('admin.bonus.toggle');
});

Route::post('/mesomb/webhook', [App\Http\Controllers\MesombWebhookController::class, 'handle'])
     ->name('mesomb.webhook');

//Partie Admin 

Route::prefix('admin')->middleware(['admin' => IsAdmin::class])->group(function () {
    Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/{id}/show', [AdminUserController::class, 'show'])->name('admin.users.show');
    Route::get('/users/{id}/edit', [AdminUserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{id}', [AdminUserController::class, 'update'])->name('admin.users.update');
    Route::post('/users/{id}/revenu', [AdminUserController::class, 'augmenterRevenu'])->name('admin.users.revenu');
    Route::post('/users/{id}/reset-password',[AdminUserController::class,'resetPassword'])->name('admin.users.reset-password');
    Route::get('/transactions', [AdminTransactionController::class, 'index'])->name('admin.transactions');
});

Route::prefix('admin')->middleware(['admin' => IsAdmin::class])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::post('/users/{id}/bonus', [AdminUserController::class, 'addBonus'])->name('admin.users.bonus');
    Route::patch('/users/{id}/ban', [AdminUserController::class, 'ban'])->name('admin.users.ban');
    Route::patch('/users/{id}/unban', [AdminUserController::class, 'unban'])->name('admin.users.unban');
});

Route::middleware('auth')->group(function () {
    Route::post('/payment/notchpay/initiate', [PaymentController::class, 'initiate'])->name('payment.notchpay.initiate');
});

// Webhook (public, sans auth)
Route::post('/payment/notchpay/webhook', [PaymentController::class, 'webhook'])->name('payment.notchpay.webhook');

});