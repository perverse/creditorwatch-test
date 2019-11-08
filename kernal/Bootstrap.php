<?php

namespace Kernal;

use Auryn\Injector;
use Symfony\Component\HttpFoundation\Request;

class Bootstrap
{
    protected $injector;

    protected function bootstrapIocContainerGoogleSearch()
    {

    }

    protected function bootstrapIocContainer()
    {
        $this->injector = new Injector();
        $this->bootstrapIocContainerGoogleSearch(); // here is where some nasty static stuff is going on. This should ideally be loaded from a provider within the google search module.
    }

    public function run()
    {
        $this->bootstrapIocContainer();
    }
}