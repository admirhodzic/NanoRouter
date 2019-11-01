# NanoRouter
Nano-size router for PHP apps in just 20 lines of code.


# Install

    composer require admirhodzic/nano-router

# Usage

    use admirhodzic\NanoRouter\NanoRouter;
    // ...
    $response = NanoRouter::run([
                    //options...
                ]);

When request URL is <b>/posts/update/123?param2=abc</b>, this code will invoke a class with name PostController and call method <b>actionUpdate</b> with $id parameter set to '123' and 'param2' set to 'abc', if param2 is not set, default value is used. "id" parameter is mandatory.

    class PostController{
        public action actionUpdate($id, $param2=''){ ... }
    }

If URL doesn't contain controller or action name, defaults will be used. By default, 'SiteController' and 'actionIndex' method.
To specify separate methods for different HTTP methods, just add a function with method name instead of 'action':

    public function getPosts() { ... } 
    public function postPosts() { ... }
    public function actionPosts() { ... }

This way, for GET request, 'getPosts' function will be called, for POST request, 'postPosts' function will be called, and 'actionPosts' will be called for all other request methods.

# Options

    'default_controller'=>'site',
    'default_action'=>'index',
    'Controller'=>'Controller',
    'controller_namespace'=>'app\Controllers',


# License
MIT
