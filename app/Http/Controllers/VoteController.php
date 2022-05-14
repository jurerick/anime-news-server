<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vote;

class VoteController extends Controller
{
 
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $newsSource = format_news_source($request->news_source_id, $request->news_source_name);

        $vote = Vote::firstOrCreate(
            [
                'news_source' => $newsSource,
                'news_keyword' => $request->news_keyword,
                'published_at' => $request->published_at
            ]
        );

        return $vote;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request /*, $id*/)
    {
        $newsSource = format_news_source($request->news_source_id, $request->news_source_name);

        $vote = Vote::where('news_source', $newsSource)
            ->where('news_keyword', $request->news_keyword)
            ->where('published_at', $request->published_at)
            ->first();

        if($vote) 
        {
            if($request->upvote) 
            {
                $vote->increment('count');
            }
            else 
            {
                if($vote->count > 0) 
                {
                    $vote->decrement('count');
                }
            }
            
            if($vote->save()) 
            {
                return $vote;
            }
        }

        return response()->json([
            'message' => 'Record not found.'
        ], 404);
    }
}
