<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use DataTables;

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

    /**
     * To fetch all categories
     */
    public function allCategories()
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
    public function deleteCategory($id)
    {
        $countArticles = Category::find($id)->countArticles()->count();
        $result = array();

        if(empty($countArticles))
        {   
            $action= Category::find($id)->delete();
            if($action)
            {   
                $result['msg'] = 'Category has been deleted.';
                $result['msgType'] = 'success';
                
            }
            else
            {
                $result['msg'] = 'There is some error.';
                $result['msgType'] = 'ErrorMessage'; 
            } 
        }
        else
        {   
            $result['msg'] = 'This category cannot be deleted as some articles are tagged.';
                $result['msgType'] = 'ErrorMessage';
        }

    return $result;
    }
      /**
     * To store or update category
     */
    public function addUpdate($request,$id)
    {
        
        $data = $request->validated();//to validate the data
        $result = array();

        if (!empty($data))
        {   
            if($id == 0)
            {
                $category = new Category;
            }
            else
            {
                $category = Category::find($id);
            }

            if($id !=0 && empty($category))
            {
                
                $result['errFlag'] = 1;
                $result['msg'] = 'Category was not found.';
                $result['route'] = 'add-category';
               
            }
            else
            {  
                $category->name = ucfirst($data['name']);
                $action = ($id == 0) ? 'Added' : 'Updated';
                $saved = $category->save();
                if($saved)
                {
                    $result['errFlag']= 0;
                    $result['msg']= 'Category was '. $action . ' successfully.';
                    $result['route']= 'view-category';
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
     * To fetch the category
     */
    public function getCategory($id)
    {
       return Category::find($id);
    }


    /**
     * to fetch the active articles
     */
    public function activeCategories()
    {

    return Category::whereHas('articles', function($query){
        $query->where('approve_status', '1');
     })->withCount('articles')->get();    
    
    }
}
