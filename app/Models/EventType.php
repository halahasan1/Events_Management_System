<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EventType extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    //If you want to show URLs like /events/concert
    public function getSlugAttribute()
    {
        return Str::slug($this->name);
    }
    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
