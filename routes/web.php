<?php

    use App\Http\Controllers\BookingController;
    use App\Http\Controllers\EventController;
    use App\Http\Controllers\LocationController;
    use App\Http\Controllers\ProfileController;
    use App\Http\Controllers\UserController;
    use App\Mail\BookingConfirmation;
    use App\Models\Booking;
    use Illuminate\Support\Facades\Mail;
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

Route::get('/', function () {
    return view('dashboard');
})->middleware(['auth', 'verified']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profilo
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Utenti
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::get('users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('users', [UserController::class, 'store'])->name('users.store');
    Route::patch('users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // Events
    Route::get('events', [EventController::class, 'index'])->name('events.index');
    Route::get('events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('events/create', [EventController::class, 'store'])->name('events.store');
    Route::get('events/{event}', [EventController::class, 'show'])->name('events.show');
    Route::get('last_location_events/{location_id}', [EventController::class, 'last_location_events'])->name('events.last_location_events');


    // Locations
    Route::get('locations', [LocationController::class, 'index'])->name('locations.index');
    Route::get('locations/create', [LocationController::class, 'create'])->name('locations.create');
    Route::post('locations/create', [LocationController::class, 'store'])->name('locations.store');
    Route::get('locations/{location}/edit', [LocationController::class, 'edit'])->name('locations.edit');
    Route::post('locations/{location}', [LocationController::class, 'update'])->name('locations.update');

    Route::get('test', function(){

        $booking = Booking::find(16);
        $event = $booking->event;
        // send BookingEmail
        $content = [
            'subject' => 'Conferma prenotazione evento',
            'urlToEvent' => env('APP_URL') . '/bookings/' . base64_encode($event->id),
            'title' => strtoupper($event->name),
            'day' => date('d/m/Y', strtotime($event->datetime_from)),
            'time' => date('H:i', strtotime($event->datetime_from)),
            'where' => $event->location->name . ' - ' . $event->location->address,
            'location_number' => $event->location->phone_number,
            'location_email' => $event->location->email,
            'referente' => $event->location->ref_user_name . ' ' . $event->location->ref_user_email . ' | ' . $event->location->ref_user_phone_number,
            'codes' => $booking->details,
            'show_referente' => $event->show_referente
        ];

        return new BookingConfirmation($content);
    });


});

/*Route::get('bookings/{base64EventId}', function(){
    return view('bookings.index',[]);
})->name('bookings.index');*/

Route::get('bookings/{base64EventId}', [BookingController::class, 'booking'])->name('bookings.index');
Route::post('bookings', [EventController::class, 'store_booking'])->name('bookings.store');
Route::get('bookings/confirmation/{booking_id}', [EventController::class, 'confirmation'])->name('bookings.confirmation');
Route::post('check-otp', [EventController::class, 'check_otp'])->name('bookings.check_otp');

Route::post('/pay', [BookingController::class, 'pay'])->name('booking.pay');
Route::get('/booking/success/{details}/{event_id}/{email}', [BookingController::class, 'success'])->name('booking.success');


require __DIR__.'/auth.php';
