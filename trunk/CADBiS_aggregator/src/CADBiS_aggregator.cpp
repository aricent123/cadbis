/*
	Tool:
		CADBiS URLs Aggregator v.1.0 (written on the night 26.11-27.11 for aproximately 3 hours)
	Author: 
		Sadykov (aka SM) 
	Desc: 
		Visited URLs aggregator. Used for aggregate data from operative data-table...
		Specially for CADBiS (CAD department Billing System)
	P.S.:
		to compile use this:
		g++ cadbisaggr.cpp -o cadbisaggr -lmysqlclient -L/usr/local/lib/mysql/ -lm
*/

#include <stdio.h>
#include <string.h>
#include <iostream>
#include <mysql.h>
#include <vector>
#include <string>
#include <fstream>
#include <sstream>
#include <map>
#include <stdlib.h>
#include <time.h>
#include <locale.h>
#define SLENGTH 80


//#define FULL_DEBUG
//#define DEBUG
//#define DEBUG_SQL
//#define ANY_DEBUG
//#define SHOW_ERRORS
//#define DEBUG_CID
#define LOG
const size_t MAX_STR_LEN = 255;
const std::string LOG_FORMAT_FILE_PATH = "/usr/local/cadbis/cadbisaggr.log.format";
const std::string LOG_FILE_PATH = "/usr/local/cadbis/cadbisaggr.log";

typedef std::string str_t;
typedef std::vector<str_t> array_str_t;

void strsplit(const str_t & text,const str_t & separators, array_str_t & words)
{
	int n = text.length();
	int start, stop;
	start = text.find_first_not_of(separators);
	while ((start >= 0) && (start < n)) {
		stop = text.find_first_of(separators, start);
		if ((stop < 0) || (stop > n)) stop = n;
		words.push_back(text.substr(start, stop - start));
		start = text.find_first_not_of(separators, stop+1);
	}
}

namespace conversions{
std::string itos(int value)
  {
	//char tmp[MAX_STR_LEN];
	//sprintf(tmp,"%d",value);
	//return std::string(tmp);
	  std::stringstream ss;
	  ss<<value;
	  return ss.str();
  }

int stoi(const std::string & str)
  {
	return atoi(str.c_str());
  }

long stol(const std::string & str)
  {
	return atol(str.c_str());
  }

std::string ltos(long long value)
  {
	  std::stringstream ss;
	  ss<<value;
	  return ss.str();
  }
unsigned long ip2value(const std::string &ip)
  {
	array_str_t aip;
	strsplit(ip,".",aip);
	if(aip.size()<4)
		return 0;
	return stoi(aip[0])*256*256*256 + stoi(aip[1])*256*256 + stoi(aip[2])*256 + stoi(aip[3]);
  }
};

using conversions::itos;
using conversions::stoi;
using conversions::ltos;
using conversions::stol;

struct SProtocolData
{
	std::string unique_id;
	long lLength;
	std::string sData;
	
	SProtocolData(std::string id="",std::string data ="", long len =0 )
	{
	unique_id = id;
	sData = data;
	lLength = len;
	}
};


struct SUrlStatistic
{
	int uid;
	std::string url;
	long lLength;
    size_t iCount;
	std::string ip;

	SUrlStatistic(int auid =0,const std::string aurl ="", long alength=0, size_t acount =0,const std::string aip ="")
	{
		url = aurl;
		uid = auid;
		lLength = alength;
		iCount = acount;
		ip = aip;
	}

SUrlStatistic & operator = (SUrlStatistic const & other)
	{
		this->uid = other.uid;
		this->url = other.url;
		this->lLength = other.lLength;
		this->iCount = other.iCount;
		this->ip = other.ip;
		return *this;
	}
SUrlStatistic & operator += (SUrlStatistic const & other)
	{
		this->lLength += other.lLength;
		this->iCount += other.iCount;
		return *this;
	}	
};

#if defined LOG

std::string get_date()
{
	   char nowstr[SLENGTH];
	   time_t nowbin;
	   const struct tm *nowstruct;

	   (void)setlocale(LC_ALL, "");

	   time(&nowbin);
	   nowstruct = localtime(&nowbin);
	   if (strftime(nowstr, SLENGTH, "%d.%m.%Y %T", nowstruct) == (size_t) 0)
	       return "DATE ERROR";

	   return nowstr;
}

void log(const std::string &str)
{
	/*FILE *fp;
	fp = fopen(LOG_FILE_PATH.c_str(),"a");
	fprintf(fp,"[%s]: %s\n",get_date().c_str(),str.c_str());
	fclose(fp);*/
}
#endif

#if defined ANY_DEBUG
void print_r_vec(const std::vector<std::string> &v)
{
	std::cout<<"VEC<String> ("<<std::endl;
	for(size_t i(0);i<v.size();++i)
		std::cout<<"["<<i<<"] = '"<<v[i]<<"';"<<std::endl;
	std::cout<<")"<<std::endl;
}

void print_r_proto(std::map<std::string,SProtocolData> &m)
{
	std::cout<<"MAP<SProtocolData> ("<<std::endl;
	for(std::map<std::string,SProtocolData>::iterator it(m.begin());it!=m.end();++it)
		std::cout<<"["<<(*it).first<<"] ( unique_id= '"<<(*it).second.unique_id<<"'; lLength = "<<(*it).second.lLength<<"; sData = '"<<(*it).second.sData<<"'), "<<std::endl;
	std::cout<<")"<<std::endl;
}

void print_r_urls(std::map<std::string,SUrlStatistic> &m)
{
	std::cout<<"MAP<SUrlStatistic> ("<<std::endl;
	for(std::map<std::string,SUrlStatistic>::iterator it(m.begin());it!=m.end();++it)
	std::cout<<"["<<(*it).first<<"] ( url= '"<<(*it).second.url<<"'; uid= '"<<(*it).second.uid<<"'; lLength = "<<(*it).second.lLength<<"; iCount = '"<<(*it).second.iCount<<"'), "<<std::endl;
	std::cout<<")"<<std::endl;
}

void print_r_map_int(std::map<std::string,size_t> &m)
{
	std::cout<<"MAP<size_t> ("<<std::endl;
	for(std::map<std::string,size_t>::iterator it(m.begin());it!=m.end();++it)
		std::cout<<"["<<(*it).first<<"] ("<<(*it).second<<"), "<<std::endl;
	std::cout<<")"<<std::endl;
}

void debug(const std::string & info)
{
	std::cout<<"CADBiS debug: "<<info<<std::endl;
}

#endif

//-------------------------------------------------------------------


/**
 class CMySQLConnector for MySQL connections
**/
class CMySQLConnector{
	MYSQL m_Mysql;
	std::string m_sServer,m_sUser,m_sPassword,m_sDatabase;
	std::string m_sLastQuery;
	bool m_IsAnyError;
	MYSQL_RES* pMysqlResult;
	MYSQL_ROW m_Mysqlrow;
	bool m_bEnableLog;


	void m_mysql_error()
	{
	std::string error(mysql_error(&m_Mysql));
#if defined SHOW_ERRORS	
	if(m_sLastQuery.length())
		debug("Last query: "+m_sLastQuery);
	if(error.length())
		debug("MySQL Error: "+error);
#endif
#if defined LOG
	if(m_bEnableLog && error.length())
		log("MySQLError:"+error);
#endif
		m_IsAnyError = true;
	}
	
	bool m_is_any_error()
	{
	return m_IsAnyError;
	}
	

public:
	CMySQLConnector(const std::string &database,const std::string &user ="root",const std::string &password="",const std::string &server="localhost", bool enable_log)
		{
		pMysqlResult = 0;
		if (!(mysql_real_connect(&m_Mysql,server.c_str(),user.c_str(),"",database.c_str(),3306,NULL,0)))
			m_mysql_error();
		if (mysql_select_db(&m_Mysql,database.c_str())) 
			m_mysql_error();
		if(m_is_any_error())
			return;
		m_sServer = server;
		m_sDatabase = database;
		m_sUser = user;
		m_sPassword = password;
		m_bEnableLog = enable_log;
		}
	~CMySQLConnector()
		{
		if(pMysqlResult)
			mysql_free_result(pMysqlResult);
		mysql_close(&m_Mysql);		
		}
	
	void Query(const std::string &query)
		{
#if defined FULL_DEBUG
	debug("Query: "+query);
#endif
#if defined LOG
	if(m_bEnableLog)
		log("Query: "+query);
#endif
		m_sLastQuery = query;	
		if(mysql_query(&m_Mysql,query.c_str()))
			m_mysql_error();
		  if (!(pMysqlResult = mysql_store_result(&m_Mysql))) 
			m_mysql_error();
		
		}
	
	bool FetchRow(std::vector<std::string> & container)
		{
		if(!pMysqlResult)
			return false;
		m_Mysqlrow = mysql_fetch_row(pMysqlResult);
		if(!m_Mysqlrow){pMysqlResult=0; return false;};
		if(container.size())
			container.clear();
		container.resize(mysql_num_fields(pMysqlResult));
		for(size_t i(0);i<container.size();++i)
			container[i] = m_Mysqlrow[i];
#if defined FULL_DEBUG
//print_r_vec(container);
#endif
		return true;
		}

	std::string LastQuery() const
		{
		return m_sLastQuery;
		}
};


//-------------------------------------------------------------------



/**
 class CCADBiSAggregator for data aggregations
**/
class CCADBiSAggregator{
	CMySQLConnector *pMySQL;
	CMySQLConnector *pMySQL2;
	std::map<std::string,SProtocolData> m_Protocols;
	std::map<std::string,SUrlStatistic> m_Urlstats;
	std::map<std::string, size_t> m_UidsTable;
	std::string m_sLogFormat;
	bool m_bEnableLog;

	size_t m_RetrieveUid(const std::string & user)
	{
		pMySQL2->Query("select uid from users where user = '"+user+"'");
#if defined FULL_DEBUG
		debug("Trying to find uid for "+user+"; query = "+pMySQL2->LastQuery());
#endif
		std::vector<std::string> vRow;
		pMySQL2->FetchRow(vRow);
#if defined FULL_DEBUG
		debug("UID for "+user+" = "+vRow[0]);
#endif
		if(vRow.size())
			return stoi(vRow[0]);
		else
			{
#if defined FULL_DEBUG
			debug("Cannot find uid for user "+user+" or wrong query...");
#endif
			return 0;
			}
	}

	void m_AddStats(const std::string &unique_id, const std::string &user, const std::string & url, const std::string & length, const std::string & count, const std::string & date, const std::string & ip)
	{
															#if defined FULL_DEBUG
																	//print_r_urls(m_Urlstats);
																	debug("m_AddStats("+unique_id+", "+user+", "+url+", "+length+", "+count+", "+date+", "+ip+")");
																	//print_r_map_int(m_UidsTable);
															#endif	
	SUrlStatistic stat;
	if(m_UidsTable.find(user) == m_UidsTable.end())
		{
		stat.uid = m_RetrieveUid(user);
		m_UidsTable[user] = stat.uid;
		}
	else
		stat.uid = m_UidsTable[user];
	stat.iCount = stoi(count);
	stat.lLength = stol(length);
	stat.url = url;
	stat.ip = ip;
	std::string key(stat.url+"-"+itos(stat.uid));

															#if defined FULL_DEBUG
																debug("key = " +key);
																debug("if(m_Urlstats.find(key) == m_Urlstats.end()) = "+itos((m_Urlstats.find(key) == m_Urlstats.end())));
															#endif
		if(m_Urlstats.find(key) == m_Urlstats.end())
			m_Urlstats[key] = stat;
		else
			m_Urlstats[key] += stat;
															#if defined FULL_DEBUG
																	debug("m_Urlstats[key] added ...");
																	debug("m_Urlstats["+key+"]=(\n"+stat.url+", \nuid="+itos(stat.uid)+", \nlLenght="+ltos(stat.lLength)+", \niCount="+itos(stat.iCount)+"\n)");
															#endif
	}

	bool m_strstr(const std::string &text, const std::string &needle)
	{
	return (text.find(needle)<text.length());
	}

	// replace needle to repl_to in text
	void m_str_replace(std::string &text, const std::string &needle, const std::string &repl_to)
	{
		if(!m_strstr(text,needle))return;
		size_t n = text.length();
		size_t stop(0);
		stop = text.find(needle);
		while ((stop <= n)) {
			std::string left = text.substr(0, stop);
			std::string right = text.substr(stop+needle.length(),text.length()-(stop+needle.length()));
			text=left+repl_to+right;
			n = text.length();
			stop = text.find(needle,stop+repl_to.length());
		}
	}
	
	void m_load_log_format_file()
	{
	char tmpstr[MAX_STR_LEN];
	std::ifstream fp_in(LOG_FORMAT_FILE_PATH.c_str());
	if(!fp_in)return;
	while(fp_in.getline(tmpstr,MAX_STR_LEN))
		m_sLogFormat += tmpstr;
	}


	// returns default log format
	const char * m_default_log_format(std::string const &date, std::string const &url, std::string const &count, std::string const &length) const
	{
	return std::string("["+date+"] <a href=\""+url+"\">"+url+"</a> ("+count+" got = "+length+" bytes)\r\n").c_str();
	}


	// returns log format... 
	const char * m_log_format(std::string const &date, std::string const &url, std::string const &count,std::string const &length, std::string const &ip )
	{
		if(m_sLogFormat.length())
			{			
			std::string tmp = m_sLogFormat;
			m_str_replace(tmp,"{DATE}",date);
			m_str_replace(tmp,"{URL}",url);
			m_str_replace(tmp,"{COUNT}",count);
			m_str_replace(tmp,"{LENGTH}",length);
			m_str_replace(tmp,"{IP}",ip);
			return tmp.c_str();
			}
		else
			return m_default_log_format(date,url,count,length);
	}



public:

// ctors:
	CCADBiSAggregator(bool enable_log = false)
	{
	pMySQL = new CMySQLConnector("nibs","root","","localhost",enable_log);
	pMySQL2 = new CMySQLConnector("nibs","root","","localhost",enable_log);
	m_load_log_format_file();
	pMySQL->Query("LOCK TABLES `url_log` WRITE, `protocols` WRITE, `url_popularity` WRITE, `ctry_popularity` WRITE, `ip2country` WRITE;"); 	
	}

	~CCADBiSAggregator()
	{
		pMySQL->Query("UNLOCK TABLES;");
		delete pMySQL;
		delete pMySQL2;
	}
	

	std::string get_cid_of_url(const std::string & url)
	{		
		pMySQL->Query("select keys,cid from `url_categories_filter`");
		std::vector<std::string> vRow;
		while(pMySQL->FetchRow(vRow))
		{
			std::string keys = vRow[0];
			std::string cid = vRow[1];
			if(m_strstr(url,keys))
				return cid;
		}
	}

// --
	void Aggregate()
	{
		m_Urlstats.clear();
		//pMySQL->Query("LOCK TABLE `protocols` WRITE;");
		//pMySQL->Query("LOCK TABLE `url_popularity` WRITE;");

		pMySQL->Query("select unique_id as '0',url as '1',SUM(length) as '2', date as '3',COUNT(*) as '4',user as '5', ip as '6' from url_log group by url order by unique_id,date,url,length;");
		std::vector<std::string> vRow;
		m_Protocols.clear();
		while(pMySQL->FetchRow(vRow))
		{
		this->m_AddStats(vRow[0],vRow[5],vRow[1],vRow[2],vRow[4],vRow[3],vRow[6]);
																#if defined FULL_DEBUG
																	debug("OK, we've started the cycle");
																#endif				

																#if defined LOG
																	if(m_bEnableLog)
																		log("OK, we've started the cycle");
																#endif
		if(m_Protocols.find(vRow[0])==m_Protocols.end()) // next session
			{
			m_Protocols[vRow[0]] = SProtocolData(vRow[0],m_log_format(vRow[3],vRow[1],vRow[4],vRow[2],vRow[6]),stol(vRow[2]));
																#if defined FULL_DEBUG
																	debug("m_Protocols.push_back(SProtocolData("+vRow[0]+","+m_log_format(vRow[3],vRow[1],vRow[4],vRow[2],vRow[6])+"));");
																#endif		

																#if defined LOG
																	if(m_bEnableLog)
																		log("m_Protocols.push_back(SProtocolData("+vRow[0]+","+m_log_format(vRow[3],vRow[1],vRow[4],vRow[2],vRow[6])+"));");
																#endif
			}
		else //continue process this session
			{
																#if defined FULL_DEBUG
																	debug("m_Protocols["+ltos(m_Protocols.size()-1)+"].lLength+="+vRow[2]);
																	debug("m_Protocols["+ltos(m_Protocols.size()-1)+"].sData+="+m_log_format(vRow[3],vRow[1],vRow[4],vRow[2],vRow[6]));
																#endif		
																#if defined LOG
																	if(m_bEnableLog)
																	{log("m_Protocols["+ltos(m_Protocols.size()-1)+"].lLength+="+vRow[2]);
																	log("m_Protocols["+ltos(m_Protocols.size()-1)+"].sData+="+m_log_format(vRow[3],vRow[1],vRow[4],vRow[2],vRow[6]));}
																#endif
			if(m_Protocols.find(vRow[0])!=m_Protocols.end()){
				m_Protocols[vRow[0]].lLength+=stol(vRow[2]);
				m_Protocols[vRow[0]].sData+=m_log_format(vRow[3],vRow[1],vRow[4],vRow[2],vRow[6]);
				}
																#if defined FULL_DEBUG
																			else
																				debug(vRow[0]+" not found!");
																#endif
			}
																#if defined FULL_DEBUG
																//print_r_proto(m_Protocols);
																#endif		
		}

																#if defined FULL_DEBUG
																//print_r_proto(m_Protocols);
																#endif		
	for(std::map<std::string,SProtocolData>::iterator it(m_Protocols.begin());it!=m_Protocols.end();++it) //store data to database
		{
																#if defined FULL_DEBUG
																			debug("insert into protocols(unique_id,data,length) values('"+(*it).second.unique_id+"','"+(*it).second.sData+"','"+ltos((*it).second.lLength)+"');");
																#endif		

																#if defined LOG
																	if(m_bEnableLog)
																		;//log("insert into protocols(unique_id,data,length) values('"+(*it).second.unique_id+"','"+(*it).second.sData+"','"+ltos((*it).second.lLength)+"');");
																#endif
		//pMySQL->Query("select IF((select count(*) from `protocols` where unique_id = '"+(*it).second.unique_id+"')>0,1,0);");
		///std::vector<std::string> vRow;
		///pMySQL->FetchRow(vRow);
		//if(vRow[0]=="0")			
			pMySQL->Query("insert into protocols(unique_id,data,length) values('"+(*it).second.unique_id+"','"+(*it).second.sData+"','"+ltos((*it).second.lLength)+"');");
		//else
		//	pMySQL->Query("update protocols set data=CONCAT(data,'"+(*it).second.sData+"'), length = length + "+ltos((*it).second.lLength)+" where unique_id='"+(*it).second.unique_id+"';");			
		}

	}

void PopularityRefresh()
	{
		for(std::map<std::string,SUrlStatistic>::iterator it(m_Urlstats.begin());it!=m_Urlstats.end();++it) //store data to database
		{
		pMySQL->Query("select IF((select count(*) from `url_popularity` where url = '"+(*it).second.url+"' and uid = "+itos((*it).second.uid)+" and `year`=YEAR(CURDATE()) and `month`=MONTH(CURDATE()))>0,1,0);");
		std::vector<std::string> vRow;
		pMySQL->FetchRow(vRow);
		std::string cid("0");

		// AUTOMATIC CATEGORY DEFINITION
		//cid = this->get_cid_of_url((*it).second.url);
		//														#if defined DEBUG_CID
		//																std::cout<<"cid = "<<cid<<std::endl;
		//														#endif

		if(vRow[0]=="0")
			pMySQL->Query("insert into `url_popularity`(url,uid,count,length,cid,year,month) values('"+(*it).second.url+"',"+itos((*it).second.uid)+","+ltos((*it).second.iCount)+","+ltos((*it).second.lLength)+","+cid+",YEAR(CURDATE()),MONTH(CURDATE()));");
		else
			if(vRow[0]=="1")
				pMySQL->Query("update `url_popularity` set count = count + "+itos((*it).second.iCount)+", length = length + "+ltos((*it).second.lLength)+" where uid = "+itos((*it).second.uid)+" and url='"+(*it).second.url+"';");

		str_t ip = ltos(conversions::ip2value((*it).second.ip));
		pMySQL->Query("select ctry from `ip2country` where sip<"+ip+" and eip>"+ip);
		pMySQL->FetchRow(vRow);
		str_t ctry = vRow[0];
		pMySQL->Query("select IF((select count(*) from `ctry_popularity` where ctry = '"+ctry+"' and uid = "+itos((*it).second.uid)+" and `year`=YEAR(CURDATE()) and `month`=MONTH(CURDATE()))>0,1,0);");
		pMySQL->FetchRow(vRow);

		if(vRow[0]=="0")
			pMySQL->Query("insert into `ctry_popularity`(ctry,count,length,uid,year,month) values('"+ctry+"',"+ltos((*it).second.iCount)+","+ltos((*it).second.lLength)+","+itos((*it).second.uid)+",YEAR(CURDATE()),MONTH(CURDATE()));");
		else
			if(vRow[0]=="1")
				pMySQL->Query("update `ctry_popularity` set count = count + "+itos((*it).second.iCount)+", length = length + "+ltos((*it).second.lLength)+" where ctry = '"+ctry+"' and uid = "+itos((*it).second.uid)+" and month=MONTH(CURDATE()) and year=YEAR(CURDATE());");
		
		}

	}

// --
void ClearLogUrlTable()
	{
	pMySQL->Query("delete from url_log;");
#if defined FULL_DEBUG
debug("ClearLogUrlTable();");
#endif	
	}
};


void cadbis_show_help()
{
	std::cout<<"\
CADBiS URL Aggregator v.1.0\n\
	Sample:	\n\
		cadbisaggr [-a][-c][-p][-m][-l]\n\
	Use following parameters:\n\
		-a - Aggregate data from url_log table\n\
		-c - Clear url_log table when done\n\
		-p - Popularity refresh\n\
		-m - Message when done\n\
		-l - Enable log file\n\
";
}

//-------------------------------------------------------------------

int main(int argc, char* argv[])
{
bool bClearLogs = false, bAggregate = false, bMessage = false, bPopular = false, bLog = false;
CCADBiSAggregator *CADBiS = 0;


if(argc<=1)
{cadbis_show_help();return 0;}

for(size_t i(1);i<argc;++i)
	{
		if(strcmp(argv[i],"-a")==0)
			bAggregate = true;
		if(strcmp(argv[i],"-c")==0)		
			bClearLogs = true;
		if(strcmp(argv[i],"-p")==0)		
			bPopular = true;		
		if(strcmp(argv[i],"-m")==0)		
			bMessage = true;
		if(strcmp(argv[i],"-l")==0)		
			bLog = true;

	}
if(bAggregate || bClearLogs)
	CADBiS = new CCADBiSAggregator(bLog);

if(bAggregate){
	CADBiS->Aggregate();
	if(bMessage)	
	std::cout<<"CADBiS aggregator: aggregated!"<<std::endl;
#if defined LOG
	if(bLog)
		log("CADBiS aggregator: aggregated!");
#endif
	}
if(bPopular && bAggregate)
{
	if(CADBiS)
		CADBiS->PopularityRefresh();
	if(bMessage)	
		std::cout<<"CADBiS aggregator: popularity refreshed!"<<std::endl;	
#if defined LOG
	if(bLog)
		log("CADBiS aggregator: popularity refreshed!");
#endif
}
if(bClearLogs)
	{
	if(CADBiS)CADBiS->ClearLogUrlTable();
if(bMessage)	
	std::cout<<"CADBiS aggregator: logs erased!"<<std::endl;	
#if defined LOG
	if(bLog)
		log("CADBiS aggregator: logs erased!");
#endif	
	}
if(bMessage)
	std::cout<<"CADBiS aggregator: run completed!"<<std::endl;
#if defined LOG
	if(bLog)
		log("CADBiS aggregator: run completed!");
#endif	
if(CADBiS){delete CADBiS; CADBiS=0;}
return 0;
}
