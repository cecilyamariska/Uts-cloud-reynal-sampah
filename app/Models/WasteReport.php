<?php

namespace App\Models;

use Throwable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class WasteReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'location',
        'latitude',
        'longitude',
        'photo_path',
        'photo_disk',
        'status',
        'reported_at',
    ];

    protected function casts(): array
    {
        return [
            'reported_at' => 'datetime',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
        ];
    }

    public function photoUrl(): ?string
    {
        if (! $this->photo_path || ! $this->photo_disk) {
            return null;
        }

        try {
            return Storage::disk($this->photo_disk)->url($this->photo_path);
        } catch (Throwable) {
            return null;
        }
    }
}
