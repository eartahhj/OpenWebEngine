# differentWebEngine
Open Source PHP Framework

# What is differentWebEngine?
This is an open source *Experimental* PHP Framework. It works as a standalone so you don't need particular dependencies. You can use some external libraries that you can find in the *vendor* folder, like PHPMailer and Matomo, but can also go without them.

I have been working on this for years for my personal projects and to learn how to do things my own way. So in the end I have decided to share it freely so that others might find inspiration from this and also to showcase some of my knowledge. This way, you can see what I can do and how I do it.

The framework is experimental because it is just not ready-to-deploy yet.

It is based on a CMS logic so is mainly for creating and managing pages, but uses a MVC-like structure that I have designed on my own based on my needs.

The CMS is driven towards a Blog type of content, for the moment.

The concept is also quite modular, of course many useful things are still missing at the moment.

# Can I run this in my server?
Well it's experimental, it works in general but will require quite some work from you.

# What languages does this framework use?
PHP, HTML, CSS, Javascript, jQuery, SQL (MySQL and PostgreSQL)

# Requirements
- Apache
- MySQL or PostgreSQL (mostly tested with MySQL)
- PHP 7.3+

# Folders and important files
- dwe is the main folder with the backend files. Also views are in here, for the moment.
- public_html only has some helper php scripts (debug.php, getfile.php, webp.php) and the main index.php which processes the request and loads the needed PHP files.
- dwe/bootstrap.php contains basic settings
- dwe/controllers is where you can setup your own Controllers
- dwe/views is where you can customize HTML views for your needs

# Features
- MVC-like structure to load Controllers starting by a Request, and then passing it to index.php. The Controller will decide which View needs to be loaded and you can customize them as you wish
- Content Management System for a Blog type of content (articles with categories)
- Admin panel protected by login form (username and password)
- Admin panel to create and manage pages and categories
- Admin panel to create and manage files, kind of like a media library
- Creating articles similarly to a blog, with fields for title, text, SEO fields and image
- Form standalone library
- Serve files with Apache if needed
- Convert images from JPEG/PNG to WEBP
- Matomo Server Side tracking (optional)
- Page Editor uses Tinymce 5 by default, but can be changed
- Articles can be commented by users (optional). You will receive an email when a user leaves a comment
- The system is configured to display success, error, informative messages
- Debug mode to see "debug messages" only visible to the admin
- Language is defined by the user's browser or eventual cookie if set

# Install instructions
Note: as mentioned, this is not ready to deploy, so will need manual work from you. You can still try it if you just want to see how the code is and how the logic works.
- Download the latest release and upload the files in your server.
- If you use MySQL, you can create tables by using the file mysql.sql
- Configure config/config.php, config/smtp.php, core/database.php and eventually bootstrap.php
- Change views according to your needs, expecially dwe/views/templates/template-*.php

You can contact me if needed.

# Demo
You can see a production demo at [GamingHouse](https://www.gaminghouse.community), it is a website that I work a lot on and is made with this framework.

# Donate and support
You can buy me a ☕ cup of tea (or a slice of 🍕 pizza hehe) at [LiberaPay](https://liberapay.com/GamingHouse/donate).
Or, if you prefer, you can just follow me on [Facebook](https://www.facebook.com/Gaming-House-101452284805560/) and [Youtube](https://www.youtube.com/GamingHouseYT?sub_confirmation=1).
Thanks!
