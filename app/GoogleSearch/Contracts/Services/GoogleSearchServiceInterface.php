<?php

namespace App\GoogleSearch\Contracts\Services;

interface GoogleSearchServiceInterface
{
    public function getNumberOfMentionsForQuery(string $query, string $website, int $num_results_to_check = 100);
}