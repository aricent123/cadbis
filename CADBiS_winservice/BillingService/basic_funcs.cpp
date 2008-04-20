//---------------------------------------------------------------------------
//basic_funcs.cpp file
//	 Author: Sadykov I gr. 823 (aka SM)
//               Barabanschikov G gr. 824
//	Basic functions and datastructs. 
//---------------------------------------------------------------------------

#include "stdafx.h"
#include "basic_funcs.h"
#include <tchar.h>
#include <stdlib.h>
#include <math.h>

/////////////////////////////////////////////////////////////////////////////////
// NAMESPACE EXTENDED MATH FUNCTIONS
/////////////////////////////////////////////////////////////////////////////////
namespace math_ext
{
	//Возведение в степень с предпроверкой аргументов
	double powd(double arg1, double arg2)
	{
		if(arg1 && !arg2)return 1;
		if(arg1 && arg2)return pow(arg1,arg2);
		return 0;
	}
};
/////////////////////////////////////////////////////////////////////////////////



/////////////////////////////////////////////////////////////////////////////////
// NAMESPACE EXT_CONV
/////////////////////////////////////////////////////////////////////////////////
namespace conv_ext
{
 //---------------------------------------------------------------------------
 //Название функции: Преобразовать целое к строке
 //Назначение функции: Преобразует целое число в строку
 //---------------------------------------------------------------------------
 std::string itos(int value)
 {
  char tmp[255];
  itoa(value,tmp,10);
  std::string str=tmp;
  return str;
 }
 //---------------------------------------------------------------------------
 //---------------------------------------------------------------------------
 //Название функции: Преобразовать вещественное к строке
 //Назначение функции: Преобразует вещественное число к строке.
 //---------------------------------------------------------------------------
 std::string ftos(float value,size_t cnt)
 {
  std::string res;
  std::string bp,ap,zeros="";
  double xzc=0.0;
  xzc=(float)(fabs(value)-fabs((float)((int)value)));
  do{zeros+="0";xzc*=10.0f;}while(xzc<1.0f && xzc!=0.0);
  xzc*=math_ext::powd(10.0f,cnt-1);
	bp=itos(static_cast<int>(value));
  ap=itos(static_cast<int>(xzc)).substr(0,cnt);
  zeros.erase(zeros.begin());
  res=bp+"."+zeros+ap;
  return res;
 }
 //---------------------------------------------------------------------------
 //---------------------------------------------------------------------------
 //Название функции: Преобразовать строку к целому
 //Назначение функции: Преобразует строку к целому
 //---------------------------------------------------------------------------
 int stoi(const std::string & str)
 {
  return atoi(str.c_str());
 }
 //---------------------------------------------------------------------------
 //---------------------------------------------------------------------------
 //Название функции: Преобразовать строку к вещественному
 //Назначение функции: Строка должна быть в формате x.y, где x- целая часть,
 //y - дробная.
 //---------------------------------------------------------------------------
  double stof(const std::string & str)
   {
   std::string left,right;
   size_t i(0);
   if(!(str.find('.')<str.length()))return stoi(str)*1.0;
   while(str[i++]!='.')left+=str[i-1];
   while(++i<=str.length())right+=str[i-1];
   return (float)stoi(left)+((float)stoi(right))/pow(10.0f,(int)right.length());
  }

  //---------------------------------------------------------------------------

  size_t stoh_u(const std::string & text)
  {
	size_t res=0;
	size_t k=1;
	for(int it(static_cast<int>(text.length()-1));it>=0;--it)
	  {
		res+=ctoh(text[it])*k;
		k*=16;
	  }
	  
	  
  return res;
  
  }
	//---------------------------------------------------------------------------
	
	size_t stoo_u(const std::string & text)
	{
		size_t res=0;
		size_t k=1;
		for(int it(static_cast<int>(text.length()-1));it>=0;--it)
		{
			res+=ctoh(text[it])*k;
			k*=8;
		}


		return res;;
	}
	//---------------------------------------------------------------------------

	size_t stou(const std::string & text)
	{
		bool sign(true);
		size_t cursym(0);
		//если число отрицательное
		if (text.empty()) return 0;

			if(text[cursym]=='-')
			{
				sign=false;
				++cursym;
				if (text.length()==1) return 0;
			}
			
			//если это 8 или 16 СИ
			if(text[cursym]=='0')
			{
				//если это 16 СИ
				if(text[++cursym]=='x')
				{
					//тут текущий символ x
					//те если за текущим символом сто нибудь есть то дальше
					if (!(text.length()-cursym+1)) return 0;
					

						std::string temp;
						//начинаем с символа за x дтпп идут 16 символы
						for (size_t i(++cursym);i<text.length();++i)
							if (isdigithex(text[i])) temp.push_back(text[i]);else break;
						return sign?stoh_u(temp):-stoh_u(temp);
				}
				//значит это 8 СИ
				else
				{					
					if (!(text.length()-cursym+1)) return 0;
					std::string temp;
					//начинаем с символа за 0 дтпп идут 8 символы
					for (size_t i(++cursym);i<text.length();++i)
						if (isdigitoct(text[i])) temp.push_back(text[i]);else break;
					return sign?stoo_u(temp):-stoo_u(temp);
				
				}
			
			
					

				
			}
			//а дальше проверяем на 10 СИ
			else return stoi(text);
}


size_t ctoh(char c)
{  switch(c)
{
		  case 'a':case 'A':return 10;
		  case 'b':case 'B':return 11;
		  case 'c':case 'C':return 12;
		  case 'd':case 'D':return 13;
		  case 'e':case 'E':return 14;
		  case 'f':case 'F':return 15;
		  default: return atoi((std::string("")+c).c_str()); 
}
}


int isdigithex(int c)
{
	return isdigit(c)||((c>='a')&&(c<='f'))||((c>='A')&&(c<='F'));
}

int isdigitoct(int c)
{
	return (c>='0')&&(c<='7');
}



};
/////////////////////////////////////////////////////////////////////////////////


