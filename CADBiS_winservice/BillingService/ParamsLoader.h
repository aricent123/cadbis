//---------------------------------------------------------------------------
// ParamsLoader.h file
// Programming: SM
// Content: Holds declaration of CParamsLoader class
//---------------------------------------------------------------------------
#ifndef ParamsLoaderH
#define ParamsLoaderH


#include <map>
#include <algorithm>
#include <vector>
#include <fstream>
#include <string>



//NEEDED TYPEDEFS....
typedef std::string str_t;
typedef std::vector<std::string> array_str_t;
typedef std::map<std::string,std::string> map_str_str_t;
typedef std::pair<std::string,std::string> pair_str_str_t;
typedef std::map<std::string,pair_str_str_t> map_str_pair_str_str_t;
typedef std::map<std::string,std::map<std::string, std::string> > map_str_map_str_str_t;
typedef std::pair<std::string,size_t> pair_str_uint_t;
typedef std::vector<pair_str_uint_t> array_pair_str_uint_t;


const size_t MAX_STR_LEN=255;			//������������ ����� ������
const char CHAR_GROUP_DCLBEGIN= '[';	//������ ���������� ������
const char CHAR_GROUP_DCLEND=   ']';	//����� ���������� ������
const char CHAR_COMMENT=        '#';	//�����������
const str_t STR_EQUAL=          "=";	//������, ���������� ���������
const str_t STR_SEPARATOR=      "<+>";	//������, ���������� ����������� (��� ������������)
const bool RETYPE_PARAMS=       true;	//��������� ��������������� ����������?


//---------------------------------------------------------------------------
//��������: Parameters Loader
//����������: ����� �������� ���������� (�� ini �����)
//---------------------------------------------------------------------------
class CParamsLoader
{
 //-------------------------members-------------------------//
 map_str_map_str_str_t	mm_params;							//��������� (������������� ������)
 array_str_t			mv_keys;							//����� (������)
 str_t					ms_curgroup;						//������� ������
 //-------------------------methods-------------------------//
 void m_del_char(str_t &text, const char chr=' ');			//�������� ������� �� ������
 void m_strsplit(const str_t & text,						
				const str_t & separators,					
				array_str_t & words);						//��������� ������ �� ���������
 void m_readfile(const str_t &file, array_str_t & strings);	//������ ����� � ������ �����
 void m_kill_spaces(array_str_t & strings);					//�������� ��������
 void m_get_groups(const array_str_t & strings,				 
				array_pair_str_uint_t & groups);			//��������� ������ �����
 void m_get_params_values(const array_str_t &strings,		
 						 map_str_str_t &parameters);		//��������� �������� ����������
 bool m_strstr(const str_t &string,const str_t &substr);	//���������� �� ��������� � ������
 void m_parse_param_string(str_t &string);					//��������� ������ � ����������
 void m_parse_group_string(str_t &string);					//��������� ������ � �������
 void m_clear();											//������� �����������
 bool m_read_parameters(const str_t &file);					//������ ���������� �����
 void m_get_group_params(const pair_str_uint_t &group,		
						const array_str_t &strings,			
						array_str_t &params);				//�������� ������ ���������� ������
 bool m_group_exists(const str_t &group)const;				//���������� �� ������?
public:
 
 //ctors:
 CParamsLoader(){}
 CParamsLoader(const str_t &FileName){m_read_parameters(FileName);}

 //MODIFIERS:
 bool LoadParameters(const str_t &FileName);				//��������� ���������
 void SetCurrentGroup(const str_t &group);					//���������� ������� ������

 //ACCESSORS:
 str_t operator()(const str_t &group,						\
					const str_t &param);					//��������� ��������� �� ������
 str_t operator [](const str_t &param);						//��������� ��������� �� ������� ������ 
};
//---------------------------------------------------------------------------

#endif
 