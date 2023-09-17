# ticket
Backend Application for ticketing management

REQUIREMENTS
------------
The minimum requirement by this project template that your Web server supports PHP 7.4.
Database MariaDb 10.XX version

INSTALATION
-------------
### Via Terminal
1. git clone https://github.com/ujang85/ticket.git
2. cd ticket
3. composer install
4. Create database and import file ticket.sql from app/data/

CONFIGURATION
-------------
### Database
Edit the file `config/db.php` with real data, for example:

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=ticket',
    'username' => 'root',
    'password' => '1234',
    'charset' => 'utf8',
];

TESTING via POSTMAN
-------------
### authentication login
https://localhost/ticket/web/auth/login
with request POST methode
{
            "username": "ujang",
            "password": "123456"
}

### Post Ticket
POST method 
https://localhost/ticket/web/rest-ticket/create

with auth bearer token from aut_key tabel user or get respone data from authentication login
with request json format example :
{
	"Ticket":{
	            "ticket_no": "456635430",
	            "description": "coba input data"
			},
	"TicketFile":[
				{
				"file":"data:image/png;base64,/9j/4AAQSkZJRgABAQfgfgfEASABIAAD/"
				},
				{
				"file":"data:image/png;base64,/9j/4AAQSkZJfdAQEASABIAAD/"
				}
			]
}
