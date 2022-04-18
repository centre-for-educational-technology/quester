<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Questionnaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'subject',
        'start_time',
        'end_time',
        'log_in_required',
        'code',
        'creator_id'
    ];

    public function creator()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function respondents()
    {
        return $this->hasMany('App\Models\Respondent');
    }

}
