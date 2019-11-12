<?php

namespace App\GoogleSearch\Contracts\Services;

interface GoogleSearchServiceInterface
{
    public function getMentionResultsForQuery(string $query, string $website, int $num_results_to_check = 100);
    public function countMentionsInResults(array $results);
}