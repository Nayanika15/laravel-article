<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Exception;

use App\Models\Article;
use App\Models\Category;
use App\Models\Comment;

use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

use App\Http\Requests\CategoryRequest;
use JamesDordoy\LaravelVueDatatable\Http\Resources\DataTableCollectionResource;

class CategoryController extends Controller
{	
	/**
	 * Function to fetch details of the requested category
	 * @param string slug
	 */
	public function detail($slug)
	{	
		$result = array(); 
		try
		{	
			$result['articles'] =  Category::categoryDetail($slug);			
			return response()->json([
            'message' => 'Success',
            'result' => $result], 200);
		}
		catch (Exception $e) 
		{
			return response()->json([
            'message' =>  $e->getMessage()
        	], 200);
		}
	}

	/**
	 * fetch active categories
	 */
	public function activeCategories()
	{
		return response()->json(Category::activeCategories());
	}

	/**
	 * Add new category API
	 */
	public function add(CategoryRequest $request)
	{	
		$data = $request->validated();//to validate the data

		if (!empty($data))
        {
			$result = Category::addUpdate($data, 0);
			if($result)
	        {   
	        	return response()->json([ 'result' =>
	            $result ], 200);
	        }
	        else
	        {	
	        	$result['msg'] = 'There is some error.Please try again.';
	        	$result['errFlag'] = 1;
	            $result['route'] = 'add-category';
	        	return response()->json([
	            'result' => $result ], 200);
	        }
	    }
	    else
	    {
	    	$result['msg'] = 'Please enter valid data.';
	        $result['errFlag'] = 1;
	    	return response()->json([
	            'result' => $result ], 200);
	    }
	}

	/**
	 * Api to update category
	 */
	public function update(CategoryRequest $request, int $id)
	{	
		$data = $request->validated();//to validate the data

		if (!empty($data))
        {
			$result = Category::addUpdate($data, $id);
			if($result)
	        {   
	        	return response()->json([ 'result' =>
	            $result ], 200);
	        }
	        else
	        {	
	        	$result['msg'] = 'There is some error.Please try again.';
	        	$result['errFlag'] = 1;
	            $result['route'] = 'add-category';
	        	return response()->json([
	            'result' => $result ], 200);
	        }
	    }
	    else
	    {
	    	$result['msg'] = 'Please enter valid data.';
	        $result['errFlag'] = 1;
	    	return response()->json([
	            'result' => $result ], 200);
	    }
	}

	/**
	 * API to list all categories
	 */
	public function list(Request $request)
	{	
		return response()->json(Category::select(['id', 'name', 'created_at', 'updated_at'])->paginate(env('PAGINATE_LIMIT', 10)), 200);
	}

	/**
	 * Api to fetch article for edit
	 */
	public function editCategory(int $id)
	{
		return response()->json(Category::find($id),200);
	}

	/**
   	* Api to delete category
   	* @param int id
   	*/
	public function delete(int $id)
	{
		$result = Category::deleteCategory($id);
		return response()->json(["errFlag" => $result["errFlag"]], 200);
	}
	
}