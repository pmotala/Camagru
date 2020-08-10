# Camagru

A social type web application that is similar to Instagram,  which allows users to upload or capture images with the option to add filters, and also the ability to like, dislike and comment on posted images.

## Requirements
- XAMPP: https://www.apachefriends.org/index.html

## Installation
### Setup and configure XAMPP
- Download XAMPP from the provided website
- Install XAMPP on you PC
- Place the downloaded Camagru folder into the installed path "C:\xampp\htdocs\"
- Ensure less secure apps enabled on gmail (as I used gmail for sending email)

- Next navigate to "C:\xampp\php\php.ini"
- Look for the heading "[mail function]"
- Set SMTP=smtp.gmail.com
- smtp_port=587
- sendmail_from = ENTER YOUR EMAIL HERE
- sendmail_path = "\"C:\xampp\sendmail\sendmail.exe\" -t"
- Save and close php.ini

- Next navigate to "C:\xampp\sendmail\sendmail.ini"
- Look for the heading "[sendmail]"
- Set smtp_server=smtp.gmail.com
- Set smtp_port=587
- Set auth_username = ENTER YOUR EMAIL HERE
- Set auth_password = ENTER YOUR GMAIL PASSWORD
- Save and close sendmail.ini

### How to download the project
- Navigate to: https://github.com/pmotala/Camagru
- Click "Code/Download Zip" or simply clone it with Git.
- Once you have downloaded the source code navigate to the folder you downloaded the repo into
- Go into the projects config/database.php file and enter your database credentials

### How to run the program
- Open XAMPP
- Click on the start button for "Apache"
- Click on the start button for "MySQL"
- Open a web browser of your choosing
- Type the following in your search bar "http://localhost/Camagru/"
- Hit submit, and the website Camagru should appear.
- Select login if an account exists or signup if a new account is required.

## Device Access
- Requires access to your webcam

## Code Breakdown
- Back end technologies
    - PHP
    - SQL

- Front-end technologies
    - HTML
    - CSS
    - Javascript

- Database management systems
    - MySQL
    - phpMyAdmin

## Tests that I ran
- Project Expectations:
    - https://github.com/pmotala/Camagru/blob/master/docs/camagru.markingsheet.pdf

- Project Design Checks:
    - Back end must be made entirely in PHP, no other frameworks should be present.
    - Front end Technology should be HTML, CSS and Javascript.

- Initialization:
    - Starting the webserver.
    - Running the setup script
        Outcome:
        - A new Camagru database should be created and accessible on myphpadmin.

-  Operation Tests:
    - Create Account Outcomes:
        - Should check for valid email
        - Should test for password strength
        - Should create account
        - Should send Authentication or Account Confirmation Email
    - Authentication Outcomes:
        - Email should be sent
        - Authentication should determine ability to login
    - Login Ouctomes:
        - Check for account confirmation/authentication
        - Log user in
        - Allow for reset password (Forgot Password)
    - Homepage Outcomes:
        - Work with both logged in users and none logged in users.
        - Provide seperation between the two types of users
        - Give access to the entire applications functionality when logged in
        - Show all posted images
    - Change Account Settings Outcome:
        - Display settings that affect the personal preferences of the user
    - Start Webcam Outcome:
        - Display a view of your webcam
    - Capture Image Outcome:
        - Display a view of your captured image
    - Uplaod Image Outcome:
        - User should be able to upload an image stored locally
    - Add Filter Outcome:
        - Ability to merge an image with a filter.