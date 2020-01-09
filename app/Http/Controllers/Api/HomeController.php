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

class HomeController extends Controller
{	
	/**
	 * Function to fetch popular articles and active categories
	 * @param string slug
	 */
    public function sideBar()
	{	
		$result = array(); 
		try
		{
			$result['popular_articles']= Article::popular()
				->map(function ($item, $key)
	    		{	
	    			return collect($item)->only(['date', 'title', 'slug', 'category_image'])->toArray();
				});

			$result['active_categories'] = Category::activeCategories();

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
}
