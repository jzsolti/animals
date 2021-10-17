<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Animal extends Model
{
    protected $casts = [
        'name' => 'array',
        'description' => 'array',
    ];
    
    public function getTranslatedNameAttribute()
    {
    	return $this->name[ app()->getLocale()];
    }
    
    public function getTranslatedDescriptionAttribute()
    {
    	return $this->description[ app()->getLocale()];
    }
}
