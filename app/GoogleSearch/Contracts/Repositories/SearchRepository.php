<?php

namespace App\GoogleSearch\Contracts\Repositories;

interface GoogleSearchRepository
{
    public function getSearchResults($query, $page = 1, $limit = null);
}