# NanoRouter
nano router for PHP apps

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
    'Controller'=>'Controller', //suffix of controller class name
    'controller_namespace'=>'app\Controllers',
    'routes'=>[
        //...custom routes. eg:
        '/login'=>'site/login',
        'r1/a1(/(?<id>[0-9]*))?'=>function ($p) {  // this matches ra/ar with optional numeric id
                return isset($p['id']) ? ('site/other/'.$p['id']) : 'site/index'; //function receives extracted parameters and returns new <controller>/<action>/<id> URI
            },
        //routes order must be from most specific to general routes
    ]

# License
MIT
