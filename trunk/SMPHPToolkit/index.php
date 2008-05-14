<?php
// AJAX корректно работает только с UTF
header("Content-Type: text/html;charset=UTF-8");

// Подключаем необходимые файлы
require_once(dirname(__FILE__)."/SMPHPToolkit/SMAjax.php");
require_once(dirname(__FILE__)."/SMPHPToolkit/templates/templater.inc.php");

// Данные для отображения 
// Допустим есть класс уровня DAO (работа с данными)
class mydata{
	private static $mydata = array(
					array(0,'Название 1'),
					array(1,'Название 2'),
					array(2,'Название 3'),
					array(3,'Название 4'),
					array(4,'Название 5'),
					array(5,'Название 6'),
					array(6,'Название 7'),
					array(7,'Название 8'),
					array(8,'Название 9'),
					);
	// функция получения общего числа записей 
	public static function get_total(){return count(self::$mydata);}
	// функция получения определённой страницы отсортированных данных
	// cортировка указывается третьим параметром (true=ASC,false=DESC)
	public static function get_page($pagesize, $page, $asc = true){
		return array_slice(
				($asc)?self::$mydata:array_reverse(self::$mydata), 
				($page-1)*$pagesize, $pagesize);
	}
	// так же допустим есть функция удаления строки
	public static function delete($num){unset(self::$mydata[$num]);}
}


// Инициализируем буфер и менеджер сущностей
$ajaxbuffer = new ajax_buffer("ajax_buffer");
$ajaxbuffer->show_progress(true);
$emanager = new ajax_entities_manager('entities_manager', $ajaxbuffer);

// Если нужно выполнить какие-то действия над данными по постбэку
if($ajaxbuffer->is_post_back() &&  $emanager->isAnyAction())
{
	switch($emanager->getAction())
	{
		// необходимо удалить строку
		case $emanager->action->DEL:
			mydata::delete($emanager->getItem());
		break;
	}
	// сбрасываем действие (в противном случае оно будет активно при каждом ajax запросе)
	$emanager->eraseAction();
}

// Форматтер для отображения данных грида
class my_grid_formatter extends grid_formatter {
	protected $_field = '';
	protected $_client_id = '';
	public function __construct($field, $client_id){
		$this->_field = $field;
		$this->_client_id = $client_id;
	}
	public function format($data, $type, $number = 0, $columns = null)
	{
		switch($this->_field){
			case 'actions':
				return '<a href="javascript:'.$this->_client_id.'.deleteItem('.$data[0].');">Удалить</a>';
			default:
				return parent::format($data,$type);	
		}
	}
};

// Создаём DataSource
$datasource = new grid_data_source(new grid_header_item_array(
					new grid_header_item('id','Id',type::STRING, true),
					new grid_header_item('title','Заголовок',type::STRING, true),
					new grid_header_item('actions','Действия',null, false, new my_grid_formatter('actions', $emanager->client_id()))
				));

// Создаём новый грид и пейджер к нему
$grid_pager = new ajax_grid_pager('my_grid_pager',mydata::get_total(),5);
$grid = new ajax_grid('my_grid',$datasource,$ajaxbuffer,$grid_pager);

// выбираем текущую страницу отсортированных данных
$mydata = mydata::get_page($grid_pager->get_pagesize(),$grid_pager->get_curpage(), $grid->get_sort_direction() != sorting::SORT_DIR_DESC);

// добавляем данные в DataSource
foreach($mydata as $data)
{
	$datasource->add_row(array(
			$data[0],
			$data[1],
			$data,
			));	
}

// чтобы было видно прогресс задержим рендеринг постбэка на секунду
if($ajaxbuffer->is_post_back())
	sleep(1);

// Выводим результат
$templater  = new templater(dirname(__FILE__).'/templates/main.tpl.php');
die($templater->render(array('grid'=>$grid,
							'ajaxbuffer'=>$ajaxbuffer,
							'title'=>'Тестовая страница',
							)));