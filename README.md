### Inpsyde ###
# Inpsyde Plugin #

This WordPress plugin display a simple loveley users table when visiting a custom endpoint '/my-lovely-users-table/'.
The frontend is implemented in React and the back end is PHP 5.3+ in compliance with WordPress standards.

## Description ##

After Installing the Plugin  and activating the plugin you can visit http://your_website.com/my-lovely-users-table/ at your website frontend.
You see a lovely users table in 3 columns (ID, USERNAME, NAME) of users list provided by a 3rd party API in https://jsonplaceholder.typicode.com/users as a test api. 
By clicking on hyperlink of each information item form the selected user, a card of detailed information user will be displayed on top of the table.

# Plugin's frontend

https://example.com/my-lovely-users-table/
  ### Plugin's frontend ###
  https://example.com/my-lovely-users-table/

# Caching HTTP Requests #

WordPress Transients API applied for caching the HTTP requests in order to improve the performance and that a backend approach to be applied for caching.
The rational behind this lands into two main reasons:
* Backend Worpress Friendly
* Simplicity and felcibility to implement

For applying Transients API no other tools is required to be installed and it is simply implemented in WordPress. This Method as in the Doc explained and as I tried in my task offers a simple and standardized way of storing cached data in the database temporarily by giving it a custom name and a timeframe to be accessable.

# Embeding REACT Component #

As It was ready a range of frontend options to be obtained as my frontend approach, I decided to embed a React Component ino the project since I had not experienced it before in WordPress. So this was new challenge for me. Of course I am aware that in a client work project and a real world one some considerations need to be taken into account like felxibility, compatibility with theme and other plugins and the requirements of the user.

# Frontend Styling #

Bootstrap css is applied in furnishing the look of the lovely table. 


# Brain Monkey #

I tried to learn how to apply and use the Brain Monkey for unit test. It was also a great challenge that I tried to get myself involved in. The approach is interesting technically and since it was a new experience to me I tried to keep it simple and apply a working test. So I applied the clever monkey's tool to mock the wordpress plugins in 4 of the methods of my plugin;s main class.

# Security #

Prohibiting direct access to the plugin's files and applying practices like escaping functions like esc_html() and fixing PHPCS warnings.

## Task Requiremnets ##

* Composer friendly
* Brain Monkey applied PHPUnit Tests provided ( 4 methods from the Plugin's main class considered in test :)  My first practice of isolated unit test wordpress)
* React Component development applied as the frontend development choice :) My First practice in embeding a React Project in WordPress Plugin development
* WordPress Transients API applied for caching the HTTP requests. 
While developing the plugin I was considering the guidelines and tips to " keep it simple, make it work, explain why, care of details". 


## Developed by Raheleh Yoosefzadeh ##

## Installation ##
### Requirements ###
* WordPress preferably latest version.
* PHP 5.3 or later.

### Installation ###

1. Unzip the downloaded package.
2. Upload folder include the file to the `/wp-content/plugins/` directory.
3. Activate the plugin through the `Plugins` menu in WordPress.

or use the git instructions to install.

#### Composer Installation ####
run the command :
```
composer require raheleh/inpsyde:dev-main
```
to install the composer package the Inpsyde plugin folder will be found in :

```
vendor/raheleh/
```

## Screenshots ##
## Screenshots ##

My lovely users table lokks like this:
![my-lovely-users-table](https://github.com/rahelehyoosefzadeh/inpsyde/blob/main/inpsyde.jpg)

