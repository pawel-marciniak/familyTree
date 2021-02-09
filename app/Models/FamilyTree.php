<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyTree extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function headPerson()
    {
        return $this->belongsTo(Person::class, 'head_person_id');
    }
}
