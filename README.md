# Milkshake

[![Milkshake](https://img.shields.io/badge/License-MIT-yellow.svg)](https://github.com/Netclear/Milkshake)

<br/>

## About Milkshake

Milkshake is a simple open-source PHP framework with core features such as:

- [MVC development architecture](https://en.wikipedia.org/wiki/Model%E2%80%93view%E2%80%93controller). 
- Simple routing engine with redirect and unlimited parameter support.
- Database model with built-in [MySQL prepared statement](https://www.w3schools.com/php/php_mysql_prepared_statements.asp) handling.
- Powerful Template engine. 
- Easy asset inclusion and merging, with support for version control. 

<br/>

## How to use

##### `#` Config 

The first time you fire up a Milkshake application, your first order of business is to look at the application settings, found in the `config.php` file. 
Some settings are already specified, such as timezone and database settings, since theese are used by the system per default. They *can* technically be removed, however we advise that you **leave them be**. 
#####
Feel free to add as many new settings as you please - just use the following format:
```
Settings::set('NAME', 'VALUE');
```

<br/>

##### `#` Router 

The router connects the requested URL to whatever controller.method or function that you specify. The router setup is located in the `routes.php` file. The setup of new routes is very similar, in syntax, to the config settings: 
```
Router::set(Method, URI, Action);
```
Below are the supported values for each parameter:
| Parameter | Supported values | Note |
| ------ | ------ |  ------ |
| Method | GET, POST | Encase in single quotes. For multiple methods, separate with comma |
| URI | /hello or /hello/{name} | Encase in single quotes. Include parameters with curly brackets |
| Action | Controller.Method or function() | Encase in single quotes when calling Controller.Method |

To create a redirect, use `'PATH'` as the **Method**, and specify the From-URI in **URI** and the To-URL or To-URI in **Action**.

<br/>

##### `#` File structure 

Milkshake utilizes the MVC development architecture, which means that Models, Views and Controllers are separated into folders of their own. 
- **Models**: Found in the `model/` folder. This is the parth of your application which interfaces with data. 
- **Views**: Found in the `view/` folder. The visual part of your application, rendered through the controller. 
- **Controllers**: Found in the `controller/` folder. The backbone of the system, chaining data and visuals together. 
<br/>

##### `#` Template Engine 

...

<br/>

##### `#` Assets

...

<br/>

##### `#` Database interface

...

<br/>

## License

Milkshake is an open-source framework, licensed under the [MIT license](https://opensource.org/licenses/MIT).
