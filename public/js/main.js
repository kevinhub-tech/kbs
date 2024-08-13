
const toast = (status, message) => {
    let className = 'kbs-success';
    if (status === 'info') {
        className = 'kbs-info'
    } else if (status === 'error') {
        className = 'kbs-error'
    }
    Toastify({
        text: message,
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
