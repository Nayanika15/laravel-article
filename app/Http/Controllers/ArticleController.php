<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleRequest;
use App\Models\Article;
use App\Models\Category;
use App\Models\ArticleCategories;
use App\Models\Comment;
use App\Models\Payment;

use Exception;
use Illuminate\Http\Request;

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
  protected $article;

  /**
   * Show the list of all articles.
   *
   * @return \Illuminate\Contracts\Support\Renderable
   */
  public function index()
  {
    if(request()->ajax())
    {   
      return Article::allArticles();
    }
    return view('site.wordify.articles.list');
  }
  
  /**
   * Show the list of all articles.
   * @param int id
   * @return \Illuminate\Contracts\Support\Renderable
   */
  public function create($id=0)
  {         
    $article = Article::find($id);

    //to fetch all the added categories
    $all_categories = Category::getAllCategories();
    
    if($id != 0 && empty($article))
    {
      return redirect()->route('all-articles')
                ->with('ErrorMessage', 'Article was not found.');
    }
    else
    {
      if(!empty($article) && (auth()->user()->is_admin !=1) && ($article->user_id !== auth()->user()->id))
      {
        return redirect()->route('all-articles')
                  ->with('ErrorMessage', 'You are not authorised for this action.');
      }

      $sel_categories = ($id==0)?array() : $article->categories->pluck('id')->toArray();

      return view('site.wordify.articles.add')
                ->with([
                  'categories' => $all_categories,
                  'article' => $article,
                  'selected' => $sel_categories
                ]);
    }       
  }

  /**
   * Store or update article details to the database.
   *
   * @param \App\Http\Requests\ArticleRequest $request, int id
   * @return \Illuminate\Http\Response
   */
  public function store(ArticleRequest $request, $id=0) 
  { 
    $data = $request->validated();//to validate the data
    if (!empty($data))
    {
      $result = Article::addUpdateArticle($data, $id);

      if($result['errFlag'] == 0)
      {
        $article = Article::find($result['article_id']);

        //check if payment not done redirect to do payment
        if($article->paid_status == '0')
          {
            return $this->makePayment($result['article_id']);
          }
        else
          {
            return redirect()->route($result['route'])
              ->with('success', $result['msg']);   
          }
      }
      else
      {
        return redirect()->route($result['route'])
          ->with('ErrorMessage', $result['msg'])
          ->withInput();
      }
    }
    else
    {
      $route = ($id == 0)? 'add-article' : 'edit-article';
      return redirect()->route($route)
          ->with('ErrorMessage', "Enter valid data.")
          ->withInput();
    }
  }

  /**
   * Delete article record.
   * @param int $id
   * @return  \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $result = Article::deleteArticle($id);

    if($result["errFlag"] == 0)
    {
      return redirect()->route($result['route'])
            ->with($result['msgType'], $result['msg']);
    }
    else
    {
      return redirect()->route('all-articles')
            ->with('ErrorMessage', 'There is some error.');
    }
      
  }

  /**
   * Show the detail of article.
   * @param string $slug
   * @return \Illuminate\Contracts\Support\Renderable
   */
  public function detail($slug)
  { 
    $data = array();    
    $data['article'] = Article::articleDetail($slug);
    //check if article found the fetch the related articles and active comments
    if(isset($data['article']))
    {
      $data['related_articles'] = Article::related($data['article']);
      $data['comments']= Comment::activeComments($data['article']);
      return view('site.wordify.articles.detail')->with('data', $data); 
    }
    else
    {
      return redirect()->route('homepage');
    }
            
  }

  /**
   * to redirect user to payment page
   * @param int $article_id
   * @return  \Illuminate\Contracts\Support\Renderable
   */
  public function makePayment($article_id)
  {
    return view('payment.pay')->with('article_id', $article_id);
  }

  /**
   * to redirect user to success page on payemnt successful
   * @return  \Illuminate\Contracts\Support\Renderable
   */

  public function succesful()
  {
    return view('payment.success');
  }


  /**
   * to create payment request
   * @param request $request
   * @return \Illuminate\Http\Response
   */
  public function doPayment(Request $request)
  {
    $article  = Article::find($request->article_id);
    //To check if article found
    if($article->count())
    {
      $payment = new Payment;
      \Log::info($request->all());

      try 
        {
          Stripe::setApiKey(env('STRIPE_SECRET', 'sk_test_o8mqyUEEcwMxjQBEBmL5gML000iVdsn8cH'));
          $customer   = Customer::create(array(
            'email'   => auth()->user()->email,
            'source'  => $request->stripeToken
          ));
          $charge      = Charge::create(array(
            'customer' => $customer->id,
            'amount'   => 100*100,
            'currency' => 'inr'
          ));

          if($charge['status'] == 'succeeded') 
          {   
            //to save the transaction details if success
            $payment->savePayment($charge, $request->article_id, '1');
            $result   = Article::updateStatus($article);
            if($result['errFlag'] == 0)
            {
              return redirect()->route('successful-payment');
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
      \Log::info($errorMessages);
    }
    else{
      $errorMessages = "Article was not found.";
    }
    
     //return with error message  
    return redirect()->route('add-article')
        ->withErrors($errorMessages)
        ->withInput();
  }

  /**
   * to make article featured article
   * @param int id
   * @return \Illuminate\Contracts\Support\Renderable
   */
  public function featured($id)
  { 
    $result = Article::makeFeatured($id);
    return redirect()->route("all-articles")
          ->with($result['msgType'], $result['msg']);  
  }
}