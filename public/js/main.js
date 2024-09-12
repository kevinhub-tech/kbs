
const toast = (status, message) => {
    let className = 'kbs-success';
    if (status === 'info') {
        className = 'kbs-info'
    } else if (status === 'error') {
        className = 'kbs-error'
    }
    Toastify({
        text: message,
        duration: 3000,
        close: true,
        style: {
            background: 'none'
        },
        offset: {
            x: 10,
            y: 65
        },
        className: className,
    }).showToast();
}

const formValidate = () => {
    let noEmptyInputs = true;
    $('input').each(function (index, input) {
        let jinput = $(input);
        if (jinput.attr('validate')) {
            if (jinput.val().length === 0) {
                jinput.addClass('input-empty');
                noEmptyInputs = false;
            } else {
                jinput.removeClass('input-empty');
            };
        }
    })

    $('input').each(function (index, input) {
        let kinput = $(input);
        if (kinput.attr('validate')) {
            if (kinput.val().length === 0) {
                kinput.addClass('input-empty');
                noEmptyInputs = false;
            } else {
                kinput.removeClass('input-empty');
            };
        }
    })

    $('select').each(function (index, select) {
        let kselect = $(select);
        if (kselect.attr('validate')) {
            if (kselect.val().length === 0) {
                if (kselect.attr('multiple')) {
                    kselect.next().addClass('input-empty');
                } else {
                    kselect.addClass('input-empty');
                }
                noEmptyInputs = false;
            } else {
                kselect.removeClass('input-empty');
            };
        }
    })

    $('textarea').each(function (index, textarea) {
        let ktextarea = $(textarea);
        if (ktextarea.attr('validate')) {
            if (ktextarea.val().length === 0) {
                ktextarea.addClass('input-empty');
                noEmptyInputs = false;
            } else {
                ktextarea.removeClass('input-empty');
            };
        }
    })
    if (!noEmptyInputs) {
        toast('error', 'All red fields must be filled!');
    }
    return noEmptyInputs
}

$(document).ready(function () {

    $('[data-toggle="tooltip"]').tooltip();
    // Getting user cart + favourite count and showing the count only if it is not 0
    let cartCount = parseInt($("small.cart-count").html());
    let favouriteCount = parseInt($("small.favourite-count").html());

    if (cartCount === 0) {
        $("small.cart-count").css('display', 'none');
    }
    if (favouriteCount === 0) {
        $("small.favourite-count").css('display', 'none');
    }

    $('div.search-bar i.fa-solid.fa-magnifying-glass').on('click', (e) => {
        let searchIcon = e.currentTarget;
        let route = searchIcon.dataset.route;
        let category = searchIcon.previousElementSibling.previousElementSibling.value;
        let searchValue = searchIcon.previousElementSibling.value;

        if (searchValue.length === 0) {
            toast('error', 'Search value cannot be empty!')
        }

        window.location.href = route + '?c=' + category + '&v=' + searchValue;
    })

    $("div.star-rating").each(function (index, div) {
        let divStarRating = $(div);
        let rating = divStarRating.attr('rating') - 1;
        for (i = 0; i <= rating; i++) {
            divStarRating.children().eq(i).addClass('checked');
        }
    })

});