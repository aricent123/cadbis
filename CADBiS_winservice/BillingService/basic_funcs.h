//---------------------------------------------------------------------------
//basic_funcs.h file
//	 Author: Sadykov I gr. 823 (aka SM)
//	Basic functions and datastructs. 
//---------------------------------------------------------------------------


#ifndef Basic_functionsH
#define Basic_functionsH


#include <map>
#include <vector>
#include <stack>
#include <string>
#include <algorithm>
#include <string>
#include <math.h>

/////////////////////////////////////////////////////////////////////////////////
// NAMESPACE EXTENDED MATH FUNCTIONS
/////////////////////////////////////////////////////////////////////////////////
namespace math_ext
{
//Возведение в степень с предпроверкой аргументов
double powd(double arg1, double arg2);
};
/////////////////////////////////////////////////////////////////////////////////


/////////////////////////////////////////////////////////////////////////////////
// NAMESPACE EXTENDED STD CONTAINERS
/////////////////////////////////////////////////////////////////////////////////
namespace std_ext
 {
 template <class T>
 class stack
 {
  std::stack<T> m_content;
  T m_default_res;
 public:
  size_t size(){return m_content.size();}
  void push(const T &val){m_content.push(val);}
  T top(){if(m_content.size()>0)return m_content.top(); return '\0';}
  T pop(){if(m_content.size()){m_default_res=m_content.top();m_content.pop();return m_default_res;}return '\0';}
 };
};
/////////////////////////////////////////////////////////////////////////////////


/////////////////////////////////////////////////////////////////////////////////
// NAMESPACE EXTENDED CONVERSIONS
/////////////////////////////////////////////////////////////////////////////////
namespace conv_ext
{
 // преобразование целого к строке
 std::string itos(int Value);
 // преобразование вещественного к строке
 std::string ftos(float Value,size_t Count=3);
 // преобразование строки к целому
 int stoi(const std::string & str);
 // преобразование строки к вещественному
 double stof(const std::string & str);
 //преоброзование строки к  беззнаковому 16 числу
 size_t stoh_u(const std::string & str);
 //преоброзование строки к  беззнаковому 8 числу
 size_t stoo_u(const std::string & str);
 //преоброзование строки к  беззнаковому 8 или 10 или 16 числу
 size_t stou(const std::string & str);

	//преоброзование 16 символа с строку
 size_t ctoh(char c);
 //проверка является ли символ 16 числом
 int isdigithex(int c);
 //проверка является ли символ 8 числом
 int isdigitoct(int c);


};
/////////////////////////////////////////////////////////////////////////////////

#endif
