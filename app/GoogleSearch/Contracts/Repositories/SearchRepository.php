<?php

namespace App\GoogleSearch\Contracts\Repositories;

interface SearchRepository
{
    public function getSearchResults(string $query, int $limit = null);
}