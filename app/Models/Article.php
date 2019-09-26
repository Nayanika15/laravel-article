<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
class Article extends Model implements HasMedia
{
    use HasMediaTrait;
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'details', 'user_id'
    ];

    /**
     * get the slug value for the provided title
     */
    public function setSlugAttribute($value)
    {   
        $slug_value = str_slug($value);
        $count = Article::where('slug', $slug_value)->count();
    
        if ($count>0)
        {
            $this->attributes['slug'] = $slug_value . '-' .$count;
        }
        else
        {
            $this->attributes['slug'] = $slug_value;
        }
        
    }
    /**
     * get the module permalink
     */
        public function getPermalinkAttribute()
    {
        return 'article/' . $this->slug;
    }

    /**
     * Defining relationship with category table
     * 
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'article_categories');
    }
    /**
     * Defining relationship with user table
     * 
     */

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    /**
     * To change the image file to different size 
     */
     public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('homepage')
            ->width(350)
            ->height(234);
        $this->addMediaConversion('detail')
            ->width(730)
            ->height(487);
        $this->addMediaConversion('flex')
            ->width(110)
            ->height(500);
    }
}
