<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'start_time',
        'end_time',
        'event_type_id',
        'location_id',
        'created_by',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    // Capitalize event title
    protected function title(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => ucfirst($value),
        );
    }
    // Store lowercase title
    protected function setTitle(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => strtolower($value),
        );
    }
    // Only upcoming events
    public function scopeUpcoming($query)
    {
        return $query->where('start_time', '>', now());
    }
    // Events by type
    public function scopeOfType($query, $typeId)
    {
        return $query->where('event_type_id', $typeId);
    }
    
    public function type()
    {
        return $this->belongsTo(EventType::class, 'event_type_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function attendees()
    {
        return $this->belongsToMany(User::class, 'reservations');
    }
}
