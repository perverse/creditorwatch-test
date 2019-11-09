<?php

namespace Kernal;

use Auryn\Injector;
use Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\Request;
use GuzzleHttp\Client as GuzzleClient;

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
        '/',
        '/results'
    ];

    /**
     * Bootstrap and run the application. This is our application entrypoint.
     *
     * @return void
     */
    public function run()
    {
        $this->bootstrapDotEnv();
        $this->bootstrapIocContainer();

        try {
            $response = $this->processRequest();
        } catch (\Exception $e) {
            $this->handleError($e);
        }

        $this->generateHeaders();
    }

    protected function processRequest()
    {

    }

    /**
     * Bootstrap the Dotenv implementation
     *
     * @return void
     */
    protected function bootstrapDotEnv()
    {
        $this->dotenv = Dotenv::create(__DIR__ . '/..');
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
    protected function bootstrapRequestObject(Auryn\Injector $injector)
    {
        $injector->share(Request::createFromGlobals());
    }

    /**
     * Bootstrap the dependencies for our GoogleSearch module
     *
     * @param Auryn\Injector $injector
     * @return void
     */
    protected function bootstrapIocContainerGoogleSearch(Auryn\Injector $injector)
    {
        // Create the google api guzzle client
        $injector->share($this->makeGoogleApiGuzzleClient());

        // Bind the concrete implementations of our module interfaces for dependency injection
        $injector->alias(App\GoogleSearch\Contracts\Controllers\SearchResultsControllerInterface::class, App\GoogleSearch\Controllers\SearchResultsController::class);
        $injector->alias(App\GoogleSearch\Contracts\Repositories\SearchRepository::class, App\GoogleSearch\Repositories\GoogleSearchRepositoryApi::class);
        $injector->alias(App\GoogleSearch\Contracts\Services\GoogleSearchServiceInterface::class, App\GoogleSearch\Services\GoogleSearchService::class);
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
        return new GuzzleClient(['base_url' => 'https://www.googleapis.com/']);
    }
}