//============================================================================
// Name        : DefaultFinder.cpp
// Author      : Sakoltsev PV
// Version     :
// Copyright   : Your copyright notice
// Description : �������� ������� ������ DefaultFinder
//============================================================================
#include <string>
#include <iostream>

#include "DefaultFinder.h"

using namespace std;

int DefaultFinder::operator ()(const char *fword,const char *opentext)
{
	int k=0; 
	const char *temp,*temp1;
	temp=strstr(opentext,fword);
	
	if(temp){
		temp1=&temp[strlen(fword)];
		while(temp1!=NULL){
			k++;
			temp1=NULL;
			temp1=strstr(temp,fword);
			if(temp1)temp=&temp1[strlen(fword)];
		}
		k--;
	}
	return k;
}