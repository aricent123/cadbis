//============================================================================
// Name        : Content_view.h
// Author      : Sakoltsev PV
// Version     :
// Copyright   : Your copyright notice
// Description : �������� ����� Content_view ������� ��� ���������� ��� ����������� ��������
//============================================================================

#ifndef CONTENT_VIEW_H_
#define CONTENT_VIEW_H_
#include <map>
#include <vector>
#include <iostream>
#include <string>
#include "AnalyzeFinderAbstract.h"

using namespace std;

struct key_word{
	string word;
	int weight_word;
	int count_text;
};
typedef vector <key_word> Word_Array;


typedef map <string,Word_Array> Content_Array;
typedef int number_metod;
typedef int number_of_word;
typedef int position;
typedef int count_of_words;


class Content_view 
{ 
	Content_Array content_array; // список полученных рубрик
	string result_text_cont; // список присвоенных рубрик
	string openText; // открываемый текст
	unsigned int ContTextFindProperties; // необходимое количество слов для определения контентов на лету
	
public:
	AnalyzeFinderAbstract *finder;
	
	int operator () (char *fword,char *opentext)
	{
		return finder->operator () (fword,opentext);
	}
	
	Content_view();
	Content_view(AnalyzeFinderAbstract *exmp);
	Content_view(AnalyzeFinderAbstract *exmp,unsigned int ContProperties);
	virtual ~Content_view();
	
	// interface
	void SetText(char *FileName);
	void SetContent();
	void PrintResult();
	void PrintText();
	void Test();
	
	// metod filter start
	bool Begin_TextForFly();
	string TextForFly(Content_Array conttab, const string &text,unsigned int ContTextFindProperties);
	
	bool Begin_Analize_Text();
	string Analize_Text(Content_Array conttab,const string &text); // content filter
	// metod filter end
	
	// add new finder
	void SetFinder(AnalyzeFinderAbstract *exmp);
	void TextCleane(string &text);
	// function create new republic

};

#endif /*Content_view_H_*/
