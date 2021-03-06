<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleRequest;

use App\Models\Article;
use App\Models\Category;
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
        ->paginate(4);

			return response()->json([
            'message' => 'Success',
            'result' => $result
        	], 200);
		
		}
    catch (Exception $e) {
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

      //check if article found the fetch the related articles and active comments
      if(isset($result['article']))
      {
  			$result['active_comments'] = Comment::activeComments($result['article'])
  				->map(function ($item, $key)
  	    		{	
  	    			$item['created_date'] = date('d-M-y', strtotime($item['created_at']));
  	    			return collect($item)->only(['comment', 'created_date', 'name'])->toArray();
  				  });

  			$result['related_articles'] = Article::related($result['article'])
  				->map(function ($item, $key)
  	    		{	
              $item['created_date'] = date('d-M-y', strtotime($item['created_at']));
                $item['image'] = $item->detail_image;
                return collect($item)->only(['title', 'comments_count', 'slug', 'created_date','image']);
  				  });
  			
  			return response()->json([
              'message' => 'Success',
              'result' => $result], 200);
      }
      else
      {
        return response()->json([
          'message' => 'error'], 200);
      }
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
    $data = $request->validated();//to validate the data
    if (!empty($data))
    {
  		$result = Article::addUpdateArticle($data, 0);

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
    else
    {
      return response()->json([
        'message' => 'Enter valid data.',
        'result' => $result], 200);
    }
	}

  /**
   * Api to update articles
   * @param int id, request
   */
  public function update(ArticleRequest $request, int $id)
  { 
    $article = Article::find($id);
    
    //check if the logged user is admin or owner of the article
    if((Auth::guard('api')->user()->is_admin !== 1) && ($article->user_id !== Auth::guard('api')->user()->id))
    {
      return response()->json([
        'message' => 'You are not authorised for this action.',
        'errFlag' => 2,
        'result' => $result], 200);
    }
    else
    { 
      $data = $request->validated();//to validate the data
      if (!empty($data))
      {
        $result = Article::addUpdateArticle($data, $id);
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
      else
      {
        return response()->json([
              'message' => 'Enter Valid data.',
              'result' => $result], 200);
      }
    }
  }

  /**
   * Api to fetch article to edit
   * @param int id
   */
  public function edit(int $id)
  {
    $article = Article::find($id);
    $is_admin = (Auth::guard('api')->user())?Auth::guard('api')->user()->is_admin:auth()->user()->is_admin;
    $user_id = (Auth::guard('api')->user())?Auth::guard('api')->user()->id:auth()->user()->id;
    
    if($id == 0 || empty($article))
    { 
      return response()->json([
        'message' => 'Article was not found.',
        'errFlag' => 1,
        'result' => $result], 200);
    }
    //check if logged user is admin or if is the owner of the article
    else if(($is_admin !== 1) && ($article->user_id !== $user_id))
    { 
      return response()->json([
        'message' => 'You are not authorised for this action.',
        'errFlag' => 2,
        'result' => $result], 200);
    }
    else
    {
      $result = $article;
      //to fetch all the tagged categories
      $result['categories_tagged'] = $article->categories->pluck('id')->toArray();
      //to fetch all the added categories
      $result['all_categories'] = Category::select('name', 'id')->get();
      return response()->json([
        'message' => 'Success',
        'errFlag' => 0,
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
   * Api to make article featured
   * @param int id
   */
  public function feature(int $id)
  { 
    $result = Article::makeFeatured($id);
    return response()->json(["errFlag" => $result], 200);
  }

  /**
   * Api to delete article
   * @param int id
   */
  public function delete(int $id)
  {
    $result = Article::deleteArticle($id);
    return response()->json(["errFlag" => $result["errFlag"]], 200);
  }
}