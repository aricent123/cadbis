// BalloonMess.cpp : implementation file
//

#include "stdafx.h"
#include "BalloonMess.h"
#include "basic_funcs.h"
#include "ParamsLoader.h"

IMPLEMENT_DYNAMIC(CBalloonMess, CDialog)

CBalloonMess::CBalloonMess(CWnd* pParent /*=NULL*/)
	: CDialog(CBalloonMess::IDD, pParent)
	, m_strMessage(_T(""))
{

}

CBalloonMess::~CBalloonMess()
{
}

void CBalloonMess::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	
}


BEGIN_MESSAGE_MAP(CBalloonMess, CDialog)
	ON_WM_SHOWWINDOW()
	ON_WM_TIMER()
END_MESSAGE_MAP()

INT_PTR CBalloonMess::DoModal(CString str)
{
	m_strMessage=str;
	return CDialog::DoModal();
}

// CBalloonMess message handlers

void CBalloonMess::OnShowWindow(BOOL bShow, UINT nStatus)
{
	LoadSettings();
	CDialog::OnShowWindow(bShow, nStatus);
	
	// TODO: Add your message handler code heresf
	this->ModifyStyleEx(WS_EX_APPWINDOW , WS_EX_TOPMOST|WS_EX_TOOLWINDOW|WS_EX_LAYERED);

	this->SetLayeredWindowAttributes(m_set.m_transcolor,0,LWA_COLORKEY|LWA_ALPHA);
	
	//перемещение окна
  CRect rect;
	this->GetWindowRect(&rect);
	size_t x_pos(0);
	size_t y_pos(0);
	
	this->SetWindowPos(&CWnd::wndTopMost,x_pos,y_pos,0,0,SWP_SHOWWINDOW|SWP_NOSIZE );
  CDC* pDC=this->GetDC();
	

	pDC->SetBkColor(m_set.m_fillcolor);
	//новая кисточка заливки и фона
	CBrush brush(m_set.m_fillcolor);
	CBrush* pOldBrush = pDC->SelectObject(&brush);

	// create and select a thick, black pen
	//пен который рисует бордер и текст
	CPen pen;
	pen.CreatePen(PS_SOLID , 3,m_set.m_bordercolor);
	CPen* pOldPen = pDC->SelectObject(&pen);
	//заливка и бордер
	pDC->Rectangle(CRect(0,0,rect.Width(),rect.Height()));


	CFont font;
	font.CreateFont(
		18,                        // nHeight
		0,                         // nWidth
		0,                         // nEscapement
		0,                         // nOrientation
		FW_BOLD,                 // nWeight
		FALSE,                     // bItalic
		FALSE,                     // bUnderline
		0,                         // cStrikeOut
		ANSI_CHARSET,              // nCharSet
		OUT_DEFAULT_PRECIS,        // nOutPrecision
		CLIP_DEFAULT_PRECIS,       // nClipPrecision
		DEFAULT_QUALITY,           // nQuality
		DEFAULT_PITCH | FF_SWISS,  // nPitchAndFamily
		TEXT("Times New Roman cyr"));

	CFont * pOldFont=pDC->SelectObject(&font);
	pDC->SetTextColor(m_set.m_textcolor);
	pDC->SetTextAlign(TA_CENTER);
	pDC->TextOut((rect.right-rect.left)/2,2,CString(m_set.m_caption.c_str()));
	pDC->TextOut((rect.right-rect.left)/2,20,CString(m_set.m_subcapt.c_str()));


	//вывод текста
	pDC->TextOut((rect.right-rect.left)/2,38,m_strMessage);

	// put back the old objects
	pDC->SelectObject(pOldBrush);
	pDC->SelectObject(pOldPen);
	pDC->SelectObject(pOldFont);
	for (size_t i(0);i<m_set.m_transcoeff;++i)
	{
		this->SetLayeredWindowAttributes(m_set.m_transcolor,i,LWA_COLORKEY|LWA_ALPHA);
		Sleep(m_set.m_showdelay);
	}


	SetTimer(1,m_set.m_delay,(TIMERPROC) NULL);     

}

void CBalloonMess::OnBnClickedBexit()
{
	// TODO: Add your control notification handler code heretz
	this->EndDialog(0);
}

void CBalloonMess::OnTimer(UINT_PTR nIDEvent)
{
	// TODO: Add your message handler code here and/or call default
	for (size_t i(0);i<m_set.m_transcoeff;++i)
	{
		this->SetLayeredWindowAttributes(m_set.m_transcolor,m_set.m_transcoeff-i,LWA_COLORKEY|LWA_ALPHA);
		Sleep(m_set.m_enddelay);
	}

	this->EndDialog(0);

	CDialog::OnTimer(nIDEvent);
}

void CBalloonMess::LoadSettings(std::string FileName)
{
	char buf[40];

	SHGetSpecialFolderPath(NULL,buf,0x24,TRUE);
	strcat(buf,"\\lanservice.ini");
	CParamsLoader params(buf);


	using namespace conv_ext;

	if (!params("TYPE",std::string(m_strMessage.GetString())).empty())
	{
		params.SetCurrentGroup(params("TYPE",std::string(m_strMessage.GetString())));
		m_strMessage=params("MESSSAGE",std::string(m_strMessage.GetString())).c_str();
	}
	else
		params.SetCurrentGroup("USERMESS");

	m_set.m_bordercolor=stou(params["BORDERCOLOR"]);
	m_set.m_fillcolor=stou(params["BACKCOLOR"]);
	m_set.m_textcolor=stou(params["TEXTCOLOR"]);
	m_set.m_transcoeff=stou(params["TRANSPARENTCOEF"]);
	m_set.m_transcolor=stou(params["TRANSPARENTCOLOR"]);
	m_set.m_delay=stou(params["DELAYTOCLOSE"]);
	m_set.m_caption=params["CAPTION"];
	m_set.m_subcapt=params["SUBCAPT"];
	m_set.m_showdelay=stou(params["SHOWDELAY"]);
	m_set.m_enddelay=stou(params["ENDDELAY"]);
}
