<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wallet extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'currency',
        'balance',
        'bonus'
    ];

     /**
     * Define a one-to-one relationship between Wallet and User.
     * A wallet belongs to a specific user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Set the balance before saving to the database.
     * This assumes that you store the balance in the database as an integer (e.g., cents).
     * The balance is multiplied by 100 before being stored.
     */
    public function setBalanceAttribute($value)
    {
        $this->attributes['balance'] = $value * 100;
    }

    /**
     * Get the balance from the database and divide it by 100.
     * This converts the stored integer (e.g., cents) back to a float.
     */
    public function getBalanceAttribute($value)
    {
        return $value / 100;
    }

    /**
     * Set the bonus before saving to the database.
     * This assumes that you store the bonus in the database as an integer (e.g., cents).
     * The bonus is multiplied by 100 before being stored.
     */
    public function setBonusAttribute($value)
    {
        $this->attributes['bonus'] = $value * 100;
    }

    /**
     * Get the bonus from the database and divide it by 100.
     * This converts the stored integer (e.g., cents) back to a float.
     */
    public function getBonusAttribute($value)
    {
        return $value / 100;
    }
}
