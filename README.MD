# Basic project

Basic project to show some vanilla skills.This project is a showcase for some vanilla HTML/CSS, Javascript, and PHP skills. Some light object-basec programming is used.

## How to install

* Download this GIT repository
* Create a database
* run /install.php

The installer will create the database tables, indexes, and fille the messages table with information. All the messages are bound to the first user that is created.

## Related task information
### User registration system
* Create a registration form with fields for display name, email address and password.
    * Open the home page and click "Register" on the login form.

* implement server-side validation with checks on display name, email address and password.

    * Fill in the register form with faulty information. A front-end check will be done on the user input, after providing information that passes the front-end validation, the back-end (user Object) will take over and will check the information based on a RegEX.

* Save the user information safely in a database table.

    * After validation is done, user information is saved in the database.
### Authentication
* Develop a login system where users can authenticate themselves with their user name and chosen password.

    * Open the  home page and provide a registered user name and password. These will log you in and take you to the dashboard. When registering, this process is automated and will log you in once validation is done.

* Implement a session to check the state of a user over various pages.
### Dynamic content
* Create a page that shows contents from the database. (a list of users and/or messages).

    * Log in, and check the interface, pages for both users and messages are reachable by navigating the header, or clicking the count buttons on the page.

### Javascript
* Create some interactive functionality with Javascript or jQuery (form validation, dynamic updates, etc).

    * AJAX Search with highlighting of the search term is implemented on the user and message pages. The user page allows to search based on id (separate field), display name of email address.

* Create AJAX functionality like live search, asynchonous form submissions, or anything that interacts with PHP.

    * Since this is basically a single-page application, all data is loaded with AJAX.

### Security
* Make sure your application is secured against SQL Injection, XSS and CSRF attacks.

    * All input data is checked with trim() and htmlspecialchars(). This is enough to prevent basic XSS and SQL Injection scripts.
    * CSRF is implemented to check form validity based on the machine it works from.
    * Also a HTACCESS is included, since we used XAMPP on the development machine we included a Content Security Policy on the server-end so we can include/exclude globally.

* Sanitize and validate all user input and output.

    * All input data is checked with trim() and htmlspecialchars(). This is enough to prevent basic XSS and SQL Injection scripts. I intended to use filter_var but this is becoming more and more depricated since PHP 8.2.

    * Output is sanitized after running the Database query