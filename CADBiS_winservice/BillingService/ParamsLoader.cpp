//---------------------------------------------------------------------------
// ParamsLoader.cpp file
// Programming: SM
// Content: Holds definition of CParamsLoader class
//---------------------------------------------------------------------------
#include "stdafx.h"

#include "ParamsLoader.h"


//---------------------------------------------------------------------------
// ParamsLoader
//---------------------------------------------------------------------------
//---------------------------------------------------------------------------
//Название функции: Удаление символа
//Назначение функции: Удаляет из строки заданный символ
//---------------------------------------------------------------------------
void CParamsLoader::m_del_char(str_t &text, const char chr)
 {
 for(str_t::iterator it(text.begin());it!=text.end();it=std::find(text.begin(),text.end(),chr))
  if(it>text.begin())text.erase(it); 
 }
//---------------------------------------------------------------------------
//Название функции: Разбивание строки
//Назначение функции: Разбивает строку по подстроке на массив строк
//---------------------------------------------------------------------------
void CParamsLoader::m_strsplit(const str_t & text,const str_t & separators, array_str_t & words)
 {
 int n = text.length();
 int start, stop;
 start = text.find_first_not_of(separators);
 while ((start >= 0) && (start < n)) {
   stop = text.find_first_of(separators, start);
   if ((stop < 0) || (stop > n)) stop = n;
   words.push_back(text.substr(start, stop - start));
   start = text.find_first_not_of(separators, stop+1);
   }
 }
//---------------------------------------------------------------------------
//---------------------------------------------------------------------------
//Название функции: Чтение файла
//Назначение функции: Читает файл в массив строк
//---------------------------------------------------------------------------
void CParamsLoader::m_readfile(const str_t &file, array_str_t & strings)
 {
 char tmpstr[MAX_STR_LEN];
 strings.clear();
 std::ifstream fp_in(file.c_str());
 if(!fp_in)return;
 while(fp_in.getline(tmpstr,MAX_STR_LEN))
   if(tmpstr[0]!=CHAR_COMMENT && tmpstr!="")
     strings.push_back(tmpstr);
 }
//---------------------------------------------------------------------------
//---------------------------------------------------------------------------
//Название функции: Удаление пробелов
//Назначение функции: Удаляет пробелы в строке
//---------------------------------------------------------------------------
void CParamsLoader::m_kill_spaces(array_str_t & strings)
 {
  for(size_t i(0);i<strings.size();++i)
    m_del_char(strings[i],' ');
 }
//---------------------------------------------------------------------------
//---------------------------------------------------------------------------
//Название функции: Получить группы
//Назначение функции: Получает из массива строк список групп
//---------------------------------------------------------------------------
void CParamsLoader::m_get_groups(const array_str_t & strings,
                                                array_pair_str_uint_t & groups)
 {
 groups.clear();
 for(size_t i(0);i<strings.size();++i)
   if(strings[i][0]==CHAR_GROUP_DCLBEGIN)
    groups.push_back(pair_str_uint_t(strings[i],i));
 }
//---------------------------------------------------------------------------
//---------------------------------------------------------------------------
//Название функции:  Содержится ли подстрока в строке
//Назначение функции: Возвращает true, если в строке содержится подстрока
//--------------------------------------------------------------------------- 
bool CParamsLoader::m_strstr(const str_t &string, const str_t &substr)
 {
 return string.find(substr)<string.length()-1;
 }
//---------------------------------------------------------------------------
//---------------------------------------------------------------------------
//Название функции: Получить параметры группы
//Назначение функции: Получает строки, относящиеся к конкретной группе
//---------------------------------------------------------------------------
void CParamsLoader::m_get_group_params(const pair_str_uint_t &group,
                                const array_str_t &strings, array_str_t &params)
 {
 params.clear();
 size_t i(group.second+1);
 while(i<strings.size() && strings[i][0]!=CHAR_GROUP_DCLBEGIN)
    {params.push_back(strings[i]);++i;}
 }
//---------------------------------------------------------------------------
//---------------------------------------------------------------------------
//Название функции: Получить значения параметров
//Назначение функции: Добавляет в массив параметры (параметр=значение)
//---------------------------------------------------------------------------
void CParamsLoader::m_get_params_values(const array_str_t &strings,
                                                     map_str_str_t &parameters)
 {
 for(size_t i(0);i<strings.size();++i)
   {
   array_str_t tmpparts;
   str_t tmpstr=strings[i];
   if(tmpstr=="")continue;
   m_parse_param_string(tmpstr);
   m_del_char(tmpstr,'\t');
   m_strsplit(tmpstr,STR_EQUAL,tmpparts);
   if(tmpparts[0]=="")continue;
   if(m_strstr(tmpparts[1],STR_SEPARATOR))
     {
      str_t tmpres("");
      array_str_t tmpparts2;
      m_del_char(tmpparts[1],' ');
      m_strsplit(tmpparts[1],STR_SEPARATOR,tmpparts2);
      for(size_t j(0);j<tmpparts2.size();++j)
       if(parameters.find(tmpparts2[j])!=parameters.end())
         tmpres+=parameters[tmpparts2[j]];
         else
         tmpres+=tmpparts2[j];
      tmpparts[1]=tmpres;
     }
   if(parameters.find(tmpparts[1])!=parameters.end())
     tmpparts[1]=parameters[tmpparts[1]];
   if(!RETYPE_PARAMS)parameters.insert(map_str_str_t::value_type(tmpparts[0],tmpparts[1]));
   else parameters[tmpparts[0]]=tmpparts[1];
   }
 }
//---------------------------------------------------------------------------
//---------------------------------------------------------------------------
//Название функции:  Очистить массивы
//Назначение функции: Очищает массивы
//---------------------------------------------------------------------------
void CParamsLoader::m_clear()
 {
 if(mm_params.size())
  {
  for(size_t i(0);i<mv_keys.size();++i)
   {
   mm_params[mv_keys[i]].clear();
   }
  mm_params.clear();
  mv_keys.clear();
  }
 }
//---------------------------------------------------------------------------
//---------------------------------------------------------------------------
//Название функции: Парсировать строку с парметром
//Назначение функции: Игнорирует комментарии
//---------------------------------------------------------------------------
void CParamsLoader::m_parse_param_string(str_t &string)
 {
 for(str_t::iterator it(string.begin());it!=string.end();++it)
  if(*it==CHAR_COMMENT){string.erase(it,string.end());return;}
 }
//---------------------------------------------------------------------------
//---------------------------------------------------------------------------
//Название функции: Парсировать имя группы
//Назначение функции: Выдирает из строки вида [group] слово group 
//---------------------------------------------------------------------------
void CParamsLoader::m_parse_group_string(str_t &string)
 {
 str_t::iterator it(string.begin());
 while(*it!=CHAR_GROUP_DCLBEGIN)++it;
 string.erase(string.begin(),++it);
 for(str_t::iterator it(string.begin());it!=string.end();++it)
  if(*it==CHAR_GROUP_DCLEND || *it==CHAR_COMMENT)
    {string.erase(it,string.end());return;}
 }
//---------------------------------------------------------------------------
//---------------------------------------------------------------------------
//Название функции:  Читать параметры
//Назначение функции: Читает файл и все его параметры
//---------------------------------------------------------------------------
bool CParamsLoader::m_read_parameters(const str_t &file)
 {
 std::ifstream tmpifp(file.c_str());
 if(!tmpifp){tmpifp.close();return false;}
 tmpifp.close();
  m_clear();
  array_str_t strings;
  array_pair_str_uint_t groups;
  m_readfile(file,strings);
  m_get_groups(strings,groups);
   for(size_t i(0);i<groups.size();++i)
    {
     array_str_t gtmpparams;
     m_get_group_params(groups[i],strings,gtmpparams);
     str_t gr_id(groups[i].first);
     ms_curgroup=gr_id;
     /*m_del_char(gr_id,CHAR_GROUP_DCLBEGIN);
     m_del_char(gr_id,CHAR_GROUP_DCLEND);*/
     m_parse_group_string(gr_id);
     m_get_params_values(gtmpparams,mm_params[gr_id]);
     mv_keys.push_back(groups[i].first);
    }
 return true;
 }
//---------------------------------------------------------------------------
//---------------------------------------------------------------------------
//Название функции: Вернуть значение
//Назначение функции: Возвращает значение параметра группы
//---------------------------------------------------------------------------
str_t CParamsLoader::operator()(const str_t &group, const str_t &param)
  {
  if(!m_group_exists(group))return "";
  if(mm_params[group].find(param)==mm_params[group].end())return "";
  return mm_params[group][param];
  }
//---------------------------------------------------------------------------
//---------------------------------------------------------------------------
//Название функции: Существует ли группа
//Назначение функции: Возвращает true, если группа определена
//---------------------------------------------------------------------------
bool CParamsLoader::m_group_exists(const str_t &group)const
  {
  return !(mm_params.find(group)==mm_params.end());
  }
//---------------------------------------------------------------------------
//---------------------------------------------------------------------------
//Название функции: Установить текущую группу
//Назначение функции: Делает группу текущей 
//---------------------------------------------------------------------------
void CParamsLoader::SetCurrentGroup(const str_t &group)
  {
  if(!m_group_exists(group))return;
  ms_curgroup=group;
  }
//---------------------------------------------------------------------------
//---------------------------------------------------------------------------
//Название функции:  Вернуть значение параметра
//Назначение функции: Возвращает значение параметра группы
//---------------------------------------------------------------------------
str_t CParamsLoader::operator [](const str_t &param)
  {
  if(!m_group_exists(ms_curgroup))return "";
  if(mm_params[ms_curgroup].find(param)==mm_params[ms_curgroup].end())return "";
  return mm_params[ms_curgroup][param];
  }
//---------------------------------------------------------------------------
//---------------------------------------------------------------------------
//Название функции:  Загрузить параметры
//Назначение функции: Вызывает загрузку
//---------------------------------------------------------------------------
bool CParamsLoader::LoadParameters(const str_t &FileName)
  {
  return m_read_parameters(FileName);
  }
//---------------------------------------------------------------------------


