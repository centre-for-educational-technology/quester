<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Statement extends Model
{
    use HasFactory;

    protected $table = 'statements';

    protected $fillable = [
        'text',
        'construct_id',
        'position'
    ];

    public function construct()
    {
        return $this->belongsTo('App\Models\Construct');
    }

}
