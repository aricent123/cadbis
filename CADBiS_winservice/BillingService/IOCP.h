/******************************************************************************
Module:  IOCP.h
Notices: Copyright (c) 2000 Jeffrey Richter
Purpose: This class wraps an I/O Completion Port.
         See Appendix B.
		 Класс обертка над портами ввода вывода
******************************************************************************/


#pragma once   // Include this header file once per compilation unit


///////////////////////////////////////////////////////////////////////////////


//#include "..\CmnHdr.h"// See Appendix A.
#include <WinSock.h>
#define chVERIFY(x) (x)
#define chASSERT(x)

///////////////////////////////////////////////////////////////////////////////

//класс обертка над портом ввода/вывода
class CIOCP {
public:
	//конструктор
	//если без аргумента то конструктор ниччего не делает
	//иначе иначе вызываем Create() c этим аргументом
   CIOCP(int nMaxConcurrency = -1) { 
      m_hIOCP = NULL; 
      if (nMaxConcurrency != -1)
         (void) Create(nMaxConcurrency);
   }

   //деструктор
   //если хендлер не нулевой то закрываем его
   ~CIOCP() { 
      if (m_hIOCP != NULL) 
         chVERIFY(CloseHandle(m_hIOCP)); 
   }

   //открываем порт
   BOOL Create(int nMaxConcurrency = 0) {
      //связываем(открываем порт) с указателем на файл 
	   m_hIOCP = CreateIoCompletionPort(
		//хендлер файла, тк у нас он INVALID_HANDLE_VALUE, то IOCP создается но не связывается с файлом
         INVALID_HANDLE_VALUE, 
		 //создаем новый порт завершения 
		 NULL,
		 //символ означающий конец пакета в файле
		 0,
		 //количество потоков(нитей-threads) которые могут одновременно работать с этим портом
		 //если 0 то кол-во нитей = колву процессоров на ПК
		 nMaxConcurrency);

	   //??
      chASSERT(m_hIOCP != NULL);
	  //возвращаем удачность выполнения функции CreateIoCompletionPort() 
	  //если она вернула NULL то плохо, иначе хорошо
      return(m_hIOCP != NULL);
   }

//связываем созданный порт с устройством/файлом
//hDevice - хендлер файла
//CompKey - символ завершающий пакет который будет передаваться
   BOOL AssociateDevice(HANDLE hDevice, ULONG_PTR CompKey) {
	   //связываем(открываем порт) с указателем на файл
	   //1 аргумент: так как здесь есть хендлер файл то происходит связывание
	   //2 аргумент: хендлер существующего порта => файл будет связан с уже открытым портом
	   //3,4 аргументы - см описание Create()
	   //проверяем на неошибочность создания
	   BOOL fOk = (CreateIoCompletionPort(hDevice,m_hIOCP, CompKey, 0)== m_hIOCP);
      chASSERT(fOk);
	  //возвращаем корректность создания
      return(fOk);
   }

  //связыванием порт и сокета
  // перегрузка функции вариант с сокетом
   BOOL AssociateSocket(SOCKET hSocket, ULONG_PTR CompKey) {
      return(AssociateDevice((HANDLE) hSocket, CompKey));
   }


   //завершение работы порта
   BOOL PostStatus(ULONG_PTR CompKey, DWORD dwNumBytes = 0, 
      OVERLAPPED* po = NULL) {
	//переносит пакет ввода - вывода к порту завершения ввода - вывода
    //m_hIOCP:порт
    //dwNumBytes:указатель на переменную содержащ кол-во байт переданных в течении операции 
    //CompKey:указатель на символ уоторый будет завершающим
    //po:указатель на переменную которая будет содержать адрес структуры коорая будет перекрыта при
	//		завершении операции 	
     //возвращает успешность операции
		  BOOL fOk = PostQueuedCompletionStatus(m_hIOCP, dwNumBytes, CompKey, po);

      chASSERT(fOk); 
	  //возвращаем результат успешности выолнения операции
      return(fOk); 
   }

//получение текущего статуса/состояния порта
   BOOL GetStatus(ULONG_PTR* pCompKey, PDWORD pdwNumBytes,
      OVERLAPPED** ppo, DWORD dwMilliseconds = INFINITE) {
//m_hIOCP:порт
//dwNumBytes:указатель на переменную содержащ кол-во байт переданных в течении операции 
//CompKey:указатель на символ уоторый будет завершающим
//po:указатель на переменную которая будет содержать адрес структуры коорая будет перекрыта при
//		завершении операции
//dwMilliseconds- колво миллисекунд, которая ждем пакета завершения появляющийся в порте завершения. 
//Если dwMilliseconds INFINITE, ждем вечно.
//если промежкьток времени истек,в ppo будет NULL и функция вернет FALSE
      return(GetQueuedCompletionStatus(m_hIOCP, pdwNumBytes, 
         pCompKey, ppo, dwMilliseconds));
   }

//в закрытой части хранится хендлер созданного порта
private:
   HANDLE m_hIOCP;
};


///////////////////////////////// End of File /////////////////////////////////
 