# Описание #

команды для управления биллингом


# Команды #
открывает инет без VPN
```
localhost#cadbis open  
```
закрывает инет без VPN
```
localhost#cadbis close
```
система поднимается командами:
```
localhost#cadbis start
localhost#cadbis proxy start
```
если проксю надо отключить то:
```
localhost#cadbis proxy stop
```

## короче ##
```
localhost# cadbis
Usage: cadbis [option] status | start | stop | restart | open | close
 option can have the following values: proxy
```





### cadbis start ###
запускает только те демоны, которые не запущены (например, вывалился фрирадиус, она это заметит и запустит только его при этом не трогая остальные, если они запущены)
при запуске она будет ждать пока запустится каждый демон, но ждать будет не более 10 раз по 5 секунд (то есть не более 50 секунд). если за 50 секунд демон не стартует, она попробует стартануть остальные.
### cadbis restart ###
убивает и потом стартует все демоны
### cadbis status ###
показывает какие демоны нормально работают, а какие вывалились
### cadbis proxy status ###
показывает включена ли прокся (и прописано ли правило)

```
localhost# cadbis status
MPD status : [RUNNED]
FreeRADIUS status : [RUNNED]
Squid status : [RUNNED]
JRadius status : [RUNNED]
```
RUNNED означает что демон запущен
STOPPED означает что демон остановлен

```
localhost# cadbis proxy status
CADBiS proxy status : [RUNNED]
```
для прокси RUNNED означает что запущены демоны и при этом прописано правило
```
localhost# cadbis proxy stop
localhost# cadbis proxy status
CADBiS proxy status : [STOPPED]
```
STOPPED означает что либо не запущены демоны либо не прописано правило.
Если демоны запущены, а эта команда показывает STOPPED значит что система работает но без прокси