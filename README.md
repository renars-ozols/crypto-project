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

### Homepage
<img width="800" alt="Screenshot 2022-12-28 at 18 10 21" src="https://user-images.githubusercontent.com/43919610/209840772-44d127d4-fca5-4ded-b54d-8ac68fb9386c.png">

### Register and login page:
![registration_login](https://user-images.githubusercontent.com/43919610/209668550-3905c201-4653-41a9-92fa-5629fdc26546.gif)

### Buy, sell and short sell crypto:
![buying_selling](https://user-images.githubusercontent.com/43919610/209668578-bf0003d3-c3a3-4b3b-93b8-773b6b079015.gif)

### Transfer crypto to another user:
![transfer](https://user-images.githubusercontent.com/43919610/209668600-0a551516-419c-4442-a628-353b5288d371.gif)
