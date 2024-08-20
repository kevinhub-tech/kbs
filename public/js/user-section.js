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
}

/**
 * Cart function starts here
 *  */
const cartFunction = () => {
    const updateTotalSection = () => {

        let totalLatestBookCost = 0;
        let totalDeliveryFee = 0;
        let totalBookCount = 0;
        $('div.kbs-cart-book-details').each((index, div) => {
            totalLatestBookCost += parseFloat($(div).children()
                .eq(1).html());


            totalDeliveryFee += parseFloat($(div).data(
                'deliveryPrice'));
            totalBookCount = index + 1;
        })
        let finalUpdatedDeliveryFee = totalDeliveryFee / totalBookCount;
        $('div.kbs-cart-book-cost').children().eq(1).html(
            totalLatestBookCost);

        $('div.kbs-cart-book-delivery-cost').children().eq(1)
            .html(
                finalUpdatedDeliveryFee);

        let finalUpdatedSumCost = totalLatestBookCost +
            finalUpdatedDeliveryFee;
        $('div.kbs-cart-book-total-cost').children().eq(1)
            .html(
                finalUpdatedSumCost.toFixed(2));
    }
    $("div.kbs-cart-book-quantity button").on('click', function (e) {
        let button = e.currentTarget;
        let buttonClassName = e.currentTarget.className
        let stock = parseInt(button.dataset.stock);

        let totalPriceElement = button.parentElement.nextElementSibling.nextElementSibling.children[1];
        let totalPrice = parseFloat(button.parentElement.nextElementSibling.children[1]
            .innerHTML);
        let addButton = button.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.children[
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
                                .children().eq(1).html(latestSubTotal);

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