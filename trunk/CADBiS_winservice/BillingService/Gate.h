#pragma once         
#include <WinSock.h>
//
class CGate {
public:
   CGate(BOOL fInitiallyUp = TRUE, PCTSTR pszName = NULL) 
   { 
      m_hevt = ::CreateEvent(NULL, FALSE, fInitiallyUp, pszName); 
   }

   ~CGate() { 
      ::CloseHandle(m_hevt); 
   }

   DWORD WaitToEnterGate(DWORD dwTimeout = INFINITE, BOOL fAlertable = FALSE) {
      return(::WaitForSingleObjectEx(m_hevt, dwTimeout, fAlertable)); 
   }
   
   VOID LiftGate() 
   { 
	   ::SetEvent(m_hevt); 
   }

private:
    HANDLE m_hevt;
};
