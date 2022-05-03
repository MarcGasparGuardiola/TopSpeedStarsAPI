<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tema extends Model
{
    use HasFactory;

    protected $table = 'temas';

     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'text',
        'userId'
    ];

    public function user()
    {
        //return $this->belongsTo(User::class);
    }

    public function respuestas()
    {
        return $this->hasMany(Respuesta::class);
    }
}
