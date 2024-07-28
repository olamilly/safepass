# SafePass
This is a secure password manager application built with Laravel (backend) and Vue.js (frontend). It allows users to:
* Create vaults and securely store their passwords for various websites.
* Encrypt passwords and other sensitive data using the AES-256 encryption algorithm before storing them in a secure database.
* Retrieve stored passwords conveniently.
* Edit and delete password records as needed.

My solution to coding challenges challenge 58: Build your own password manager https://codingchallenges.fyi/challenges/challenge-password-manager

## Technologies Used:

  - Backend: Laravel (PHP framework).
  - Frontend: Vue.js (JavaScript framework).
  - Database: MySQL.
  - Encryption: AES-256 Encryption Algorithm.

## Installation:

Prerequisites:
Ensure you have PHP, Composer, and Node.js (with npm) installed on your system.
Configure a database connection in your Laravel .env file.
Clone the Repository:

```Bash
git clone https://github.com/olamilly/safepass.git
```

Install Dependencies:
```Bash
cd password-manager
composer install
npm install
npm run build
```

Generate Application Key:
```Bash
php artisan key:generate
```

Database Migrations:
```Bash
php artisan migrate
```

Start the Development Server:
```Bash
php artisan serve
```

## Usage:

* Create a new user account.
* Login to your account.
* Add new password entries, specifying the website URL, username, password, and any additional notes.
* View a list of your stored passwords (decrypted upon successful login).
* Edit or delete existing password records as needed.


If you'd like to contribute to the project, please feel free to create a pull request or hmu on linkedin.

### Check out a live demo I hosted on an AWS EC2 Instance: http://ec2-54-242-210-101.compute-1.amazonaws.com/

