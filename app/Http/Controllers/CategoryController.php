<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;

use App\Models\Category;
use App\Models\Article;

class CategoryController extends Controller
{

    /**
     * Show the categories listing.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {   
        if(request()->ajax())
        {
            $categories = new Category;
            return $categories->allCategories();
        }

        return view('site.wordify.categories.view');
    }

    /**
     * Delete record.
     *
     * @param int $id
     * @return  \Illuminate\Http\Response
     */
    public function destroy($id)
    {  
        $category = new Category;
        $result = $category->deleteCategory($id);

        return redirect()->route('view-category')
                    ->with($result['msgType'], $result['msg']);
    }

      /**
     * Show the add category page for add and update.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create($id=0)
    {   
        $category = new Category;
        $categories = ($id != 0)?$category-> getCategory($id) : array();
        
        if ($id != 0 && empty($categories)) 
        {
            return redirect()->route('view-category')->with('ErrorMessage', 'Category was not found.');
        }

        return view('site.wordify.categories.add', compact('categories'));
    }
     /**
     * Store or update Category details to the database.
     *
     * @param \App\Http\Requests\CategoryRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request,$id=0) 
    {   
        $category = new Category;
        $result = $category->addUpdate($request,$id);
        if($result['errFlag'] == 0)
        {   
            return redirect()->route($result['route'])
                        ->with('success', $result['msg']);
        }
        else
        {
            return redirect()->route($result['route'])
                    ->with('ErrorMessage', $result['msg'])
                    ->withInput();
        }
    }
      /**
     * Fetch active articles in a particular category.
     * @param string $slug
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function detail($slug)
    {   
        $article = new Article;
        $category = new Category;
        $data = array();
        $data['categoryArticles'] = $category->categoryDetail($slug);
        $data['category']=$category->getSlugCategory($slug);

        if(!empty($data['categoryArticles']))
        {
            return view('site.wordify.categories.show')->with('data', $data); 
        }
        else
        {
            return redirect()->route('homepage');
        }
              
    }
}
