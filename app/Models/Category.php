<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use DataTables;
use JamesDordoy\LaravelVueDatatable\Traits\LaravelVueDatatableTrait;
use Illuminate\Notifications\Notifiable;

class Category extends Model
{   

    use Notifiable, LaravelVueDatatableTrait;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    /**
     * datatable columns
     */
     protected $dataTableColumns = [
        'id'    => [
            'searchable' => false,
        ],
        'name' => [
            'searchable' => true,
        ],
        'created_at' => [
            'searchable' => true,
        ]
    ];

    /**
     * append parameter to api response
     */
     protected $appends = ['date'];

    /**
     * get the data format
     */
    public function getDateAttribute()
    {
        return date('d-M-Y', strtotime($this->created_at));
    }

    /**
     * get the slug value for the provided name
     */
    public function setSlugAttribute($value)
    {   
        $slug_value = str_slug($value);
        $this->attributes['slug'] = $slug_value;
               
    }

    /**
     * get the module permalink
     */
    public function getPermalinkAttribute()
    {
        return 'category/' . $this->slug;
    }

    /**
     * To get articles related to categories
     */
 	  public function countArticles()
    {   
        return $this->hasMany(ArticleCategories::class)->count();
    }

    /**
     * defining relationship with category
     */
    public function articles()
    {
        return $this->belongsToMany('App\Models\Article', 'App\Models\ArticleCategories');
    }

    /**
     * To fetch all categories
     */
    public static function allCategories()
    {
        $category = Category::select(['id', 'name', 'created_at', 'updated_at']);
        return Datatables::of($category)
            ->editColumn('created_at', function($category){
                return date("d-M-Y", strtotime($category->created_at));
            })
            ->addColumn('action', function($category){
                $edit_route=route('edit-category', $category->id);
                $delete_route=route('destroy-category', $category->id);

                return "<a href='" . $edit_route . "' class='btn btn-primary'>Edit</a>" . " <a href='".$delete_route."' class='btn btn-danger delete' onclick='return confirm(\"Are you sure?\")' >Delete</a>";
            })
            ->make(true);
    }

    /**
     * To delete category
     */
    public static function deleteCategory($id)
    {
        $countArticles = Category::find($id)->countArticles();
        $result = array();
        
        if($countArticles == 0)
        {  
            $action= Category::find($id)->delete();
            if($action)
            {   
                $result['msg'] = 'Category has been deleted.';
                $result['msgType'] = 'success';
                $result['errFlag']  = 0;
                
            }
            else
            {
                $result['msg'] = 'There is some error.';
                $result['msgType'] = 'ErrorMessage';
                $result['errFlag']  = 1;
            } 
        }
        else
        {  
            $result['msg'] = 'This category cannot be deleted as some articles are tagged.';
            $result['msgType'] = 'ErrorMessage';
            $result['errFlag']  = 2;
        }

        return $result;
    }
      /**
     * To store or update category
     */
    public static function addUpdate($data,$id)
    {   
        $result = array();
        $category = Category::firstOrNew(array('id' => $id));
       
        if($id !=0 && isset($category))
        {
            
            $result['errFlag']  = 1;
            $result['msg']      = 'Category was not found.';
            $result['route']    = 'add-category';
           
        }
        else
        {  
            $category->name = ucfirst($data['name']);
            $category->slug = $data['name'];
            $action         = ($id == 0) ? 'Added' : 'Updated';
            $saved          = $category->save();

            if($saved)
            {
                $result['errFlag']  = 0;
                $result['msg']      = 'Category was '. $action . ' successfully.';
                $result['route']    = 'view-category';
            }
        }
        
        return $result;
    }

    /**
     * To fetch the category
     */
    public static function getCategory($id)
    {
       return Category::find($id);
    }


    /**
     * to fetch the active articles
     */
    public static function activeCategories()
    {
        return Category::whereHas('articles', function($query){
            $query->where('approve_status', '1');
        })->withCount('articles')->get();
    }
    
    /**
     * To fetch the category from slug
     */
    public static function getSlugCategory($slug)
    {
       return Category::where('slug', $slug)->first();
    }

    /**
     * to fetch the active articles in a particular category
     */
    public static function categoryDetail($slug)
    {

        $data = Category::where('slug', $slug)->whereHas('articles', function($query){
            $query->where('approve_status', '1');
         })->first();

        return $data->articles()
            ->latest()
            ->Paginate(env('PAGINATE_LIMIT', 4));
    }

    /**
     * To fetch all categories
     */
    public static function getAllCategories()
    {
        return Category::select('id', 'name')
            ->pluck('name', 'id')
            ->toArray();
    }
}