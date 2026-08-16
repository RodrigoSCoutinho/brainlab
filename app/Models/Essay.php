<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Essay extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'subject',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function analyses()
    {
        return $this->hasMany(EssayAnalysis::class);
    }

    public function latestAnalysis()
    {
        return $this->hasOne(EssayAnalysis::class)->latestOfMany();
    }

    public function lineComments()
    {
        return $this->hasMany(EssayLineComment::class)->orderBy('line_number');
    }
}
