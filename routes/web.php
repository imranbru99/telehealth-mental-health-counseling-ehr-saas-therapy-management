<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ClientPortalController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Public SaaS Website & Marketing
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/features', [HomeController::class, 'features'])->name('features');
Route::get('/modules', [HomeController::class, 'modules'])->name('modules');
Route::get('/pricing', [HomeController::class, 'pricing'])->name('pricing');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'storeContact'])->name('contact.store');
Route::get('/request-demo', [HomeController::class, 'requestDemo'])->name('demo.request');

// Multilingual Blog & Resources
Route::get('/blog', [HomeController::class, 'blog'])->name('blog.index');
Route::get('/articles', [HomeController::class, 'blog'])->name('blog');
Route::get('/blog/{slug}', [HomeController::class, 'blogShow'])->name('blog.show');
Route::get('/faq', [HomeController::class, 'faq'])->name('faq');

// Compliance & Legal Safeguards
Route::get('/privacy-policy', [HomeController::class, 'privacyPolicy'])->name('privacy');
Route::get('/privacy', [HomeController::class, 'privacyPolicy'])->name('legal.privacy');
Route::get('/terms-and-conditions', [HomeController::class, 'termsConditions'])->name('terms');
Route::get('/terms', [HomeController::class, 'termsConditions'])->name('legal.terms');
Route::get('/cookie-policy', [HomeController::class, 'cookiePolicy'])->name('cookies');
Route::get('/cookies', [HomeController::class, 'cookiePolicy'])->name('legal.cookies');
Route::get('/data-protection', [HomeController::class, 'dataProtection'])->name('data-protection');
Route::get('/legal/data-protection', [HomeController::class, 'dataProtection'])->name('legal.data-protection');
Route::get('/security-information', [HomeController::class, 'securityInfo'])->name('security');
Route::get('/legal/security', [HomeController::class, 'securityInfo'])->name('legal.security');
Route::get('/accessibility-statement', [HomeController::class, 'accessibilityStatement'])->name('accessibility');
Route::get('/legal/accessibility', [HomeController::class, 'accessibilityStatement'])->name('legal.accessibility');

// Public Appointment Booking Engine
Route::get('/book', [BookingController::class, 'index'])->name('booking.index');
Route::post('/book', [BookingController::class, 'store'])->name('booking.store');
Route::get('/book/slots', [BookingController::class, 'slots'])->name('booking.slots');
Route::get('/book/confirmation/{appointment}', [BookingController::class, 'confirmation'])->name('booking.confirmation');

// Invoices & Unicode PDF Generator
Route::get('/invoices/{invoice}/pdf', [InvoiceController::class, 'downloadPdf'])->name('invoices.pdf');

// Authentication & Demo Switcher
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Client Self-Service Portal (Protected)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('portal')->as('portal.')->group(function () {
    Route::get('/', [ClientPortalController::class, 'dashboard'])->name('dashboard');
    Route::get('/appointments', [ClientPortalController::class, 'appointments'])->name('appointments');
    Route::post('/appointments/{appointment}/cancel', [ClientPortalController::class, 'cancelAppointment'])->name('appointments.cancel');
    Route::get('/telehealth/{appointment}', [ClientPortalController::class, 'telehealth'])->name('telehealth');

    Route::get('/invoices', [ClientPortalController::class, 'invoices'])->name('invoices');
    Route::post('/invoices/{invoice}/pay', [ClientPortalController::class, 'payInvoice'])->name('invoices.pay');

    Route::get('/documents', [ClientPortalController::class, 'documents'])->name('documents');
    Route::get('/consents', [ClientPortalController::class, 'consents'])->name('consents');
    Route::post('/consents', [ClientPortalController::class, 'storeConsent'])->name('consents.store');

    Route::get('/messages', [ClientPortalController::class, 'messages'])->name('messages');
    Route::post('/messages', [ClientPortalController::class, 'sendMessage'])->name('messages.send');

    Route::get('/profile', [ClientPortalController::class, 'profile'])->name('profile');
    Route::put('/profile', [ClientPortalController::class, 'updateProfile'])->name('profile.update');
});
