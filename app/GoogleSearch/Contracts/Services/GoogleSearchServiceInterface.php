<?php

namespace App\GoogleSearch\Contracts\Services;

interface GoogleSearchServiceInterface
{
    public function getNumberOfMentionsInQuery($query, $num_results_to_check = 100);
}