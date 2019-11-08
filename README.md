# CreditorWatch Technical Test
This application has been built in accordance with the instructions of a technical test as part of an interview process for CreditorWatch.

## Dependencies

### Running Locally
* PHP 7.2+
* Composer (https://getcomposer.org/download/)

### Running with Docker
* Docker CLI
* Docker compose CLI

## Running the application

### Running Locally
* Clone this repository - `git clone https://github.com/perverse/creditorwatch-test`
* Go to repository directory and install dependencies - `composer install`
* Run from inside the repository directory - `php -S localhost:8000`

### Running with Docker/docker-compose
* Clone this repository - `git clone https://github.com/perverse/creditorwatch-test`
* Go to repository directory and run with docker-compose - `docker-compose up`

## Running tests
Once you have the application running using one of the above methods, you can run the test using PHPUnit installed by composer

### Running Locally
* From the repository directory run `./vendor/bin/phpunit`

### Running with docker
* From the repository directory run `docker-compose exec web ./var/www/html/vendor/phpunit/bin/phpunit`

## Shortcuts
Most of my shortcuts are based around not wasting time solving already solved problems within the PHP community (without employing a framework, as stipulated in the task instructions). These are components that certainly could be built  in addition to the rest of the project, but for brevity and to focus time on the business logic of the task I've chosen to employ some external libraries. I've effectively strung together my own micro framework in doing so.
- Using a 3rd party IOC container for dependency injection (rdlowrey/Auryn). I believe a inversion of control container encourages good OOP programming practices when used effectively, and wanted to easily demonstrate that in the project.
- Using a 3rd party request object builder (Symfony HttpFoundation component). Just a clean way to access properties of the URL and request variables.
- Using symfony/dotenv to process environment variables. Environment control is important, and this is mainly to facilitate the Google API key without needing to modify git-aware code.
- I'm not using a templating engine to keep my use of external libraries to a minimum. It's a good thing PHP was originally designed as a templating language ;)

## Architecture
I am employing an MVC and SOA-like architecture for this project. The SOA elements come from isolating disparate modules of logic that could be deployed seperately (along with the same bootstrapping) into a more microservices-like architecture. In this case there is only a single module to begin with in the application as per instructions of the task (the GoogleSearch module). The MVC element dictates the layout of the logic that happens within each module. The responsibility of different layers of the stack are as follows. This architecture will should familiar and comfortable to anyone that has used a modern PHP framework with dependency injection.

### Bootrapper
This layer is used to bootstrap the various wiring required for the app to function. This includes bootstrapping the service container, application config and routing. This layer selects the different modules to be included into the application stack.

### Modules
Modules are discrete, decoupled coolections of data logic relating to a particular business function of the system. They are structured using an MVC-ish pattern. Each module comprises of 4 layers - Controllers, Services, Repositories and Views.

#### Controller
The responsibility of the controller layer is to collect data from a request, pass it to the business logic (service) layer and then bind the returns data to a View object. Controllers serve as the HTTP interface to the business logic of the application, and would be replaced by something else if you were to write another interface (such as websockets, CLI, etc) to this logic.

#### Service
Services contain the business logic and workings of the system. They are loaded by controllers and utilize repositories to fetch, manipulate and store data within the system based on the request being made of them by an interface.

#### Repositories
This is the data logic layer of a module. Repositories are used by services to talk to internal and external sources of data.

#### Views
These are the presentation logic of the system. Views take data from services (facilitated by controllers) and bind it to html/json/xml/other outputs from the system.
