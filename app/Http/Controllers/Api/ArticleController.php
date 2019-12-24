<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleRequest;

use App\Models\Article;
use App\Models\Comment;
use App\Models\Payment;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use JamesDordoy\LaravelVueDatatable\Http\Resources\DataTableCollectionResource;
use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Charge;
use Stripe\Error\Card;
use Stripe\Error\ApiConnection;
use Stripe\Error\InvalidRequest;
use Stripe\Error\Api;
use Stripe\Error\Base;
   
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

	/**
	 * API to list all Articles
	 */
	public function list(Request $request)
	{	
		return response()->json(Article::select(['id', 'title', 'user_id', 'approve_status', 'created_at', 'updated_at', 'is_featured', 'paid_status'])->get(), 200);
	}

	/**
	 * Api to add new articles
	 */
	public function add(ArticleRequest $request)
	{
		$result = Article::addUpdateArticle($request, 0);

	    if($result['errFlag'] == 0)
	    { 
	      $article = Article::find($result['article_id']);
	      $result['paid_status'] = $article->paid_status;
	      
	      return response()->json([
            'message' => 'Success',
            'result' => $result], 200);
	    }
	    else
	    {
	      return response()->json([
            'message' => 'error',
            'result' => $result], 200);
	    }
	}

	/**
   * to create payment request
   *
   * @return \Illuminate\Http\Response
   */
  public function doPayment(Request $request)
  {
  	$payment = new Payment;

  	\Log::info($request->all());
   
    try 
      {
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $customer = Customer::create(array(
          'email' => Auth::guard('api')->user()->email,
          'source'  => $request->stripeToken
        ));

        \Log::info($customer);

        $charge = Charge::create(array(
          'customer' => $customer->id,
          'amount'   => 100*100,
          'currency' => 'inr'
        ));

        if($charge['status'] == 'succeeded') 
        {  
          //to save the transaction details if success

          $payment->savePayment($charge, $request->article_id, '1');
          $article = Article::find($request->article_id);
          $result = Article::updateStatus($article);
          if($result['errFlag'] == 0)
          {
            return response()->json([
            'route' => 'successful-payment',
            'msg' => 'success'], 200);
          }
        }

      }
      catch(Card $e) 
      {
        // Since it's a decline, \Stripe\Error\Card will be caught
        $errorMessages = $e->getMessage();
      }
      catch (RateLimit $e)
      {
        // Too many requests made to the API too quickly
        $errorMessages = $e->getMessage();
      }
      catch (InvalidRequest $e)
      {
        // Invalid parameters were supplied to Stripe's API
        $errorMessages = $e->getMessage();
      } 
      catch (Authentication $e)
      {
        // Authentication with Stripe's API failed
        $errorMessages = $e->getMessage();
      }
      catch (ApiConnection $e)
      {
        // Network communication with Stripe failed
        $errorMessages = $e->getMessage();
      }
      catch (Base $e)
      {
        // Display a very generic error to the user
        $errorMessages = $e->getMessage();
      } 
      catch (Exception $e)
      {
        // Something else happened, completely unrelated to Stripe
        $errorMessages = $e->getMessage();
      }
    //to save the transaction details even if failed
    $payment->savePayment($e, $request->article_id, '0');

    //return with error message
    return response()->json([
    'route' => 'view-articles',
    'msg' => $errorMessages], 200);
  }

  /**
   * Api to get featured articles
   */
  public function featuredArticles()
  {
  	return response()->json(Article::featuredArticles(), 200);
  }

  /**
   * Add new category API
   */
  public function update(ArticleRequest $request, int $id)
  {
    $result = Article::addUpdateArticle($request, $id);
    if($result)
        {   
          return response()->json([ 'result' =>
            $result ], 200);
        }
        else
        { 
          $result['msg'] = 'There is some error.Please try again.';
          $result['errFlag'] = 1;
            $result['route'] = 'add-article';
          return response()->json([
            'result' => $result ], 200);
        }
  }

  /**
   * Api to delete article
   */
  public function delete(int $id)
  {
      $result = Article::deleteArticle($id);
      return response()->json(['errFlag' => $result['errFlag']], 200);
  }

  /**
   * Api to fetch article for edit
   */
  public function editArticle(int $id)
  {
    $article = Article::find($id);
    if(empty($article))
    {
      return response()->json(['errFlag' => 1], 200);
    }
    else if($article->user_id == Auth::guard('api')->user()->id && Auth::guard('api')->user()->is_admin !=1)
    {
      return response()->json(['errFlag' => 2], 200);
    }
    else
    {
      return response()->json(['errFlag' => 0, 'result' => Article::find($id)], 200);
    }
  }

  /**
   * Api to make article featured and unfeatured
   */
  public function featured($id)
  {
    $article = Article::find($id);
          
    if(!empty($article))
    {

      $result = Article::makeFeatured($article);
      
      if($result)
      {
        return response()->json(['errFlag'=> 0],200);
      }
      else
      {
        return response()->json(['errFlag'=> 1],200);
      }
    }
    else
    {
      return response()->json(['errFlag'=> 2],200);
    }
    
  }
}