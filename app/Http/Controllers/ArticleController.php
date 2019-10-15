<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleRequest;
use Illuminate\Http\Request;

use App\Models\Article;
use App\Models\Category;
use App\Models\ArticleCategories;
use App\Models\Comment;
use App\Models\Payment;
use Exception;

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
            $articles = new Article;
            $data = $articles->allArticles();
            return $data;
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
            return redirect()->route('all-articles')->with('ErrorMessage', 'Article was not found.');
        }
        else
        {
            if(!empty($article) && (auth()->user()->is_admin !=1) && ($article->user_id !== auth()->user()->id))
            {
                return redirect()->route('all-articles')->with('ErrorMessage', 'You are not authorised for this action.');
            }
            $sel_categories = ($id==0) ? array() : $article->categories->pluck('id')->toArray();

            return view('site.wordify.articles.add')->with(['categories' => $all_categories, 'article' => $article, 'selected' => $sel_categories]);
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
        $article = new Article;
        $result = $article->addUpdateArticle($request, $id);

        if($result['errFlag'] == 0)
        {

            if((Article::find($result['article_id'])->paid_status) == '0')
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
        $article = new Article;
        $result = $article->deleteArticle($id);
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
        $article = new Article;
        $category = new Category;
        $comment = new Comment;
        $data = array();
        $data['related_articles'] = $article->related($slug);
        $data['article'] = $article->articleDetail($slug);
        $data['comments']= $comment->activeComments($slug);
        
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
            //to save the transaction details
              $payment->savePayment($charge, $request->article_id, '1');
              $article = Article::find($request->article_id);
              $result = $article->updateStatus($article);
              if($result['errFlag'] == 0)
              {
                  return redirect()->route('successful-payment');
              }
          }

        }catch(Card $e) {
          // Since it's a decline, \Stripe\Error\Card will be caught
          $errorMessages = $e->getMessage();
        } catch (RateLimit $e) {
          // Too many requests made to the API too quickly
            $errorMessages = $e->getMessage();
        } catch (InvalidRequest $e) {
          // Invalid parameters were supplied to Stripe's API
            $errorMessages = $e->getMessage();
        } catch (Authentication $e) {
          // Authentication with Stripe's API failed
            $errorMessages = $e->getMessage();
          // (maybe you changed API keys recently)
        } catch (ApiConnection $e) {
          // Network communication with Stripe failed
            $errorMessages = $e->getMessage();
        } catch (Base $e) {
          // Display a very generic error to the user, and maybe send
            $errorMessages = $e->getMessage();
          // yourself an email
        } catch (Exception $e) {
          // Something else happened, completely unrelated to Stripe
            $errorMessages = $e->getMessage();

        }
        //to save the transaction details
        $payment->savePayment($e, $request->article_id, '0');
        return back()->withErrors($errorMessages)->withInput();
    }
}