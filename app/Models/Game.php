<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\GameDetail;

class Game extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'provider_id', 'provider_game_id','name','status',
        'type_id', 'type_description',
        'technology', 'platform', 'demo', 'aspect_ratio',
        'technology_id', 'game_id_numeric', 'frb_available', 'variable_frb_available',
        'lines', 'data_type', 'jurisdictions', 'features',
    ];

     // Specifies the data types for certain attributes
    // 'jurisdictions' and 'features' will automatically be cast to and from arrays
    protected $casts = [
        'jurisdictions' => 'array',
        'features' => 'array',
    ];

     /**
     * Define a one-to-many relationship between the Game model and the Transaction model.
     * A game can have multiple transactions associated with it.
     */
    public function transaction()
    {
        return $this->hasMany(Transaction::class);
    }
}
