To submit your article for approval you need to pay <i class="fa fa-inr"></i> 100.
    {!! Form::open(['url' => route('do-payment')]) !!}
        <script
            src="https://checkout.stripe.com/checkout.js" class="stripe-button"
            data-key="{{ env('STRIPE_KEY') }}"
            data-amount="10000"
            data-name="Pay for Submission"
            data-description="Pay fees to submit article"
            data-email="{{ auth()->user()->email }}"
            data-locale="auto"
            data-currency="inr">
        </script>
        {!! Form::hidden('article_id',$article_id)!!}
    {!! Form::close() !!}