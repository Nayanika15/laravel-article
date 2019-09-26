<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;

use App\Models\Category;

use DataTables;

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
        $articles = Category::find($id)->countArticles();
        if(empty($countArticles))
        {   
            $action= Category::find($id)->delete();
            if($action)
            {
                return redirect()->route('view-category')
                    ->with('success', 'Category has been deleted.');
            }
            else
            {
                return redirect()->route('view-category')
                    ->with('ErrorMessage', 'There is some error.');  
            } 
        }
        else
        {
            return redirect()->route('view-category')
                    ->with('ErrorMessage', 'This category cannot be deleted as some articles are tagged.');
        }
      }

      /**
     * Show the add category page for add and update.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create($id=0)
    {   
        $categories = Category::find($id);

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
        $data = $request->validated();//to validate the data

        if (!empty($data))
        {
            if ($id == 0)
            {   
                $result = Category::create(array_map('ucfirst', $data));

                if ($result->wasRecentlyCreated)
                {
                    return redirect()->route('view-category')->with('success', 'Category created successfully.');
                }
                else
                {
                    return redirect()->route('add-category')->with('ErrorMessage', 'There is some error.')->withInput();
                }
            }
            else
            {
                $result = Category::where('id', $id)->update($data);
                
                if ($result)
                {
                    return redirect()->route('view-category')->with('success','Category updated successfully.');
                }
                else
                {
                    return redirect('edit-category')->with('ErrorMessage', 'There is some error while updating the record.')->withInput();
                }

            }
        }
         else
        {
            return redirect()->back();
        }
    }
}
