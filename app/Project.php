<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['title', 'begin_date', 'end_date', 'description', 'status'];
    #arreglo de los atributos que va a poder guardar en la base d datos
}
