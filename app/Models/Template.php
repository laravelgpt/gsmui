<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Template extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'type',
        'preview_html',
        'thumbnail_path',
        'metadata',
        'download_count',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_active' => 'boolean',
        'download_count' => 'integer',
        'created_by' => 'integer',
    ];

    /** RELATIONSHIPS */

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function purchases()
    {
        return $this->morphMany(Purchase::class, 'purchasable');
    }

    /** SCOPES */

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePremium($query)
    {
        return $query->where('type', 'premium');
    }

    /** ACCESSORS */

    public function getPriceAttribute()
    {
        return $this->metadata['price'] ?? 99.99;
    }

    public function getFeaturesAttribute()
    {
        return $this->metadata['features'] ?? [];
    }
}
