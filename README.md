<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## Инструкция развёртывния проекта


- Создать пустую папку для клонирования проекта
- Прописать в терминале в той же папке `git clone https://github.com/Zuev-Yaroslav/yandex-maps-admin.git` (убедитесь, что на вашем ПК установлен GIT)
- Создать дубликат файла .env.example и переименовать на .env
- В .env указать:
    * пароль {DB_PASSWORD}
    * базу данных {DB_DATABASE}
    * порт {DB_PORT}
    * url proxy {PROXY_URL} обязательно, чтобы не получить капчу

- Запускаем докер:
```` bash
docker-compose up -d
````
- Заходим в wb_api_app контейнер
```` bash
docker exec -it yandex_map_reviews_app bash
````
```` bash
composer update --no-scripts
php artisan key:generate
php artisan migrate
composer update
npm i
php artisan ziggy:generate --types
npm run build
chmod -R 777 ./
````

Можно пользоваться

## Инструкция пользования

- Зайти на свое приложение и зарегистрироваться (sign up)
- В Настройке (в левом панели) найдите текстовое поле, где над ним написан "Укажите ссылку на Яндекс, пример https://yandex.ru/maps/org/samoye_populyarnoye_kafe/1010501395/reviews/"
- Укажите ссылку строго по этому примеру, иначе получите ошибку "The org reviews url field format is invalid."
- Нажмите на "Сохранить", если всё правильно появится сообщение "Сохранено"
- Перейдите на Отзывы и вы увидите их.
- Отзывы сохранены в кэш со сроком 60 минут, если хотите очистить, то нажмите еще раз сохранить.
