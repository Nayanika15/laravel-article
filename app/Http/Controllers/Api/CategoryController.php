<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Exception;

use App\Models\Article;
use App\Models\Category;
use App\Models\Comment;

use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

use App\Http\Requests\CategoryRequest;
class CategoryController extends Controller
{
    /**
	 * Function to fetch details of the requested article
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
		$result = Category::addUpdate($request, 0);
		if($result)
        {   
        	return response()->json([
            $result ], 200);
        }
        else
        {	
        	return response()->json([
            $msg => 'There is some error.' ], 200);
        }
	}
	
}
