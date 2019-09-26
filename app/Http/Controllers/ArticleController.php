<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleRequest;

use App\Models\Article;
use App\Models\Category;
use App\Models\ArticleCategories;

use DataTables;

class ArticleController extends Controller
{
    /**
     * Show the list of all articles.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {    
        // dd(Article::select(['id', 'title', 'details'])->with('categories'));
        if(request()->ajax())
        {   
            if(auth()->user()->is_admin)
            {
                $article = Article::select(['id', 'title', 'details', 'user_id', 'approve_status', 'created_at', 'updated_at']);
            }
            else
            {  
                $article=auth()->user()->articles()->get();
            }
            
            
            return Datatables::of($article)
                /*->addColumn('categories', function($article){
                    return implode(', ', $article->categories->pluck('name')->toArray());
                    })*/
                ->editColumn('approve_status', function($article){
                    $status= $article->approve_status;

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
                ->editColumn('user_id', function($article){
                    return $article->user->name;
                })
                ->editColumn('created_at', function($article){
                    return date("d-M-Y", strtotime($article->created_at));
                })
                ->addColumn('action', function($article){
                    $edit_route=route('edit-article',$article->id);
                    $delete_route=route('delete-article',$article->id);
                    return "<a href='".$edit_route."' class='btn btn-primary'>Edit</a>" . " <a href='".$delete_route."' class='btn btn-danger delete' onclick='return confirm(\"Are you sure?\")' >Delete</a>";
                })
                ->escapeColumns(['action'])
                ->make(true);
        }

        return view('site.wordify.articles.list');
    }
    
    /**
     * Show the list of all articles.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create($id=0)
    {   
        $article = Article::find($id);
        $all_categories = Category::select('id', 'name')->pluck('name', 'id')->toArray();

        if ($id != 0 && empty($article))
        {   
            return redirect()->route('all-articles')->with('ErrorMessage', 'Category was not found.');
        }
        else
        {   
            $sel_categories = ($id==0)? '' : $article->categories->pluck('id')->toArray();
            return view('site.wordify.articles.add')->with(['categories' => $all_categories,'article' => $article,'selected' => $sel_categories]);
        }
        
    }

     /**
     * Store or update article details to the database.
     *
     * @param \App\Http\Requests\CategoryRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArticleRequest $request, $id=0) 
    {   
        $data = $request->validated();//to validate the data
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
                return redirect()->route('add-article')
                    ->with('ErrorMessage', 'Article not found.')
                    ->withInput();
            }
            else
            {  
                $article->title = $data['title'];
                $article->details = $data['details'];
                $article->user_id = auth()->user()->id;
                $article->slug = $data['title'];
                $article->approve_status = ($request->has('approve_status'))?$data['approve_status']:'0';
                $action = ($id == 0) ? "Added" : "Updated";
                $saved = $article->save();

                if($saved)
                {
                    $article->categories()->sync($data['categories']);
                    $article->addMedia($request->file('image'))
                           ->toMediaCollection('articles');
                
                    return redirect()->route('all-articles')
                        ->with('success', 'Article ' .$action. ' successfully.');
                }
                else
                {
                    return redirect()->route('add-article')
                        ->with('ErrorMessage', 'There is some error.')
                        ->withInput();
                }
            }
        }
         else
        {
            return redirect()->back();
        }
    }

    /**
     * Delete article record.
     *
     * @param int $id
     * @return  \Illuminate\Http\Response
     */
     public function destroy($id)
     {  
         
            $article = Article::find($id);
            $action = $article->delete();
            if($action)
            {   
                $article->categories()->detach();
                return redirect()->route('all-articles')
                    ->with('success', 'Article has been deleted.');
            }
            else
            {
                return redirect()->route('all-articles')
                    ->with('ErrorMessage', 'There is some error.');  
            } 
        
      }
}
