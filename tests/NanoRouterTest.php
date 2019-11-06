<?php
declare(strict_types=1);

//  vendor\bin\phpunit --bootstrap .\vendor\autoload.php  --testdox .\tests\NanoRouterTest.php

use PHPUnit\Framework\TestCase;

use admirhodzic\NanoRouter\NanoRouter;

class SiteController
{
    public function actionIndex()
    {
        return 'ok';
    }
    public function actionOther($id)
    {
        return 'other '.$id;
    }
    public function getPosts($filter='')
    {
        return $filter;
    }
    public function postPosts()
    {
        return 'ok';
    }
    public function actionPosts()
    {
        return 'index';
    }
}
final class NanoRouterTest extends TestCase
{
    public function testRouteToSiteIndex(): void
    {
        $_SERVER['REQUEST_URI']='';
        $_SERVER['REQUEST_METHOD']='GET';
        $this->assertEquals(
            NanoRouter::run(),
            'ok'
        );
    }

    public function testRouteToGetPosts(): void
    {
        $_SERVER['REQUEST_URI']='/site/posts?a=b&filter=123';
        $_REQUEST['filter']='123';
        $_SERVER['REQUEST_METHOD']='GET';
        $this->assertEquals(
            NanoRouter::run(),
            '123'
        );
        $_SERVER['REQUEST_METHOD']='OPTIONS';
        $this->assertEquals(
            NanoRouter::run(),
            'index'
        );
    }

    public function testRouteToPOSTPosts(): void
    {
        $_SERVER['REQUEST_URI']='/site/posts?a=b&filter=123';
        $_SERVER['REQUEST_METHOD']='POST';
        $this->assertEquals(
            NanoRouter::run(),
            'ok'
        );
    }

    public function testNoMethodException(): void
    {
        $this->expectException(Exception::class);

        $_SERVER['REQUEST_URI']='site/action';
        $_SERVER['REQUEST_METHOD']='GET';

        NanoRouter::run();
    }

    public function testNoControllerException(): void
    {
        $this->expectException(Exception::class);

        $_SERVER['REQUEST_URI']='dummy/action';
        $_SERVER['REQUEST_METHOD']='GET';

        NanoRouter::run();
    }

    public function testCustomRoutes(): void
    {
        $_SERVER['REQUEST_METHOD']='GET';

        $_SERVER['REQUEST_URI']='r1';
        $this->assertEquals(
            NanoRouter::run([
                'routes'=>[
                    'r1/a1'=>'site/index',
                    'r1'=>'site/other',
                ]
            ]),
            'other '
        );
        $_SERVER['REQUEST_URI']='r1/a1?dummy';
        $this->assertEquals(
            NanoRouter::run([
                'routes'=>[
                    'r1/a1'=>'site/index',
                    'r1'=>'site/other',
                ]
            ]),
            'ok'
        );
        $_SERVER['REQUEST_URI']='r1/a1/1122';
        $this->assertEquals(
            NanoRouter::run([
                'routes'=>[
                    'r1/a1/(?<id>[0-9]*)'=>function ($p) {
                        return 'site/other/'.$p['id'];
                    },
                ]
            ]),
            'other 1122'
        );
        $_SERVER['REQUEST_URI']='r1/a1/5';
        $this->assertEquals(
            NanoRouter::run([
                'routes'=>[
                    'r1/a1(/(?<id>[0-9]*))?'=>function ($p) {
                        return $p['id']==5?'site/other/99':'site/index';
                    },
                ]
            ]),
            'other 99'
        );
        $this->expectException(Exception::class);
        $_SERVER['REQUEST_URI']='route22/sdfs';
        NanoRouter::run([
            'routes'=>[
                'r1'=>'site/index',
                'r1/a1'=>'site/index',
            ]
        ]);
    }
}
