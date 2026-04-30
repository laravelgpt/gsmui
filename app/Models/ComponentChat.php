<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ComponentChat extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'parent_id',
        'message',
        'context',
        'type',
        'category',
        'template_category',
        'template_data',
        'attachment_path',
        'metadata',
        'is_active',
        'sort_order',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'metadata' => 'array',
        'template_data' => 'array',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<string>
     */
    protected $hidden = [
        'attachment_path',
    ];

    /**
     * Default attributes
     */
    protected $attributes = [
        'is_active' => true,
        'type' => 'user',
        'category' => 'general',
    ];

    /**
     * Get the parent chat.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(ComponentChat::class, 'parent_id');
    }

    /**
     * Get the child responses.
     */
    public function children(): HasMany
    {
        return $this->hasMany(ComponentChat::class, 'parent_id');
    }

    /**
     * Get the user that owns the chat.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for active chats only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for user chats
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for suggestion type
     */
    public function scopeSuggestions($query)
    {
        return $query->where('category', 'suggestion');
    }

    /**
     * Scope for type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Get attachment URL
     */
    public function getAttachmentUrlAttribute()
    {
        if (!$this->attachment_path) {
            return null;
        }
        return asset('storage/' . $this->attachment_path);
    }

    /**
     * Check if this is a recording
     */
    public function getIsRecordingAttribute()
    {
        return $this->type === 'recording' && 
               isset($this->metadata['status']) && 
               $this->metadata['status'] === 'recording';
    }

    /**
     * Get recording duration
     */
    public function getRecordingDurationAttribute()
    {
        if (!$this->is_recording && isset($this->metadata['duration'])) {
            return $this->metadata['duration'];
        }
        return 0;
    }
}
