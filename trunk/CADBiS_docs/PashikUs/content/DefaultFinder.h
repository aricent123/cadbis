//============================================================================
// Name        : DefaultFinder.h
// Author      : Sakoltsev PV
// Version     :
// Copyright   : Your copyright notice
// Description : дополнительный модуль с классом содержащим метод поиска подстроки в строке
//============================================================================
#ifndef DEFAULTFINDER_H_
#define DEFAULTFINDER_H_
#include <string>
#include <iostream>
using namespace std;

#include "AnalyzeFinderAbstract.h"

class DefaultFinder : public AnalyzeFinderAbstract
{ 
public:
	virtual int operator () (const char *fword,const char *opentext);

	
};

#endif /*DEFAULTFINDER_H_*/
