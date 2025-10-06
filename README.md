1. - Скопируй проект 

```
https://github.com/V-Kozintsev/file_2_report.git
```

2. - Установи зависимости проекта командой из корневой директории проекта:

```
composer install
```

```
npm install
```

3. - Скопируйте файл `Homestead.yaml.example` и переименуйте в `Homestead.yaml`. Отредактируй.

4. - Скопируйте файл `.env.example` и переименуйте в `.env`. Отредактируй.

5. - Отредактируй файл `hosts` на уровне системы, например, в Windows по пути `c:\windows\system32\drivers\etc\hosts`. Добавь строку:

```
192.168.10.10 http://file2report.test
```

6. - Запускаем виртуалку:

```
vagrant up
```

## Если же появляется ошибка или предупреждение с текстом типа "Repository ... changed its 'Label' value" ,выполни следующее

1. - Зайти внутрь виртуальной машины через SSH:

```
vagrant ssh
```

2. - Выполни команду
```
sudo apt-get update --allow-releaseinfo-change
```

3. - Перезапусти виртуалку:

```
vagrant reload --provision
```

7. - Сгенерируй ключ приложения в директории `code`(через SSH на виртуалке `vagrant ssh`):

```
php artisan key:generate
```

8. - Выполни миграции базы данных:

```
php artisan migrate
```

9. - Создай символическую ссылку для хранения файлов:

```
php artisan storage:link
```

10. - Перезагрузка виртуалки

```
vagrant reload --provision
```

- Временно но пока такой путь

```
// Register the Composer autoloader...
require __DIR__.'/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/bootstrap/app.php';
```


- Проверка бд в директории `code`(через SSH на виртуалке `vagrant ssh`):

```
mysql -u root -e "SHOW DATABASES;"
```



