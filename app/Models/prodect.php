<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class prodect extends Model
{
    use HasFactory;

    protected $fillable = [
    	'id','Product_name','description','section_id','created_at','updated_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
        'updated_at	'
    ];


    public function section(){

        return $this->belongsTo('App\Models\section');
    }
}
