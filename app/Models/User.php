<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username', // Sesuai migration
        'photo',    // <--- SUDAH DITAMBAHKAN
        'email',
        'password',
        'phone',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // --- RELASI ---

    // Relasi ke Siswa (Jika User adalah Parent)
    public function students() { 
        return $this->hasMany(Student::class, 'parent_id'); 
    }

    // Relasi ke Profil Driver (Jika User adalah Driver)
    public function driverProfile() { 
        return $this->hasOne(Driver::class, 'user_id'); 
    }

    // --- RELASI JADWAL (PENTING) ---
    // Agar bisa dipanggil: $user->schedules
    // Digunakan untuk filter "Driver Jomblo" di ScheduleController
    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'driver_id');
    }
}