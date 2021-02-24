# To-Do List Application 

[![](https://img.shields.io/badge/vue-2.x-brightgreen.svg)](https://vuejs.org/)
[![](https://img.shields.io/badge/Codeigniter-4.x-ee4323.svg)](https://codeigniter.com/user_guide/intro/index.html)
[![](https://img.shields.io/badge/MariaDB-10.4.17-c92ddc.svg)](https://mariadb.org/)
[![](https://img.shields.io/badge/Composer-2.0.9-1f4074.svg)
](https://getcomposer.org/)

## [Live Demo](https://todo-ci4.herokuapp.com/)
## Server Requirements
PHP version 7.3 or higher is required, with the following extensions installed:

- [intl](http://php.net/manual/en/intl.requirements.php)
- [libcurl](http://php.net/manual/en/curl.requirements.php) if you plan to use the HTTP\CURLRequest library

Additionally, make sure that the following extensions are enabled in your PHP:

- json (enabled by default - don't turn it off)
- [mbstring](http://php.net/manual/en/mbstring.installation.php)
- [mysqlnd](http://php.net/manual/en/mysqlnd.install.php)
- xml (enabled by default - don't turn it off)

## Initiating the local server
_`php spark serve`_

## Database
During the development I used the _XAMPP_ to run the MariaDB service. You can choose what is convenient for you. You will find SQL file in the `SQL` folder of the project's root directory. For configuring database settings to run project, please check the `.env` file.


## Project Overview

The frontne part of the application can be found in `app > Views > templates` directory as `frontend.php`

The vuejs front-end communicates with todo REST API via JavaScript Fetch API.

To check the REST Controller please find `TodoController.php` in the `app > Controllers` directory.

The model (`TodoModel.php`) is in `app > Models` directory

## Cool Tip 
To easily brows through this application without downloading the source, you may use [this](https://github1s.com/ganmahmud/todo-app). 
