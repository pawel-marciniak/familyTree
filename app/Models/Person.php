<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Kalnoy\Nestedset\NodeTrait;

class Person extends Model
{
    use HasFactory, NodeTrait;

    protected $guarded = ['id'];

    protected $table = 'persons';

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(self::class, 'partner_id');
    }
}
