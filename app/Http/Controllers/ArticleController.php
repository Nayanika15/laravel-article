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
   *
   * @return \Illuminate\Contracts\Support\Renderable
   */
  public function create($id=0)
  {         
    $article = Article::find($id);

    //to fetch all the added categories
    $all_categories = Category::getAllCategories();
    
    if ($id != 0 && empty($article))
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

      $sel_categories = ($id==0) ? array() : $article->categories->pluck('id')->toArray();

      return view('site.wordify.articles.add')
                ->with(['categories' => $all_categories, 'article' => $article, 'selected' => $sel_categories]);
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
    $result = Article::addUpdateArticle($request, $id);

    if($result['errFlag'] == 0)
    { 
      $article = Article::find($result['article_id']);
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

  /**
   * Delete article record.
   *
   * @param int $id
   * @return  \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $result = Article::deleteArticle($id);
    if($result)
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
    $data['related_articles'] = Article::related($slug);
    $data['article'] = Article::articleDetail($slug);
    $data['comments']= Comment::activeComments($slug);

    if(!empty($data['article']))
    {  
        return view('site.wordify.articles.detail')->with('data', $data); 
    }
    else
    {
        return redirect()->route('homepage');
    }
            
  }

  /**
   * to redirect user to payment page
   */
  public function makePayment($article_id)
  {
    return view('payment.pay')->with('article_id', $article_id);
  }

  /**
   * to redirect user to success page on payemnt successful
   */

  public function succesful()
  {
    return view('payment.success');
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
          'email' => $request->stripeEmail,
          'source'  => $request->stripeToken
        ));

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
     //return with error message  
    return redirect()->route('add-article')
        ->withErrors($errorMessages)
        ->withInput();
  }

  /**
   * to make article featured article
   */
  public function featured($id)
  {
    $article = Article::find($id);
          
    if(!empty($article))
    {

      $result = Article::makeFeatured($article);
      
      if($result)
      {
        return redirect()->route('all-articles')
                        ->with('success', 'Article featured successfully.');
      }
      else
      {
        return redirect()->route('all-articles')
                        ->with('ErrorMessage', 'There is some error.');
      }
    }
    else
    {
      return redirect()->route('all-articles')
                        ->with('ErrorMessage', 'Article was not found.');
    }
    
  }
}