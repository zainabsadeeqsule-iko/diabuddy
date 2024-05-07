<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserFood extends Model
{
    protected $fillable = [
        'user_id',
        'food_id',
    ];

    /**
     * Get the user associated with the user food.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the food associated with the user food.
     */
    public function food()
    {
        return $this->belongsTo(Food::class);
    }
}
