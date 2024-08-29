/**
 * Common function starts here
 *  */

$("h3.kbs-remove-all").on('click', function (e) {
    let removeType = e.currentTarget.dataset.removeType;
    let popupText = null;
    let route = e.currentTarget.dataset.route;
    let token = e.currentTarget.dataset.token;
    if (removeType === 'favourite') {
        popupText = "You are about to delete all item from your favourites. Are you sure?"
    } else if (removeType === 'cart') {
        popupText = "You are about to delete all item in your cart. Are you sure?"
    }

    Swal.fire({
        title: "Warning!",
        icon: 'info',
        text: popupText,
        showConfirmButton: true,
        showDenyButton: true,
        allowOutsideClick: false,
        confirmButtonText: 'Proceed',
        denyButtonText: "Cancel"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: route,
                method: 'POST',
                headers: {
                    Accept: "application/json",
                    Authorization: token
                },
                data: {
                    method: 'all'
                },
                success: (res) => {
                    if (res.status === 'success') {
                        toast('success', res.message);
                        if (removeType === 'favourite') {
                            $("small.favourite-count").html(res.payload
                                .favourite_count);
                            $("small.favourite-count").css('display', 'none');

                            $('section.kbs-favourite-listing-container').remove();
                            $('h3.kbs-remove-all').remove();
                            let cartEmptyhtml = `
<section class="kbs-favourite-empty">
<h3>
    You currently don't have any favourite books yet...
</h3>
</section>`;
                            $('main').append(cartEmptyhtml);
                        }

                        if (removeType === 'cart') {
                            $("small.cart-count").html(res.payload.cart_count);
                            $("small.cart-count").css('display', 'none');

                            $('section.kbs-main-cart-container').remove();
                            $('h3.kbs-remove-all').remove();
                            let cartEmptyhtml = `
<section class="kbs-cart-empty">
<h3>
    Your cart is empty at the moment...
</h3>
</section>`;
                            $('main').append(cartEmptyhtml);
                        }

                    }
                },
                error: (jqXHR, exception) => {
                    var errorMessage = "";

                    if (jqXHR.status === 0) {
                        errorMessage =
                            "Not connect.\n Verify Network.";
                    } else if (jqXHR.status == 404) {
                        errorMessage =
                            "Requested page not found. [404]";
                    } else if (jqXHR.status == 409) {
                        errorMessage = jqXHR.responseJSON.message;
                    } else if (jqXHR.status == 500) {
                        errorMessage =
                            "Internal Server Error [500].";
                    } else if (exception === "parsererror") {
                        errorMessage =
                            "Requested JSON parse failed.";
                    } else if (exception === "timeout") {
                        errorMessage = "Time out error.";
                    } else if (exception === "abort") {
                        errorMessage = "Ajax request aborted.";
                    } else {
                        let html = ''
                        Object.values(jqXHR.responseJSON.errors).forEach((
                            err) => {
                            err.forEach((e) => {
                                html += `${e}
<hr />`;
                            });
                        });
                        Swal.fire({
                            title: 'Error!',
                            html: html,
                            icon: 'error',
                            animation: true,
                            showConfirmButton: true,
                        })
                        return;
                    }
                    toast("error", errorMessage);
                }
            })
        } else if (result.isDenied) {
            Swal.close();
        }
    })
})


$("div.star-rating").each(function (index, div) {
    let divStarRating = $(div);
    let rating = divStarRating.attr('rating') - 1;
    for (i = 0; i <= rating; i++) {
        divStarRating.children().eq(i).addClass('checked');
    }
})

$("div.cart-quantity button").on('click', function (e) {
    let button = e.currentTarget;
    let buttonClassName = e.currentTarget.className
    let stock = parseInt(button.parentElement.previousElementSibling.previousElementSibling
        .previousElementSibling.children[0].innerHTML);
    let totalPriceElement = button.parentElement.nextElementSibling.children[0];
    let totalPrice = parseFloat(button.parentElement.nextElementSibling.children[0]
        .getAttribute('original-price'));
    let addButton = button.parentElement.parentElement.parentElement.parentElement
        .nextElementSibling.children[1];
    if (buttonClassName === 'add-quantity') {
        let quantityElement = button.previousElementSibling;
        let quantity = parseInt(quantityElement.innerHTML);
        quantity += 1;
        if (quantity === stock) {
            quantityElement.innerHTML = quantity;
            button.setAttribute('disabled', true);
        } else if (quantity > 1) {
            button.previousElementSibling.previousElementSibling.removeAttribute('disabled');
            quantityElement.innerHTML = quantity;
        } else {
            quantityElement.innerHTML = quantity;
        }
        totalPrice *= quantity;
        totalPriceElement.innerHTML = totalPrice;
        addButton.dataset.quantity = quantity;
    } else {
        let quantityElement = button.nextElementSibling;
        let quantity = parseInt(quantityElement.innerHTML);
        quantity -= 1;

        if (quantity < 2) {
            button.setAttribute('disabled', true);
            quantityElement.innerHTML = quantity;
        } else if (quantity < stock) {
            button.nextElementSibling.nextElementSibling.removeAttribute('disabled');
            quantityElement.innerHTML = quantity;
        } else {
            quantityElement.innerHTML = quantity;
        }
        totalPrice *= quantity;
        totalPriceElement.innerHTML = totalPrice;
        addButton.dataset.quantity = quantity;
    }
})
$("div.modal-footer button.kbs-btn").on('click', function (e) {
    // Get book_id and quantity
    let bookId = $(this).data('book-id');
    let quantity = $(this).data('quantity');
    let route = $(this).data('route');
    let token = $(this).data('token');
    // Call ajax and once it is succesful, check cart-count and add that number there
    $.ajax({
        url: route,
        method: 'POST',
        headers: {
            Accept: "application/json",
            Authorization: token
        },
        data: {
            book_id: bookId,
            quantity: quantity
        },
        success: (res) => {
            if (res.status === 'success') {
                toast('success', res.message);
                $("small.cart-count").html(res.payload.cart_count);
                $("small.cart-count").css('display', 'flex');
                setTimeout(() => {
                    $(`#${bookId}`).modal('hide');
                }, 1500);
            }
        },
        error: (jqXHR, exception) => {
            var errorMessage = "";

            if (jqXHR.status === 0) {
                errorMessage =
                    "Not connect.\n Verify Network.";
            } else if (jqXHR.status == 404) {
                errorMessage =
                    "Requested page not found. [404]";
            } else if (jqXHR.status == 409) {
                errorMessage = jqXHR.responseJSON.message;
            } else if (jqXHR.status == 500) {
                errorMessage =
                    "Internal Server Error [500].";
            } else if (exception === "parsererror") {
                errorMessage =
                    "Requested JSON parse failed.";
            } else if (exception === "timeout") {
                errorMessage = "Time out error.";
            } else if (exception === "abort") {
                errorMessage = "Ajax request aborted.";
            } else {
                let html = ''
                Object.values(jqXHR.responseJSON.errors).forEach((err) => {
                    err.forEach((e) => {
                        html += `${e} <hr />`;
                    });
                });
                Swal.fire({
                    title: 'Error!',
                    html: html,
                    icon: 'error',
                    animation: true,
                    showConfirmButton: true,
                })
                return;
            }
            toast("error", errorMessage);
        }
    })
})

$("i.fa-solid.fa-heart.add-to-favourite").on('click', (e) => {
    let bookId = e.currentTarget.dataset.bookId;
    let route = e.currentTarget.dataset.route;
    let token = e.currentTarget.dataset.token;
    // Call ajax and once it is succesful, check cart-count and add that number there
    $.ajax({
        url: route,
        method: 'POST',
        headers: {
            Accept: "application/json",
            Authorization: token
        },
        data: {
            book_id: bookId
        },
        success: (res) => {
            if (res.status === 'success') {
                toast('success', res.message);
                $("small.favourite-count").html(res.payload.favourite_count);
                $("small.favourite-count").css('display', 'flex');
            }
        },
        error: (jqXHR, exception) => {
            var errorMessage = "";

            if (jqXHR.status === 0) {
                errorMessage =
                    "Not connect.\n Verify Network.";
            } else if (jqXHR.status == 404) {
                errorMessage =
                    "Requested page not found. [404]";
            } else if (jqXHR.status == 409) {
                errorMessage = jqXHR.responseJSON.message;
            } else if (jqXHR.status == 500) {
                errorMessage =
                    "Internal Server Error [500].";
            } else if (exception === "parsererror") {
                errorMessage =
                    "Requested JSON parse failed.";
            } else if (exception === "timeout") {
                errorMessage = "Time out error.";
            } else if (exception === "abort") {
                errorMessage = "Ajax request aborted.";
            } else {
                let html = ''
                Object.values(jqXHR.responseJSON.errors).forEach((err) => {
                    err.forEach((e) => {
                        html += `${e} <hr />`;
                    });
                });
                Swal.fire({
                    title: 'Error!',
                    html: html,
                    icon: 'error',
                    animation: true,
                    showConfirmButton: true,
                })
                return;
            }
            toast("error", errorMessage);
        }
    })
})


/**
 * Home function starts here
 *  */
const homeFunction = () => {
    const setBookListingHeightDynamically = () => {
        if (screen.width > 1116) {
            if ($(".kbs-book-listing-container").height() > $(".kbs-book-categories ul").height()) {
                $(".kbs-book-listing-container").css("height", $(".kbs-book-categories ul")
                    .height());
            }
            $(".kbs-book-listing-container").css("height", $(".kbs-book-categories ul")
                .height());
        } else {
            $(".kbs-book-listing-container").removeAttr('style');
        }
    }
    $(document).ready(function () {
        $(window).resize(function () {
            setBookListingHeightDynamically();
        });
        setBookListingHeightDynamically();

    });

    $('aside.kbs-book-categories ul li a').on('click', (e) => {
        let route = e.currentTarget.dataset.route;
        let category = e.currentTarget.dataset.categoryId;
        window.location.href = route + '?category=' + category;
    })
}

/**
 * Cart function starts here
 *  */
const cartFunction = () => {
    const updateTotalSection = () => {

        let totalLatestBookCost = 0;
        let totalDeliveryFee = 0;
        let vendorId = [];
        $('div.kbs-cart-book-details').each((index, div) => {
            if (!vendorId.includes($(div).data(
                'vendorId'))) {
                vendorId.push($(div).data(
                    'vendorId'))
            }
        })

        vendorId.forEach((id, index) => {
            let deliveryFee = 0;
            let bookCount = 0;
            $(`div.kbs-cart-book-details[data-vendor-id=${id}]`).each((index, div) => {

                totalLatestBookCost += parseFloat($(div).children()
                    .eq(1).html());

                deliveryFee += parseFloat($(div).data(
                    'deliveryPrice'));
                bookCount = index + 1;
            })

            totalDeliveryFee += deliveryFee / bookCount;
        })


        $('div.kbs-cart-book-cost').children().eq(1).html(
            totalLatestBookCost);

        $('div.kbs-cart-book-delivery-cost').children().eq(1)
            .html(
                totalDeliveryFee);

        let finalUpdatedSumCost = totalLatestBookCost +
            totalDeliveryFee;
        $('div.kbs-cart-book-total-cost').children().eq(1)
            .html(
                finalUpdatedSumCost.toFixed(2));
    }

    $("input[type='checkbox']").on('click', function (e) {
        let bookId = $(this).val();
        let price = $(this).data('originalPrice');
        let deliPrice = $(this).data('deliveryPrice');
        let bookTitle = $(this).data('title');
        let bookQuantity = $(this).data('quantity');
        let vendorId = $(this).data('vendorId');
        if ($(this).prop("checked")) {
            let selectedBook = `
            <div class="kbs-cart-book-details" data-book-id="${bookId}" data-original-price=" ${price} " data-delivery-price="${deliPrice}" data-vendor-id="${vendorId}">
                            <h5>${bookTitle} x <span class="quantity">${bookQuantity}</span></h5>
                                                            <h5>${price * bookQuantity}</h5>
                                                    </div>`

            $('section.kbs-cart-book-details-container').append(selectedBook);
        } else {
            $(`div.kbs-cart-book-details[data-book-id='${bookId}']`)
                .remove();
        }
        updateTotalSection();
    })
    $("div.kbs-cart-book-quantity button").on('click', function (e) {
        let button = e.currentTarget;
        let buttonClassName = e.currentTarget.className
        let stock = parseInt(button.dataset.stock);
        let totalSubBookPriceElement = button.parentElement.nextElementSibling.nextElementSibling.children[1];
        let totalPriceElement = button.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.children[1];
        let totalPrice = parseFloat(totalPriceElement.dataset.price);
        let DeliveryFee = parseFloat(totalPriceElement.dataset.deliveryFee);
        let addButton = button.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.children[
            0];
        $(addButton).css('display', 'inline-block');
        if (buttonClassName === 'add-quantity') {
            let quantityElement = button.previousElementSibling;
            let quantity = parseInt(quantityElement.innerHTML);
            quantity += 1;
            if (quantity === stock) {
                quantityElement.innerHTML = quantity;
                button.setAttribute('disabled', true);
            } else if (quantity > 1) {
                button.previousElementSibling.previousElementSibling.removeAttribute('disabled');
                quantityElement.innerHTML = quantity;
            } else {
                quantityElement.innerHTML = quantity;
            }
            totalPrice *= quantity;
            totalSubBookPriceElement.innerHTML = totalPrice.toFixed(2);
            totalPrice += DeliveryFee;
            totalPriceElement.innerHTML = totalPrice.toFixed(2);
            addButton.dataset.quantity = quantity;
        } else {
            let quantityElement = button.nextElementSibling;
            let quantity = parseInt(quantityElement.innerHTML);
            quantity -= 1;

            if (quantity < 2) {
                button.setAttribute('disabled', true);
                quantityElement.innerHTML = quantity;
            } else if (quantity < stock) {
                button.nextElementSibling.nextElementSibling.removeAttribute('disabled');
                quantityElement.innerHTML = quantity;
            } else {
                quantityElement.innerHTML = quantity;
            }
            totalPrice *= quantity;
            totalSubBookPriceElement.innerHTML = totalPrice.toFixed(2);
            totalPrice += DeliveryFee;
            totalPriceElement.innerHTML = totalPrice.toFixed(2);
            addButton.dataset.quantity = quantity;
        }
    })

    $("i.fa-solid.fa-check").on('click', function (e) {
        let route = e.currentTarget.dataset.route;
        let token = e.currentTarget.dataset.token;
        Swal.fire({
            title: "Info!",
            icon: 'info',
            text: "You are about to update an item in your cart. Are you sure?",
            showConfirmButton: true,
            showDenyButton: true,
            allowOutsideClick: false,
            confirmButtonText: 'Update',
            denyButtonText: "Cancel"
        }).then((result) => {
            if (result.isConfirmed) {
                let bookId = e.currentTarget.dataset.bookId;
                let updatedBookQuantity = parseInt(e.currentTarget.dataset.quantity);

                $.ajax({
                    url: route,
                    method: 'POST',
                    headers: {
                        Accept: "application/json",
                        Authorization: token
                    },
                    data: {
                        book_id: bookId,
                        quantity: updatedBookQuantity
                    },
                    success: (res) => {
                        if (res.status === 'success') {
                            toast('success', res.message);
                            $(this).css('display', 'none');
                            $("small.cart-count").html(res.payload.cart_count);
                            $("small.cart-count").css('display', 'flex');

                            let originalPrice = parseFloat($(
                                `div.kbs-cart-book-details[data-book-id='${bookId}']`
                            )
                                .data('original-price'));

                            $(`div.kbs-cart-book-details[data-book-id='${bookId}']`)
                                .children().eq(0).children().html(updatedBookQuantity);
                            let latestSubTotal = originalPrice * updatedBookQuantity;

                            $(`div.kbs-cart-book-details[data-book-id='${bookId}']`)
                                .children().eq(1).html(latestSubTotal.toFixed(2));

                            updateTotalSection();
                        }
                    },
                    error: (jqXHR, exception) => {
                        var errorMessage = "";

                        if (jqXHR.status === 0) {
                            errorMessage =
                                "Not connect.\n Verify Network.";
                        } else if (jqXHR.status == 404) {
                            errorMessage =
                                "Requested page not found. [404]";
                        } else if (jqXHR.status == 409) {
                            errorMessage = jqXHR.responseJSON.message;
                        } else if (jqXHR.status == 500) {
                            errorMessage =
                                "Internal Server Error [500].";
                        } else if (exception === "parsererror") {
                            errorMessage =
                                "Requested JSON parse failed.";
                        } else if (exception === "timeout") {
                            errorMessage = "Time out error.";
                        } else if (exception === "abort") {
                            errorMessage = "Ajax request aborted.";
                        } else {
                            let html = ''
                            Object.values(jqXHR.responseJSON.errors).forEach((
                                err) => {
                                err.forEach((e) => {
                                    html += `${e} <hr />`;
                                });
                            });
                            Swal.fire({
                                title: 'Error!',
                                html: html,
                                icon: 'error',
                                animation: true,
                                showConfirmButton: true,
                            })
                            return;
                        }
                        toast("error", errorMessage);
                    }
                })
            } else if (result.isDenied) {
                Swal.close();
            }
        })
    })

    $("i.fa-solid.fa-trash").on('click', function (e) {
        let route = e.currentTarget.dataset.route;
        let token = e.currentTarget.dataset.token;
        Swal.fire({
            title: "Info!",
            icon: 'info',
            text: "You are about to delete an item in your cart. Are you sure?",
            showConfirmButton: true,
            showDenyButton: true,
            allowOutsideClick: false,
            confirmButtonText: 'Remove',
            denyButtonText: "Cancel"
        }).then((result) => {
            if (result.isConfirmed) {
                let bookId = e.currentTarget.dataset.bookId;
                $.ajax({
                    url: route,
                    method: 'POST',
                    headers: {
                        Accept: "application/json",
                        Authorization: token
                    },
                    data: {
                        book_id: bookId,
                        method: 'partial'
                    },
                    success: (res) => {
                        if (res.status === 'success') {
                            toast('success', res.message);
                            if (res.payload.cart_count > 0) {
                                $("small.cart-count").html(res.payload.cart_count);
                                $("small.cart-count").css('display', 'flex');

                                // Remove from cart item and cart summary
                                $(`div.kbs-cart-card[data-book-id='${bookId}']`)
                                    .remove();

                                // Reset serial number
                                $('div.kbs-cart-card').each((index, div) => {
                                    $(div).children().eq(0).html(index + 1);
                                })

                                $(`div.kbs-cart-book-details[data-book-id='${bookId}']`)
                                    .remove();

                                updateTotalSection();
                            } else if (res.payload.cart_count === 0) {
                                $("small.cart-count").html(res.payload.cart_count);
                                $("small.cart-count").css('display', 'none');
                                $('section.kbs-main-cart-container').remove();
                                $('h3.kbs-remove-all').remove();
                                let cartEmptyhtml = `  
                            <section class="kbs-cart-empty">
                                <h3>
                                    Your cart is empty at the moment...
                                </h3>
                            </section>`;
                                $('main').append(cartEmptyhtml);
                            }

                        }
                    },
                    error: (jqXHR, exception) => {
                        var errorMessage = "";

                        if (jqXHR.status === 0) {
                            errorMessage =
                                "Not connect.\n Verify Network.";
                        } else if (jqXHR.status == 404) {
                            errorMessage =
                                "Requested page not found. [404]";
                        } else if (jqXHR.status == 409) {
                            errorMessage = jqXHR.responseJSON.message;
                        } else if (jqXHR.status == 500) {
                            errorMessage =
                                "Internal Server Error [500].";
                        } else if (exception === "parsererror") {
                            errorMessage =
                                "Requested JSON parse failed.";
                        } else if (exception === "timeout") {
                            errorMessage = "Time out error.";
                        } else if (exception === "abort") {
                            errorMessage = "Ajax request aborted.";
                        } else {
                            let html = ''
                            Object.values(jqXHR.responseJSON.errors).forEach((
                                err) => {
                                err.forEach((e) => {
                                    html += `${e} <hr />`;
                                });
                            });
                            Swal.fire({
                                title: 'Error!',
                                html: html,
                                icon: 'error',
                                animation: true,
                                showConfirmButton: true,
                            })
                            return;
                        }
                        toast("error", errorMessage);
                    }
                })
            } else if (result.isDenied) {
                Swal.close();
            }
        })
    })
}

/**
 * Favourite function starts here
 *  */

const favouriteFunction = () => {
    $("i.fa-solid.fa-x").on('click', function (e) {

        let route = e.currentTarget.dataset.route;
        let token = e.currentTarget.dataset.token;
        Swal.fire({
            title: "Info!",
            icon: 'info',
            text: "You are about to remove an item from your favourite. Are you sure?",
            showConfirmButton: true,
            showDenyButton: true,
            allowOutsideClick: false,
            confirmButtonText: 'Remove',
            denyButtonText: "Cancel"
        }).then((result) => {
            if (result.isConfirmed) {
                let bookId = e.currentTarget.dataset.bookId;
                $.ajax({
                    url: route,
                    method: 'POST',
                    headers: {
                        Accept: "application/json",
                        Authorization: token
                    },
                    data: {
                        book_id: bookId,
                        method: 'partial'
                    },
                    success: (res) => {
                        if (res.status === 'success') {
                            toast('success', res.message);

                            if (res.payload.favourite_count > 0) {
                                $("small.favourite-count").html(res.payload.favourite_count);
                                $("small.favourite-count").css('display', 'flex');
                                $(`div.kbs-favourite-book-card[data-book-id='${bookId}']`).remove();
                            } else if (res.payload.favourite_count === 0) {
                                $("small.favourite-count").html(res.payload.favourite_count);
                                $("small.favourite-count").css('display', 'none');
                                $('section.kbs-favourite-listing-container').remove();
                                $('h3.kbs-remove-all').remove();
                                let cartEmptyhtml = `  
                            <section class="kbs-favourite-empty">
                                <h3>
                                    You currently don't have any favourite books yet...
                                </h3>
                            </section>`;
                                $('main').append(cartEmptyhtml);
                            }

                        }
                    },
                    error: (jqXHR, exception) => {
                        var errorMessage = "";

                        if (jqXHR.status === 0) {
                            errorMessage =
                                "Not connect.\n Verify Network.";
                        } else if (jqXHR.status == 404) {
                            errorMessage =
                                "Requested page not found. [404]";
                        } else if (jqXHR.status == 409) {
                            errorMessage = jqXHR.responseJSON.message;
                        } else if (jqXHR.status == 500) {
                            errorMessage =
                                "Internal Server Error [500].";
                        } else if (exception === "parsererror") {
                            errorMessage =
                                "Requested JSON parse failed.";
                        } else if (exception === "timeout") {
                            errorMessage = "Time out error.";
                        } else if (exception === "abort") {
                            errorMessage = "Ajax request aborted.";
                        } else {
                            let html = ''
                            Object.values(jqXHR.responseJSON.errors).forEach((
                                err) => {
                                err.forEach((e) => {
                                    html += `${e} <hr />`;
                                });
                            });
                            Swal.fire({
                                title: 'Error!',
                                html: html,
                                icon: 'error',
                                animation: true,
                                showConfirmButton: true,
                            })
                            return;
                        }
                        toast("error", errorMessage);
                    }
                })
            } else if (result.isDenied) {
                Swal.close();
            }
        })
    })
}

/**
 * Checkout function starts here
 *  */

const checkoutFunction = () => {
    $('input[type="checkbox"]').on('click', function (event) {
        let addressId = $(this).data('addressId');
        let checkboxParentSection = $(this).parent().parent().parent();
        $(checkboxParentSection).children().each((index, element) => {
            if ($(element).prop('nodeName') === 'DIV') {
                let checkbox = $(element).children().eq(0).children().eq(0);
                if (checkbox.data('addressId') !== addressId) {
                    checkbox.prop('checked', false);
                } else {
                    checkbox.prop('checked', true);
                }
            }
        });
    });

    $('button.kbs-confirm-order').on('click', function (event) {
        let addressId = $('input[type="checkbox"][name="address"]:checked').val();
        let billingAddressId = $('input[type="checkbox"][name="billing_address"]:checked').val();
        let payment = $('input[type="checkbox"][name="payment"]:checked').val();
        let total = parseFloat($('div.kbs-order-book-total-cost').children().eq(1).children().eq(0).html());
        let route = $(this).data('route');
        let token = $(this).data('token');
        let redirectUrl = $(this).data('redirectUrl');
        let orderedBooks = [];
        $('div.kbs-order-book-details').each((index, div) => {
            let bookId = $(div).data('bookId');
            let price = $(div).data('originalPrice');
            let deliveryFee = $(div).data('deliveryPrice');
            let quantity = $(div).data('quantity');
            orderedBooks[index] = {
                book_id: bookId,
                quantity: quantity,
                ordered_book_price: price,
                ordered_book_delivery_fee: deliveryFee
            }
        })
        $.ajax({
            url: route,
            method: 'POST',
            headers: {
                Accept: "application/json",
                Authorization: token
            },
            data: {
                address_id: addressId,
                billing_address_id: billingAddressId,
                payment: payment,
                total: total,
                order_book_mapping: orderedBooks
            },
            success: (res) => {
                if (res.status === 'success') {
                    toast('success', res.message);

                    let order_number = res.payload.order_number
                    setTimeout(() => {
                        window.location.href = redirectUrl + '?' + 'id=' + order_number;
                    }, 1500);
                }
            },
            error: (jqXHR, exception) => {
                var errorMessage = "";

                if (jqXHR.status === 0) {
                    errorMessage =
                        "Not connect.\n Verify Network.";
                } else if (jqXHR.status == 404) {
                    errorMessage =
                        "Requested page not found. [404]";
                } else if (jqXHR.status == 409) {
                    errorMessage = jqXHR.responseJSON.message;
                } else if (jqXHR.status == 500) {
                    errorMessage =
                        "Internal Server Error [500].";
                } else if (exception === "parsererror") {
                    errorMessage =
                        "Requested JSON parse failed.";
                } else if (exception === "timeout") {
                    errorMessage = "Time out error.";
                } else if (exception === "abort") {
                    errorMessage = "Ajax request aborted.";
                } else {
                    let html = ''
                    Object.values(jqXHR.responseJSON.errors).forEach((
                        err) => {
                        err.forEach((e) => {
                            html += `${e} <hr />`;
                        });
                    });
                    Swal.fire({
                        title: 'Error!',
                        html: html,
                        icon: 'error',
                        animation: true,
                        showConfirmButton: true,
                    })
                    return;
                }
                toast("error", errorMessage);
            }
        })
    })
}