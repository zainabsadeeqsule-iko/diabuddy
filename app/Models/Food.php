<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'food_class',
        'sugar_content',
    ];

    /**
     * Get the users associated with the food.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_foods');
    }
}
