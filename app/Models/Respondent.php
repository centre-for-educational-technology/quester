<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Respondent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'start_time',
        'end_time',
        'questionnaire_id'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function questionnaire()
    {
        return $this->belongsTo('App\Models\Questionnaire');
    }

    public function responses()
    {
        return $this->hasMany('App\Models\Response');
    }

}
