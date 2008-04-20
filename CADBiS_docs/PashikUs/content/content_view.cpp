//============================================================================
// Name        : Content_view.cpp
// Author      : Sakoltsev PV
// Version     :
// Copyright   : Your copyright notice
// Description : �������� ������� ������ Content_view
//============================================================================

#include "content_view.h"
#include <map>
#include <vector>
#include <iostream>
#include <string>
#include <math.h>
#include <stdio.h>
#include <fstream>

using namespace std;

Content_view::Content_view()
{
	
}

///////////////////////////////////////////////////////////////////

Content_view::Content_view(AnalyzeFinderAbstract *exmp)
{
	delete finder;
	finder=exmp;
	ContTextFindProperties=2;
}

Content_view::Content_view(AnalyzeFinderAbstract *exmp,unsigned int ContProperties)
{
	delete finder;
	finder=exmp;
	ContTextFindProperties=ContProperties;
	
}

/////////////////////////////////////////////////////////////////////

Content_view::~Content_view()
{
}


////////////////////////////////////////////////////////////////////


/*void Content_view::LoadContentTable(char *FileName)
{
	
}


////////////////////////////////////////////////////////////////////
 
 
 
void Content_view::LoadTextFile(char *FileName)
{
	
}

//////////////////////////////////////////////////////////////////////


void Content_view::setCountWord(int countword)
{
	
}*/

///////////////////////////////////////////////////////////////////////


void Content_view::SetContent()
{ 

}

/////////////////////////////////////////////////////////////////////////////////////////////////


void Content_view::SetText(char *FileName)
{
	ifstream in(FileName);
	while (!in)
	{
		string temp;
		in >>temp;
		openText=openText+temp;
	}

}

///////////////////////////////////////////////////////////////////////////////////////////////////


////////////////////////////////////////////////////////////////////////////////////////////////////


string Content_view::TextForFly(Content_Array conttab, const string &text,unsigned int ContTextFindProperties)  // ������� ������������� ��������� �����
{
	int temp2=0;
	string result;
	
	for(Content_Array::iterator it(conttab.begin()) ; it!=conttab.end();++it)
	{
		temp2=0;
		for(Word_Array::iterator it1(it->second.begin()); it1!=it->second.end();++it1)
		{
			
			int d;
			d=finder->operator () (it1->word.c_str(), text.c_str()); // солько слов из списка ключевых присутствует в тексте		
			if(d>0)temp2++;
		
		};
		// если количество слов превышает заданное значение то присваиваем
		if(temp2>ContTextFindProperties) 
			{
				result=result+' '+it->first;	
				
			};
	};
	
	return result;
}


////////////////////////////////////////////////////////////////////////////////////////////////////


string Content_view::Analize_Text(Content_Array conttab,const string &text) // ������� ������������� ������� �����
{
	string result;
	
	for(Content_Array::iterator it(conttab.begin()) ; it!=conttab.end();++it)
	{
		for(Word_Array::iterator it1(it->second.begin()); it1!=it->second.end();++it1)
		{
			// проставляем какое количество раз встречаются ключевые слова в тексте
			it1->count_text=finder->operator () (it1->word.c_str(), text.c_str()); 
		}

	};
	float weight_content, sum_temp,weight_content_procent ;

	for(Content_Array::iterator it(conttab.begin()) ; it!=conttab.end();++it)
	{
		sum_temp=0;
		weight_content_procent=0;
		for(Word_Array::iterator it1(it->second.begin()); it1!=it->second.end();++it1)
		{
			// считаем общий вес сумм(вес кл. слова*на количество присутствия его в тексте)
			sum_temp=sum_temp+it1->weight_word*it1->count_text;
			weight_content_procent++;
		}
		float len_text;
		len_text=(float)text.length();
		
		//вычисляем средний вес относительно длинны текста
		weight_content=floorf((sum_temp/len_text)*100);
		
		//проверяем если этот средний вес выше чем удвоенное количество слов то этот контент подходит
		if(weight_content>(weight_content_procent*weight_content_procent))result=result+' '+it->first;
		// для доработки (weight_content/(weight_content_procent*weight_content_procent))- это и есть оценка соответствия
		// у кого оценка выше тот и больше подходит
	};

	
	return result;
}


/////////////////////////////////////////////////////////////////////////////////////////////////


void Content_view::SetFinder(AnalyzeFinderAbstract *exmp) // ustanovit noviy poiskovik
{
	delete finder;
	finder=exmp;	
}


////////////////////////////////////////////////////////////////////////////////////////////////


void Content_view::PrintResult() //pechataet rezultati
{
	cout<<"\n<"<<result_text_cont;
}

/////////////////////////////////////////////////////////////////////////////////////////////////


void Content_view::PrintText() // pechataet tekst
{
	cout<<"\nText: "<<openText;
}

///////////////////////////////////////////////////////////////////////////////////////////////////


void Content_view::Test()
{

}

////////////////////////////////////////////////////////////////////////////////////////////////////

void TextCleane(string &text)
{
	int i=0;
	while(i<text.length())
	{
		if(text[i]<'а')
		i++;
	}		
}

bool Content_view::Begin_TextForFly()
{	
	result_text_cont=TextForFly(content_array,openText,ContTextFindProperties);
	if(result_text_cont!=NULL){return true;}else{return false;} 
}

bool Content_view::Begin_Analize_Text()
{	
	result_text_cont=Analize_Text(content_array,openTexts);
	if(result_text_cont!=NULL){return true;}else{return false;} 
}

/////////////////////////////////////////////////////////////////////////////////////////////////////



