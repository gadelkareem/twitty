# Twitty
[![Build Status](https://travis-ci.org/gadelkareem/twitty.svg)](https://travis-ci.org/gadelkareem/twitty)
 # Installation
- fill and rename .env.dist to .env
- `docker-compose build --no-cache`
- `docker-compose up`
- open [http://localhost:8000/](http://localhost:8000)
 # Run from command line
`docker-compose exec symfony /app/bin/console wonderkind:count-retweeter-followers -vvv --tweet-url=https://twitter.com/SpaceX/status/995043176363671552`






 # TODO
- caching
- tests
- pub/sub ?
    
    
