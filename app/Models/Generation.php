<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Generation extends Model
{
    use HasFactory;
    protected $fillable = ['name','market','period','generation','pictureUrl','link','model_id'];
    public $timestamps = false;

    //relations
    public function model(){
        return $this->belongsTo(CarModel::class,'model_id','id');
    }
}
