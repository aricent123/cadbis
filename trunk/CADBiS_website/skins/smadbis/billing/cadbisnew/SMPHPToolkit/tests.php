<?php
require_once(dirname(__FILE__)."/common.inc.php");

// C# code:
//var ary = { 1, 2, 3 };
// var x = 2;
// ary.Select(elem => elem * x;);

// PHP code:
$arr = arr(1,2,3);
$x = 2;
$arr->select(eval(${new closure('$el')}->{'$el*=$x'}));


$a = 10;
eval(${new closure()}->invoke('echo $a+10;'));

closure(eval(${new closure()}->{'
	$a*=10;
'}));

/**
 PHP Closure
Когда со мной начали спорить что PHP - вообще никакой язык, я молчать не смог. <br/>
Утверждалось, что в PHP вообще нет никаких преимуществ перед великим и могучим C#. 
Ну как тут было не поспорить? Лично я считаю, что PHP в любом случае более гибкий язык (в силу
того что он интерпретируем) и всё что можно реализовать на C# - можно несложно реализовать и 
на PHP, а вот обратное во множестве случаев практически невозможно! <br/>
Мне говорили "но ведь PHP - не объектно-ориентированный язык! в C# - есть полноценная 
объектная модель, наследование, интерфейсы, абстрактные классы, виртуальные методы, свойства, и 
большая коллекция паттернов!", "Позвольте, позвольте, но ведь всё это так же есть и в PHP!" - 
отвечал я. В PHP5 есть нормальная объектная модель со всеми этими необходимыми фичами. 
"А есть там делегаты?" - спросили меня. "Они там попросту не нужны" - отвечал я. - 
"Язык скриптовый, динамический и нестрого типизированный, поэтому нечего говорить про
generics, delegates и прочие приблуды - зачем они здесь? Я больше чем уверен, что если мне 
потребуется делегат, я смогу его реализовать на PHP в 2 счёта!". 
Вообще, я быстро устаю от подобных нападок. Они, как правило, абсолютно беспочвенны. Ну почему
мало кто воспринимает скриптовые языки всерьёз? Один лишь Ruby как-то выделяется на фоне 
остальных. Sun даже собирается включить его в следующую версию JDK. А PHP, мол, какашка - 
вчерашний день, не для серьёзных проектов... Ну что за бред, извините меня?  <br/>
У Ruby очень грамотная реклама и хороший подход к обучению - "как писать правильно". И пожалуйста - 
Ruby On Rails лучший фреймворк в Интернете. Может быть, так оно и есть, я спорить не буду. Мне
нравится идеология Rails, но то что Ruby - намного продвинутее PHP как язык, я сказать не могу. 
Думаю, что они скорее сравнимы. Просто почему-то никто не учится писать на PHP правильно. 
Почему-то люди чаще начинают на нём писать плохо, копируют дурацкие примеры, не обращают внимания на 
абстракцию и декомпозицию... В итоге - в Интернете очень много плохого PHP кода.  
На PHP гораздо больше фреймворков чем на Ruby, но далеко не все они сравнимы с ROR по удобству. 
Думаю, что по той же причине, которую я обозначил выше. PHP, видимо, располагает к тому чтобы писать
на нём плохо. Он расслабляет? Позволяет не обращать внимания на мелочи? Я в замешательстве.
Что же до сравнения PHP и C# давайте пройдём по наиболее существенным различиям, сравнивая лишь сам 
язык. Я ни в коей мере не собираюсь сравнивать скажем .NET и PHP - говорю только за  язык. Итак:<br/>
- <b>ООП</b>? Что сказать - необходимый минимум в PHP есть. Интерфейсы, абстрактные классы, наследование, 
паттерны - это всё легко реализуемо. Ну да, нет в нём парциальных классов. Но, по-моему, это совершенно
несущественно. Вот то что нет пространств имён - уже минус, здесь спорить не буду. Хотя, при грамотном
подходе можно обойтись и без них. В конце концов их можно реализовать самостоятельно (есть прекрасные
примеры - ZendFramework, PRADO... etc).<br/>
- <b>Строгая типизация</b>? Такой же плюс как и минус, объяснять, надеюсь, смысла нет.<br/>
- <b>Generics</b>? В PHP от них никакого толка поскольку язык нестрого типизированный. <br/>
- <b>Delegates</b>? - Ну не нужны они здесь! В крайнем случае я могу вызвать create_function(). Это похоже на 
создание анонимного делегата в C#. Например, сортировка массива: <br/>
<pre name="code" class="php">
// PHP 5.x code:
usort($arr, create_function('$a,$b','return ($a==$b)?0:($a<$b)? 1 : -1;'));
</pre>
- <b>Closures</b>? - Вот это уже интересно. В PHP их нет. Думаю, что это довольно существенный недостаток. Однако, 
нет ничего невозможного. Можно реализовать и такую приблуду, если захотеть. Один из вариантов реализации - 
в SMPHPToolkit. Для сравнения:<br/>
C# 3.0 code:<br/>
<pre name="code" class="csharp">
var ary = { 1, 2, 3 };
var x = 2;
ary.Select(elem => elem * x;);
</pre><br/>
То же самое на PHP:
<pre name="code" class="php">
$arr = arr( 1, 2, 3 );
$x = 2;
$arr->select(eval(${new closure('$el')}->{'$el*=$x'}));
</pre>
В принципе получилось не сильно больше кода. А по сути - почти то же самое. То что это можно реализовать
средствами самого языка а не встроенной поддержкой - уже говорит о большей гибкости языка. <br/>
Возможно, это не лучшая эмуляция closure на PHP. Очень надеюсь что полноценные замыкания 
войдут в 6-ю версию.<br/>
Но, признаться, я не считаю это таким уж существенным преимуществом. В конце концов в Java тоже нет 
нормальных замыканий, но самые лучшие фреймворки и паттерны написаны как раз на нём. <br/> Всегда
можно воспользоваться классическими интерфейсами. <br/>
А теперь давайте рассмотрим преимущества PHP, которых уж точно нет в C# и которые так просто не реализовать.
- <b>Буферизованный вывод</b>. Считаю что это существенное преимущество PHP перед конкурентами. Например,
для создания простейшего темплейтного движка: 
<pre name="code" class="php">
 function apply_template($filename, $context)
 {
  ob_start();
  extract($context);
  require($filename);
  return ob_get_clean();
 }

 $content = apply_template("template.tpl.php", array('title'=>'Мой заголовок'));
</pre>
Что делает эта фунцкия? Элементарно - включает файл шаблона в текст текущей страницы и при этом извлекает
переменные из массива $context в текущий контекст. Таким образом, в шаблоне будет доступна переменная
$title со значением 'Мой заголовок'. А весь текст шаблона (с любым PHP/HTML кодом) будет
интерпретирован с текущим контекстом и результат будет возвращён функцией в виде строки. <br/>
- <b>Eval</b>. Элементарно - я могу в любой строке написать php/html код и сделать eval($code). Этот код
будет интерпретирован с текущим контекстом. Таким образом, можно сделать так чтобы код генерировал код. 
Очень удобная возможность, отсутствующая в C#.<br/>
- <b>Autoload</b>. В PHP есть прекрасный механизм - можно подключать файлы с классами лишь тогда, когда
они запрашиваются в первый раз. Например:
<pre name="code" class="php">
function __autoload($class_name) {
    require_once $class_name . '.inc.php';
}
$obj  = new MyClass1();
</pre>
В данном примере при создании объекта класса MyClass1 будет подключён файл MyClass1.inc.php. <br/>
- <b>"Magic methods"</b>. Суть "магических" методов в том, что можно реализовать динамически изменяемые
классы меняющие своё поведение при обращении к определённым свойствам, методам или при преобразовании
их к строке. С помощью __get и __set можно реализовать добавление новых свойств и методов для объекта 
при первом обращении к ним:
<pre name="code" class="php">
class Setter {
  private $_props = array();
  public function __get($prop) {
    if (isset($this->_props[$prop]))
	  return $this->_props[$prop]
    return null;
  }
  function __set($prop, $val) {
      $this->_props[$prop] = $val;
  }
}
$foo = new Setter();
$foo->MyProperty1 = 1;
$foo->MyProperty2 = 2;
</pre>
Такое поведение похоже на поведение объекта в Ruby, где каждый класс является объектом с изменяемой
в процессе работы сигнатурой.<br/>
- <b>${generate_id()}</b>. Под этим я имею ввиду возможность обращаться к переменным или методам с
именем, которое является просто строкой. Мне необязательно писать $var. Я могу написать ${'var'} или
${gen_var()}. Это всё будет обращением к переменной $var. На мой взгляд очень полезная возможность.<br/>
<br/>
<b>Conclusion</b>
Я перечислил далеко не всё, что, по-моему, легко реализуется в PHP, а в C# почти нереализуемо. Но и 
этих примеров должно хватить, чтобы понять что PHP всё-таки динамический и гибкий язык и не стоит к 
нему обращаться как к вчерашнему дню. У PHP свои преимущества и недостатки. Он не хуже Ruby или C#, 
но и не лучше их. Каждый язык предназначен для своей цели. PHP изначально затачивался под WEB. И он
является в настоящее время лидирующей технологией, которая используется в Интернете. Не думаю, что это
простая случайность и недоразумение. Да, в нём нет той изящности которая есть в Ruby. Да, его не сравнить
по мощи с .NET или Java. Он делает лишь то, для чего создан. Чтобы найти на нём хороший фреймворк для
веб-разработки, долго искать не нужно - их сотни, что так же касается и CMS. Примеров крупномасштабных
проектов так же предостаточно. Это заблуждение, что PHP не для них, а только для персональных сайтов.
Так было когда-то давно, когда он только появился. Сейчас же всё совсем по-другому. <br/>
Так что всё зависит от подхода и от программиста. То, что PHP расслабляет при программировании 
вовсе не значит, что нужно отказаться от написания хорошего кода и писать adhock. Нужно 
просто выбрать тот framework, который не позволяет этого делать. Вот Ruby On Rails во многом 
ограничивает. И это правильный подход, поскольку свобода всегда оставляет возможность для написания 
хардкода. А такой возможности программиста нужно безжалостно лишать.    
*/