# Rent&Ride

Rent&Ride is an open source for Car Rental Script. It is written in Lumen 5.2 & Angular Base Script for high performance in mind.

> This is project is part of Agriya Open Source efforts. Rent&Ride was originally a paid script and was selling around 60000 Euros. It is now released under dual license (OSL 3.0 & Commercial) for open source community benefits.

![rentnride-home](https://user-images.githubusercontent.com/4700341/47705875-c6297b80-dc4d-11e8-89e0-77e17f69c13e.png)


## Support

Rent&Ride is an open source project. Full commercial support (commercial license, customization, training, etc) are available through [Rent&Ride platform support](https://www.agriya.com/products/car-rental-script)

Theming partner [CSSilize for design and HTML conversions](http://cssilize.com/)

## Rent&Ride

This readily available car rental script is developed especially for helping the entrepreneurs to effortlessly bring out their customized online vehicle rental platform swiftly. By taking this immediate online car rental software, it facilitate the entrepreneurs to establish their own online auto rental system in just days. They can immensely reach out absolute needs of the target audience with this Car hire script.

* Analytics - Take advantage of this Google Analytics module and analysis the performance of your auto rental business whenever it is necessary.
* Banner - Implement banner module in your online vehicle rental platform. Host third party organization advertisement and gain additional revenues to your business.
* Currency conversion - Make use of the currency conversion option to facilitate the website users to comfortably transact their payments without any hassle.
* Zazpay - Include ZazPay integrated payment gateways and avail 50+ payment gateway options to your users and car owners.
* Vehicle coupon - Enable this module and set coupon code for the listed cars during the special occasion. Gain more users which in turn brings in more business
* Vehicle dispute - Integrate this vehicle dispute option that helps in solving the misunderstanding between the users and car owners effortlessly.

## How it works

[Coroflot clone](https://www.agriya.com/products/car-rental-script) Your most economical ride, it has survived not only five countries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing.

![rent ride-how it works](https://user-images.githubusercontent.com/4700341/47709376-22909900-dc56-11e8-9841-397171079045.png)

## Features

### List a car

The vehicle owners can simply list any type of cars in minutes by giving all necessary car information.
  
### Categories

Distinctively list various kinds of cars and its specification based on the car category insisted in your auto rental platform.

### Fixed rate

Define minimum and maximum rate for each and every car category. Below or above which the car owners can't fix the rental rate.

### Location based rental

Allows the vehicle owners to rent the car and any other vehicle with respect to the location.

### Service charges

For every car renting, webmaster can get a service charge from the person who rent the car.

### Special discount

On every special day or time, you can provide special offers to the users, increase the site traffic and earn more money in a short duration.

### Coupon

This script have exclusive option for availing coupon for all listed cars. Vehicle owners can eventually utilize it to gain more users.

### Late payment charges

An extra amount is collected from the users for every late hour after the deadline by using this car rental template.

### Car features

The vehicle owners can list their car with all specification to get more attention from the users' perspective with your auto rental system.

### Cancellation charge

This car hire rental script doesn't charge money for cancellation fee from it's users.

## Getting Started

## Lumen 5.2 & Angular Base Script

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

### Contributing

Our approach is similar to Magento. If anything is not clear, please [contact us](https://www.agriya.com/contact).

All Submissions you make to Getlancer through GitHub are subject to the following terms and conditions:

* You grant Agriya a perpetual, worldwide, non-exclusive, no charge, royalty free, irrevocable license under your applicable copyrights and patents to reproduce, prepare derivative works of, display, publicly perform, sublicense and distribute any feedback, ideas, code, or other information ("Submission") you submit through GitHub.
* Your Submission is an original work of authorship and you are the owner or are legally entitled to grant the license stated above.


### License

Copyright (c) 2014-2018 [Agriya](https://www.agriya.com/).

Dual License (OSL 3.0 & [Commercial License](https://www.agriya.com/contact))
