<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarModel extends Model
{
    use HasFactory;

    protected $fillable = ['name','url'];
    public $timestamps = false;

    //relations
    public function generations(){
        return $this->hasMany(Generation::class,'model_id','id');
    }
}
