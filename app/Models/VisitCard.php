<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitCard extends Model
{
    use HasFactory;

    protected $table = 'visitcards';
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'tel',
        'adress',
        'company',
        'description'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
