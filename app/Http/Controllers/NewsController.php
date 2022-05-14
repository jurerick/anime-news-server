<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vote;

class NewsController extends Controller
{
    public function index ($page, $limit) 
    {
        return $this->getNews($page, $limit);
    }  


    public function popular ($page, $limit) 
    {
        return $this->getNews($page, $limit, 'popular');
    }


    private function getNews($page, $limit, $filter = null) 
    {
        $keyword = config('news.default_keyword');

        $result = search_news($keyword, $page, $limit);

        if($result->object()->status === 'ok') 
        {
            $articles = $this->getArticles($result->collect('articles'), $keyword, $filter);
            
            return [
                'result' => $articles,
                'total' => $result->object()->totalResults
            ]; 
        }
        
        return response()->json([
            'message' => $result->object()->message
        ], 404);
    }


    private function getArticles($collection, $keyword, $filter = 'all') 
    {
        $articles = $collection->map(function ($article) use ($keyword) {

            $newsSource = format_news_source($article['source']['id'], $article['source']['name']);

            $vote = Vote::where('news_keyword', $keyword)
                ->where('news_source', $newsSource)
                ->where('published_at', $article['publishedAt'])
                ->first();

            $article['voteCount'] = ($vote) ? $vote->count: 0;

            return $article;
        });

        if($filter === 'popular') 
        {
            $articles = $articles->reject(function ($article) {
                
                if($article['voteCount'] <= 0)
                {
                    return true;
                }
            });
        }

        return $articles;
    }

    public function mock() 
    {
        $keyword = config('news.default_keyword');

        $mockData = $this->mockResult();

        $articles = $this->getArticles(
            collect($mockData['articles']), 
            $keyword);

        return [
            'result' => $articles,
            'total' => $mockData['totalResults']
        ]; 
    }

    private function mockResult () 
    {
        $mockData = [
            "status" => "ok",
            "totalResults" => 5137,
            "articles" => [
                [
                    "source" => [
                        "id" => "reuters",
                        "name" => "Reuters"
                    ],
                    "author" => null,
                    "title" => "EXCLUSIVE Netflix inks Japan studio deal in anime push - Reuters.com",
                    "description" => "Netflix Inc <a href=\"https://www.reuters.com/companies/NFLX.O\" target=\"_blank\">(NFLX.O)</a> on Tuesday announced a multi-film deal with Japan's Studio Colorido, as the streaming giant ramps up its anime offering and looks to Asia for growth.",
                    "url" => "https://www.reuters.com/technology/exclusive-netflix-inks-japan-studio-deal-anime-push-2022-04-26/",
                    "urlToImage" => "https://www.reuters.com/resizer/or3HOfuTUsQBe2B1y0AtJ5SI2OA=/1200x628/smart/filters:quality(80)/cloudfront-us-east-2.images.arcpublishing.com/reuters/JA4YLM2RLZMO5H5BQEFTASENRI.jpg",
                    "publishedAt" => "2022-04-26T23:26:00Z",
                    "content" => "LOS ANGELES/TOKYO, April 26 (Reuters) - Netflix Inc (NFLX.O) on Tuesday announced a multi-film deal with Japan's Studio Colorido, as the streaming giant ramps up its anime offering and looks to Asia … [+2309 chars]"
                ],
                [
                    "source" => [
                        "id" => null,
                        "name" => "Kotaku"
                    ],
                    "author" => "Isaiah Colbert",
                    "title" => "Your Spring 2022 Anime Guide",
                    "description" => "Springtime is upon us once again, and much like the sprouting of cherry blossoms, new anime are cropping up all over the pace. Read more...",
                    "url" => "https://kotaku.com/anime-spring-2022-season-netflix-funimation-crunchyroll-1848831655",
                    "urlToImage" => "https://i.kinja-img.com/gawker-media/image/upload/c_fill,f_auto,fl_progressive,g_center,h_675,pg_1,q_80,w_1200/4baa13bbff335a5d3e7c89e4ca232f43.jpg",
                    "publishedAt" => "2022-04-25T14:00:00Z",
                    "content" => "Springtime is upon us once again, and much like the sprouting of cherry blossoms, new anime are cropping up all over the pace. \r\nLike tending a garden, weeding and pruning your backlog of anime to ma… [+270 chars]"
                ],
                [
                    "source" => [
                        "id" => null,
                        "name" => "Gizmodo.com"
                    ],
                    "author" => "Justin Carter",
                    "title" => "Tanjiro Gets a Fancy New Sword in Demon Slayer's First Season 3 Tease",
                    "description" => "Demon Slayer: Kimetsu No Yaiba became a pretty big deal last year. Not only did its theatrical film, Mugen Train, make plenty of money during its theatrical box office run and become Japan’s most financially successful movie in their box office history, its s…",
                    "url" => "https://gizmodo.com/demon-slayer-season-3-swordsmith-village-arc-1848802426",
                    "urlToImage" => "https://i.kinja-img.com/gawker-media/image/upload/c_fill,f_auto,fl_progressive,g_center,h_675,pg_1,q_80,w_1200/af8f0654333370a140d454393346b9dd.jpg",
                    "publishedAt" => "2022-04-16T18:40:00Z",
                    "content" => "Demon Slayer: Kimetsu No Yaibabecame a pretty big deal last year. Not only did its theatrical film, Mugen Train, make plenty of money during its theatrical box office run and become Japans most finan… [+1917 chars]"
                ],
                [
                    "source" => [
                        "id" => null,
                        "name" => "Kotaku"
                    ],
                    "author" => "Isaiah Colbert",
                    "title" => "Tired Of Battle Anime? Here's Four Wholesome Shows You Should Check Out",
                    "description" => "For those who tire of battle-centric shonen anime series and want a little bit more “aww” in their lives, these anime will warm your heart. Read more...",
                    "url" => "https://kotaku.com/anime-netflix-crunchyroll-spy-x-family-kotaro-lives-alo-1848917781",
                    "urlToImage" => "https://i.kinja-img.com/gawker-media/image/upload/c_fill,f_auto,fl_progressive,g_center,h_675,pg_1,q_80,w_1200/c30f5c777ac65f970b0f87c02d9f15e8.jpg",
                    "publishedAt" => "2022-05-12T18:35:00Z",
                    "content" => "For those who tire of battle-centric shonen anime series and want a little bit more aww in their lives, these anime will warm your heart. \r\nIf you were to look at anime as a genre from a distance, yo… [+6956 chars]"
                ],
                [
                    "source" => [
                        "id" => null,
                        "name" => "Android Central"
                    ],
                    "author" => "rmogan11@gmail.com (Rachel Mogan)",
                    "title" => "Tower of Fantasy preview: It's not just a Genshin clone",
                    "description" => "A new anime-styled MMORPG is coming up to bat with Tower of Fantasy for Android. Is it really the \"Genshin Killer\" people claim it is? Is the comparison even accurate?",
                    "url" => "https://www.androidcentral.com/gaming/android-games/tower-of-fantasy-preview",
                    "urlToImage" => "https://cdn.mos.cms.futurecdn.net/74jmUiKrz4Wueq8MHz2nnL-1200-80.png",
                    "publishedAt" => "2022-04-28T13:00:00Z",
                    "content" => "If you’re into mobile gaming, especially mobile gacha games, you’ve probably heard a lot of buzz around Tower of Fantasy. Touted by some as a possible “Genshin Killer,\" this MMORPG recently entered i… [+10073 chars]"
                ],
                [
                    "source" => [
                        "id" => "engadget",
                        "name" => "Engadget"
                    ],
                    "author" => "Mat Smith",
                    "title" => "The Morning After: Our verdict on Playdate, the console with a crank",
                    "description" => "After all that waiting, Senior Editor Jessica Conditt finally got her hands on Playdate. From the makers of Firewatch and meme-friendly Untitled Goose Game, it’s a petite portable console that marries the familiarity of the GameBoy with the spirit of indie ga…",
                    "url" => "https://www.engadget.com/the-morning-after-playdate-console-with-crank-review-111546719.html",
                    "urlToImage" => "https://s.yimg.com/os/creatr-uploaded-images/2021-07/d7669710-eabf-11eb-babb-322abab2b70c",
                    "publishedAt" => "2022-04-19T11:15:46Z",
                    "content" => "After all that waiting, Senior Editor Jessica Conditt finally got her hands on Playdate. From the makers of Firewatch and meme-friendly Untitled Goose Game, its a petite portable console that marries… [+2783 chars]"
                ],
                [
                    "source" => [
                        "id" => null,
                        "name" => "CNET"
                    ],
                    "author" => "Mark Serrels",
                    "title" => "The 16 Best Fantasy TV Shows to Watch on Netflix - CNET",
                    "description" => "Netflix is stacked with amazing fantasy shows. Here are our favourites...",
                    "url" => "https://www.cnet.com/culture/entertainment/the-16-best-fantasy-tv-shows-to-watch-on-netflix/",
                    "urlToImage" => "https://www.cnet.com/a/img/resize/fb4f2cac09e53fda4fcdbaac827c4d821bf29b6c/2020/02/19/d9814fc6-4b9b-463b-8206-e0475a1ce0b1/csv3-301-master-v01-01-10-49-00-still022.jpg?auto=webp&fit=crop&height=630&width=1200",
                    "publishedAt" => "2022-04-26T03:34:00Z",
                    "content" => "Netflix is stacked with fantasy TV shows. There's so much quality it's tough to figure out where to start. \r\nHere are our picks for the best fantasy shows on Netflix. If you're after fantasy movies, … [+5444 chars]"
                ],
                [
                    "source" => [
                        "id" => "polygon",
                        "name" => "Polygon"
                    ],
                    "author" => "Kambole Campbell",
                    "title" => "Spy x Family makes parenthood the real impossible mission",
                    "description" => "One of the spring’s most anticipated new anime, Spy x Family delightfully mixes Cold War-era spy dramas with charming slice-of-life comedy.",
                    "url" => "https://www.polygon.com/23020978/spy-x-family-series-premiere-review",
                    "urlToImage" => "https://cdn.vox-cdn.com/thumbor/HVJnnWKbMvUJrU8WsI4zLqqM1MA=/0x30:2880x1538/fit-in/1200x630/cdn.vox-cdn.com/uploads/chorus_asset/file/23383214/Screen_Shot_2022_04_11_at_4.40.11_PM.png",
                    "publishedAt" => "2022-04-12T15:05:00Z",
                    "content" => "Not long after literally barricading his adopted child inside his new apartment, Spy x Familys frosty and aloof secret agent, codenamed Twilight, discovers that this whole parenthood thing isnt quite… [+5941 chars]"
                ],
                [
                    "source" => [
                        "id" => null,
                        "name" => "CNET"
                    ],
                    "author" => "Mark Serrels",
                    "title" => "The Best Fantasy TV Shows on Netflix - CNET",
                    "description" => "Looking for a fantasy show to watch? Look no further.",
                    "url" => "https://www.cnet.com/culture/entertainment/the-17-best-fantasy-tv-shows-on-netflix/",
                    "urlToImage" => "https://www.cnet.com/a/img/resize/02fd557b3ea0382604abc02eeb04f9782df8f854/2019/05/24/a1007442-9731-4a1b-aa9c-632a6569db42/supernatural-season-3.jpg?auto=webp&fit=crop&height=630&width=1200",
                    "publishedAt" => "2022-05-08T22:48:00Z",
                    "content" => "Netflix and fantasy go together salt and pepper, ham and eggs, any classic food pairing you care to name. There's so much quality Fantasy it's tough to figure out where to start. \r\nHere are our picks… [+5991 chars]"
                ],
                [
                    "source" => [
                        "id" => null,
                        "name" => "Digital Trends"
                    ],
                    "author" => "Guillermo Kurten",
                    "title" => "Great anime movie hits worth streaming",
                    "description" => "Anime movies have never been more accessible internationally, and these are some of the best available on the most popular streamers.",
                    "url" => "https://www.digitaltrends.com/movies/anime-movies-streaming-hbo-amazon-netflix-hulu/",
                    "urlToImage" => "https://icdn.digitaltrends.com/image/digitaltrends/promare-anime-movie.jpg",
                    "publishedAt" => "2022-04-27T16:00:26Z",
                    "content" => "It arguably has never been a better time to be an anime fan outside the genre’s domestic audience. Anime TV shows and movies have become far more embedded into western pop culture, with the likes of … [+7832 chars]"
                ],
                [
                    "source" => [
                        "id" => "polygon",
                        "name" => "Polygon"
                    ],
                    "author" => "Michael McWhertor",
                    "title" => "Nintendo’s weird Super Mario anime from 1986 has been lovingly restored in 4K",
                    "description" => "Nintendo’s 1986 anime movie, Super Mario. Bros - The Great Mission to Rescue Princess Peach, has been remastered and newly translated. It’s available to watch free on YouTube or download from the Internet Archive.",
                    "url" => "https://www.polygon.com/23030167/super-mario-bros-anime-4k-remaster-great-mission-rescue-princess-peach",
                    "urlToImage" => "https://cdn.vox-cdn.com/thumbor/CcUs5zYHDFf67YAnYTFAXtZWrwU=/0x21:1920x1026/fit-in/1200x630/cdn.vox-cdn.com/uploads/chorus_asset/file/23397222/mario_anime_toad.jpg",
                    "publishedAt" => "2022-04-18T16:33:00Z",
                    "content" => "Image: Grouper Productions/Nintendo/FemBoy Films\r\n\n \n\n Restoration of The Great Mission to Rescue Princess Peach available to stream or download\n \n Continue reading…"
                ],
                [
                    "source" => [
                        "id" => "buzzfeed",
                        "name" => "Buzzfeed"
                    ],
                    "author" => "Joshua Correa",
                    "title" => "These 5 Anime Should Be Ion Must Watch List If You Like HBO Max's \"Tokyo Vice\"",
                    "description" => "If you like organized crime shows, these anime are right up your alley.View Entire Post ›",
                    "url" => "https://www.buzzfeed.com/joshcorrea/5-anime-you-should-be-watching-if-you-like-hbo-maxs-tokyo",
                    "urlToImage" => "https://img.buzzfeed.com/buzzfeed-static/static/2022-04/22/14/campaign_images/286df5b87cbc/5-anime-you-should-be-watching-if-you-like-hbo-ma-2-1710-1650638768-12_dblbig.jpg",
                    "publishedAt" => "2022-04-24T12:51:17Z",
                    "content" => "Sunrise\r\nA bounty hunting show set in space, what's there not to love? It's hands down one of my favorite anime growing up and, in my opinion, a must-watch for anybody diving into anime for the first… [+497 chars]"
                ],
                [
                    "source" => [
                        "id" => null,
                        "name" => "Boing Boing"
                    ],
                    "author" => "Devin Nealy",
                    "title" => "How did Avatar construct such realistic characters?",
                    "description" => "Few cartoons boast the unassailable reputation of Nickelodeon's Avatar: The Last Airbender. Now more than 20 years old, Avatar remains among the most popular animated shows in the West. You can attribute Avatar's success to a myriad of factors, including the …",
                    "url" => "https://boingboing.net/2022/04/21/how-did-avatar-construct-such-realistic-characters.html",
                    "urlToImage" => "https://i0.wp.com/boingboing.net/wp-content/uploads/2022/04/Screen-Shot-2022-04-19-at-3.40.31-PM.png?fit=1200%2C791&ssl=1",
                    "publishedAt" => "2022-04-21T12:45:07Z",
                    "content" => "Few cartoons boast the unassailable reputation of Nickelodeon's Avatar: The Last Airbender. Now more than 20 years old, Avatar remains among the most popular animated shows in the West. You can attri… [+600 chars]"
                ],
                [
                    "source" => [
                        "id" => null,
                        "name" => "Digital Trends"
                    ],
                    "author" => "Guillermo Kurten",
                    "title" => "Why 2022 could be a big year for seinen anime series",
                    "description" => "Shonen anime series are the majority of high-profile releases, but 2022 has the makings of being a big year for seinen shows like Vinland Saga and Golden Kamuy.",
                    "url" => "https://www.digitaltrends.com/movies/major-2022-anime-seinen-tv-series/",
                    "urlToImage" => "https://icdn.digitaltrends.com/image/digitaltrends/jojo-stone-ocean.jpg",
                    "publishedAt" => "2022-04-19T01:00:29Z",
                    "content" => "Anime has never had such a big mainstream spotlight on the international stage as it does now. When you once had to be an in-the-know fan to watch the latest anime TV series subtitled in English, all… [+7958 chars]"
                ],
                [
                    "source" => [
                        "id" => null,
                        "name" => "Gizmodo.com"
                    ],
                    "author" => "James Whitbrook and Gordon Jackson",
                    "title" => "Updates From Ultraman, Riverdale, and More",
                    "description" => "Steph Curry stars in a peculiar get for Nope. Get a glimpse of what’s coming on Halo. Superman & Lois, Naomi, and more tease what’s next. Plus, Riverdale teases its continued descent into hysteria with a teaser for its upcoming time travel episode. Spoilers n…",
                    "url" => "https://gizmodo.com/netflix-ultraman-anime-season-3-final-2023-1848804084",
                    "urlToImage" => "https://i.kinja-img.com/gawker-media/image/upload/c_fill,f_auto,fl_progressive,g_center,h_675,pg_1,q_80,w_1200/249b6eab0029660e2112dfde41045d7c.png",
                    "publishedAt" => "2022-04-18T13:51:00Z",
                    "content" => "Steph Curry stars in a peculiar get for Nope. Get a glimpse of whats coming on Halo. Superman &amp; Lois, Naomi, and more tease whats next. Plus, Riverdale teases its continued descent into hysteria … [+4717 chars]"
                ]
            ]
        ];

        return $mockData;
    }

}
