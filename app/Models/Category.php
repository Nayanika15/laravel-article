<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];
    
 	  public function countArticles()
    {   
        return $this->hasMany(ArticleCategories::class);
    }

    /**
     * defining relationship with category
     */
    public function articles()
    {
        return $this->belongsToMany('App\Models\Article', 'App\Models\ArticleCategories');
    }
}
