#include <sys/types.h>
#include <sys/socket.h>
#include <stdio.h>
#include <errno.h>
#include <string.h>
#include <fstream>
#include <netinet/in.h>
#include <netdb.h>
#include <arpa/inet.h> 
#include <unistd.h> 
#include <time.h>
#include <memory>
#include <string>
#include <stdlib.h>
#include <regex.h>
//---------------------------------------------------------------------------
int time()
{
  time_t t1;
  (void) time(&t1);
   return (int)t1;
}
//---------------------------------------------------------------------------
std::string itos(int value)
{
 char tmp[255];
 sprintf(tmp,"%d",value);
 std::string str=tmp;
 return str;
}
//---------------------------------------------------------------------------
int stoi(const std::string & str)
{
  return atoi(str.c_str());
}
//---------------------------------------------------------------------------
const size_t MAX_STR_LEN(255);
std::string LOG_FILE;
//---------------------------------------------------------------------------
std::string get_file(const std::string & filename)
{
std::string res;
std::ifstream inf(filename.c_str());
char buf[256];
 while(inf.getline(buf,MAX_STR_LEN))res+=std::string(buf)+"\r\n";
inf.close();
return res;
}
//---------------------------------------------------------------------------
int send_str_to(char * ip, char * port, char * str)
 {
  //std::string res=get_file(LOG_FILE);
  std::string res="";
  std::ofstream of(LOG_FILE.c_str());
  res+="------------------------\r\n";
  res+=std::string("ip: ")+ip+std::string("\r\n");
  res+=std::string("port: ")+port+std::string("\r\n");
  res+=std::string("str: ")+str+std::string("\r\n");
  of<<res;
  of.close();
 	int client, con;
	struct sockaddr_in a;
	struct sockaddr* b;
	int length;
	printf("creating socket...\n");
	//res+=std::string("creating socket... ")+std::string("\r\n");
	client=socket(AF_INET, SOCK_STREAM,0);
	printf("created...(%d)\n",client);
	//res+=std::string("created... ")+std::string("\r\n");
	if(-1 == client)return 0;
	printf("cast from sockaddr_in to sockaddr *...\n");
	//res+=std::string("cast sockaddr... ")+std::string("\r\n");
	b = (struct sockaddr*)&a;
	length = sizeof(a);
	printf("zeroing memory for sockaddr_in...\n");
	//res+=std::string("zero memory... ")+std::string("\r\n");
	bzero ((char*)&a,sizeof(a));
	a.sin_family = AF_INET;
	printf("sin_port=%s\n",port);
	//res+=std::string("sin port... ")+std::string("\r\n");
	a.sin_port = atoi(port);
	printf("converting ip adress(%s) to inet_addr...\n",ip);
	//res+=std::string("conv ip... ")+std::string("\r\n");
	a.sin_addr.s_addr = inet_addr(ip);
	printf("converted(%d)...\n",a);
	//res+=std::string("converted... ")+std::string("\r\n");
	printf("connecting to %s...\n",ip);
	con = connect(client, b, length);
	//res+=std::string("connected... ")+std::string("\r\n");
	printf("connected(%d)...\n",con);
	if (-1 == con)return 0;
	printf("sending string \"%s\"...\n",str);
    send(client,str,strlen(str),0);
    //res+=std::string("string sent... ")+std::string("\r\n");
    printf("ok. releasing socket...\n");
    shutdown(client,0);
    //of<<res;
    //of.close();
 return 0;
 }

int main(int argc, char *argv[])
{
LOG_FILE="/home/smecsia/send2ip.logs/"+itos(time());
if(argc<3)exit(1);
exit(send_str_to(argv[1],"12001",argv[2]));
}