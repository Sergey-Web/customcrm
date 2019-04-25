<?php

namespace App\System;


class App
{
    private $request;
    private $router;
    private $routers;
    private $requestContext;
    private $controller;
    private $arguments;
    private $basePath;
    private static $instance = null;

    private function __construct($basePath)
    {
        $this->setRequest();
        $this->setRequestContext();
        $this->setTouter();
    }

    public static function getInstance($basePath)
    {
        if (is_null(static::$instance)) {
            static::$instance = new static($basePath);
        }

        return static::$instance;
    }

    public function run()
    {
        echo 'hello world';
    }
}