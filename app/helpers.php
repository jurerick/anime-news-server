<?php 

/**
 * Build the news source string
 * This will be use as the identifier to the votes under news.
 */
if (! function_exists('format_news_source')) {

    function format_news_source($sourceId, $sourceName) 
    {
        $combinedSource = ($sourceId) 
            ? $sourceId . '_' . $sourceName 
            : $sourceName;
        
        return Str::snake(strtolower($combinedSource), '-');
    }
}

/**
 * External search request to the News API
 */
if (! function_exists('search_news')) {

    function search_news($keyword, $page, $limit) {

        $endpoint = config('news.end_point') . '?';

        $limit = ($limit <= config('news.max_page_size')) 
            ? $limit
            : config('news.max_page_size');

        $params = [
            'q' => $keyword,
            'apiKey' => config('news.api_key'),
            'pageSize' => $limit,
            'page' => $page
        ];

        return Http::get($endpoint . Arr::query($params));
    }
}