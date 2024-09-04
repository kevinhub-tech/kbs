<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<style>
    /* Author/Vendor/Username fontstyle */
    @import url('https://fonts.googleapis.com/css2?family=Roboto+Mono:ital,wght@0,100..700;1,100..700&display=swap');

    /* Description/Generat Use fontstyle */
    @import url('https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&family=Roboto+Mono:ital,wght@0,100..700;1,100..700&display=swap');

    /* Heading, Title, Nav font-style */
    @import url('https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100..900;1,100..900&display=swap');

    * {
        margin: 0;
        padding: 0;
        border: none;
        outline: none;
        box-shadow: none;
        box-sizing: border-box;
    }


    body {
        margin: 0;
        padding: 0;
        height: 100dvh;
    }

    body>section {
        height: 100%;
        display: flex;
        justify-content: center;
        align-content: center;
        flex-direction: column;
        text-align: center;
    }

    body>section h4 {
        margin-bottom: 1rem;
        font-family: "Jost", sans-serif;
        font-size: 1.5rem;
        font-weight: 600
    }

    body>section a {
        background-color: #005D6C;
        padding: 1rem;
        width: 15%;
        margin: 0 auto;
        color: #ffffff;
        text-decoration: none;
        border-radius: 0.5rem;
        font-family: "Roboto Mono", monospace;
        font-weight: 600;
    }

    body>section a:hover {
        background-color: #ffffff;
        color: #005D6C;
        border: 1px dashed #005D6C;
        transition: all 0.4s ease;
    }

    body>section span.accepted {
        color: #005D6C
    }
</style>

<body>
    <section>
        <h4>
            Dear, {{ $vendor_application->email }}
        </h4>
        <h4>
            We would like to inform you that your partnership with KBS has been <span class="accepted">accepted
            </span>.
        </h4>
        <h4>
            Please fill out all the information about your vendor to proceed with gaining access to the app. <span
                style="color:red"> (This link is only available for 24 hours. If it expired, you can regenerate link from
                that page itself)</span>
        </h4>
        <a href="{{ route('vendor.vendorinfo', ['token' => $vendor_application->token]) }}">Click Here to fill out
            information</a>

    </section>
</body>

</html>
