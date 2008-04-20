/******************************************************************************
Module:  IOCP.h
Notices: Copyright (c) 2000 Jeffrey Richter
Purpose: This class wraps an I/O Completion Port.
         See Appendix B.
		 ����� ������� ��� ������� ����� ������
******************************************************************************/


#pragma once   // Include this header file once per compilation unit


///////////////////////////////////////////////////////////////////////////////


//#include "..\CmnHdr.h"// See Appendix A.
#include <WinSock.h>
#define chVERIFY(x) (x)
#define chASSERT(x)

///////////////////////////////////////////////////////////////////////////////

//����� ������� ��� ������ �����/������
class CIOCP {
public:
	//�����������
	//���� ��� ��������� �� ����������� ������� �� ������
	//����� ����� �������� Create() c ���� ����������
   CIOCP(int nMaxConcurrency = -1) { 
      m_hIOCP = NULL; 
      if (nMaxConcurrency != -1)
         (void) Create(nMaxConcurrency);
   }

   //����������
   //���� ������� �� ������� �� ��������� ���
   ~CIOCP() { 
      if (m_hIOCP != NULL) 
         chVERIFY(CloseHandle(m_hIOCP)); 
   }

   //��������� ����
   BOOL Create(int nMaxConcurrency = 0) {
      //���������(��������� ����) � ���������� �� ���� 
	   m_hIOCP = CreateIoCompletionPort(
		//������� �����, �� � ��� �� INVALID_HANDLE_VALUE, �� IOCP ��������� �� �� ����������� � ������
         INVALID_HANDLE_VALUE, 
		 //������� ����� ���� ���������� 
		 NULL,
		 //������ ���������� ����� ������ � �����
		 0,
		 //���������� �������(�����-threads) ������� ����� ������������ �������� � ���� ������
		 //���� 0 �� ���-�� ����� = ����� ����������� �� ��
		 nMaxConcurrency);

	   //??
      chASSERT(m_hIOCP != NULL);
	  //���������� ��������� ���������� ������� CreateIoCompletionPort() 
	  //���� ��� ������� NULL �� �����, ����� ������
      return(m_hIOCP != NULL);
   }

//��������� ��������� ���� � �����������/������
//hDevice - ������� �����
//CompKey - ������ ����������� ����� ������� ����� ������������
   BOOL AssociateDevice(HANDLE hDevice, ULONG_PTR CompKey) {
	   //���������(��������� ����) � ���������� �� ����
	   //1 ��������: ��� ��� ����� ���� ������� ���� �� ���������� ����������
	   //2 ��������: ������� ������������� ����� => ���� ����� ������ � ��� �������� ������
	   //3,4 ��������� - �� �������� Create()
	   //��������� �� ������������� ��������
	   BOOL fOk = (CreateIoCompletionPort(hDevice,m_hIOCP, CompKey, 0)== m_hIOCP);
      chASSERT(fOk);
	  //���������� ������������ ��������
      return(fOk);
   }

  //����������� ���� � ������
  // ���������� ������� ������� � �������
   BOOL AssociateSocket(SOCKET hSocket, ULONG_PTR CompKey) {
      return(AssociateDevice((HANDLE) hSocket, CompKey));
   }


   //���������� ������ �����
   BOOL PostStatus(ULONG_PTR CompKey, DWORD dwNumBytes = 0, 
      OVERLAPPED* po = NULL) {
	//��������� ����� ����� - ������ � ����� ���������� ����� - ������
    //m_hIOCP:����
    //dwNumBytes:��������� �� ���������� �������� ���-�� ���� ���������� � ������� �������� 
    //CompKey:��������� �� ������ ������� ����� �����������
    //po:��������� �� ���������� ������� ����� ��������� ����� ��������� ������ ����� ��������� ���
	//		���������� �������� 	
     //���������� ���������� ��������
		  BOOL fOk = PostQueuedCompletionStatus(m_hIOCP, dwNumBytes, CompKey, po);

      chASSERT(fOk); 
	  //���������� ��������� ���������� ��������� ��������
      return(fOk); 
   }

//��������� �������� �������/��������� �����
   BOOL GetStatus(ULONG_PTR* pCompKey, PDWORD pdwNumBytes,
      OVERLAPPED** ppo, DWORD dwMilliseconds = INFINITE) {
//m_hIOCP:����
//dwNumBytes:��������� �� ���������� �������� ���-�� ���� ���������� � ������� �������� 
//CompKey:��������� �� ������ ������� ����� �����������
//po:��������� �� ���������� ������� ����� ��������� ����� ��������� ������ ����� ��������� ���
//		���������� ��������
//dwMilliseconds- ����� �����������, ������� ���� ������ ���������� ������������ � ����� ����������. 
//���� dwMilliseconds INFINITE, ���� �����.
//���� ����������� ������� �����,� ppo ����� NULL � ������� ������ FALSE
      return(GetQueuedCompletionStatus(m_hIOCP, pdwNumBytes, 
         pCompKey, ppo, dwMilliseconds));
   }

//� �������� ����� �������� ������� ���������� �����
private:
   HANDLE m_hIOCP;
};


///////////////////////////////// End of File /////////////////////////////////
 