<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Illuminate\Support\Str;

use App\Mail\ArticleMail;
use Illuminate\Support\Facades\Mail;

use DataTables;

class Article extends Model implements HasMedia
{

    use HasMediaTrait;
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'details', 'user_id', 'paid_status', 'is_featured'
    ];

    /**
     * get the slug value for the provided title
     */
    public function setSlugAttribute($value)
    {   
        $slug_value = str_slug($value);
        //to check if any slug with the same name exists
        $count = Article::where('slug', $slug_value)->count();
        
        //this will append the slug with the count if duplicate name exists
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
     * get the excerpt
     */
    public function getExcerptAttribute()
    {
        return str::words($this->details, 25);
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
     * Defining relationship with comments table
     * 
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    /**
     * Defining relationship with payments table
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * To change the image file to different size 
     */
     public function registerMediaConversions(Media $media = null)
    {   
        //to generate the image required for homepage
        $this->addMediaConversion('homepage')
            ->width(350)
            ->height(234);
        //to generate the image required for detail page
        $this->addMediaConversion('detail')
            ->width(730)
            ->height(487);
        //to generate the image required for slider
        $this->addMediaConversion('slider')
            ->width(1110)
            ->height(500);
        //to generate the image required for category detail page
        $this->addMediaConversion('category')
            ->width(200)
            ->height(168);
    }

    /**
     * Function to fetch all articles depending on the user logged in
     */
    Public function allArticles()
    {   
        //To fetch all articles for admin and for other users only the articles posted by them
        if(auth()->user()->is_admin)
        {
            $articles = Article::select(['id', 'title', 'user_id', 'approve_status', 'created_at', 'updated_at', 'is_featured']);
        }
        else
        {  
            $articles=auth()->user()->articles()->get();
        }
        
        
        return Datatables::of($articles)
            ->editColumn('paid_status', function($articles){
                $paid_status= $articles->paid_status;

                if($paid_status == 0)
                {
                    return "<p class='text-danger'>Unpaid </p>";
                }
                elseif ($paid_status == 1)
                {
                   return "<p class='text-success'> Paid </p>";
                }
                elseif ($paid_status == 1)
                {
                   return "<p class='text-warning'> Failed </p>";
                }
            })
            ->editColumn('approve_status', function($articles){
                $status= $articles->approve_status;

                if($status == 0)
                {
                    return "<p class='text-danger'>Unpublished </p>";
                }
                elseif ($status == 1)
                {
                   return "<p class='text-success'> Published </p>";
                }
                elseif ($status == 1)
                {
                   return "<p class='text-warning'> Unapproved </p>";
                }
            })
            ->editColumn('user_id', function($articles){
                return $articles->user->name;
            })
            ->editColumn('created_at', function($articles){
                return date("d-M-Y", strtotime($articles->created_at));
            })
            ->addColumn('action', function($articles){
                $edit_route = route('edit-article', $articles->id);
                $delete_route = route('delete-article', $articles->id);
                if(auth()->user()->is_admin == 1)
                {
                    $feature_class = ($articles->is_featured == 1) ? 'star': 'star-o';
                    $make_featured = "<a href='".route('feature-article', $articles->id)."' class='btn btn-warning'><i class='fa fa-".$feature_class."'></i></a> ";
                }
                else
                {
                    $make_featured='';
                }
                

                return "<a href='" . $edit_route . "' class='btn btn-primary'><i class='fa fa-edit'></i></a>" . " <a href='" . $delete_route . "' class='btn btn-danger delete' onclick='return confirm(\"Are you sure?\")' ><i class='fa fa-trash'></i></a> ".$make_featured;
            })
            ->escapeColumns(['action'])
            ->make(true);
    }

    /**
     * to add and update articles
     */
    public function addUpdateArticle($request, $id)
    {   
        $data = $request->validated();//to validate the data
        $result = array();
        $route = ($id == 0)? 'add-article' : 'edit-article';
        if (!empty($data))
        {   

            if($id == 0)
            {
                $article = new Article;
            }
            else
            {
                $article = Article::find($id);
            }

            if($id !=0 && empty($article))
            {
                
                $result['errFlag'] = 1;
                $result['msg'] = 'Article was not found.';
                $result['route'] = 'add-article';
               
            }
            else
            {  
                $article->title = $data['title'];
                $article->details = $data['details'];
                $article->user_id = auth()->user()->id;
                $article->slug = $data['title'];

                if($request->has('approve_status'))
                {
                    $article->approve_status=$data['approve_status'];
                }
                
                $action = ($id == 0) ? 'added' : 'updated';
                $saved = $article->save();
                
                if($saved)
                {
                    $article->categories()->sync($data['categories']);
                    if($request->hasFile('image'))
                    {   
                        $images = $article->getMedia();
                        if(empty($images))
                        {
                            $article->clearMediaCollection();
                        }
                        
                        $article->addMedia($request->file('image'))
                               ->toMediaCollection('articles');
                        if($id == 0)
                        {
                            $admin = User::where('is_admin', '1')->first();
                            Mail::to($admin->email)->send(new ArticleMail($article));   
                        }
                        
                    }

                    $result['errFlag']= 0;
                    $result['msg']= 'Article was '. $action . ' successfully.';
                    $result['route']= 'all-articles';
                    $result['article_id']= $article->id;
                    
                }
                else
                {   
                    $result['errFlag'] = 1;
                    $result['msg'] = 'There is some error.';
                    $result['route'] = $route;
                }
            }
        }
         else
        {
            $result['errFlag'] = 1;
            $result['msg'] = '';
            $result['route'] = $route;
        }

        return $result;
    }
    /**
     * To show the article detail and update the view count
     */
    public function articleDetail($slug)
    {
       $data = Article::select(['id', 'title', 'details', 'user_id', 'approve_status', 'created_at', 'updated_at'])->where(['slug' => $slug, 'approve_status' =>'1', 'paid_status' => '1'])->first();

       //to increase the view count on visting the view page
       if($data)
       {
            $data->increment('views_count');
       }
        return $data;
    }
    /**
     * To destroy the article
     */
    public function deleteArticle($id)
    {
        $article = Article::find($id);

         if((auth()->user()->is_admin !== 1) && ($article->user_id !== auth()->user()->id))
        {
            return redirect()->route('all-articles')->with('ErrorMessage', 'You are not authorised for this action.');
        }
        
        $action = $article->delete();
        $result = array();
        $result['route'] = 'all-articles';

        //to delete the media associated on deleting the article successfully
        if($action)
        {   
            $article->categories()->detach();
            $article->clearMediaCollection();
            $result['msg'] = 'Article has been deleted.';
            $result['errFlag'] = 0;
            $result['msgType'] = 'success';
        }
        else
        {   
            $result['errFlag'] = 1;
            $result['msg'] = 'There is some error.';
            $result['msgType'] = 'ErrorMessage'; 
              
        }
        return $result;
    }
    /**
     * Show the popular articles.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function popular()
    {   
        $articles = Article::select(['id', 'title', 'user_id','created_at', 'updated_at', 'slug'])
            ->where(['approve_status'=> '1', 'paid_status'=>'1'])
            ->orderBy('views_count', 'desc')
            ->limit(4)
            ->get();
        return  $articles;
    }
    /**
     * Show the related articles.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function related($slug)
    {   
         $article_categories = Article::where(['slug' => $slug, 'approve_status' =>'1', 'paid_status'=>'1'])
            ->first()
            ->categories
            ->pluck('id')->toArray();

        $related_articles = Article::wherehas('categories', function($query) use($article_categories){
                $query->whereIn('id', $article_categories);
            })->withCount(['comments' => function($query){
                $query->where(['approve_status'=>'1']);
            }])->inRandomOrder()
            ->limit(3)
            ->get();
            
        return  $related_articles;
    }
    /**
     * Show the latest article.
     * @param string $slug
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function latestArticle()
    {   
        return Article::where(['approve_status'=>'1', 'paid_status' => '1'])
            ->withCount(['comments' => function($query){
                $query->where('approve_status', '1');
            }])->latest();              
    }
    /**
     * to update status after successful payment
     */
    public function updateStatus($article)
    {   
        $updated = $article->update(['paid_status'=> '1']);
        $result = array();
        
        if($updated)
        {   
            $result['errFlag'] = 0; 
        }
        else
        {   
            $result['errFlag'] = 1;
        }

        return $result; 
    }
    /**
     * To make articles featured
     */
    public function makeFeatured($article)
    {
        $article->is_featured = !($article->is_featured);
        return $article->save();
    }
    /**
     * to fetch the featured articles
     */
    public function featuredArticles()
    {
        $articles = Article::select(['id', 'title', 'user_id','created_at', 'updated_at', 'slug'])
                ->where(['approve_status'=> '1', 'paid_status'=>'1', 'is_featured'=> '1'])
                ->get();
            return  $articles;
    }
    
}
