<?php

namespace admirhodzic\NanoRouter;

final class NanoRouter
{
    private static function raise_error($msg)
    {
        throw new \Exception($msg);
    }

    public static function run($options=[])
    {
        //route to controller class and action
        $options=array_merge(
            [
                'default_controller'=>'site',
                'default_action'=>'index',
                'Controller'=>'Controller',
            ],
            $options
        );

        $uri = trim(rawurldecode(((false !== $pos = strpos($_SERVER['REQUEST_URI'], '?')))?substr($_SERVER['REQUEST_URI'], 0, $pos):$_SERVER['REQUEST_URI']), '/');
        $req=$_REQUEST;
        if (isset($options['routes'])) {
            $params=[];
            foreach ($options['routes'] as $p=>$a) {
                if ($p==$uri || preg_match('/'.str_replace('/', '\\/', $p).'/', $uri, $params)) {
                    if (is_string($a)) {
                        $uri=$a;
                    } else {
                        $uri=$a($params);
                    }
                    //add url named params to request params
                    foreach ($params as $k=>$v) {
                        if (\is_string($k)) {
                            $req[$k]=$v;
                        }
                    }
                }
            }
        }
        list($controller, $action, $id)=array_filter(explode("/", $uri))+[$options['default_controller'],$options['default_action'],null];
        $cmodel=(isset($options['controller_namespace'])?('\\'.\trim($options['controller_namespace'], ' \\').'\\'):'').ucfirst($controller).$options['Controller'];
        try {
            $controller=new $cmodel;
        } catch (\Error $e) {
            self::raise_error($e->getMessage());
        }
        
        $act=strtolower($_SERVER['REQUEST_METHOD']).ucfirst($action);
        if (!method_exists($controller, $act)) {
            $act='action'.ucfirst($action);
        }
        if (!method_exists($controller, $act)) {
            self::raise_error('404 : '.get_class($controller).'/'.$act);
        }

        //prepare action params
        $params=[];
        $ref = new \ReflectionMethod($controller, $act);
        foreach ($ref->getParameters() as $param) {
            $name = $param->name;
            $params[$name]=($name=='id')?$id:(isset($req[$name])?$req[$name]:($param->isDefaultValueAvailable()?$param->getDefaultValue():(self::raise_error('required parameter missing: '.$name))));
        }

        //call
        return call_user_func_array([$controller, $act], $params);
    }
}
