# MicroMVC (name subject to change)#
# A simple, fast MVC PHP framework #

## System Outline ##

----------



## Section 0.1 -  Introduction ##

This a simple breakdown of the whole application, including code review.

This application is built using the MVC (Model-View-Controller) architecture. This helps separate our application logic from our views (front-end pages) hence helping increase the mantainability of the software's code.


## Section 1.0 - M.V.C (Model-View-Controller) ##

*What is the MVC architecture?*

> In a nutshell MVC is made up of three parts **i.e.** the model, the controller and the view. The model basically
> represents data in our application, this might be data from a database etc. The view represents the front-end
> (what the user of the software will see e.g. HTML pages) and the controller handles interaction between the two..,
> that is, the controller gets data from the model and feeds it to the view and vice-versa.


*Why MVC?*

> Standards used in taking business are always subject to change, and when they do, we'll be able to
> efficiently and easily update our codebase. Finding and fixing bugs will be much easier since we separated
> the logic (PHP code) from the views (HTML)

## Section 1.1 - Directory Structure ##

In the root directory of our application, you'll find a few directories and files.

The first directory you'll see is the *'app'* directory, this contains our application's controllers,
models, and views. This is where we'll spend most of our time in. Writing our code in controllers and all that other stuff.

After that we have the *'public '* folder... this is the directory which is publicly accessible. You should put all of your js, css, images and other publicly visible resources
in this directory

Next, we have the *'system'* directory, this contains all of our application framework code. **I would recommend against changing the contents of this folder, except the POS subdirectory, which contains our POS's reusable logic code**. All of the code for the database transacting, controllers, routing etc.. resides in this directory. Do tread carefully when doing modifications

## Section 1.1 - Routing ##

Assume that our application is running on the web server 'micropos.com'. Traditionally, if you visited the page 'http://micropos/test', you'd get a 'Page not Found' error unless you created a test folder in the root folder of 'micropos.com'. Through 'routing' ( not sure if that's even the formal term but whatever :/ ), we can programmatically assign these 'routes'. Basically we are saying, rather than manually create a the folder 'test' in root folder of 'micropos.com', we could write PHP code that says - "If the user visits '/test', do this, or if the user visits '/whatever', do that", so all this is done programmatically, not the usual annoying way.

*How do I add a route in the application?*

**The routes file is located in 'app/routes.php'.**

> The syntax for adding a route is as follows:
>      
> `$route->get('/test', 'TestController::home');`
>      
> The name of the function you call to is the HTTP request method of the route you want to add. If you wanted to
> add a route for the GET request method, you call '$route->get' and if you wanted to add a route for the POST
> request method you'd call '$route->post'
>
> **NOTE**: Currently we currently only support the POST and GET request methods since the other request method are out
> of the scope of our project
>
> You can also add middleware (Section 1.2) to a route by adding a third argument when adding a route. An example is below:
>
> `$route->get('/test', 'TestController::home', "TestMiddleware");`
>
> If you wanted to add more than one middleware, you'll need to provide an array of middleware in the third argument. An example is below:
>
> `$route->get('/test', 'TestController::home', [ "TestMiddleware", "AuthMiddleware" ]);`

## Section 1.2 Middleware ##

We won't go into much depth about these, but simply put, middleware evaluate the incoming request before further excecuting the application's code. For example, you could create an Authentication middleware that checks if someone is authenticated, and if they aren't, it might redirect the to login page etc.


## Section ... ##

I had meant to make it longer so it could cover everything but meh :/, too much work. Will finish up
soon
