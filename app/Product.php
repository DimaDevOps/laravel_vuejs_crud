<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
	 public function category()
    {
    	return $this->belongsTo(Category::class);

    }
	
    // protected $table ='products';
    // protected $fillable=['name','description','category_id'];
}
