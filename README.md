# NanoRouter
nano router for PHP apps

# Install

    composer require admirhodzic/nano-router

# Usage

    use admirhodzic\NanoRouter\NanoRouter;
    // ...
    NanoRouter::run([
        //options...
    ]);

When request URL is <b>/posts/update/123?param2=abc</b>, this code will invoke a class with name PostController and call method <b>actionUpdate</b> with $id parameter set to '123' and 'param2' set to 'abc', if param2 is not set, default value is used. "id" parameter is mandatory.

    class PostController{
        public action actionUpdate($id, $param2=''){ ... }
    }

# Options

    'default_controller'=>'site',
    'default_action'=>'index',
    'Controller'=>'Controller',
    'controller_namespace'=>'app\Controllers',


# License
MIT
