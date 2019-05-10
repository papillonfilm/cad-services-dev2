# Cad Services
Web Services for the Court Administration.

This is the main base system build in Laravel. It is a modular design, where modules are created as applications. All applications are under the app/Applications folder.

## Installation Requirements
php7.2 or up  
php7.2-ldap  (or equivalent for php version)  
mysql-server or mariaDB  
libapache2-mod-php7.2  
php7.2-mysql  
php7.2-json  
postfix or an email server to deliver emails.
## Configuration

On the virtual host we need to have apache mod-rewrite enable
```
#a2enmod rewrite
```
Also we need to give permission to make the site or virtual host use the re-write
```
<Directory "/path/to/site/public">
	AllowOverride All
	Require all granted
</Directory>
 ```
## Installation
Create the database and database user.
```
#mysql -u root -p
mysql> CREATE DATABASE [databaseName];
mysql> GRANT ALL PRIVILEGES ON [databaseName].* TO [USER]@localhost IDENTIFIED BY '[someRandomPassword]';
mysql> exit
```

1. go to the path to install/download the code usually /var/www/html  
```git clone https://github.com/Circuit15-Florida/cad-services.git .```  
[This will download all the code to the current directory] 
1. ```cp .env.example .env ```
1. To add the database configuration information  
```nano .env```   
1. ```php artisan key:generate```  to generate a new key for the application
1. Allow apache user [www-data or apache*] to write on storage folder ./storage  
	```chgrp apache storage/ -R```  
	```chmod 775 storage/ -R```  
1. Allow apache user [www-data or apache*] to write on user profiles folder /public/images/profilePicture  
	```chgrp apache public/images/profilePicture -R ```  
	```chmod 775 public/images/profilePicture -R ```  
1. Run the migrations to create the system database.  
``` php artisan migrate ```  
1. Import the default database information. (This contains the applications, groups and relations to be able to manage it.)  
	``` mysql -u root -p DATABASE < /path/to/file.sql ```  

* Apache user varies from linux distributions, apache is mostly use in RedHat and its derivatives(CentOS, Fedora), while www-data is in debian and its derivatives(ubuntu, mint).
* As a note the DocumentRoot for the site must be the public folder under the laravel installation. For example if we install it on the default location [/var/www/html] the site root must point /var/www/html/public.

### Application Structure
Applications are stored on [ app/Applications ] all applications have folders for Controllers, Models, Migrations, and Views. The routes file must be place on the root of the application folder; this will be loaded by AplicationsServiceProvider. The ApplicationsServiceProvider will also load all the views and Migrations that are on the application folder and make them available on Laravel.
On the routes.php file, you must name a default application route that the user will be sent when go to this application. Ex.
```php
Route::get('/dashboard/logs','LogsController@index')->name('logs');
```
This route is defined in the Web Manager > Applications as [ Application Default Route ].

### Namespaces
Since we created a modular approach to organize more efficient the different applications that we are developing into the system, we must define namespaces for all the routes. The easies approach is to use a Route::group to define the middlewares and namespace. We are using a namespace structure similar to the folder structure for easy location of files and issues. Ex.
```php
App\Applications\ApplicationFolder\Controllers
```

### Middleware
Middlewares are very important in our framework. For example, we are using the Laravel Authentication plugin to validate and manage our users. In addition, we have our own middleware that manage the access to the different applications. This middleware [ applicationKey ], we must use it with a secret ['applicationKey:AppSecr3t'] and also define it in our Web Manager > Applications so the middleware authenticate if the user have access to this application or not. 
Example of routes.php file:

```php
$arguments = array(
	'applications'=>'logs',
	'middleware'=>['web','auth','applicationKey:APPSECRET'],
	'namespace'=>'App\Applications\logs\Controllers',
	'prefix'=>'/dashboard/logs'
);


Route::group($arguments, function() {
	Route::get('/','LogsController@index')->name('logs');
});

```

Please note that we defined a prefix to all of our routes ['/dashboard/logs'] meaning that all routes will be prefixed with this. Also the main ['/'] route of the application must be named with the name of the application so the breadcrum function can create the browse path effectively.


## Models
Models are save in the app/Applications/**appName**/Models and we must specify a namespace like [ App\Applications\**logs**\Models ] following the folder structure. Also to use this models in any controllers we must use the full namespace for example:
```php
use App\Applications\logs\Models\sysLog;
```

## Emails
Emails can be send thanks to our sendEmail() helper function. To be able to send emails using this function we must pass an array of values.
sendEmail( array(), $returnHTML = false );
Array values:
  1. to
  2. name
  3. subject
  4. msg
  5. trackingImage

Example:
```php
$emailValues = array(
     ‘to’=>’user@domain.com’,
     ’name’=>’Name Lastname’,
     ’subject’=>’Some Subject’,
     ’msg’=>’Body of email or contents’
     );
sendEmail($emailValues);

```
By using this helper function, all emails sent will be logged and available in the Mail Log application. Emails can be seen and re sended if needed.

## Logs
Logs can be made from anywhere using the logAction() helper function. 
Function details:

logAction(string [Description], string|int[Category],string|int[Type],int[UserID]);

Description: a string description of the log.
Category, can be a numeric value or a string. Numeric values are converted to string using some predefined numbers to string switch case. Numbers options are:
  1. Account Creation
  2. Login
  3. Logout
  4. Recover Password
  5. Activation Email
  6. Validation Error
  7. System process
  8. Email Sended
  9. User Actions
  10. Sudo

Type, can be a numeric value or string. Numeric values are converted to string using some predefined numbers to string switch case. Numbers options are:
  1. Update
  2. Error
  3. Hack Attempt
  4. Info
  5. Debug
  6. Login Error
  7. Create Account
  8. Activate Account
  9. Success
  10. System Information
  
UserId (optional) if no value passed, current login user id will be set.

## Notifications
Notifications are a small text that can be posted on the notifications application and will be available on the notification icon (Bell) on the interface.   
Notifications can be posted from anywhere using the helper function addNotification.
```php
addNotification(“Notification message”, [userId]);
```

## Sub Menu on Selected Application
To be able to have a submenu on the current application that we are browsing or using, we can do it by sharing a variable(array) into all the views that are use on the controller. 
To be able to do this we do a ```view()->share('subNavigation',[arrayVariable]);```

Example on controller __construct method.
```php
public function __construct(){
	// Create Array
	$subNavigation = array();
	$subNavigation['Application Name'][] = array('title'=>'Link Title', 'link'=>route('routeName'));
	view()->share('subNavigation',$subNavigation);
}
```

## Permissions on Routes
To be able to enforce permissions on the applications we need to assign the **'permissions'** route middleware to the routes group.
When this middleware is added, the routes will be verified to check if the user have access or not. To grant access to users to this specific route we can do this by group or direct into the users permision( Web Manager > User > Select User > Manage Permission ). We can also grant or deny access using the Groups ( Web Manager > Groups > Select Group > Manage Permission ). Each group have a permission priority where the **lower number** have **priority** over all other groups. If the policy is allow or denied it will not look for other groups if the permission is Not Configured then it will look in the next group and so on. If the route permission is **not defined** in any of the groups or direct to the user the route access **will be denied**.

Asign permission middleware to route group.

```php
$arguments = array(
	'applications'=>'webManager',
	'middleware'=>['web','auth','applicationKey:someRandomKey123', 'permissions' ], 
	'namespace' => 'App\Applications\webManager\Controllers',
	'prefix'=>'/dashboard/webManager'
);
 
Route::group( $arguments , function() {
	Route::get('/','webManagerController@index')->name('webManager');
}

```
