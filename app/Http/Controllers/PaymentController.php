<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;

use Illuminate\Http\Request;

use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Charge;

class PaymentController extends Controller
{
	/**
	 * to redirect user to payment page
	 */

    public function makePayment()
    {
        return view('payment.pay');
    }

    /**
	 * to redirect user to success page on payemnt successful
	 */

    public function succesful($CHECKOUT_SESSION_ID)
    {
        return view('payment.success')->with('CHECKOUT_SESSION_ID', $CHECKOUT_SESSION_ID);
    }
  
    /**
     * to create payment request
     *
     * @return \Illuminate\Http\Response
     */
    public function doPayment(Request $request)
    {
    	
    	try {
		
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
		}

		catch (Exception $e)
	    {
	        report($e);
	        return false;
	    }
	    
        
    }
}
