//============================================================================
// Name        : main.cpp
// Author      : Sakoltsev PV
// Version     :
// Copyright   : Your copyright notice
// Description : шаблон абстрактный класс
//============================================================================
#ifndef ANALYZEFINDERABSTRACT_H_
#define ANALYZEFINDERABSTRACT_H_
#include <string>
#include <iostream>
using namespace std;

class AnalyzeFinderAbstract
{
public:
	virtual int operator () (const char *fword,const char *opentext)=0;
	
};

#endif /*ANALYZEFINDERABSTRACT_H_*/
