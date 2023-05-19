# OnlyFood Recipe App 

Recipe App with VueJs Symfony to create new recipes. You can plan them for the week & also get a shopping list of it.
This App will also be published when it's finished :) I'm streaming the whole progress every Tuesday & Thursday on Twitch -> joeel561 💜


## Preview

![App Screenshot](https://i2.paste.pics/56eb3acfc0ee7781fc681fe792861f6e.png)
![App Screenshot](https://i2.paste.pics/1fa6834ff067694fee577e9ab676f896.png)
![App Screenshot](https://i2.paste.pics/19ae15bf74aca194b8523d41620065ab.png)
![App Screenshot](https://i2.paste.pics/1f65f80dcd082a1b39b17c6b861c502e.png)
![App Screenshot](https://i2.paste.pics/e16fa461c4e2bec2d229f3b856ca577b.png)


## Tech Stack

**Client:** VueJs, Bootstrap

**Server:** Symfony, MySQL, Docker


## Run Locally
```shell
docker-compose up -d
docker-compose exec php composer install
docker-compose exec php php bin/console doctrine:migrations:diff
docker-compose exec php php bin/console doctrine:migrations:migrate
docker-compose exec php npm ci
docker-compose exec php npm run build
```
