# Basic project

Basic project to show some vanilla skills. This project is a showcase for some vanilla HTML/CSS, Javascript, and PHP skills. Some light object-based programming is used. Ofcourse, this is not a real-world example but rather a showcase of skill.

## How to install

* Download this GIT repository
* Create a database
* run /install.php

The installer will create the database tables, indexes, and fill the messages table with 50 random messages. All the messages are bound to the first user that is created.

## Related task information

This project was created as an assignment to show my skills in "vanilla" or "plain" versions of code. We all know we could have used a framework to do the same thing in half the time but there is no challende in that. That's why I used the most barebone way of creating a project, with mostly vanilla code (Yes, I cheated a little by using jQuery to process click events).

### User registration system
* Create a registration form with fields for display name, email address and password.

    * Open the home page and click "Register" on the login form.

* implement server-side validation with checks on display name, email address and password.

    * Fill in the register form with faulty information. A front-end check will be done on the user input
    * After providing information that passes the front-end validation, the back-end (user Object) will take over and will check the information again based on a RegEX.

* Save the user information safely in a database table.

    * After validation is done, user information is saved in the database, and you will be transferred to the Dashboard.

### Authentication
* Develop a login system where users can authenticate themselves with their user name and chosen password.

    * Open the home page and provide a registered user name and password. These will log you in and take you to the dashboard.
    * When registering, this process is automated and will log you in once validation is done.
    * Validation is done each time a page is loaded which requires a logged in user. Otherwise you will be redirected to the login page.

* Implement a session to check the state of a user over various pages.

    * Validation is done each time a page is loaded which requires a logged in user. Otherwise you will be redirected to the login page.

### Dynamic content
* Create a page that shows contents from the database. (a list of users and/or messages).

    * Log in, and check the interface, pages for both users and messages are reachable by navigating the header, or clicking the count buttons on the page.

### Javascript
* Create some interactive functionality with Javascript or jQuery (form validation, dynamic updates, etc).

    * AJAX Search the search term is implemented on the user and message pages. The user page allows to search based on id (separate field), display name or email address.
    * Search result highlighting. It's one thing to have a search result on screen, but it's equally important to see where in context the result was found. This is implemented across all searchable fields.

* Create AJAX functionality like live search, asynchonous form submissions, or anything that interacts with PHP.

    * Since this is basically a single-page application, all data is loaded with AJAX. We return HTML pages, strings, JSON,.. Whatever we need.

### Security
* Make sure your application is secured against SQL Injection, XSS and CSRF attacks.

    * All input data is checked with trim() and htmlspecialchars(). This is enough to prevent basic XSS and SQL Injection scripts.
    * CSRF is implemented to check form validity based on the machine it works from.
    * Also a HTACCESS is included, since we used XAMPP on the development machine we included a Content Security Policy on the server-end so we can include/exclude globally.

* Sanitize and validate all user input and output.

    * All input data is checked with trim() and htmlspecialchars(). This is enough to prevent basic XSS and SQL Injection scripts. I intended to use filter_var but this is becoming more and more depricated since PHP 8.2.

    * Output is sanitized after running the Database query, in the same manner as input is sanitized.

## fun Stuff

Some small did-you-know's about this project

* It cost me approx 16Hrs to build this, divided over 3 days, not including documentation.
* ChatGPT was used to create an array. Because I needed some "dumb" data, I decided that it was a computer's job to generate it for me. So I let ChatGPT create a dictionary of words to use for the seeding of messages.
* I love music, every character I put on screen was done while listening to music.
* My database experience were used while creating the database tables. Getting a fairly simple requirement from the assignment, made me think about the indexing and uniqueness of data in the database.
* This project did not need to be single-page, but I created the project like that anyway.
* There is some inline PHP used? The most ancient version of creating server-side code, hated by many and most of the time a reason to remove a project entirely.
* I considered using VueJS but didn't want 90% of my development time being peeking at documentation. That's why I opted for vanilla code.
* PHPDoc blocks are implemented in all classes and helper methods?
* Every single line of code in this project was typed, not copied from the internet (Because what's the fun in that?).
* Visual Studio Code was used to make this. I love myself some PHPStorm, but since I do not own a license, I used the free version of VSC.
* All pushes to GIT were typed in terminal as well? Even though Visual Studio Code can do it, I wanted this project to have the most barebone mindset I could come up with.
* For styling, the Bootstrap 5 framework is used. Next with some light jQuery the only packages used in this project. This choice was made only because we needed a simple front-end fast!

## More stuff to do
There is always more to do. that's why I created a list of thing I would like to have in this project.

* View Controllers for pages, to finally botch the inline PHP used in the components, maybe even streamline the process by using a general Controller class for everything. All the pages need to go through one flow.
* Models are a requirement too, for now a basic MySQL query is done after which the result is processed, but that's not a nice way to go, and should be better.
* Automatic sanitizing of input/output, the way I did it now is ugly and should be way better.
* Javascript could be done way shorter too, by defining all the AJAX requests as separate, I created a lot of overhead which should be eliminated.
* Use Object-based Javascript, because we can make better use of scopes when we do.
* Add fancy URL's to the interface, even though it is an app, we could use this for users who want to continue where they left off.
* Add a favicon to the project.
* Add case-insensitive search, current search only does exact matches. We can do better than that!