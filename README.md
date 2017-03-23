# PHP Restful API (Template) [Free for use]
PHP template for create RestfulAPI.

### File lists.
- api_list.php #Auth
- database_config.php #Database config
- manage_class.php #Main class
- user_class.php #Class for manage data from user/index.php
- user/index.php #Receipt data

### How to use.
Set up your api auth.
- api_list.php
```php
<?php
    define('API_KEY', '1234567890'); # Add you key for authentication
    define('API_URL', 'http://localhost'); # Add url api url
?>
```

- database_config.php 
Config your database data.
```php
$dblink = mysqli_connect('localhost','root','root','dbname');
```

- manage_class.php 

Class -> manageSql::insert
Class -> manageSql::update
Class -> manageSql::delete
Class -> manageSql::select

You can use follow 'user/index.php'


