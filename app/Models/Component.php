
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Component extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'category',
        'type',
        'code_snippet',
        'preview_html',
        'metadata',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_active' => 'boolean',
        'created_by' => 'integer',
    ];

    protected $withCount = ['purchases'];

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

    public function scopeFree($query)
    {
        return $query->where('type', 'free');
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /** ACCESSORS */

    public function getPriceAttribute()
    {
        return $this->metadata['price'] ?? ($this->type === 'premium' ? 49.99 : 0);
    }

    public function getVersionAttribute()
    {
        return $this->metadata['version'] ?? '1.0.0';
    }
}
