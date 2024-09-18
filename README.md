# KBS

KBS stands for "Kevin Book Store" and it is a e-commerce project with three different users (admin, vendor, customers).

## Applied Technology

-   [Laravel v11.x](https://laravel.com/docs/11.x)
-   Jquery v3.7.1
-   [Bootstrap 5.3](https://getbootstrap.com/docs/5.3/getting-started/introduction/)
-   [SweetAlert2]()
-   [toastify](https://apvarun.github.io/toastify-js/)
-   HTML5
-   CSS
-   JavaScript(ES6)
-   [Socialite](https://laravel.com/docs/11.x/socialite)
-   [Livewire](https://laravel-livewire.com/)
-   MySQL
-   PhpMyAdmin
-   XAMPP

## Features in the project

-   Users
    -   Cart System
    -   Favourites
    -   Order Tracking
    -   Review System
    -   Facebook/Google Login
-   Vendors
    -   Book Management
    -   Order Management
    -   Discount Management
    -   Dashboard
-   Admin
    -   Vendor Application Management
    -   Vendor Parternship Management
    -   Dashboard

# Run the project

You can create your own folder and clone this repo directly into your newly create folder. After that, proceed to the project main folder and write command.

`composer install`

This will install all other dependencies that have been used inside the project.

After installing all dependencies, you will need to configure your env file. You will be needing this credential set up in your env file to make the program run correctly.
- DB_DATABASE
- DB_USERNAME
- FACEBOOK_CLIENT_ID
- FACEBOOK_CLIENT_SECRET
- GOOGLE_CLIENT_ID
- GOOGLE_CLIENT_SECRET
- MAIL_MAILER
- MAIL_HOST
- MAIL_PORT
- MAIL_USERNAME
- MAIL_PASSWORD
- MAIL_ENCRYPTION
- MAIL_FROM_ADDRESS

Facebook and google client ID is for their Oauth Service Provider. For Mailing System, gmail is currently in use.

After setting up all the credentials, you can run these commands to set up the app key since this is laravel project.

`php artisan key:generate`

Run migration file after app key has been generated.

`php artisan migrate`

Once database tables are generated, you must run this data seeder for necessary data to be generated before you proceed any process on the app since these data are foundation of the data.

```
php artisan db:seed --class=RoleSeeder
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=CategorySeeder
php artisan db:seed --class=VendorApplicationSeeder
php artisan db:seed --class=VendorInfoSeeder
```

> [!CAUTION]
> Please run the exact command in the exact order provided above. Otherwise, it will not work as there are relationship between tables.

After that, you can navigate through vendor system to create demo book by logging in from this "http://localhost:8000/vendor/login" and test out all the featured mentioned above 🌟.
