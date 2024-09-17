@extends('main')
@section('main.content')
    <section class="kbs-faq">
        <h1>User's FAQ</h1>
        <div class="accordion" id="accordionExample">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne"
                        aria-expanded="true" aria-controls="collapseOne">
                        Is debit/credit card payment available?
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        Currently, we only support Cash on Delivery (COD) for payments. However, we're actively working on
                        adding debit/credit card and online payment options in future updates—stay tuned.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Can I save a book for later in the app?
                    </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        Yes! If you've found a book you'd like to come back to, you can save it under your
                        <strong>favorites</strong>. While
                        we don't have a dedicated wishlist feature yet, we're planning to roll that out soon to make it even
                        easier for you to keep track of the books you love.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        How can I track my order?
                    </button>
                </h2>
                <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        To check your order status, simply enter your order number on our tracking page. It's a quick way to
                        stay updated on the progress of your delivery. Here's the link. <a
                            href="{{ route('user.ordertracking') }}"></a>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                        How do I cancel an order, and what’s the policy?
                    </button>
                </h2>
                <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        You can cancel your order as long as <strong>the vendor hasn’t started packing it.</strong> Once
                        packing has begun,
                        we won’t be able to cancel it. So make sure to cancel early if needed!
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
