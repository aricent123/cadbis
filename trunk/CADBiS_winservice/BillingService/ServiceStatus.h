
#pragma once  
#include "Gate.h" //������� ������

//����� �������� ���������� � ������� ������� � ����������� ��������� ��������
//����������� �� ������������ SERVICE_STATUS ����������� �������� ������ ����������
class CServiceStatus : public SERVICE_STATUS 
{
public:
   CServiceStatus();
	//����� �������	
   void SetDebugMode() { m_fDebug = TRUE; }
	//�������������
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
	//����� �� ������� � WIN32
   dwWin32ExitCode = dwError;
   dwServiceSpecificExitCode = 0;
   return(ReportStatus());
}


//����� � ������������� ������
inline BOOL CServiceStatus::ReportServiceSpecificError(DWORD dwError) 
{
   dwWin32ExitCode = ERROR_SERVICE_SPECIFIC_ERROR;
   dwServiceSpecificExitCode = dwError;
   return(ReportStatus());
}
//#ifdef SERVICESTATUS_IMPL
//������������� ���������� �������
BOOL CServiceStatus::Initialize(PCTSTR szServiceName,	//��� ������
								LPHANDLER_FUNCTION_EX pfnHandler, //��������� �� ����������� �������
								PVOID pvContext,			//�����-�� ������(iocp)
								BOOL fOwnProcess,			//���� �������
								BOOL fInteractWithDesktop)  //����������� ������ � ��� ������
{

   if (!m_fDebug) 
   {
	   //����������� ������� ������� ����� ������������ ������� ������� �� ��������(����������)
	   //szServiceName- ��� �������
	   //pfnHandler - ��������� �� ������� �������������� �������
	   //pvContext - ��������������� ������ 
      m_hss = RegisterServiceCtrlHandlerEx(szServiceName, pfnHandler, pvContext);
   }
	
	//���������� ��������� �������

   //��� �������
   //������ ����������� � ����������� �������� ��� ��������� ��� � ������� ��������
   dwServiceType = fOwnProcess 
      ? SERVICE_WIN32_OWN_PROCESS : SERVICE_WIN32_SHARE_PROCESS;

   //����������� ������ ���������������� � ������� ������
   if (fInteractWithDesktop) 
      dwServiceType |= SERVICE_INTERACTIVE_PROCESS;

   //��������� ������ 
   //SERVICE_START_PENDING -������ �����������
   dwCurrentState = SERVICE_START_PENDING;
   //������� �������� ������
   dwControlsAccepted = 0; 
  //��� ������ ������� ������ ������ ���� ������ ��� ������� ��� ��������
   dwWin32ExitCode = NO_ERROR;
   //� ����� ������� ������������
   dwServiceSpecificExitCode = 0;
   //������� ������������ ��� ������ ������
   dwCheckPoint = 0;
   //�������� ������� ����������� ��� ���������� ������, ��������
   dwWaitHint = 2000;
   return(m_fDebug ? TRUE : (m_hss != NULL));
}

//��������� ���������
BOOL CServiceStatus::SetUltimateState(DWORD dwUltimateState, DWORD dwWaitHint) 
{

   DWORD dwPendingState = 0;  
   switch (dwUltimateState) 
   {
	//���� ��������� ���������� �� �������� �����������
   case SERVICE_STOPPED: 
      dwPendingState = SERVICE_STOP_PENDING; 
      break;
   //���� ��������� ��������� ��
  //���� �� � ����� �� ���������� ��� ���������
  //����� �������� ������
   case SERVICE_RUNNING:
      dwPendingState = 
		  (dwCurrentState == SERVICE_PAUSED) ? SERVICE_CONTINUE_PENDING : SERVICE_START_PENDING; 
      break;
//�����
   case SERVICE_PAUSED:
      dwPendingState = SERVICE_PAUSE_PENDING; 
      break;

   default:
      (dwPendingState != 0);   
      break;
   }

    dwCheckPoint = 1;
   this->dwWaitHint = dwWaitHint;

//������ �� ����
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

//��������� ��������� (�.�. ��� �������� �������� ��������� ������, �.�. ������� ����� �����������)
BOOL CServiceStatus::AdvanceState(DWORD dwWaitHint, DWORD dwCheckPoint) 
{

   this->dwCheckPoint = 
      (dwCheckPoint == 0) ? this->dwCheckPoint + 1 : dwCheckPoint;
   this->dwWaitHint = dwWaitHint;
   //��� ������
   dwWin32ExitCode = NO_ERROR;
   dwServiceSpecificExitCode = 0;

   return(ReportStatus());
}

//��������� ��������� �������� 
//�� ��������� ���������� �������� � �������������� ���������
BOOL CServiceStatus::ReportUltimateState() {

   DWORD dwUltimateState = 0;  
   switch (dwCurrentState) 
   {
	//������
   case SERVICE_START_PENDING:
   case SERVICE_CONTINUE_PENDING:
         dwUltimateState = SERVICE_RUNNING; 
         break;
	//�������
   case SERVICE_STOP_PENDING:
         dwUltimateState = SERVICE_STOPPED; 
         break;
   //�����
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

