# Lumen 5.2 & Angular Base Script

* Lumen: 5.2 
* Angularjs: 1.5.2

## Requirements:

* PHP >= 5.5.9
* Enable extensions in **php.ini** file(OpenSSL PHP Extension, PDO PHP Extension, Mbstring PHP Extension)
* Nodejs
* Composer
* Bower
* Grunt

First you should set "**\trunk\lumen**" path as your root path.

## Server Side:
### Composer Updation:

* To Update the Composer, please run the below command in your Project Path.  

        composer update
    
* The above Updation doesn't work to you, need to install Composer, please refer this link **https://getcomposer.org/**  for "**How to install Composer**".

### Environmental Settings:

* In the root path "**\trunk\lumen**", you can find the "**.env.example**" file.
* Rename the file **.env.example** -> **.env** for further environmental changes.
* The **.env** contain below lines

    * APP_DEBUG=true
    * DB_CONNECTION=mysql
    * DB_HOST=localhost
    * DB_PORT=3306
    * DB_DATABASE=lumen
    * DB_USERNAME=root
    * DB_PASSWORD=

* Make changes in the file as you need, set DB_CONNECTION, DB_HOST, DB_DATABASE.. etc, 
* Now create Database for your project.
* open command prompt and go to exact path "**\trunk\lumen**" then run the below command(imports all tables into the database).
            
        php artisan migrate
* Run the below command, which is used to imports the all values into the tables.
        
        php artisan db:seed

## Front Side:

* You need to install nodejs, bower, grunt.

* Go to "**\trunk\lumen**" path in command prompt.
* Run the below command, the bower used to download and installed all front-end development libraries.

        bower install

* The npm used to install the all dependencies in the local node_modules folder.

        npm install    

* In "**public/.htaccess**" you can change the project base url in **RewriteBase /lumen/public** as you need.

* Also change the project base url in **\client\src\ag-admin\js\ng-admin.app.js**, here find **admin_api_url** variable and change as you need.

* Also change the project base url in **\client\src\app\Constant.js**, here find **GENERAL_CONFIG** constant and change as you need.

* The build task used to execute particular tasks which is build with grunt.
* If modify the files in local, you should run the below command for further updation.  
  
        grunt build

* The compile task used to concatenating and minifying your code.

        grunt compile

* If you want to upload files to dev1 server, you have to run the below command with server name.

        grunt upload:dev1

### Cron updates on local:

* To check curreny conversion from one country to another country, run below command.

        php artisan currency:cron

* To update all booking status manually, run below command.

        php artisan status_update:cron

### Cron updates on server:

* To update curreny conversion, you have set like below with server url.

        0 0 * * * php /server URL/artisan currency:cron
        Eg: 0 0 * * * php /home/spgsql/html/lumenbase/artisan currency:cron

* To update project status, you have set like below with server url.

        0 0 * * * php /server URL/artisan status_update:cron
        Eg: 0 0 * * * php /home/spgsql/html/lumenbase/artisan status_update:cron

 
### FAQ
1. If facing any problem(values not imports into table properly) while run the "**db:seed**" command, run the below command and again run the "**db:seed**" command for futher imports.

        composer dump-autoload
