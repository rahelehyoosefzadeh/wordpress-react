# Inpsyde Plugin #
This WordPress plugin display a lovely users table when visiting a custom endpoint '/my-lovely-users-table/'.
The frontend is implemented in React and the back end is PHP 8.2 in compliance with WordPress and Inpsyde standards.

## Description ##
After Installing the Plugin  and activating the plugin you can visit http://your_website.com/my-lovely-users-table/ at your website frontend.
You see a lovely users table in 3 columns (ID, USERNAME, NAME) of users list provided by a 3rd party API in https://jsonplaceholder.typicode.com/users . 
By clicking on hyperlink of each information item form the selected user, a card of detailed information user will be displayed on top of the table.

  ### Plugin's frontend ###
  https://example.com/my-lovely-users-table/

  ### Installed example on a live address ###
  http://185.242.161.39//my-lovely-users-table/


Both the all users' information and each user's details are fetched in the backend of the plugin through calling the API in the backend and cached for 1 hour iorder to improve the data fetching for next time (within 1 hour).

## Task Requiremnets ##
* Composer friendly
* Inpsyde code style Compliance by utilizing inpsyde/php-coding-standards in https://github.com/inpsyde/php-coding-standards
* Brain Monkey applied PHPUnit Tests provided ( 4 methods from the Plugin's main class considered in test :)  My first practice of isolated unit test wordpress)
* React Component development applied as the frontend development choice :) My First practice in embeding a React Project in WordPress Plugin development


= Developed by Raheleh Yoosefzadeh =
Inorder to take the technical evaluation as an adventurous step of joining Inpsyde's wonderful team.


## Installation ##
### Requirements ###
* WordPress preferably latest version.
* PHP 5.6 or later.

### Installation ###
1. Unzip the downloaded package.
2. Upload folder include the file to the `/wp-content/plugins/` directory.
3. Activate the plugin through the `Plugins` menu in WordPress.

or use the git instructions to install.

## Screenshots ##

