<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Location extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'address'];

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => ucwords($value),
            set: fn ($value) => strtolower($value)
        );
    }


    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }}
