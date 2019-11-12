<?php

namespace Kernel;

use Auryn\Injector;
use Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use GuzzleHttp\Client as GuzzleClient;
use Kernel\Contracts\View;
use Kernel\Exceptions\HttpException;

class Bootstrap
{
    /**
     * The IoC container for the application
     *
     * @var Auryn\Injector
     */
    protected $injector;

    /**
     * Dotenv object that is handling the population of the dotenv function in our application
     *
     * @var Dotenv\Dotenv
     */
    protected $dotenv;

    /**
     * Super basic routing, we'll only need to serve GET requests here
     *
     * @var array
     */
    protected $routes = [
        'GET' => [
            '/' => 'App\\GoogleSearch\\Contracts\\Controllers\\SearchResultsControllerInterface@index',
            '/results' => 'App\\GoogleSearch\\Contracts\\Controllers\\SearchResultsControllerInterface@results'
        ]
    ];

    /**
     * Our default headers for responses
     *
     * @var array
     */
    protected $headers = [
        'Content-Type', 'text/html'
    ];

    /**
     * Bootstrap and run the application. This is our application entrypoint.
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function run()
    {
        try {
            $this->bootstrapDotEnv();
            $this->bootstrapIocContainer();

            $response = $this->processRequest();
        } catch (\Exception $e) {
            $response = $this->handleError($e);
        }

        return $response;
    }

    /**
     * Render the view. In a perfect world this would be done from within a service, but for brevity we'll just do it here.
     * Inspired by laravels php template handler, with a small twist for basic layouts without a proper templating engine
     *
     * @return string
     */
    public function renderView(View $view)
    {
        $__data = $view->getData();

        ob_start();

        extract($__data, EXTR_SKIP); // make our tempalte data available in this scope

        include $view->getTemplatePath();

        $render = ltrim(ob_get_clean());

        // very primitive layout view implementation. It will do for the purpose of the exercise.
        if (method_exists($view, 'getLayoutClass')) {
            $layout_class = $view->getLayoutClass();

            $render = $this->renderView($layout_class::make(['page' => $render]));
        }

        return $render;
    }

    /**
     * Attach headers to the repsonse object
     *
     * @param Symfony\Component\HttpFoundation\Response $response
     * @return Symfony\Component\HttpFoundation\Response
     */
    protected function attachHeaders(Response $response)
    {
        foreach ($this->headers as $name => $value) {
            $response->headers->set($name, $value);
        }

        return $response;
    }

    /**
     * Parse the route definition into controller and controller method
     *
     * @param string $route
     * @return array
     */
    protected function parseRoute($route)
    {
        return explode('@', $route);
    }

    /**
     * The bulk of the work to render the response is done here.
     *
     * @return void
     */
    protected function processRequest()
    {
        $request = $this->injector->make(Request::class);

        $path = $request->getPathInfo();
        $request_method = $request->getMethod();

        if (isset($this->routes[$request_method]) && isset($this->routes[$request_method][$path])) {
            $view = $this->getViewFromRoute($this->routes[$request_method][$path]);
        } else {
            throw new \Kernel\Exceptions\HttpException('Page not found', 404);
        }

        $response = $this->injector->make(Response::class);

        $response = $this->attachHeaders($response);
        $response->setContent($this->renderView($view));

        return $response;
    }


    /**
     * Runs the controller method specified by our route and returns a view object
     *
     * @param string $route
     * @return Kernel\Contracts\View
     */
    protected function getViewFromRoute(string $route)
    {
        list($controller_name, $controller_method) = $this->parseRoute($route);
        $controller = $this->injector->make($controller_name);

        return $controller->$controller_method();
    }

    /**
     * Basic error handler. Logging would be implemented here or here-abouts.
     *
     * @param Exception $e
     * @return Symfony\Component\HttpFoundation\Response
     */
    protected function handleError(\Exception $e)
    {
        /*if (!$this->injector || getenv('APP_ENV') === 'dev') {
            // error has happened earlier in the stack than injector being setup, throw full error
            throw $e;
        }*/

        $response = $this->injector->make(Response::class);

        $response->setContent($e->getMessage());

        if ($e instanceof HttpException) {
            $status_code = $e->getCode();
        } else {
            $status_code = 500;
        }

        $response->setStatusCode($status_code);

        return $response;
    }

    /**
     * Bootstrap the Dotenv implementation
     *
     * @return void
     */
    protected function bootstrapDotEnv()
    {
        $this->dotenv = Dotenv::create(__DIR__ . '/../');
        $this->dotenv->load();
        $this->dotenv->required([
            'GOOGLE_API_KEY',
            'GOOGLE_SEARCH_ENGINE_ID',
            'GOOGLE_SEARCH_APP_NAME'
        ]);
    }

    /**
     * Bootstrap our dependency injector
     *
     * @return void
     */
    protected function bootstrapIocContainer()
    {
        $this->injector = new Injector();

        // here is where some nasty static stuff is going on. This should ideally be loaded from a provider within the google search module.
        // I'll claim this as a bit of a shortcut! :)

        $this->bootstrapRequestObject($this->injector);
        $this->bootstrapIocContainerGoogleSearch($this->injector);
    }

    /**
     * Bootstrap the HttpFoundation Request object for injection into controllers
     *
     * @param Auryn\Injector $injector
     * @return void
     */
    protected function bootstrapRequestObject(Injector $injector)
    {
        $injector->share(Request::createFromGlobals());
    }

    /**
     * Bootstrap the dependencies for our GoogleSearch module
     *
     * @param Auryn\Injector $injector
     * @return void
     */
    protected function bootstrapIocContainerGoogleSearch(Injector $injector)
    {
        // Create the google api guzzle client as a singleton
        $injector->share($this->makeGoogleApiGuzzleClient());

        // Create other singletons
        $injector->share(\App\GoogleSearch\Contracts\Repositories\SearchRepository::class);
        $injector->share(\App\GoogleSearch\Contracts\Services\GoogleSearchServiceInterface::class);

        // Bind the concrete implementations of our module interfaces for dependency injection
        $injector->alias(\App\GoogleSearch\Contracts\Controllers\SearchResultsControllerInterface::class, \App\GoogleSearch\Controllers\SearchResultsController::class);
        $injector->alias(\App\GoogleSearch\Contracts\Repositories\SearchRepository::class, \App\GoogleSearch\Repositories\GoogleSearchRepositoryApi::class);
        $injector->alias(\App\GoogleSearch\Contracts\Services\GoogleSearchServiceInterface::class, \App\GoogleSearch\Services\GoogleSearchService::class);
    }

    /**
     * Create a Guzzle http client for googleapis.com urls
     * 
     * This is another shortcut I've taken in the project that saves me writing my own curl, data containers for results, etc.
     * If you were to deploy this as some sort of micro service, this would be the first thing cut for a raw curl implementation at the repository level.
     *
     * @return GuzzleClient
     */
    protected function makeGoogleApiGuzzleClient()
    {
        return new GuzzleClient(['base_uri' => 'https://www.googleapis.com/']);
    }
}