//---------------------------------------------------------------------------
// ParamsLoader.h file
// Programming: SM
// Content: Holds declaration of CParamsLoader class
//---------------------------------------------------------------------------
#ifndef ParamsLoaderH
#define ParamsLoaderH


#include <map>
#include <algorithm>
#include <vector>
#include <fstream>
#include <string>



//NEEDED TYPEDEFS....
typedef std::string str_t;
typedef std::vector<std::string> array_str_t;
typedef std::map<std::string,std::string> map_str_str_t;
typedef std::pair<std::string,std::string> pair_str_str_t;
typedef std::map<std::string,pair_str_str_t> map_str_pair_str_str_t;
typedef std::map<std::string,std::map<std::string, std::string> > map_str_map_str_str_t;
typedef std::pair<std::string,size_t> pair_str_uint_t;
typedef std::vector<pair_str_uint_t> array_pair_str_uint_t;


const size_t MAX_STR_LEN=255;			//максимальная длина строки
const char CHAR_GROUP_DCLBEGIN= '[';	//начало объявления группы
const char CHAR_GROUP_DCLEND=   ']';	//конец объявления группы
const char CHAR_COMMENT=        '#';	//комментарий
const str_t STR_EQUAL=          "=";	//строка, означающая равенство
const str_t STR_SEPARATOR=      "<+>";	//строка, означающая разделитель (при конкатенации)
const bool RETYPE_PARAMS=       true;	//разрешить переопределение параметров?


//---------------------------------------------------------------------------
//Название: Parameters Loader
//Назначение: Класс загрузки параметров (из ini файла)
//---------------------------------------------------------------------------
class CParamsLoader
{
 //-------------------------members-------------------------//
 map_str_map_str_str_t	mm_params;							//параметры (ассоциативный массив)
 array_str_t			mv_keys;							//ключи (список)
 str_t					ms_curgroup;						//текущая группа
 //-------------------------methods-------------------------//
 void m_del_char(str_t &text, const char chr=' ');			//удаление символа из строки
 void m_strsplit(const str_t & text,						
				const str_t & separators,					
				array_str_t & words);						//разбиение строки по подстроке
 void m_readfile(const str_t &file, array_str_t & strings);	//чтение файла в массив строк
 void m_kill_spaces(array_str_t & strings);					//удаление пробелов
 void m_get_groups(const array_str_t & strings,				 
				array_pair_str_uint_t & groups);			//получение списка групп
 void m_get_params_values(const array_str_t &strings,		
 						 map_str_str_t &parameters);		//получение значений параметров
 bool m_strstr(const str_t &string,const str_t &substr);	//содержится ли подстрока в строке
 void m_parse_param_string(str_t &string);					//обработка строки с параметром
 void m_parse_group_string(str_t &string);					//обработка строки с группой
 void m_clear();											//очистка содержимого
 bool m_read_parameters(const str_t &file);					//чтение параметров файла
 void m_get_group_params(const pair_str_uint_t &group,		
						const array_str_t &strings,			
						array_str_t &params);				//получить список параметров группы
 bool m_group_exists(const str_t &group)const;				//существует ли группа?
public:
 
 //ctors:
 CParamsLoader(){}
 CParamsLoader(const str_t &FileName){m_read_parameters(FileName);}

 //MODIFIERS:
 bool LoadParameters(const str_t &FileName);				//загрузить параметры
 void SetCurrentGroup(const str_t &group);					//установить текущую группу

 //ACCESSORS:
 str_t operator()(const str_t &group,						\
					const str_t &param);					//получение параметра из группы
 str_t operator [](const str_t &param);						//получение параметра из текущей группы 
};
//---------------------------------------------------------------------------

#endif
 