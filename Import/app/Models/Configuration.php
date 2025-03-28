<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    protected $fillable = ['remise_globale'];

    public static function getRemiseGlobale(){
        $config=self::latest()->first();
        return $config ? $config->remise_globale : 0;
    }
}
