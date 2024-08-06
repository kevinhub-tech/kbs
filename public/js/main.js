
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
    let noEmptyInputs = false;
    $('input').each(function (index, input) {
        let jinput = $(input);
        if (jinput.attr('validate')) {
            if (jinput.val().length === 0) {
                jinput.addClass('input-empty');
                noEmptyInputs = true;
            } else {
                jinput.removeClass('input-empty');
            };
        }
    })
    if (noEmptyInputs) {
        toast('error', 'All red fields must be filled!');
    }
    return noEmptyInputs
}
