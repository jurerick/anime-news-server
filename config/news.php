<?php 

return [

    /**
     * NEWS API 
     */

    'api_key' => env('NEWS_API_KEY'),

    'end_point' => 'https://newsapi.org/v2/everything',

    'default_keyword' => '"dragon ball"', // todo: make this param available to client.

    'max_page_size' => 15
];