// BillingService.cpp : Defines the entry point for the console application.
//

#include "stdafx.h"
#include "BillingService.h"
#include "BalloonMess.h"


#ifdef _DEBUG
#define new DEBUG_NEW
#endif


// The one and only application object

CWinApp theApp;

#include "EnsureCleanup.h"
#include "ServiceStatus.h"
#include "IOCP.h"
TCHAR g_szServiceName[] = TEXT("BS_WinClient");
TCHAR g_szShowName[] = TEXT("Биллинг система");
const int g_ncPort(12001);
#define SERVICESTATUS_IMPL


CServiceStatus g_ssSpy;
enum COMPKEY
{
	CK_SERVICECONTROL,
	CK_VOID		//reserved for removed acess=)
};

void fInstallService() {

	//открываем диспетчер служб на локальном компьютереx
	CEnsureCloseServiceHandle hSCM = ::OpenSCManager(NULL, NULL, SC_MANAGER_CREATE_SERVICE);

	TCHAR szModulePathname[_MAX_PATH * 2];
	//узнаем имя файла откуда были запущены
	::GetModuleFileName(NULL, szModulePathname, 
		sizeof(szModulePathname)/sizeof(szModulePathname[0]));


	//добавляем 
	::lstrcat(szModulePathname, TEXT(" /service"));   

	//создаем наш сервис
	CEnsureCloseServiceHandle 
		hService =	::CreateService(hSCM,	//диспетчер сервисов 
		g_szServiceName, //имя сервиса
		g_szShowName, //отображемое имя
		SERVICE_CHANGE_CONFIG|SERVICE_ENUMERATE_DEPENDENTS|GENERIC_READ, //права сервиса
		SERVICE_WIN32_OWN_PROCESS|SERVICE_INTERACTIVE_PROCESS,//тип сервиса 
		SERVICE_AUTO_START,//само запуск 
		SERVICE_ERROR_IGNORE,//забиваем на ошибки
		szModulePathname,//чего запускать и откуда
		NULL, //все остальные по умолч
		NULL, 
		NULL, 
		NULL, 
		NULL);

	//описание
	SERVICE_DESCRIPTION sd = {TEXT("Windows клент для биллинговой системы кафедры САПРиУ")};
	//изменяем описание сервисов
	::ChangeServiceConfig2(hService, SERVICE_CONFIG_DESCRIPTION, &sd);
	//принудительно запускаем его

	if(hService)	StartService(hService,0,NULL);

}
//удаление сервиса
void fRemoveService() 
{
	//открыли диспетчер
	CEnsureCloseServiceHandle hSCM = ::OpenSCManager(NULL, NULL, SC_MANAGER_CONNECT);
	//закрыли сервис путем его открыванием...
	CEnsureCloseServiceHandle hService = ::OpenService(hSCM, g_szServiceName, DELETE);
	//...и убили :-((
	::DeleteService(hService);
}
DWORD WINAPI TimeHandlerEx(DWORD dwControl, DWORD dwEventType, PVOID pvEventData, PVOID pvContext) 
{

	DWORD dwReturn = ERROR_CALL_NOT_IMPLEMENTED;
	BOOL fPostControlToServiceThread = FALSE;


	switch (dwControl) 
	{
	case SERVICE_CONTROL_STOP:
	case SERVICE_CONTROL_SHUTDOWN:
		g_ssSpy.SetUltimateState(SERVICE_STOPPED, 2000);
		fPostControlToServiceThread = TRUE;
		break;

	case SERVICE_CONTROL_PAUSE:
		g_ssSpy.SetUltimateState(SERVICE_PAUSED, 2000);
		fPostControlToServiceThread = TRUE;
		break;

	case SERVICE_CONTROL_CONTINUE:
		g_ssSpy.SetUltimateState(SERVICE_RUNNING, 2000);
		fPostControlToServiceThread = TRUE;
		break;

	case SERVICE_CONTROL_INTERROGATE:
		g_ssSpy.ReportStatus();
		break;

	case SERVICE_CONTROL_PARAMCHANGE:
		break;

	case SERVICE_CONTROL_DEVICEEVENT:
	case SERVICE_CONTROL_HARDWAREPROFILECHANGE:
	case SERVICE_CONTROL_POWEREVENT:
		break;
	}
	if (fPostControlToServiceThread) {

		CIOCP* piocp = (CIOCP*) pvContext;  
		piocp->PostStatus(CK_SERVICECONTROL, dwControl);
		dwReturn = NO_ERROR;
	}

	return(dwReturn);
}

bool fSocketCreate(SOCKET& ListenSocket)
{
	WSADATA wsaData;
	int iResult = WSAStartup(MAKEWORD(2,2), &wsaData);
	if (iResult != NO_ERROR)return false;


	//----------------------
	// Create a SOCKET for listening for
	// incoming connection requests.
	//SOCKET ListenSocket;
	ListenSocket = socket(AF_INET, SOCK_STREAM, 0);
	if (ListenSocket == INVALID_SOCKET) {

		WSACleanup();
		return 1;
	}

	//----------------------
	// The sockaddr_in structure specifies the address family,
	// IP address, and port for the socket that is being bound.
	sockaddr_in service;
	service.sin_family = AF_INET;
	service.sin_addr.s_addr = INADDR_ANY;
	service.sin_port =12001;

	if (bind( ListenSocket, 
		(SOCKADDR*) &service, 
		sizeof(service)) == SOCKET_ERROR) {
			printf("bind() failed.\n");
			closesocket(ListenSocket);
			return 0;
	}

	//----------------------
	// Listen for incoming connection requests.
	// on the created socket
	if (listen( ListenSocket, 1 ) == SOCKET_ERROR)
		return false;


}

void fSocketKill(SOCKET& AcceptSocket)
{

	shutdown(AcceptSocket,0);
	closesocket(AcceptSocket);
	WSACleanup();
}
DWORD WINAPI TPAcceptSocket(LPVOID lpParameter)
{
	SOCKET* lpListenSocket=static_cast<SOCKET*>(lpParameter);
	SOCKET AcceptSocket;

	for(;;)
	{
		while(1) {
			AcceptSocket = SOCKET_ERROR;
			while( AcceptSocket == SOCKET_ERROR ) {
				AcceptSocket = accept(*lpListenSocket, NULL, NULL );
			}
			//MessageBox(NULL,TEXT("Клиент подключился"),TEXT("Lanservice"),MB_OK);
			break;
		}
		size_t len(0);
		char buf[100];
		do{

			len=recv(AcceptSocket,buf,100,0);
			buf[len]='\0';
			if (strlen(buf))
			{
				//MessageBox(NULL,TEXT(buf),TEXT("Сообщение"),MB_OK);
				CBalloonMess _bm;
				_bm.DoModal(CString(buf));
			}

		}
		while(len);


	}	
	return 0;
}


void BeginAccept(SOCKET& AcceptSocket,HANDLE &hthread)
{
	hthread=::CreateThread(NULL,0,TPAcceptSocket,&AcceptSocket,0,NULL);
}
VOID WINAPI LanServiceMain(DWORD dwArgc,LPTSTR* lpszArgv)
{
	DWORD dwControl = SERVICE_CONTROL_CONTINUE;
	OVERLAPPED *po;
	DWORD dwNumBytes;
	CIOCP iocp(0);
	ULONG_PTR CompKey = CK_SERVICECONTROL;
	SOCKET AcceptSocket;
	HANDLE hthread(NULL);



	//связываем СServiceStatus и наш сервис
	g_ssSpy.Initialize(g_szServiceName, TimeHandlerEx, (PVOID) &iocp, TRUE, TRUE);
	//останавливаем дотступ к процессу
	g_ssSpy.AcceptControls(SERVICE_ACCEPT_STOP);

	//указатели на функции установки и деустновки

	//если подключилась
	//делаем это

	fSocketCreate(AcceptSocket);
	BeginAccept(AcceptSocket,hthread);

	do 
	{
		switch (CompKey) 
		{
		case CK_SERVICECONTROL:
			//в зависимости от состояния сервиса, т.е. в зависимости от того что он делает
			switch (dwControl) 
			{
				//если просто работает	
			case SERVICE_CONTROL_CONTINUE:


				//видимо переволи в состояние работы
				g_ssSpy.ReportUltimateState();
				break;
				//если (при)остановлен
				//то сохраняем то что собрали и удалемся
			case SERVICE_CONTROL_PAUSE:
				g_ssSpy.ReportUltimateState();
				break;
				//

			case	SERVICE_CONTROL_SHUTDOWN:
			case SERVICE_CONTROL_STOP: 
				fSocketKill(AcceptSocket);
				TerminateThread(hthread,0);


				g_ssSpy.ReportUltimateState();
				break;
			}
			break;
		case CK_VOID: //забито под отправку отчётов по сети.
			break;
		} 

		//если сервис не остановлен то получаем его статус
		if (g_ssSpy != SERVICE_STOPPED) {
			iocp.GetStatus(&CompKey, &dwNumBytes, &po);
			dwControl = dwNumBytes;
		}

		//пока сервис не остановится
	} while (g_ssSpy != SERVICE_STOPPED);

}

int _tmain(int argc, TCHAR* argv[], TCHAR* envp[])
{
	int nRetCode = 0;

	// initialize MFC and print and error on failure
	if (!AfxWinInit(::GetModuleHandle(NULL), NULL, ::GetCommandLine(), 0))
	{
		// TODO: change error code to suit your needs
		_tprintf(_T("Fatal Error: MFC initialization failed\n"));
		nRetCode = 1;
	}
	else
	{
		for (int it(0);it<argc;++it)
		{
			std::wcout<<argv[it];
			std::cout<<std::endl;
		}
		//char c;
		//std::cin>>c;

		if(argc>1)
		{
			if ((argv[1][0] == TEXT('-')) || (argv[1][0] == TEXT('/'))) 
			{
				//если устанавливается
				if (lstrcmpi(&argv[1][1], TEXT("install")) == 0) 
				{
					fInstallService();
				}
				//если удаляется
				if (lstrcmpi(&argv[1][1], TEXT("remove"))  == 0)
				{
					fRemoveService();
				}
				//если в отладке
				if (lstrcmpi(&argv[1][1], TEXT("debug"))   == 0) 
				{
					//
				}
				if (lstrcmpi(&argv[1][1], TEXT("test"))   == 0) 
				{
					MessageBox(NULL,argv[1],TEXT("MessageBox"),MB_OK);
					//
				}

				//напрямую не вызывается, вызывается через /install
				if (lstrcmpi(&argv[1][1], TEXT("service")) == 0) 
				{

					SERVICE_TABLE_ENTRY ServiceTable[] = 
					{
						//связываем созданнй процесс с функцией
						{ g_szServiceName, LanServiceMain },
						//нулы нужны чтобы знать сколько элементов
						{ NULL,            NULL }   
					};
					//непосредственое свзывание процесса и функции
					::StartServiceCtrlDispatcher(ServiceTable);
				}
			}
		}	
		return 0;
	}

	return nRetCode;
}
