<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
 use HasFactory;

 protected $primaryKey = 'idestudiante';

 protected $fillable =  array('nombre', 'apellido', 'edad','genero','imagen');

 protected $hidden = ['created_at','updated_at'];
}
