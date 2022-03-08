<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Construct extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'objective',
        'creator_id'
    ];

    public function statements() {
        return $this->hasMany(Statement::class);
    }

    public function creator() {
        return $this->belongsTo(User::class, 'creator_id');
    }

}
