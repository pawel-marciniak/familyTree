<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
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

    public function partner(): HasOne
    {
        return $this->hasOne(self::class, 'partner_id');
    }

    public function headPartner(): belongsTo
    {
        return $this->belongsTo(self::class, 'partner_id');
    }
}
