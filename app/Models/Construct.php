<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    public function questionnaires()
    {
        return $this->belongsToMany(Questionnaire::class, 'model_has_constructs', 'construct_id', 'model_id');

    }

    public function isUsed() {
        $construct_used_count = DB::table('model_has_constructs')->where('construct_id', $this->id)->count();
        if ($construct_used_count > 0) {
            return true;
        }
        return false;
    }

}
