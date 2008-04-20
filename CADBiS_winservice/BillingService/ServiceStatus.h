
#pragma once  
#include "Gate.h" //простой эвентс

//класс хранящий информацию о статусе сервиса и позволяющая упарвлять сервисом
//наследуется от стандартного SERVICE_STATUS содержащего значения многих параметров
class CServiceStatus : public SERVICE_STATUS 
{
public:
   CServiceStatus();
	//режим отладки	
   void SetDebugMode() { m_fDebug = TRUE; }
	//инициализации
   BOOL Initialize(PCTSTR szServiceName, LPHANDLER_FUNCTION_EX pfnHandler, 
      PVOID pvContext, BOOL fOwnProcess, BOOL fInteractWithDesktop = TRUE);

   VOID AcceptControls(DWORD dwFlags, BOOL fAccept = TRUE);
   BOOL ReportStatus();

   BOOL SetUltimateState(DWORD dwUltimateState, DWORD dwWaitHint = 0);
   BOOL AdvanceState(DWORD dwWaitHint, DWORD dwCheckPoint = 0);
   BOOL ReportUltimateState();
   BOOL ReportWin32Error(DWORD dwError);
   BOOL ReportServiceSpecificError(DWORD dwError);

   operator DWORD() const 
   { 
	   return(dwCurrentState); 
   }

private:
   BOOL m_fDebug;
   SERVICE_STATUS_HANDLE m_hss;
	CGate m_gate;
};

inline CServiceStatus::CServiceStatus() 
{
   ZeroMemory(this, sizeof(SERVICE_STATUS));
   m_hss = NULL;
   m_fDebug = FALSE;
}


inline VOID CServiceStatus::AcceptControls(DWORD dwFlags, BOOL fAccept) 
{

   if (fAccept) dwControlsAccepted |= dwFlags;
   else dwControlsAccepted &= ~dwFlags;
}
inline BOOL CServiceStatus::ReportStatus() 
{

   BOOL fOk = m_fDebug ? TRUE : ::SetServiceStatus(m_hss, this);

   return(fOk);
}
 
inline BOOL CServiceStatus::ReportWin32Error(DWORD dwError) 
{
	//отчет об ошибках в WIN32
   dwWin32ExitCode = dwError;
   dwServiceSpecificExitCode = 0;
   return(ReportStatus());
}


//отчет о специфических ршбках
inline BOOL CServiceStatus::ReportServiceSpecificError(DWORD dwError) 
{
   dwWin32ExitCode = ERROR_SERVICE_SPECIFIC_ERROR;
   dwServiceSpecificExitCode = dwError;
   return(ReportStatus());
}
//#ifdef SERVICESTATUS_IMPL
//инициализация параметров сервиса
BOOL CServiceStatus::Initialize(PCTSTR szServiceName,	//имя службы
								LPHANDLER_FUNCTION_EX pfnHandler, //указатель на управляющую функцию
								PVOID pvContext,			//какие-то данные(iocp)
								BOOL fOwnProcess,			//один процесс
								BOOL fInteractWithDesktop)  //возможность работы с раб столом
{

   if (!m_fDebug) 
   {
	   //регистрация функции которая будет обрабатывать запросы сервиса по контролю(урпраления)
	   //szServiceName- имя сервиса
	   //pfnHandler - указатель на хендлер регистрируемой функции
	   //pvContext - ползовательские данные 
      m_hss = RegisterServiceCtrlHandlerEx(szServiceName, pfnHandler, pvContext);
   }
	
	//определяем параметры сервиса

   //тип сервиса
   //служба запускается в собственном процессе или разделяет его с другими службами
   dwServiceType = fOwnProcess 
      ? SERVICE_WIN32_OWN_PROCESS : SERVICE_WIN32_SHARE_PROCESS;

   //возможность службы взамодействовать с рабочим столом
   if (fInteractWithDesktop) 
      dwServiceType |= SERVICE_INTERACTIVE_PROCESS;

   //состояние службы 
   //SERVICE_START_PENDING -служба запускается
   dwCurrentState = SERVICE_START_PENDING;
   //уровень контроля службы
   dwControlsAccepted = 0; 
  //код ошибки который вернет служба если упадет при запуске или останове
   dwWin32ExitCode = NO_ERROR;
   //в нашем случаее игнорируется
   dwServiceSpecificExitCode = 0;
   //счетчик показывающей ход работы службы
   dwCheckPoint = 0;
   //интервал времени необходимый для выполнения старта, останова
   dwWaitHint = 2000;
   return(m_fDebug ? TRUE : (m_hss != NULL));
}

//установка состояния
BOOL CServiceStatus::SetUltimateState(DWORD dwUltimateState, DWORD dwWaitHint) 
{

   DWORD dwPendingState = 0;  
   switch (dwUltimateState) 
   {
	//если требуется остановить то начинаем останоление
   case SERVICE_STOPPED: 
      dwPendingState = SERVICE_STOP_PENDING; 
      break;
   //если требуется запустить то
  //если он в паузе то продолжаем его выполение
  //иначе начинаем запуск
   case SERVICE_RUNNING:
      dwPendingState = 
		  (dwCurrentState == SERVICE_PAUSED) ? SERVICE_CONTINUE_PENDING : SERVICE_START_PENDING; 
      break;
//пауза
   case SERVICE_PAUSED:
      dwPendingState = SERVICE_PAUSE_PENDING; 
      break;

   default:
      (dwPendingState != 0);   
      break;
   }

    dwCheckPoint = 1;
   this->dwWaitHint = dwWaitHint;

//ошибок не было
   dwWin32ExitCode = NO_ERROR;
   dwServiceSpecificExitCode = 0;

   BOOL fOk = FALSE; 
   if (dwPendingState != 0) 
   {
      m_gate.WaitToEnterGate();
      dwCurrentState = dwPendingState; 
      fOk = (dwWaitHint != 0) ? ReportStatus() : ReportUltimateState();
   }

   return(fOk);
}

//состояния прогресса (т.е. как проходит изменние состояния службы, т.е. переход между состояниями)
BOOL CServiceStatus::AdvanceState(DWORD dwWaitHint, DWORD dwCheckPoint) 
{

   this->dwCheckPoint = 
      (dwCheckPoint == 0) ? this->dwCheckPoint + 1 : dwCheckPoint;
   this->dwWaitHint = dwWaitHint;
   //без ошибок
   dwWin32ExitCode = NO_ERROR;
   dwServiceSpecificExitCode = 0;

   return(ReportStatus());
}

//изменения состояния процесса 
//из состояния выполнения перехода в установившиеся состояния
BOOL CServiceStatus::ReportUltimateState() {

   DWORD dwUltimateState = 0;  
   switch (dwCurrentState) 
   {
	//Запуск
   case SERVICE_START_PENDING:
   case SERVICE_CONTINUE_PENDING:
         dwUltimateState = SERVICE_RUNNING; 
         break;
	//останов
   case SERVICE_STOP_PENDING:
         dwUltimateState = SERVICE_STOPPED; 
         break;
   //пауза
   case SERVICE_PAUSE_PENDING:
         dwUltimateState = SERVICE_PAUSED; 
         break;
   }
   dwCheckPoint = dwWaitHint = 0; 
   dwWin32ExitCode = NO_ERROR;
   dwServiceSpecificExitCode = 0;

   BOOL fOk = FALSE; 

   if (dwUltimateState != 0) 
   {
      dwCurrentState = dwUltimateState;   
      fOk = ReportStatus();
      m_gate.LiftGate();
   }

   return(fOk);
}

//#endif  

