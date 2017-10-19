# Iron Source - Senior PHP Developer Web Test

Author: Valentin Ruskevych <Leaderpvp@gmail.com>
Task: check out task.pdf in root folder.

## Software Requirements:

* PHP 7.1+
* Composer
* NodeJs (for npm)
* npm
* MySQL
* AngularJS 4.3+
---

## Application Startup

* ***FYI***: Front end directory: ```is-frontend``` inside project.
* ***NOTE:*** PHP webserver **MUST** be configured to 127.0.0.1:8000 as frontend heavily dependent on this resource.

After You have installed necessary software
1. Head to project directory
2. Run following command in your console ```composer install```
3. Within project's directory, Open Config/Configuration.php in order to configure Your database connection. (everything is simple)
4. Import initial data and SQL structure received at the beginning, can be found in ```db/dump20170727```
5. You will have to run SQL Script which resides in `db/scripts.sql`
6. To install AngularJS, execute in your command line: ```npm install -g @angular/cli``` (Warning, this command installs Angular globally! Remove if required, or install locally inside directory named `is-frontend`)
7. After this, we have to manage our dependencies. Run: `npm install`
8. Startup php built-in webserver by executre ```php -S 127.0.0.1:8000``` from project dir.
9. Open new command line window, head to frontend directory. Now we would run our AngularJS by executing the following: ```ng server -open``` Last command will startup angular cli's development server and **OPEN** the application in your browser window.

P.S: Refer to next paragraph if something is wrong. Below is written all software versions used during development process, so to ensure you get maximally close environment.

*Enjoy*

### Application Stack 
PHP 7.1 + AngularJS v4.3 + Bootstrap CSS v3.3

##### Developed under:

PHP 7.1.8,
AngularJS 4.3.4, 
Bootstratp 3.3.7,
NodeJS 8.4.0,
MySQL 5.7.16,
NPM 5.3.0,
Composer 1.5.1

# BackEnd 
* PHP without additional packages
* Architecture: Event Based
* Support for REST Resources and standard route (no fancies on backend)
* REST Resources capable of receiving data in plain text and json
* Pagination is not implemented
* UnitTest is dropped, except of few (sorry, they consume time).
* App Lifecycle is *initialize -> request -> dispatch -> response*
* Package files is store within directory ```packages_files```
* Offers mapping done using stored procedure **NOTE**: procedure everytime truncates package_offer and might be improved to continue records order.


