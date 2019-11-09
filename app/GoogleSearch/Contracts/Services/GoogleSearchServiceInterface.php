<?php

namespace App\GoogleSearch\Contracts\Services;

interface GoogleSearchServiceInterface
{
    public function getNumberOfMentionsForQuery(string $query, int $num_results_to_check = 100);
}