test_job2_yaho_fin
==================

## установка ##

1. установить требуемые либы композером

2. создать таблицы для данных приложения
```cmd
php app/console doctrine:schema:update --force
```

## этапы и затраченое время ##
1. скелет + регистрация / авторизация - 8 часов
2. CRUD портфеля акций - 6 часов
3. изучение работы Yahoo Finance + получение данных - 10 часов
4. вывод графиков - 4 часа