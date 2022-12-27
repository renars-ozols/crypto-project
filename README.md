# crypto-project

## Description:
In this website, user can buy various cryptocurrencies with real-time pricing information, 
short sell crypto and also transfer crypto to another registered user.
Website is designed using bootstrap 5 css.

## Features:
* Search functionality.
* Buy and Sell crypto.
* Crypto short selling feature.
* Transfer crypto to another user.
* Transaction history.

## Prerequisites:
<ol>
<li>PHP 7.4.32</li>
<li>MySQL 8.0.31</li>
<li>Composer</li>
</ol>

## Instructions to run the website:
<ol>
<li>Clone the repository:<br><code>git clone https://github.com/renars1988/crypto-project.git</code></li><br>
<li>Install dependencies:<br><code>composer install</code></li><br>
<li>Register at https://coinmarketcap.com/api/ and get your API key. </li><br>
<li>Rename <code>.env.example</code> to <code>.env</code>.</li><br>
<li>Enter your API key and database settings in the <code>.env</code> file.</li><br>
<li>Import <code>database.sql</code> schema in to your database.</li><br>
<li>Navigate to public folder and run the server:<br><code>php -S localhost:8000</code></li>
</ol>

### Home page, register and login page:
![]registration_login.gif

### Buy, sell and short sell crypto:
![]buying_selling.gif

### Transfer crypto to another user:
![]transfer.gif

