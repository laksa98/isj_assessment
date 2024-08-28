<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
Use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Support\Facades\Hash;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

     /**
     * Define a one-to-one relationship between User and Wallet.
     * A user has one wallet associated with their account.
     */
    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    /**
     * Define a one-to-many relationship between User and Transactions.
     * A user can have multiple transactions.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = (!empty($value)) ? Hash::make($value) : $this->password;
    }
}
