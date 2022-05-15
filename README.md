# Anime News Server

**Anime News Server** is the api server for **Anime News** project.
This is a [**Laravel**](https://laravel.com/) powered application.

## Features
- Exposes API for retrieving live articles regarding anime all over the web.
- Create & Update api to vote an article.

## Get Started

### Docker Desktop
Install [**Docker Desktop**](https://www.docker.com/products/docker-desktop/)

### git clone
Clone the repository by typing in your terminal: 
```sh
git clone https://github.com/jurerick/anime-news-server.git
```

### sail up
If Docker Desktop is already installed, navigate to the root directory of the application and start [**Laravel Sail**](https://laravel.com/docs/9.x/sail).
```sh
./vendor/bin/sail up
```
The first time you run the Sail **up** command will take several minutes, but subsequent attempts to start Sail will be much faster.


### News API
This application is using [**News API**](https://newsapi.org/) service with a developer subscription. 
Update the ```.env``` file in the root directory of the project and add ```NEWS_API_KEY```. 
```sh
NEWS_API_KEY=put-here-your-api-key
```
Register to [**News API**](https://newsapi.org/register) to get your API key.

Once the application's Docker containers have been started, access the application in your web browser at: ```http://localhost```


## Todos

 - Write unit tests
 - Authentication
 - Search news API

## License

MIT