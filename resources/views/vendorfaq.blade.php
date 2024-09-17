@extends('main')
@section('main.content')
    <section class="kbs-faq">
        <h1>Vendor's FAQ</h1>
        <div class="accordion" id="accordionExample">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne"
                        aria-expanded="true" aria-controls="collapseOne">
                        What features are available on the vendor dashboard?
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        On the vendor dashboard, you'll find a detailed overview of your business performance. This includes
                        the <strong>
                            <ul class="mt-2">
                                <li>Total number of books listed</li>
                                <li>Active discounts</li>
                                <li>Total orders received</li>
                                <li>Customer
                                    reviews</li>
                            </ul>
                        </strong>
                        Additionally, you’ll have access to visual charts that break down your order statuses and track your
                        monthly revenue, giving you a clear view of your progress.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Is there a way to bulk upload books using CSV or Excel?
                    </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        At the moment, we don’t support bulk uploads via CSV or Excel. However, we've received feedback from
                        users about the need for this feature, and it’s a top priority for future updates.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        Can I apply bulk discounts to my books?
                    </button>
                </h2>
                <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        Yes, you can apply discounts to multiple books at once. Just select the books you want to discount,
                        but keep in mind that each book can only have one active discount at a time. If needed, you can
                        easily remove and update a discount on any book.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                        How easy is it to manage orders on the app?
                    </button>
                </h2>
                <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        Order management is designed to be straightforward and efficient. You can update an order status
                        with a single click, and any changes will automatically reflect on the customer’s order tracking
                        page. The system also allows you to search by order number and filter orders by status, ensuring a
                        smooth and hassle-free experience.
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
