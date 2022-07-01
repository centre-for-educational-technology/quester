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

    public function constructs()
    {
        return $this->belongsToMany(Construct::class, 'model_has_constructs', 'model_id');
    }

    public function respondents()
    {
        return $this->hasMany('App\Models\Respondent')->whereNotNull('end_time');
    }

    public function statements() {
        return $this->hasMany(Statement::class);
    }

}
