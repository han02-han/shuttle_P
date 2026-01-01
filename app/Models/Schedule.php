<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'day_of_week', 
        'pickup_time',  // Waktu jemput pagi
        'dropoff_time', // Waktu antar sore
        'route_id', 
        'driver_id', 
        'shuttle_id'
    ];

    public function route() { 
        return $this->belongsTo(Route::class); 
    }

    public function driver() { 
        return $this->belongsTo(User::class, 'driver_id'); 
    }

    public function shuttle() { 
        return $this->belongsTo(Shuttle::class); 
    }

    public function students() {
        return $this->belongsToMany(Student::class, 'schedule_student');
    }

    // --- TAMBAHAN BARU ---

    /**
     * Relasi ke semua history perjalanan dari jadwal ini
     */
    public function trips() {
        return $this->hasMany(Trip::class);
    }
}