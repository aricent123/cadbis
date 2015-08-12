[Оригинал на http://www.thierryb.net/pdtwiki/index.php?title=Using_PDT_:_Installation_:_Installing_the_Zend_Debugger]

# Introduction #
Для начала надо всё это иметь на флешке.
Лучше всего создать директорию WWW где-нибудь на диске (пусть будет C).
Дальше в WWW создать директории /bin и /home.

  * Поставить Apache в директорию C:\www\bin\apache (внимательно, чтобы не получилось C:\www\bin\apache\apache2.2 или что-то подобное)
  * Поставить PHP в директорию C:\www\bin\php (то же самое). Отметить при установке "поставить как модуль для Apache 2.2 or higher".
  * Открыть файл C:\www\bin\apache\conf\httpd.conf и исправить в нём строчку
```
  DocumentRoot "C:/www/bin/apache/htdocs"
```
> на
```
  DocumentRoot "C:/www/home"
```
И заменить все вхождения "C:/www/bin/apache/htdocs" в этом файле на "C:/www/home"
так же найти строчку
```
DirectoryIndex index.html
```
добавить туда index.php так:
```
DirectoryIndex index.html index.php
```
  * Создать в папке C:/www/home файл index.php с содержимым:
```
  <?php phpinfo(); ?> 
```
  * Проверить как работает PHP на Apache. Для этого - перезапустить Apache и набрать в адресной строке браузера http://localhost
**_Внимание_** Если Apache не запускается по какой-то причине, возможно, неверно отредактирован файл httpd.conf или же 80 порт занят. Для этого в файле httpd.conf исправить строчку
```
Listen 80
```
> на
```
Listen 8080
```
или любой другой свободный порт. (тогда проверка работоспособности будет заключаться в заходе на URL http://localhost:8080).
  * Скопировать ZendDebugger.dll в папку C:\www\bin\php\ext (если нет - создать её).
  * Посмотреть через phpinfo где находится файл php.ini (Loaded Configuration File). Открыть его в редакторе. Далее найти строчку "extension\_dir = ...". Под ней вставить следующие строки:
```
extension=ZendDebugger.dll
extension=php_mysql.dll
```
Далее в конец файла добавить строки:
```
[Zend]
zend_extension_ts="C:/www/bin/php/ext/ZendDebugger.dll"
zend_debugger.allow_hosts=localhost,127.0.0.1,[твой IP адрес]
zend_debugger.expose_remotely=always 
```
Так же заменить implicit\_flush = Off на implicit\_flush = On и  заменить output\_buffering = 4096 на output\_buffering = 0
Ещё заменить short\_open\_tag = Off на short\_open\_tag = On
Так же заменить display\_errors = Off на display\_errors = On
  * Перезапустить Apache и убедиться по phpinfo, что в группе "Powered by" появилась надпись
```
with Zend Debugger v5.2.12, Copyright (c) 1999-2007, by Zend Technologies
```
  * Скопировать PDT All in one в папку C:\Eclipse (так чтобы eclipse.exe находился в корне этой папки)
  * Запустить Eclipse.
  * Нажать Help -> Software Update -> Find And Install -> Next -> New Remote Site. Далее ввести "Subclipse" и "http://subclipse.tigris.org/update_1.2.x" и нажать Finish. Дальше следовать инструкциям визарда (отметить галочками Subclipse в нужном месте) и после установки перезапустить Eclipse.
  * Далее нажать File -> New -> Project...  Выбрать Checkout projects from SVN и нажать Next. Потом задать параметры репозитория (https://cadbis.googlecode.com/svn/trunk). Подождать когда появится список проектов и выбрать CADBiS\_website. Потом нажать Next -> и Finish.
  * 