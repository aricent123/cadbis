/*var EditFieldHeader =
	"<html><head>\n" +
	"<style>\n" +
	"body,td {font-family:Verdana,sans-serif;font-size:11px;}\n" +
	"td { border:1px dotted #dddddd;}" +
	"p {margin-top:0px;margin-bottom:0px;}\n" +
	"a:link {text-decoration:underline;color:#444444}\n" +
	"a:hover {text-decoration:none;color:#444444}\n" +
	"</style></head>\n" +
	"<body leftmargin=1 rightmargin=0 topmargin=1 bottommargin=0 marginwidth=1 marginheight=1>\n";*/
var EditFieldHeader =
	"<html><head>\n" +
	"<style>\n" +
	"p {margin-top:0px;margin-bottom:0px;}\n" +
	"</style></head>\n";
/*
var EditFieldHeaderOut =
	"<html><head>\n" +
	"<style>\n" +
	"body,td {font-family:Verdana,sans-serif;font-size:11px;}\n" +
	"p {margin-top:0px;margin-bottom:0px;}\n" +
	"a:link {text-decoration:underline;color:#444444}\n" +
	"a:hover {text-decoration:none;color:#444444}\n" +
	"</style></head>\n";

var EditFieldFooter = "\n</body><html>";
var EditFieldFooterOut = "\n<html>"; 

var PanelStyle = "<style>\n" +
	"body,td {font-family:Verdana,sans-serif;font-size:11px;}\n" +
	"form {margin-top:0px;margin-bottom:0px;}\n" +
	"textarea,input {font-weight:normal; font-size:11px; color:#000000; font-family:Tahoma,Arial; margin-top:0px; margin-bottom:0px; border-style: solid; border-width: 1px; border-color:#666666;}\n" +
	"\n" +
	"</style>\n";
*/
//var EditFieldHeader = "";

var EditFieldHeaderOut = "";
var EditFieldFooter = "";
var EditFieldFooterOut = ""; 
var PanelStyle = "";


function Toolkit () {
	var TStyle = '';
	if (navigator.userAgent.match(/msie/i)) TStyle = new Array('Undo','Redo','Cut','Copy','Paste','Bold','Italic','Underline','StrikeThrough','Superscript','Subscript','tr','JustifyLeft','JustifyCenter','JustifyRight','JustifyFull','InsertOrderedList','InsertUnorderedList','Indent','Outdent','CreateLink','InsertHorizontalRule','InsertTable','tr','ForeColor','BackColor','RemoveFormat');
	if (navigator.userAgent.match(/gecko/i)) TStyle = new Array('undo','redo','cut','copy','paste','bold','italic','underline','strikethrough','superscript','subscript','tr','justifyleft','justifycenter','justifyright','justifyfull','insertorderedlist','insertunorderedlist','indent','outdent','createlink','inserthorizontalrule','inserttable','tr','forecolor','backcolor','removeformat');
	var TImage = new Array('undo','redo','cut','copy','paste','b','i','u','s','sup','sub','tr','l','c','r','j','ol','ul','in','out','a','hr','tab','tr','cfg','cbg','F');
	var TTitle = new Array('Отменить','Повторить','Вырезать','Копировать','Вставить','Полужирный','Курсив','Подчеркнутый','Перечеркнутый','Степень','Индекс','tr','По левому краю','По центру','По правому краю','По ширине','Нумерованный список','Маркированный список','Увеличить отступ','Уменьшить отступ','Гиперссылка','Линия','Вставить таблицу','tr','Цвет шрифта','Цвет фона','Снять форматирование');

	var Toolkit = "<Table cellpadding=0 cellspacing=1 border=0><Tr>\n";

	for (i in TStyle) {
		if (TStyle[i] != 'separator' && TStyle[i] != 'tr') Toolkit += "<Td><button class=tools width=20 height=20 onclick=\"setStyle('"+TStyle[i]+"')\" onmouseover=\"style.background='#c0c0c0';style.borderColor='#666666';\" onmouseout=\"style.background='#dddddd';style.borderColor='#dddddd';\" title=\""+TTitle[i]+"\"><img src=img/editor/"+TImage[i]+".gif></button></Td>\n";
		else if(TStyle[i] == 'tr') Toolkit+='</tr><tr>';
	        else Toolkit += "<Td width=1></Td>\n";
	}
	document.getElementById('tools').innerHTML = Toolkit+"</Tr></Table>";
}
Toolkit();

function setStyle (TStyle) {
	if (TStyle.match(/inserttable/i)) {
		var Form = "<html>" +
			"<head>" +
			"<title>Вставка таблицы</title>" +
        	PanelStyle +
			"</head>\n" +
			"<script language=Javascript>\n" +
			"function AddTbl() {\n" +
				"var nTable = '<TABLE width='+document.forms['tblf'].elements['width'].value+' height='+document.forms['tblf'].elements['height'].value+' cellpadding='+document.forms['tblf'].elements['padding'].value+' cellspacing='+document.forms['tblf'].elements['spacing'].value+' border='+document.forms['tblf'].elements['border'].value+' bgcolor=#'+document.forms['tblf'].elements['bgcolor'].value+'>';\n" +
				"for (r=0;r<document.forms['tblf'].elements['rows'].value;r++) {\n" +
					"nTable += '<tr>';\n" +
					"for (c=0;c<document.forms['tblf'].elements['cols'].value;c++) {\n" +
						"nTable += '<td></td>';\n" +
					"}\n" +
					"nTable += '</tr>';\n" +
				"}\n" +
				"nTable += '</TABLE>';\n" +
				"//window.opener.EditField.focus();\n" +
				"var Field = window.opener.EditFieldHeader+window.opener.EditField.body.innerHTML+nTable+window.opener.EditFieldFooter;\n" +
				"window.opener.EditField.open();\n" +
				"window.opener.EditField.write(Field);\n" +
				"window.opener.EditField.close();\n" +
				"window.close();\n" +
			"}</script>\n" +
			"<body topmargin=0 leftmargin=0>\n" +
			"<br><form id=tblf><table width=100%>" +
				"<tr><td>Ширина таблицы</td><td><input size=15 name=width value='100%'></td></tr>" +
				"<tr><td>Высота таблицы</td><td><input size=15 name=height value='200'></td></tr>" +
				"<tr><td>Количество столбцов</td><td><input size=15 name=cols value='5'></td></tr>" +
				"<tr><td>Количество строк</td><td><input size=15 name=rows value='2'></td></tr>" +
				"<tr><td>Ширина бордюра</td><td><input size=15 name=border value='1'></td></tr>" +
				"<tr><td>Отступ</td><td><input size=15 name=padding value='2'></td></tr>" +
				"<tr><td>Расстояние между ячейками</td><td><input size=15 name=spacing value='1'></td></tr>" +
				"<tr><td>Цвет фона</td><td><input size=15 name=bgcolor value='FFFFFF' maxlength=6></td></tr>" +
				"<tr><td colspan=2><input type=button value=Вставить style='width:100%' OnClick=\"AddTbl()\"></td></tr>" +
			"</table></form>\n" +
			"</body>" +
			"</html>";

        var TabPanel = window.open("","TabPanel","dependent=1,width=300,height=220,status=yes");
		TabPanel.document.open();
        TabPanel.document.write(Form);
        TabPanel.document.close(); 
	} else if (TStyle.match(/insertimage/i)) {
        var Form = "<html><head>" +
			PanelStyle +
			"<title>Загрузка изображения</title></head>" +
			"<body leftmargin=0 rightmargin=0 topmargin=0 bottommargin=0 marginwidth=0 marginheight=0>\n" +
			"<FORM action=\"saveimg.php\" method=\"POST\" enctype=\"multipart/form-data\" id=\"imgform\">" +
			"<input type=\"file\" name=\"img\" style=\"width:400\">" +
			"<input type=\"hidden\" name=\"id\" value=\"165\">" +
			"<br><input type=\"submit\" value=\"OK\" style=\"width:400\" onclick=\"window.opener.EditField.execCommand('insertimage',false,document.forms['imgform'].elements['img'].value);\">" +
			"</FORM>" +
			"\n</body><html>";

		ImgPanel = open('', 'ImgPanel', 'dependent=1,width=400,height=10,status=no,toolbar=no,menubar=no,location=no,resizable=yes');

		ImgPanel.document.open();
		ImgPanel.document.write(Form);
		ImgPanel.document.close();
	} else if (TStyle.match(/^createlink$/i)) {
		var Url = prompt('Введите адрес','http://');
		EditField.execCommand('CreateLink',false,Url);
	} else if (TStyle.match(/forecolor|backcolor/i)) {
		var Form = "<html><head>" +
		PanelStyle +
		"<title>Палитра</title></head>" +
		"<body leftmargin=0 rightmargin=0 topmargin=0 bottommargin=0 marginwidth=0 marginheight=0>\n<table width=\"360\" height=\"100\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tr><td height=\"40\">" +
		"<form name=clr>" +
		"&nbsp;<input type=text name=colr style=\"width:40;height:30\" readonly>&nbsp;" +
		"<input type=text name=colr_hex style=\"width:60\" value=#ffffff>" +
		"</form>" +
		"</td></tr><tr><td><table width=\"360\" height=\"60\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
		var i = 0;
		for(r=0; r<6; r++){
			for(g=0; g<6; g++){
				for(b=0; b<6; b++){
					if (i==0) Form += '<tr>\n';
					colr = i2hx(r)+i2hx(g)+i2hx(b);
					Form += "<td width=10 height=10 bgcolor=#"+colr+
					" onclick=\"window.opener.EditField.execCommand('"+TStyle+"',false,'#"+colr+"');window.close();\""+
					" onmouseover=\"document.forms['clr'].elements['colr'].style.background='#"+colr+"';document.forms['clr'].elements['colr_hex'].value='#"+colr+
					"'\"><img src=img/editor/1x1.gif height=10 border=0></td>\n";
					i++;
					if (i==36) {
						Form += '</tr>';
						i=0;
					}
				}
			}
		}
		Form += '</table></td><tr><table>';
		Form += "\n</body><html>";

		ColorPanel = open('', 'ColorPanel', 'dependent=1,width=360,height=80,status=yes,toolbar=no,menubar=no,location=no,resizable=no');

		ColorPanel.document.open();
		ColorPanel.document.write(Form);
		ColorPanel.document.close();
	} else {
		EditField.execCommand(TStyle,false,null);
	}
}

function i2hx(i) {
  i*=51;
  if (i<16) return "0"+i.toString(16);
  else return i.toString(16);
}

function SetFace () {EditField.execCommand('fontname',false,document.getElementById('fface').value)}
function SetSize () {EditField.execCommand('fontsize',false,document.getElementById('fsize').value)}


function Save () {
	if (navigator.userAgent.match(/msie/i)) document.forms['EditForm'].elements['text'].value = EditFieldHeaderOut + EditField.body.outerHTML + EditFieldFooter;
	if (navigator.userAgent.match(/gecko/i)) document.forms['EditForm'].elements['text'].value = EditFieldHeaderOut + "<body bgcolor=" + EditField.bgColor + ">\n" + EditField.body.innerHTML + "\n</body>" + EditFieldFooter;
	//Saved = 1;
	document.forms['EditForm'].submit();
	//alert(document.forms['EditForm'].elements['text'].value);
}


if (navigator.userAgent.match(/msie/i)) EditField = frames['EditFrame'].document;
else if (navigator.userAgent.match(/gecko/i)) EditField = document.getElementById('EditFrame').contentDocument;
else alert("ArthEdit is not supported by your browser");
EditField.designMode = 'On';

EditField.open();
EditField.write(EditFieldHeader);
EditField.write(Content);
EditField.write(EditFieldFooter);
EditField.close();

EditField.execCommand('fontname',false,'Verdana');
EditField.execCommand('fontsize',false,'2');