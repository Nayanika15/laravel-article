<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Exception;
use App\Models\Article;
use App\Models\Comment;
use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;


   
class ArticleController extends Controller
{	
	/**
	 * Function to fetch latest articles
	 */
	public function latest()
	{	
		try {

			$result = Article::latestArticle()
						->get()
						->each(function ($item, $key) 
						{
						    $item['created_date'] = date('d-M-y', strtotime($item['created_at']));
						    $item['user_name'] = ($item->user->name == '') ? 'guest' : $item->user->name;
						    $item['image'] = $item->homepage_image;
			    		})
			    		->map(function ($item, $key)
			    		{	
			    			return collect($item)->only(['title', 'created_date', 'created_by', 'image', 'slug', 'comments_count'])->toArray();
  						});

			return response()->json([
            'message' => 'Success',
            'result' => $result
        	], 200);
		
		} catch (Exception $e) {
			return response()->json([
            'message' =>  $e->getMessage()
        	], 200);
		}
		
	}

	/**
	 * Function to fetch details of the requested article
	 * @param string slug
	 */
	public function detail($slug)
	{	
		$result = array(); 
		try
		{	
			$result['article'] = Article::articleDetail($slug)
				->load('categories');

			$result['active_comments'] = Comment::activeComments($slug)
				->map(function ($item, $key)
	    		{	
	    			$item['created_date'] = date('d-M-y', strtotime($item['created_at']));;
	    			return collect($item)->only(['comment', 'created_date', 'name'])->toArray();
				});

			$result['related_articles'] = Article::related($slug)
				->map(function ($item, $key)
	    		{	
	    			$item['created_date'] = date('d-M-y', strtotime($item['created_at']));
	    			$item['image'] = $item->detail_image;
	    			//$item['detail_link'] = $item->permalink;
	    			return collect($item)->only(['title', 'comments_count', 'slug', 'created_date','image']);
				});
			
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