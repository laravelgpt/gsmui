
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'type',
        'value',
        'label',
        'group',
        'description',
    ];

    protected $casts = [
        'type' => 'string',
        'group' => 'string',
    ];

    public $timestamps = true;
}
