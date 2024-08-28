<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Provider;
use App\Models\Game;
use Illuminate\Database\Eloquent\SoftDeletes;


class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'wallet_id',
        'game_id',
        'round_id',
        'amount',
        'reference',
        'provider_id',
        'timestamp',
        'round_details',
        'type',
        'status',
    ];

    /**
     * Define a many-to-one relationship between Transaction and User.
     * A transaction belongs to a specific user.
     */
    public function users()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Define a many-to-one relationship between Transaction and Provider.
     * A transaction is associated with a specific provider.
     */
    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    /**
     * Define a many-to-one relationship between Transaction and Game.
     * A transaction is associated with a specific game.
     */
    public function game()
    {
        return $this->belongsTo(Game::class);
    }

     /**
     * Set the amount before saving to the database.
     * This assumes that you store the amount in the database as an integer (e.g., cents).
     * The amount is multiplied by 100 before being stored.
     */
    public function setAmountAttribute($value)
    {
        // Multiply the amount by 100 before saving to the database
        $this->attributes['amount'] = $value * 100;
    }

     /**
     * Get the amount from the database and divide it by 100.
     * This converts the stored integer (e.g., cents) back to a float.
     */
    public function getAmountAttribute($value)
    {
        // Divide the amount by 100 when retrieving from the database
        return $value / 100;
    }
}
