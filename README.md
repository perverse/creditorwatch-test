# CreditorWatch Technical Test
This application has been built in accordance with the instructions of a technical test as part of an interview process for CreditorWatch.

## Dependencies

### Running Locally
* PHP 7.2+ with the following modules installed (all are included auitomatically using the docker method):
    * common
    * curl
    * json
    * mbstring
    * opcache
    * readline
    * xml
* Composer (https://getcomposer.org/download/)

### Running with Docker
* Docker CLI

## Running the application

### Running Locally
* Clone this repository - `git clone https://github.com/perverse/creditorwatch-test`
* Go to repository directory and install dependencies - `composer install`
* Run from inside the repository directory - `php -S localhost:8000`
* The website is now running on http://localhost:8000

### Running with Docker
* Clone this repository - `git clone https://github.com/perverse/creditorwatch-test`
* Go to the repository directory and install dependencies - `docker run --rm -it --volume $(pwd):/app prooph/composer:7.2 install`
* From the same directory, build the container with docker - `docker build -t ronnie-googlecheck-test-app .`
* Now run the container `docker run -d -p 8000:8000 --rm --name ronnie-googlecheck-test-app-container ronnie-googlecheck-test-app`
* The website is now running on http://localhost:8000

## Running tests
Once you have the application running using one of the above methods, you can run the test using PHPUnit installed by composer

### Running Locally
* From the repository directory run `./vendor/bin/phpunit --testdox`

### Running with docker
* From the repository directory run `docker-compose exec web ./var/www/html/vendor/phpunit/bin/phpunit`

## Architecture
I am employing an MVC and SOA-like architecture for this project. The SOA elements come from isolating disparate modules of logic that could be deployed seperately (along with the same bootstrapping) into a more microservices-like architecture. In this case there is only a single module to begin with in the application as per instructions of the task (the GoogleSearch module). The MVC element dictates the layout of the logic that happens within each module. The responsibility of different layers of the stack are as follows. This architecture will should familiar and comfortable to anyone that has used a modern PHP framework with dependency injection.

### Bootstrapper
This layer is used to bootstrap the various wiring required for the app to function. This includes bootstrapping the service container, application config and routing. This layer selects the different modules to be included into the application stack.

### Modules
Modules are discrete, decoupled collections of logic relating to a particular business function of the system. They are structured using an MVC-ish pattern. Each module comprises of 4 layers - Controllers, Services, Repositories and Views.

#### Controller
The responsibility of the controller layer is to collect data from a request, pass it to the business logic (service) layer and then bind the returns data to a View object. Controllers serve as the HTTP interface to the business logic of the application, and would be replaced by something else if you were to write another interface (such as websockets, CLI, etc) to this logic.

#### Service
Services contain the business logic and workings of the system. They are loaded by controllers and utilize repositories to fetch, manipulate and store data within the system based on the request being made of them by an interface.

#### Repositories
This is the data logic layer of a module. Repositories are used by services to talk to internal and external sources of data.

#### Views
These are the presentation logic of the system. Views take data from services (facilitated by controllers) and bind it to html/json/xml/other outputs from the system.

## Shortcuts
Most of my shortcuts are based around not wasting time solving already solved problems within the PHP community (without employing a framework, as stipulated in the task instructions). These are components that certainly could be built  in addition to the rest of the project, but for brevity and to focus time on the business logic of the task I've chosen to employ some external libraries. I've effectively strung together my own micro framework in doing so.
* Using a 3rd party IOC container for dependency injection (rdlowrey/Auryn). I believe a inversion of control container encourages good OOP programming practices when used effectively, and wanted to easily demonstrate that in the project.
* Using a 3rd party request object builder (Symfony HttpFoundation component). Just a clean way to access properties of the URL and request variables.
* Using vlucas/phpdotenv to process environment variables. Environment control is important, and this is mainly to facilitate the Google API key without needing to modify git-aware code.
* I'm not using a templating engine to keep my use of external libraries to a minimum. Raw PHP will work just fine here for my purposes.
* A lot of the bootstrapping could be a lot more dynamically driven and generated. There is a lot of static binding (the GoogleSearch module doesn't have its own bootstrapper/provider, rather is handled all manually inside the main bootstrapper for example) and concrete classes are used at that level just to save some time and keep the task on a reasonable scope. The task isn't to build my own framework, but build enough wiring so that I can implement my architectural pattern easily. If more time was to be invested I would have some sort of Provider classes for the Modules that are then loaded via a configuration file in the main bootstrapping logic. This wouldn't be a difficult refactor at the current level of complexity.
* I have done a very messy job on the "routing" in that it only accomodates the exact url patterns expected by this application. Very basic and again, if the task was more oriented towards building my own framework this would be better.
* The testing could be far more robust (including testing my bootstrapper). I wanted to illustrate that my architecture was fully testable without going too overboard for the scale of this example.

## Regrets
I had some regrets while rolling this out...
* Not using SASS made things predictably messy and painful in the styles
* Not using a templating engine over raw php also made things messy in the templates

## Licence
The MIT License (MIT)

Copyright (c) 2019 Ronnie Pyne

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.