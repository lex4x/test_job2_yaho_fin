test_job2_yaho_fin
==================

## установка ##

1. склонировать репозиторий
```cmd
git clone https://github.com/lex4x/test_job2_yaho_fin.git
```

2. установить требуемые либы композером
```cmd
composer update
```

3. создать таблицы для данных приложения
```cmd
php app/console doctrine:schema:update --force
```

4. установить assets
```cmd
php app/console assets:install
```

## этапы и затраченое время ##
1. скелет + регистрация / авторизация - 8 часов
2. CRUD портфеля акций - 6 часов
3. изучение работы Yahoo Finance + получение данных - 10 часов
4. вывод графиков - 4 часа