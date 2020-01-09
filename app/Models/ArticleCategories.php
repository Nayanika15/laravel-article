<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleCategories extends Model
{	
	 public $incrementing = false;
	 
      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'article_id', 'category_id'
    ];
}
