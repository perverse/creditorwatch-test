<?php

namespace App\Main\Contracts\Repositories;

interface GoogleSearchRepository
{
    public function getSearchResults(string $query, int $page = 1, int $limit = null);
}