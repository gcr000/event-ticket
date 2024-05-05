<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tenants';

    public function users() {
        return $this->hasMany(User::class);
    }

    public function locations() {
        return $this->hasMany(Location::class);
    }

    public function events() {
        return $this->hasMany(Event::class)->orderBy('datetime_from', 'desc');
    }

    public function permissionroles() {
        return $this->hasMany(PermissionRole::class);
    }

    public function bookings() {
        return $this->hasMany(Booking::class)->orderBy('bookings.created_at', 'desc');
    }

    public function last_bookings() {
        return $this->hasMany(Booking::class)->orderBy('bookings.created_at', 'desc')->limit(10);
    }

    public function booking_details() {
        return $this->hasMany(BookingDetail::class)->orderBy('booking_details.created_at', 'desc');
    }
}
