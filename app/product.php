<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\tb_product_price_mission;

class product extends Model
{
    protected $fillable =['name','file_path','date_expir','categorize','communicate','amount','price','user_id'];


    public function discounts(){
        return $this->hasMany(tb_product_price_mission::class, 'product_id')->orderBy('date');
     }
}

