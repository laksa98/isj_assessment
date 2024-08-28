<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Transaction;
use App\Models\Game;

use Illuminate\Database\Eloquent\SoftDeletes;


class Provider extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'status',
    ];
    
    /**
     * Define a one-to-many relationship between the Provider and Transaction models.
     * A provider can be associated with multiple transactions.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Define a one-to-many relationship between the Provider and Game models.
     * A provider can be associated with multiple games.
     */
    public function games()
    {
        return $this->hasMany(Game::class);
    }
}
