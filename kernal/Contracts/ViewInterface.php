<?php

namespace Kernal\Contracts;

interface ViewInterface
{
    public function getLayout();
    public function toString();
}