<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    use HasFactory;

    // Agrega los campos que son masivos asignables
    protected $fillable = [
        'title',
        'descripcion',
        'color',
        'textColor',
        'start',
        'end',
        'user_id',
    ];

    // Define la relación con el modelo User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

