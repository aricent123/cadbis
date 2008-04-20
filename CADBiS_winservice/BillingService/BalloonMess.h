#pragma once

#include "resource.h"
#include "stdafx.h"
// CBalloonMess dialog
struct CSettings
{
	COLORREF m_transcolor;
	size_t m_transcoeff;
	COLORREF m_textcolor;
	COLORREF m_bordercolor;
	COLORREF m_fillcolor;
	size_t m_delay;
	size_t m_showdelay;
	size_t m_enddelay;
	std::string m_caption;
	std::string m_subcapt;

};
class CBalloonMess : public CDialog
{
	DECLARE_DYNAMIC(CBalloonMess)

public:
	CBalloonMess(CWnd* pParent = NULL);   // standard constructor
	virtual ~CBalloonMess();

// Dialog Data
	enum { IDD = IDD_BALLOONMESS };

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV support

	DECLARE_MESSAGE_MAP()
public:
	CString m_strMessage;
	INT_PTR DoModal(CString str);
public:
	afx_msg void OnShowWindow(BOOL bShow, UINT nStatus);
public:
	afx_msg void OnBnClickedBexit();
private:
	CSettings m_set;

	afx_msg void OnTimer(UINT_PTR nIDEvent);
	void LoadSettings(std::string FileName = "lanservice.ini");
};

