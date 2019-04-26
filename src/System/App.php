<?php

namespace App\System;

use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\HttpKernel\Controller;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing;
use Symfony\Component\HttpKernel;
 use Symfony\Component\Routing\Matcher\UrlMatcher;


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

    public function getRequest()
    {
        return $this->request;
    }

    public function getRequestContext()
    {
        return $this->requestContext;
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
        $matcher = new UrlMatcher($this->routes, $this->requestContext);

        try {
            $this->request->attributes->add($matcher->match($this->request->getPathInfo()));
            $controller = $this->getController();
            dd($controller);
            //$arguments = $this->getArguments($controller);
        } catch(\Exeption $e) {
            die('error');
        }
    }

    private function getController()
    {
        return (new ControllerResolver())->getController($this->request);
    }

    private function getArguments()
    {
        
    }

    private function __construct($basePath)
    {
        $this->basePath = $basePath;
        $this->setRequest();
        $this->setRequestContext();
        $this->setRouter();
        $this->routes = $this->router->getRouteCollection();
    }

    private function setRequest()
    {
        $this->request = Request::createFromGlobals();
    }

    private function setRequestContext()
    {
        $this->requestContext = new RequestContext();
        $this->requestContext->fromRequest($this->request);
    }

    private function setRouter()
    {
        $fileLocator = new FileLocator([__DIR__]);

        $this->router = new Router(
            new YamlFileLoader($fileLocator),
            $this->basePath.'/config/routes.yaml',
            ['cache_dir' => $this->basePath.'storage/cache']
        );
    }
}