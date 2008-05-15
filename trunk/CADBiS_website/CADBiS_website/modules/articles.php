<?php
//----------------------------------------------------------------------//
//    TITLE: PHP Class for read/edit/delete/comment articles            //
//    MAKER: SMStudio                                                   //
//    specially for SMS CMS (SM & Shurup Content Management System)     //
//----------------------------------------------------------------------//

$MDL_TITLE="Articles";
$MDL_DESCR="For articles";
$MDL_UNIQUEID="articles";
$MDL_MAKER="SMStudio";


if(!$_GETINFO)
{


/////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////

//.............................................................//
//..........................CLASSES............................//
//.............................................................//

//********************* class for projects ****************************//
///////////////////////////////////////////////////////////////////////// 
if(!class_exists("CArticles"))
{class CArticles
{
 var $top_file; //topics file
 var $data_dir;  //data dir
 var $cmnts_dir;  //dir of info
 var $chr1;     //separator level1
 var $chr2;     //separator level2
 var $chr3;     //separator level3

 //-----------------------------------------------------------------------

 function CArticles($art_dir,$cmnts_dir,$top_file)
  {
  $this->data_dir=$art_dir;
  $this->top_file=$top_file;
  $this->cmnts_dir=$cmnts_dir;
  }
  
  //-----------------------------------------------------------------------
  
 function SetSeparators($chr1,$chr2,$chr3) 
  {
  $this->chr1=$chr1;
  $this->chr2=$chr2;
  $this->chr3=$chr3;
  }
  
  //-----------------------------------------------------------------------
  
  function GetTopicData($top_i)
  {
  include($this->top_file);
  return $topics[$top_i];
  }
 
  //-----------------------------------------------------------------------
  
  function GetTopicDataById($top_id)
  {
  include($this->top_file);
  for($i=0;$i<count($topics);$i++)
    if($topics[$i]["id"]==$top_id)return $topics[$i];  
  }
     
  //-----------------------------------------------------------------------
   
  function GetTopicsCount()
  {
  include($this->top_file);
  return count($topics);
  }
    
    
  //-----------------------------------------------------------------------
   
  function GetTopics()
  {
  include($this->top_file);
  return $topics;
  }    
  //-----------------------------------------------------------------------
  
  function SaveTopics($data)
  {
  $str="<?php\r\n";
  for($i=0;$i<count($data);$i++)
    {     
    $id=$i;
    $str.="\$topics[$id][\"id\"]=\"".$data[$i]["id"]."\";\r\n";
    $str.="\$topics[$id][\"title\"]=\"".$data[$i]["title"]."\";\r\n";
    $str.="\$topics[$id][\"author\"]=\"".$data[$i]["author"]."\";\r\n";
    $str.="\$topics[$id][\"descr\"]=\"".$data[$i]["descr"]."\";\r\n";
    for($j=0;$j<count($data[$i]["articles"]);++$j)
      $str.="\t\$topics[$id][\"articles\"][$j]=\"".$data[$i]["articles"][$j]."\";\r\n";
    }
  $str.="?>";
  $fp=fopen($this->top_file,"w+"); 
  fwrite($fp,$str);
  fclose($fp); 
  }
  
  //-----------------------------------------------------------------------
  
  function DeleteTopic($top_i)
  {
  include($this->top_file);
  $str="<?php\r\n";
  for($i=0;$i<count($topics);$i++)
    {    
    if($i<$top_i)$id=$i;else $id=$i-1;
    if($i!=$top_i)
      {
      $str.="\$topics[$id][\"id\"]=\"".$topics[$i]["id"]."\";\r\n";
      $str.="\$topics[$id][\"title\"]=\"".$topics[$i]["title"]."\";\r\n";
      $str.="\$topics[$id][\"author\"]=\"".$topics[$i]["author"]."\";\r\n";
      $str.="\$topics[$id][\"descr\"]=\"".$topics[$i]["descr"]."\";\r\n";
      for($j=0;$j<count($data[$i]["articles"]);++$j)
       $str.="\t\$topics[$id][\"articles\"][]=\"".$topics[$i]["articles"][$j]."\";\r\n";
      }
    }
  $str.="?>";
  $fp=fopen($this->top_file,"w+"); 
  fwrite($fp,$str);
  fclose($fp);   
  }
  
    
  //-----------------------------------------------------------------------
  
  function GetArticlesList($top_i)
  {
  include($this->top_file);  
  return $topics[$top_i]["articles"];
  }
  
  //-----------------------------------------------------------------------
  
  function GetNLastArticles($n)
  {
  include($this->top_file);
  $all=NULL;
  $k=0;  
  for($i=0;$i<count($topics);$i++)
    for($j=0;$j<count($topics[$i]["articles"]);++$j) 
     {
     $all[$k]["id"]=$topics[$i]["articles"][$j];
     $all[$k]["tid"]=$topics[$i]["id"];
     $all[$k++]["ttitle"]=$topics[$i]["title"];
     }  
  if(!count($all))return NULL;
  rsort($all);
  $all=array_values($all);
  $res=NULL;
  for($i=0;$i<count($all) && $i<$n;++$i)
     {
     echo($all[$i]["title"]);
     $data=$this->GetArticleData($all[$i]["id"]);
     $res[$i]=$all[$i];
     $res[$i]["descr"]=$data["descr"];
     $res[$i]["date"]=$data["date"];
     $res[$i]["author"]=$data["author"];     
     $res[$i]["title"]=$data["title"];     
     }
  return $res;
  }  
  
  //-----------------------------------------------------------------------
  
  function GetCommentData($id,$cid)
  {
  $cmts=$this->GetComments($id);
  for($i=0;$i<count($cmts);++$i)
    {
    if($cmts[$i]["id"]==$cid)return $cmts[$i];
    }
  }  
  
  //-----------------------------------------------------------------------
  
  function GetCommentsCount($art_id)
  {
  $file=get_file($this->cmnts_dir."/".$art_id);
  $data=explode($this->chr1,$file);
  if($data[1]=="")return 0;
  $data=explode($this->chr2,$data[1]);
  return count($data);
  }
  
  //-----------------------------------------------------------------------
  
  function GetComments($id)
  {
  if($this->GetCommentsCount($id)==0)return NULL;
  $file=get_file($this->cmnts_dir."/".$id);
  $data=array();
  $data=explode($this->chr1,$file);
  if($data[1]=="")return;
  $data=explode($this->chr2,$data[1]);
  $res=array(array());
  $k=0;
  for($i=0;$i<count($data);++$i)
   {
   if(!$data[$i])continue;
    $cdata=explode($this->chr3,$data[$i]);
    $res[$k]["id"]=     $cdata[0];
    $res[$k]["nick"]=   $cdata[1];
    $res[$k]["date"]=   $cdata[2];
    $res[$k]["email"]=  $cdata[3];
    $res[$k]["url"]=    $cdata[4];
    $res[$k]["ip"]=     $cdata[5];
    $res[$k++]["text"]=   $cdata[6];  
   }                              
   return $res;
  }
  
  //-----------------------------------------------------------------------
  
  function DeleteComment($id,$cid)
  {
  $file=get_file($this->cmnts_dir."/".$id);
  $data=array();
  $data=explode($this->chr1,$file);
  $res=$data[0].$this->chr1;
  $cms=$this->GetComments($id);
  $ok=false;
  for($i=0;$i<count($cms);++$i)
    {
    if($cms[$i]["id"]!=$cid)
     {
     $res.= $cms[$i]["id"].$this->chr3.
            $cms[$i]["nick"].$this->chr3.
            $cms[$i]["date"].$this->chr3.
            $cms[$i]["email"].$this->chr3.
            $cms[$i]["url"].$this->chr3.
            $cms[$i]["ip"].$this->chr3.
            $cms[$i]["text"];
     if($i<count($cms)-1)
      $res.=$this->chr2;
     }
     else $ok=true;
    }   
  $fp=fopen($this->cmnts_dir."/".$id,"w+");
  if(!$fp)return false;
  if(!fwrite($fp,$res))return false;
  fclose($fp);
  return $ok;
  }
  
  //-----------------------------------------------------------------------
  
  function SetComment($id, $cid, $nick, $email, $url, $text)
  {
  $cms=$this->GetCommentData($id,$cid);
  $cms["nick"]=$nick;
  $cms["email"]=$email;
  $cms["url"]=$url;
  $cms["text"]=$text;
  $this->SetCommentData($id,$cid,$cms);
  }
  
  //-----------------------------------------------------------------------  
    
  function SetCommentData($id, $cid, $udata)
  {
  $file=get_file($this->cmnts_dir."/".$id);
  $data=array();
  $data=explode($this->chr1,$file);
  $res=$data[0].$this->chr1;
  $cms=$this->GetComments($id);
  $ok=false;
  for($i=0;$i<count($cms);++$i)
    {
    if($cms[$i]["id"]!=$cid)
     {
     $res.= $cms[$i]["id"].$this->chr3.
            $cms[$i]["nick"].$this->chr3.
            $cms[$i]["date"].$this->chr3.
            $cms[$i]["email"].$this->chr3.
            $cms[$i]["url"].$this->chr3.
            $cms[$i]["ip"].$this->chr3.
            $cms[$i]["text"];
     }
     else
     {
     $res.= $udata["id"].$this->chr3.
            $udata["nick"].$this->chr3.
            $udata["date"].$this->chr3.
            $udata["email"].$this->chr3.
            $udata["url"].$this->chr3.
            $udata["ip"].$this->chr3.
            $udata["text"];
      $ok=true;
      }
     if($i<count($cms)-1)
      $res.=$this->chr2;
    }
    
  //die("<plaintext>$res");
  $fp=fopen($this->cmnts_dir."/".$id,"w+");
  if(!$fp)return false;
  if(!fwrite($fp,$res))return false;
  fclose($fp);
  return $ok;
  }
  
  //-----------------------------------------------------------------------
  
  function SetArticleData($id,$data)
  {
  $file=get_file($this->cmnts_dir."/".$id);
  $odata=array();
  $odata=explode($this->chr1,$file);
  $res=$this->chr1.$odata[1];
  $res=     $data["title"].$this->chr2.
            $data["author"].$this->chr2.
            $data["date"].$this->chr2.
            $data["descr"].$res;
  $fp=fopen($this->cmnts_dir."/".$id,"w+");
  if(!$fp)return false;
  if(!fwrite($fp,$res))return false;  
  fclose($fp);
  $fp=fopen($this->data_dir."/".$id,"w+");
  fwrite($fp,$data["text"]);
  fclose($fp);    
  return true;
  }
     
  //-----------------------------------------------------------------------
  
  function SetArticleText($id,$text)
  {
  $fp=fopen($this->data_dir."/".$id,"w+");
  fwrite($fp,$text);
  fclose($fp);  
  }     
             
  //-----------------------------------------------------------------------
  
  function DeleteArticle($id)
  { 
  include($this->top_file); 
  $data=$this->GetArticleData($id);
  $str="<?php\r\n";
  for($i=0;$i<count($topics);$i++)
    {    
      $str.="\$topics[$i][\"id\"]=\"".$topics[$i]["id"]."\";\r\n";
      $str.="\$topics[$i][\"title\"]=\"".$topics[$i]["title"]."\";\r\n";
      $str.="\$topics[$i][\"author\"]=\"".$topics[$i]["author"]."\";\r\n";
      $str.="\$topics[$i][\"descr\"]=\"".$topics[$i]["descr"]."\";\r\n";
      $k=0;     
      for($j=0;$j<count($topics[$i]["articles"]);++$j)
       {
       if($topics[$i]["articles"][$j]!=$id)
        {$str.="\t\$topics[$i][\"articles\"][$k]=\"".$topics[$i]["articles"][$j]."\";\r\n";$k++;}
       }               
    }
  $str.="?>";
  
  
  $fp=fopen($this->top_file,"w+"); 
  fwrite($fp,$str);
  fclose($fp);    
  unlink($this->data_dir."/".$id);
  unlink($this->cmnts_dir."/".$id);
  }
  
  //-----------------------------------------------------------------------
  
  function MoveArticle($id,$newtopic)
  { 
  include($this->top_file); 
  $data=$this->GetArticleData($id);
  $str="<?php\r\n";
  for($i=0;$i<count($topics);$i++)
    {    
      $str.="\$topics[$i][\"id\"]=\"".$topics[$i]["id"]."\";\r\n";
      $str.="\$topics[$i][\"title\"]=\"".$topics[$i]["title"]."\";\r\n";
      $str.="\$topics[$i][\"author\"]=\"".$topics[$i]["author"]."\";\r\n";
      $str.="\$topics[$i][\"descr\"]=\"".$topics[$i]["descr"]."\";\r\n";
      $k=0;     
      for($j=0;$j<count($topics[$i]["articles"]);++$j)
       {
        if($topics[$i]["articles"][$j]!=$id)
        {$str.="\t\$topics[$i][\"articles\"][$k]=\"".$topics[$i]["articles"][$j]."\";\r\n";$k++;}
       }
       if($topics[$i]["id"]==$newtopic)  
        {$str.="\t\$topics[$i][\"articles\"][$k]=\"".$id."\";\r\n";$k++;}                     
    }
  $str.="?>";  
  $fp=fopen($this->top_file,"w+"); 
  fwrite($fp,$str);
  fclose($fp);    
  }  
  
  
  //-----------------------------------------------------------------------
  
  function AddArticleWithData($top_id,$data)
  {
  $fp=fopen($this->cmnts_dir."/".$data["id"],"w+");
  if(!$fp)return false;
  $res=     $data["title"].$this->chr2.
            $data["author"].$this->chr2.
            $data["date"].$this->chr2.
            $data["descr"].$this->chr1;
  if(!fwrite($fp,$res))return false;  
  fclose($fp);
    $fp=fopen($this->data_dir."/".$data["id"],"w+");
    if(!$fp)return false;
    fwrite($fp,$data["text"]);
    fclose($fp);
   include($this->top_file);   
  $str="<?php\r\n";
  for($i=0;$i<count($topics);$i++)
    {    
      $str.="\$topics[$i][\"id\"]=\"".$topics[$i]["id"]."\";\r\n";
      $str.="\$topics[$i][\"title\"]=\"".$topics[$i]["title"]."\";\r\n";
      $str.="\$topics[$i][\"author\"]=\"".$topics[$i]["author"]."\";\r\n";
      $str.="\$topics[$i][\"descr\"]=\"".$topics[$i]["descr"]."\";\r\n";
      for($j=0;$j<count($topics[$i]["articles"]);++$j)
       $str.="\t\$topics[$i][\"articles\"][$j]=\"".$topics[$i]["articles"][$j]."\";\r\n";
      if($topics[$i]["id"]==$top_id)
       $str.="\t\$topics[$i][\"articles\"][".count($topics[$i]["articles"])."]=\"".$data["id"]."\";\r\n";                   
    }
  $str.="?>";
  $fp=fopen($this->top_file,"w+"); 
  fwrite($fp,$str);
  fclose($fp);
  return true;  
  }
  
  //-----------------------------------------------------------------------  
  
  function AddArticle($top_id,$title,$author,$desc,$text)
  {
  $data=array();
  $data["id"]=time();
  $data["title"]=$title;
  $data["author"]=$author;
  $data["date"]=time();
  $data["descr"]=$desc;
  $data["text"]=$text;
  $this->AddArticleWithData($top_id,$data); 
  return $data["id"]; 
  }
  
  //-----------------------------------------------------------------------
  
  function AddCommentWithData($id,$cms)
  {
  $file=get_file($this->cmnts_dir."/".$id);
  $data=array();
  $data=explode($this->chr1,$file);
  $res=$data[0].$this->chr1.$data[1];
  if($this->GetCommentsCount($id)>0)$res.=$this->chr2;  
  $res.= $cms["id"].$this->chr3.
         $cms["nick"].$this->chr3.
         $cms["date"].$this->chr3.
         $cms["email"].$this->chr3.
         $cms["url"].$this->chr3.
         $cms["ip"].$this->chr3.
         $cms["text"];         
  //����� �� �������
  //if(strlen($cms["text"])<$ART_CONF["min_comment_len"])
  //return;
  $fp=fopen($this->cmnts_dir."/".$id,"w+");
  if(!$fp)return false;
  if(!fwrite($fp,$res))return false;
  fclose($fp);
  }
  
  //-----------------------------------------------------------------------
  
  function AddComment($id,$nick,$email,$url,$text)
  {
  $data=array();
  $data["id"]=time();
  $data["nick"]=$nick;
  $data["date"]=time();
  $data["email"]=$email;
  $data["url"]=$url;
  $data["ip"]=get_ip_adress();
  $data["text"]=$text; 
  $this->AddCommentWithData($id,$data);
  } 
  
  //-----------------------------------------------------------------------
    
  function GetArticlesCount($top_id)
  {
   include($this->top_file); 
    for($i=0;$i<$topics;++$i)
     {
      if($topics[$i]["id"]==$top_id) return count($topics[$i]["articles"]);
     }  
  }

  //-----------------------------------------------------------------------
  
  function GetArticles($top_id)
  {
   include($this->top_file); 
    for($i=0;$i<$topics;++$i)
     {
      if($topics[$i]["id"]==$top_id) return $topics[$i]["articles"];
     }  
  }
 
  //----------------------------------------------------------------------- 
  
  function GetArticleData($id)
  {
  $file=get_file($this->cmnts_dir."/".$id);
  $data=array();
  $data=explode($this->chr1,$file);
  $data_a=explode($this->chr2,$data[0]);
  $res=array();
  $res["title"]= $data_a[0];
  $res["author"]=$data_a[1];
  $res["date"]=  $data_a[2];
  $res["descr"]= $data_a[3];
  $datar=explode($this->chr2,$data[1]);
  $res["ccount"]=($data[1])?count($datar):0;
  return $res;
  } 

  //-----------------------------------------------------------------------
 
  function GetArticleText($id)
  {
  return get_file($this->data_dir."/".$id);
  }
};}
/////////////////////////////////////////////////////////////////////////


  if($_INSTALL)
  {
  //��������� ������
  }
  elseif($_UNINSTALL)
  {
  //�������� ������
  }                 
  elseif($_CONFIGURE)
  {
  //��������� ������
  } 
  elseif($_MENU)
  {
  //���� ��������������
  }  
//================================================
// ������ ��� ��������������
//================================================
  elseif($_ADMIN && check_auth() && $CURRENT_USER["level"]>=5)
  {
    if(!file_exists(SK_DIR."/articles_admin.php"))
     {
     ?>
     <div align=center><b>������. �����������������</b></div>
     <?
     include "config.php";
     global $CURRENT_USER,$MDL;
     $ART=new CArticles($DIRS["arts_data"],$DIRS["arts_comments"],$DIRS["arts_list"]);
     $ART->SetSeparators($GV["sep1"],$GV["sep2"],$GV["sep3"]);
     $tcnt=$ART->GetTopicsCount();
     global $FLTR;
        switch($a)
         {
         //.............................//
         //   �������� ������           //
         //.............................//
         case "delete":
           if(!isset($topic))$topic="";
           if(!isset($page) || $page<0)$page=1; 
           $data=$ART->GetArticleData($id);
           $ud=get_user_data($data["author"]); 
           if($ud["level"]>=$CURRENT_USER["level"] && $ud["id"]!=$CURRENT_USER["id"])
             {
             $page="403";
             $MDL->LoadModule("error",false);
             ?>
             <div align=center><a href="<? OUT("?p=$p&act=$act&a=view&topic=$topic&page=$page") ?>">�����</a><br></div>         
             <?              
             return;   
             }
         
           if(isset($mod) && $mod=="sure")
            {
            $ART->DeleteArticle($id);
            
            OUT("�������!");            
            }
            else
            {          
             ?>
             <b><font color=red>��������!</font></b> �� ����������� ������� ������. ���� �� �������, ��� ������ ��� ������� -
             ������� "� ������". � ��������� ������ �������� "�����". <br><br><br>
             
             <div align=center><a href="<? OUT("?p=$p&act=$act&a=delete&topic=$topic&page=$page&id=$id&mod=sure") ?>">� ������, ��� ���� ������� "<? OUT($data["title"]) ?>"</a><br></div><br><br>
             <?
            }
         ?>
          <div align=center><a href="<? OUT("?p=$p&act=$act&a=view&topic=$topic&page=$page") ?>">�����</a><br></div>         
         <? 
         break;
         
         //.............................//
         //   �������� ������������
         //.............................//
         case "comments":
           if(!isset($topic))$topic="";
           if(!isset($page) || $page<0)$page=1;         
           if(!isset($mod)) $mod="";
           
           
           if($mod=="delete")
            {
             $data=$ART->GetCommentData($id,$cid);
              $uid=$data["nick"];
              $cid=$data["id"];
              if(is_user_exists($uid))
                {
                $udata=get_user_data($uid);
                $MDL->Load("users");
                $USR=new CUsers($DIRS["users_data"],$DIRS["users_list"],$DIRS["users_private"],$DIRS["users_groups"],$DIRS["users_online"]);
                $USR->SetSeparators($GV["sep1"],$GV["sep2"]);            
                if($USR->GetUserLevel($udata["id"])<$CURRENT_USER["level"] || $udata["id"]==$CURRENT_USER["id"])
                $ART->DeleteComment($id,$cid);
                }
                else
                {
                $ART->DeleteComment($id,$cid);
                }
            }
           
           if($mod=="choose")
            {
            include "config.php";
            if($MDL->IsModuleExists("users"))
              {
              $MDL->Load("users");
              $USR=new CUsers($DIRS["users_data"],$DIRS["users_list"],$DIRS["users_private"],$DIRS["users_groups"],$DIRS["users_online"]);
              $USR->SetSeparators($GV["sep1"],$GV["sep2"]);
              }             
              
            $cmts=$ART->GetComments($id);
            }                                      
            
            
            if($mod=="choose" && isset($modc) && $modc=="delete")
             {
             for($i=0;$i<count($ids);++$i)
               for($j=0;$j<count($cmts);++$j)
                 if($ids[$i]==$cmts[$j]["id"])
                   {
                   $uid=$cmts[$j]["nick"];
                   if(is_user_exists($uid))
                     {
                     $udata=get_user_data($uid);
                     if($USR->GetUserLevel($udata["id"])<$CURRENT_USER["level"] || $udata["id"]==$CURRENT_USER["id"])
                        $ART->DeleteComment($id,$ids[$i]);
                     }
                     else
                       $ART->DeleteComment($id,$ids[$i]);
                   }
             }

             if($mod=="choose" && (isset($modc) && $modc=="save"))
             {
             for($i=0;$i<count($ids);++$i)
               for($j=0;$j<count($cmts);++$j)
                 if($ids[$i]==$cmts[$j]["id"])
                   {
                   $uid=$cmts[$j]["nick"];
                    $text=$FLTR->DirectProcessText($texts[$i],1,1);
                    $url=$FLTR->DirectProcessString($urls[$i],1);
                    $email=$FLTR->DirectProcessString($emails[$i],1);
                    $nick=$FLTR->DirectProcessString($nicks[$i],1);
                   if(is_user_exists($uid))
                     {
                     $udata=get_user_data($uid);
                     if($USR->GetUserLevel($udata["id"])<$CURRENT_USER["level"] || $udata["id"]==$CURRENT_USER["id"])
                       $ART->SetComment($id,$ids[$i],$cmts[$i]["nick"],$cmts[$i]["email"],$cmts[$i]["url"],$text);
                     }
                     else
                       $ART->SetComment($id,$ids[$i],$nick,$email,$url,$text);
                   }

             
             }
             
             if($mod=="choose" && (!isset($modc) || ($modc!="save" && $modc!="delete")))
             {    
             ?>
             <form action="<? OUT("?p=$p&act=$act&a=$a&topic=$topic&mod=choose&id=$id") ?>" method=post>
             <? 
             for($i=0;$i<count($cmts);++$i)
               for($j=0;$j<count($selected);++$j)
                 if($cmts[$i]["id"]==$selected[$j])
                   {
                   $uid=$cmts[$i]["nick"];
                   if(is_user_exists($uid))
                     {                     
                     $udata=get_user_data($uid);
                     $nickselect=$udata["nick"];
                     $emailselect=$udata["email"];
                     $urlselect=$udata["url"];
                     $text=$FLTR->ReverseProcessText($cmts[$i]["text"]);
                     if($USR->GetUserLevel($udata["id"])<$CURRENT_USER["level"] || $udata["id"]==$CURRENT_USER["id"])                                          
                       $textselect="<textarea style=\"width:100%\" rows=5 name=texts[]>".$text."</textarea>";
                     else
                       $textselect=$cmts[$i]["text"]; 
                     }
                     else
                     {
                     $nick=$cmts[$i]["nick"];
                     $email=$cmts[$i]["email"];
                     $url=$cmts[$i]["url"];
                     $text=$FLTR->ReverseProcessText($cmts[$i]["text"]);
                     $nickselect="<input type=text style=\"width:100%\" name=nicks[] value=\"".$nick."\">";
                     $emailselect="<input type=text style=\"width:100%\"name=emails[] value=\"".$email."\">";
                     $urlselect="<input type=text style=\"width:100%\"name=urls[] value=\"".$url."\">";
                     $textselect="<textarea style=\"width:100%\" rows=5 name=texts[]>".$text."</textarea>";
                     }
                   ?>  
                   <table width=100%><tr><td width=100%>
                   <input type=hidden name=ids[] value="<? OUT($selected[$j]) ?>">
                   <table width=100% class=tbl1>
                    <tr>
                      <td width=50%>
                       Date:
                      </td>
                      <td width=50%>
                      <? OUT(norm_date($cmts[$i]["date"])) ?>
                      </td>  
                    </tr>                     
                    <tr>
                      <td width=50%>
                       Nick:
                      </td>
                      <td width=50%>
                      <? OUT($nickselect) ?>
                      </td>  
                    </tr>      
                    <tr>              
                      <td width=50%>
                       Email:
                      </td>
                      <td width=50%>
                      <? OUT($emailselect) ?>
                      </td> 
                    </tr>
                    <tr>                    
                      <td width=50%>
                       URL:
                      </td>
                      <td width=50%>
                      <? OUT($urlselect) ?>                      
                      </td>
                    </tr>
                   </table>
                   </td></tr>
                   <tr><td>
                   <? OUT($textselect) ?>                   
                   </td></tr></table> 
                   <?
                   }
             ?>
             <input type=radio name=modc value="save" checked>���������<br>
             <input type=radio name=modc value="delete">�������<br>
             <div align=center><input type=submit value="���������"></div>            
             </form>
            <div align=center><a href="<? OUT("?p=$p&act=$act&a=comments&topic=$topic&id=$id&page=$page") ?>">�����</a><br></div> 
             
             <?
            }
            else
            {
            $data=$ART->GetArticleData($id);
            ?>
             ������������:<? OUT ($data["ccount"]) ?><br>        
            <div align=center><a href="<? OUT("?p=$p&act=$act&a=view&topic=$topic&page=$page") ?>">�����</a><br></div>     
            <table align=center width=100%><td>
            <div align=center><b><? OUT($data["title"]); ?></b></div><br>
            <div align=right><small><? $ud=get_user_data($data["author"]); OUT($ud["nick"]) ?>, <? OUT(norm_date($data["date"])) ?></small></div>
            <? OUT($data["descr"]) ?>
            </td></table>
            <?
            global $PDIV;        
            $cmts=$ART->GetComments($id); 
            if(count($cmts))
            {                          
            
            ?>
            <form action="<? OUT("?p=$p&act=$act&a=$a&topic=$topic&mod=choose&id=$id") ?>" method=post>
            <?   
            $pcnt=$PDIV->GetPagesCount($cmts);
            if(!isset($page) || $page<0 || $page>$pcnt)$page=1;   
            if($page!="all")$cmts=$PDIV->GetPage($cmts,$page);             
            $pagescode="";
            for($i=0;$i<$pcnt;++$i)
              {
              if($page!=$i+1)$pagescode.="<a href=\"?p=$p&act=$act&a=comments&topic=$topic&id=$id&page=".($i+1)."\">".($i+1)."</a>, ";
               else $pagescode.="".($i+1).", ";
              } 
            if($page!="all")$pagescode.="<a href=\"?p=$p&act=$act&a=comments&topic=$topic&id=$id&page=all\">���</a>";      
            ?>                                                                            
            <br>��������: <? OUT($pagescode) ?><br>  
            <?             
            for($i=0;$i<count($cmts);++$i)
              {
              $data=$cmts[$i];
              $uid=$data["nick"];
              $cid=$data["id"];
              if(is_user_exists($uid))
                {
                $udata=get_user_data($uid);
                $nick=$udata["nick"];
                $email=make_email_str($udata["email"]);
                $url=make_url_str($udata["url"]);
                $MDL->Load("users");
                $USR=new CUsers($DIRS["users_data"],$DIRS["users_list"],$DIRS["users_private"],$DIRS["users_groups"],$DIRS["users_online"]);
                $USR->SetSeparators($GV["sep1"],$GV["sep2"]);            
               }  
              else 
                {$nick=$data["nick"];$email=make_email_str($data["email"]);$url=make_url_str($data["url"]);}              
              ?>
              <table width=100% class=tbl1>
              <tr><td width=100%>
              <? OUT($nick) ?>, <? OUT(norm_date($data["date"])) ?>, <? OUT($email) ?>, <? OUT($url) ?>
              </td><tr>
              <td width=100% class=tbl1>
              <? OUT($data["text"]) ?>
              </td></tr>
              <?
              if((!is_user_exists($uid)) || ($USR->GetUserLevel($udata["id"])<$CURRENT_USER["level"] || $udata["id"]==$CURRENT_USER["id"]))
                {
                ?>  
                <tr><td class=tbl1>
                <? OUT($data["ip"]) ?>
                </td></tr>                            
                <tr><td width=100% align=right>
                <table width=100%><td align=left width=50%>
                <a href="<? OUT("?p=$p&act=$act&a=$a&mod=delete&id=$id&cid=$cid&topic=$topic") ?>">�������</a>               
                </td><td align=right width=50%>
                <input type=checkbox name=selected[] value="<? OUT($cid) ?>">�������              
                </td></table>
                <?
                }                 
                ?>                          
                </td></tr>
                </table><br>
                <?             
              }
            ?>
            ��������: <? OUT($pagescode) ?><br><br>
            <small><b>��������!</b> ��� �������� �� ������ "�������" ����������� ��������� ��� ������� �������������!</small>
            <div align=right><input type=submit value="�������"></div>
            </form>
            <?
            }
            else {OUT("<div align=center><b>��� ������������</b></div>");}
            ?>
            <div align=center><a href="<? OUT("?p=$p&act=$act&a=view&topic=$topic&page=$page") ?>">�����</a><br></div>         
            <? 
            }
         break;                  
         //.............................//
         //   �������� ����
         //.............................//
         case "view":

         if(!isset($topic))$topic="";
         if(!isset($page) || $page<0)$page=1;
         $data=$ART->GetTopicDataById($topic);
         global $PDIV;
         
         $pcnt=$PDIV->GetPagesCount($data["articles"]);
         
         $pagescode="";
         for($i=0;$i<$pcnt;++$i)
          {
          if($page!=$i+1)$pagescode.="<a href=\"?p=$p&act=$act&a=view&topic=$topic&page=".($i+1)."\">".($i+1)."</a>";
           else $pagescode.="".($i+1);
          if($i<$pcnt-1) $pagescode.=", ";
          }
         ?>         
                     
         ��������: <? OUT($pagescode); ?>
         
         
         <?
         $alist=$PDIV->GetPage($data["articles"],$page);
         

         ?>
         <table width=100%>
         <tr>
           <td class=tbl1 width=5%>�</td>
           <td class=tbl1 width=35%>��������</td>
           <td class=tbl1 width=20%>�����</td>
           <td class=tbl1 width=20%>����</td>           
           <td class=tbl1 width=20%>����������</td>    
         </tr>
         <?
         for($i=0;$i<count($alist);++$i)
          {
          $data=$ART->GetArticleData($alist[$i]);
          ?>
           <tr>
            <td class=tbl1 width=5%>
              <? OUT($i) ?>
            </td>           
            <td class=tbl1 width=35%>
              <a href="<? OUT("?p=$p&act=$act&a=add&type=edit&topic=$topic&id=".$alist[$i])?>"> <? OUT($data["title"]) ?></a>
            </td>
            <td class=tbl1 width=30%>
              <? $ud=get_user_data($data["author"]); OUT("<a href=?p=users&act=userinfo&id=".$data["author"].">".$ud["nick"]."</a>"); ?>
            </td> 
            <td class=tbl1 width=30%>
              <? OUT(norm_date($data["date"])) ?>
            </td> 
            <td class=tbl1 width=30%>
              <a href="<? OUT("?p=$p&act=$act&a=delete&topic=$topic&page=$page&id=".$alist[$i]) ?>">�������</a><br>
              <a href="<? OUT("?p=$p&act=$act&a=add&type=edit&topic=$topic&id=".$alist[$i])?>">�������������</a>              
              <a href="<? OUT("?p=$p&act=$act&a=comments&topic=$topic&id=".$alist[$i])?>">�����������</a>
            </td> 
           </tr>                       
          <?
          }
          
          ?>
          </table>
                
         ��������: <? OUT($pagescode); ?>     
         
           <br><br>
          <div align=center><a href="<? OUT("?p=$p&act=$act") ?>">�����</a></div>              
          <?  
          
         break;
         //.............................//
         //   �������� ���������
         //.............................//
         case "cat":

          if($CURRENT_USER["level"]<7)
             {
             $page="403";
             $MDL->LoadModule("error",false);
             ?>
             <div align=center><a href="<? OUT("?p=$p&act=$act") ?>">�����</a></div>          
             <?              
             return;   
             }

         if(!isset($ncnt) || $ncnt<0)$ncnt=0;
         if(isset($mod) && $mod=="save")
         {
         $topics=array(array());
         $told=$ART->GetTopics();
         for($i=0;$i<count($ids);++$i) 
           {
           $cur_i=($FLTR->DirectProcessString($is[$i],1))-1;           
           $cur_id=$FLTR->DirectProcessString($ids[$i],1);
           $cur_tit=$FLTR->DirectProcessString($tits[$i],1);
           $cur_auth=$FLTR->DirectProcessString($authors[$i],1);   
           if($MDL->IsModuleExists("users"))
             {            
             $MDL->Load("users");
             $USR=new CUsers($DIRS["users_data"],$DIRS["users_list"],$DIRS["users_private"],$DIRS["users_groups"],$DIRS["users_online"]);
             $USR->SetSeparators($GV["sep1"],$GV["sep2"]);            
             if($USR->GetUserLevel($cur_auth)>=$CURRENT_USER["level"])$cur_auth=$CURRENT_USER["id"];
             }                   
           $cur_descr=$FLTR->DirectProcessText($descrs[$i],1,1);
           if($cur_i>=0)
            for($j=0;$j<count($told);++$j)
             {
             //OUT("i=$i, j=$j) ".$told[$j]["id"]."==".$cur_id."<br>");
             if($told[$j]["id"]==$cur_id)
               {
               $topics[$cur_i]["id"]=$cur_id;
               $topics[$cur_i]["title"]=$cur_tit;
               $topics[$cur_i]["author"]=$cur_auth;
               $topics[$cur_i]["descr"]=$cur_descr;
               for($k=0;$k<count($told[$j]["articles"]);++$k)
                $topics[$cur_i]["articles"][$k]=$told[$j]["articles"][$k];
               //echo($cur_tit." | ".$cur_i."<br>");  
               }
             }              
           }
           
         for($i=0;$i<count($isnew);++$i) 
           {
           $cur_i=($FLTR->DirectProcessString($isnew[$i],1))-1;           
           $cur_id=time();
           $cur_tit=$FLTR->DirectProcessString($titsnew[$i],1);
           $cur_auth=$FLTR->DirectProcessString($authorsnew[$i],1);           
           $cur_descr=$FLTR->DirectProcessText($descrsnew[$i],1,1);
           if($MDL->IsModuleExists("users"))
             {            
             $MDL->Load("users");
             $USR=new CUsers($DIRS["users_data"],$DIRS["users_list"],$DIRS["users_private"],$DIRS["users_groups"],$DIRS["users_online"]);
             $USR->SetSeparators($GV["sep1"],$GV["sep2"]);         
             if($USR->GetUserLevel($cur_auth)>=$CURRENT_USER["level"])$cur_auth=$CURRENT_USER["id"];
             }           
           $topics[$cur_i]["id"]=$cur_id;
           $topics[$cur_i]["title"]=$cur_tit;
           $topics[$cur_i]["author"]=$cur_auth;
           $topics[$cur_i]["descr"]=$cur_descr; 
           $topics[$cur_i]["articles"]=array(); 
           //echo($cur_tit." | ".$cur_i."<br>");         
           }
             
           $ART->SaveTopics($topics);
           
           OUT("���������");
          ?>
          <div align=center><a href="<? OUT("?p=$p&act=$act&a=$a") ?>">�����</a></div>
          <?
         }
         else
         {
         ?>           
         <br><br>
         ����������������� ��������� ������. ����� �������� ������� ���������� - �������� ���������� ������
         � ������������ � ����� ��������. ��� �������� ��������� ���������� ������ ������������� ���������� �����.<br>
         <b>��������!</b> ���������� ����������� � ����������� ��������! ������� ����� �� ���� ����������!
         <form action="<? OUT("?p=$p&act=$act&a=$a") ?>&mod=save" method=post>     
         <table width=100%>
         <?      
          for($i=0;$i<$tcnt;++$i)
           {
            $data=$ART->GetTopicData($i);
            if($i/2.0==round($i/2.0))OUT("<tr>");
            
            $descr=$FLTR->ReverseProcessText($data["descr"]);
            $author=$data["author"];
            $title=$data["title"];

            if($MDL->IsModuleExists("users"))
             {            
             $MDL->Load("users");
             $USR=new CUsers($DIRS["users_data"],$DIRS["users_list"],$DIRS["users_private"],$DIRS["users_groups"],$DIRS["users_online"]);
             $USR->SetSeparators($GV["sep1"],$GV["sep2"]);
             
             if($USR->GetUserLevel($author)<$CURRENT_USER["level"] || $author==$CURRENT_USER["id"])
               {
               $authorselect="<select name=authors[] style=\"width:100%\">";
               $udata=$USR->GetUsers();
               for($j=0;$j<count($udata);++$j)
                 {
                 if($udata[$j]["id"]==$author)$sel=" selected"; else $sel=""; 
                 if($USR->GetUserLevel($udata[$j]["id"])<$CURRENT_USER["level"] || $udata[$j]["id"]==$CURRENT_USER["id"]) 
                   $authorselect.="<option value=\"".$udata[$j]["id"]."\"$sel>".$udata[$j]["nick"]."</option>";
                 }         
               $authorselect.="</select>";
               }
               else
               {
               $udata=$USR->GetUserData($author);               
               $authorselect=$udata["nick"];
               }
             }
             else {$authorselect="root";}  
                       
           ?><tr><td class=tbl1>
           <div align=center><b><? OUT($data["id"]) ?></b></div>
           <input type=hidden name=ids[] value="<? OUT($data["id"]) ?>">
           <table width=100%>
           <tr><td width=50%>���������� �����:</td><td width=50%><input style="width:100%" type=text name=is[] value="<? OUT($i+1) ?>"></td></tr>
           <tr><td width=50%>���������:</td><td width=50%><input style="width:100%" type=text name=tits[] value="<? OUT($title) ?>"></td></tr>           
           <tr><td width=50%>�����:</td><td width=50%><? OUT($authorselect) ?></td></tr>
           </table>
           <table width=100%>
           <tr><td width=100%>��������:</td></tr>     
           <tr><td width=100%><textarea name=descrs[] style="width:100%" rows=3><? OUT($descr) ?></textarea></td></tr>
           </table><br>
           <? 
           if(count($data["articles"]))
           {
           ?>
           ������ ������:<br>
           <?
           for($j=0;$j<count($data["articles"]);++$j) 
             {             
             $adata=$ART->GetArticleData($data["articles"][$j]);
             ?>
             <li><small>"<? OUT($adata["title"]) ?>"</small></li>
             <?
             } 
           }
           ?>           
           </td></tr>
           <?php
           }             
         ?>
         
         <?
          for($i=0;$i<$ncnt;++$i)
           {
           
            if($MDL->IsModuleExists("users"))
             {            
             $MDL->Load("users");
             $USR=new CUsers($DIRS["users_data"],$DIRS["users_list"],$DIRS["users_private"],$DIRS["users_groups"],$DIRS["users_online"]);
             $USR->SetSeparators($GV["sep1"],$GV["sep2"]);
             $authorselect="<select name=authorsnew[] style=\"width:100%\">";
             $udata=$USR->GetUsers();
             for($j=0;$j<count($udata);++$j)
               {
               if($USR->GetUserLevel($udata[$j]["id"])<$CURRENT_USER["level"])
                 $authorselect.="<option value=\"".$udata[$j]["id"]."\">".$udata[$j]["nick"]."</option>";
               }
             $authorselect.="<option value=\"".$CURRENT_USER["id"]."\" selected>".$CURRENT_USER["nick"]."</option>";
             $authorselect.="</select>";
             } else {$authorselect="root";}            
           ?><tr><td class=tbl1>
           <div align=center><b>����� ������������� <? OUT($i+1) ?>*</b></div>
           <table width=100%>
           <tr><td width=50%>���������� �����:</td><td width=50%><input style="width:100%" type=text name=isnew[] value="<? OUT($tcnt+$i+1) ?>"></td></tr>
           <tr><td width=50%>���������:</td><td width=50%><input style="width:100%" type=text name=titsnew[] value=""></td></tr>           
           <tr><td width=50%>�����:</td><td width=50%><? OUT($authorselect) ?></td></tr>
           </table>
           <table width=100%>
           <tr><td width=100%>��������:</td></tr>     
           <tr><td width=100%><textarea name=descrsnew[] style="width:100%" rows=3></textarea></td></tr>
           </table>
           <?php
           }             
         ?>         
        </td></table>
        
        
        <div align=center><input type=submit name=savebutton class=button value="���������"></div>
        <div align=center><small><big><b>*</b></big>�������������� ����� ������� ����� ������������� ����� ����������</small></div>
        
        </form><br>
        <div align=center>
          | <a href="<? OUT("?p=$p&act=$act&a=$a") ?>&ncnt=<? OUT($ncnt+1) ?>">�������� ������</a>
          | <a href="<? OUT("?p=$p&act=$act&a=$a") ?>&ncnt=<? OUT($ncnt-1) ?>">������ ��������� ������</a> |
        <br>(<small><b>��������!</b> ��� ������� �� ��� ������ �������� ��� ������������ ������!</small>)</div> 
         <br><br>
         <div align=center><a href="<? OUT("?p=$p&act=$act") ?>">�����</a></div>
         
         <?         
         }        
         break;
         //.............................//
         //   ��������� ������
         //.............................//
         case "add":



         if(!isset($form))$form=true;

         global $FLTR;

         if(!isset($type))$type="new";   
         if(!isset($title))$title="";
         if(!isset($cat))$cat="";
         if(!isset($text))$text="";
         if(!isset($desc))$desc="";
         if(!isset($id))$id="0";
         if(!isset($type))$type="";
         
         if($type=="edit")
           {
           $data=$ART->GetArticleData($id);
           $ud=get_user_data($data["author"]); 
           if($CURRENT_USER["level"]<=$ud["level"] && $ud["id"]!=$CURRENT_USER["id"])
             {
             $page="403";
             $MDL->LoadModule("error",false);
             ?>
             <div align=center><a href="<? OUT("?p=$p&act=$act") ?>">�����</a></div>          
             <?              
             return;   
             }         
          }
          
         if($type=="edit" && !(isset($mod) && $mod=="save"))
           {
            $data=$ART->GetArticleData($id);
            $title=$data["title"];
            if(!isset($editor) || $editor!="html")$text=$FLTR->ReverseProcessText($ART->GetArticleText($id));
            else $text=$ART->GetArticleText($id);
            $desc=$FLTR->ReverseProcessText($data["descr"]);
            //$author=$data["author"];
            $ud=get_user_data($data["author"]); 
            $author=$ud["nick"];
            $cat=$topic;                                                     
           } 
           else
           {
           $author=$CURRENT_USER["nick"];
           }           

         $catsel="<select style=\"width:100%\" name=cat>";
          for($i=0;$i<$tcnt;++$i)
           {
            $data=$ART->GetTopicData($i);
            if($cat==$data["id"])$sel=" selected"; else $sel="";
            $catsel.="<option value=\"".$data["id"]."\"$sel>".$data["title"]."</option>";
           }
          $catsel.="</select>";
         
          if(isset($mod) && $mod=="save")
          {
          $error="";
          if(!$title)$error.="<br><b>������: </b> ������� �������� ������!<br>";
          if(!$text)$error.="<br><b>������: </b> ������� ����� ������!<br>";
          if(!$desc)$error.="<br><b>������: </b> ������� �������� ������!<br>";                           
          
           if($CURRENT_USER["level"]<7)$kt=true;
           
            $form=false;          
           if($type=="edit" && !$error)
            if(isset($editor) && $editor=="html")
            {
            $olddata=$ART->GetArticleData($id);
            $data=array();
            $data["title"]=$FLTR->DirectProcessString($title,1);
            $data["text"]=$FLTR->DirectProcessHTML($text);
            $data["descr"]=$FLTR->DirectProcessText($desc,1,1);
            $data["id"]=$FLTR->DirectProcessString($id,1);
            $data["author"]=$olddata["author"];
            $data["date"]=$olddata["date"];
            $cat_new=$FLTR->DirectProcessString($cat,1);
            $ART->MoveArticle($id,$cat_new);
            $ART->SetArticleData($id,$data);            
            }
            else
            {
            $olddata=$ART->GetArticleData($id);
            $data=array();            
            if($nb=="on")$nb=1;else $nb=0;            
            if($kt=="on")$kt=1;else $kt=0;       
            $data["title"]=$FLTR->DirectProcessString($title,1);
            //echo($kt." | ".$nb);
            $data["text"]=$FLTR->DirectProcessText($text,$kt,$nb);
            //die($data["text"]);
            
            $data["descr"]=$FLTR->DirectProcessText($desc,1,1);
            $data["id"]=$FLTR->DirectProcessString($id,1);
            $data["author"]=$olddata["author"];
            $data["date"]=$olddata["date"];
            $cat_new=$FLTR->DirectProcessString($cat,1); 
            

            $ART->MoveArticle($id,$cat_new);
            $ART->SetArticleData($id,$data);
            }             
           elseif(!$error)        
            if(isset($editor) && $editor=="html")
            {
            $form=false;
            $tit_new=$FLTR->DirectProcessString($title,1);
            $txt_new=$FLTR->DirectProcessHTML($text);
            $desc_new=$FLTR->DirectProcessText($desc,1,1);
            $cat_new=$FLTR->DirectProcessString($cat,1);
            $auth_new=$CURRENT_USER["id"];
            $dat_new=time();
             $id=$ART->AddArticle($cat_new,$tit_new,$auth_new,$desc_new,$txt_new);
            }
            else
            {
            $form=false;
            $tit_new=$FLTR->DirectProcessString($title,1);
            if($nb=="on")$nb=1;else $nb=0;            
            if($kt=="on")$kt=1;else $kt=0;                
            $txt_new=$FLTR->DirectProcessText($text,$nb,$kt);
            $desc_new=$FLTR->DirectProcessText($desc,1,1);
            $cat_new=$FLTR->DirectProcessString($cat,1);
            $auth_new=$CURRENT_USER["id"];
            $dat_new=time();
             $id=$ART->AddArticle($cat_new,$tit_new,$auth_new,$desc_new,$txt_new);
            }         
            if(!$error)
             OUT("���������");
            else {OUT($error); $form=true;}
             ?>
             <div align=center><a href="<? OUT("?p=$p&act=$act&a=$a&id=$id&topic=$topic&type=edit&editor=$editor&topic=$topic") ?>">�����</a></div>
             <?
          }
         if($form)
          {
            ?>           
            <br><br>
            ���������� ����� ������. ��������� ������������ ���� � ������� ������ "���������".            
            <form action="<? OUT("?p=$p&act=$act&a=$a&type=$type&id=$id&topic=$topic&editor=$editor") ?>&mod=save" method=post id="EditForm" enctype="multipart/form-data">
            <table width=100%>
            <tr><td class=tbl1 width=50%>��������:</td><td class=tbl1 width=50%><input style="width:100%" type=text name=title value="<? OUT($title) ?>"><br><small>������: ������� �� ���������</small></td></tr>
            <tr><td class=tbl1 width=50%>���������:</td><td class=tbl1 width=50%><? OUT($catsel) ?></td></tr>                        
            <tr><td class=tbl1 width=50%>�����:</td><td class=tbl1 width=50%><? OUT($author) ?></td></tr>    
            </table>
            <table width=100%>
            <tr><td class=tbl1 width=100%>�������� ������:<br><small>������: � ������ �������� �������� ��� ����, ��������� � ���������</small></td></tr>
            <tr><td class=tbl1 width=100%>
             <textarea style="width:100%" rows=5 name=desc><? OUT($desc) ?></textarea>
            </td></tr>             
            <tr><td class=tbl1 width=100%>����� ������:</td></tr>
            <tr><td class=tbl1 width=100%>
            <?
            if(isset($editor) && $editor=="html")
             {
            ?>
            <input type="hidden" name="text">
            </TD></TR>
            </form>                        
            <TR><TD height="1" bgcolor="#dddddd"></TD></TR>
            <TR><TD height="25" bgcolor="#dddddd"><div id="tools"></div></TD></TR>
            <TR><TD height="25" bgcolor="#dddddd">
            �����
            <div id="fonts">        
            <select id="fface" onchange="SetFace()">
            <option value="Arial">Arial
            <option value="Courier New">Courier New
            <option value="Tahoma">Tahoma
            <option value="Times New Roman">Times New Roman
            <option value="Verdana" selected>Verdana
            </select>
            ������
            <select id="fsize" style="width:40" onchange="SetSize()">
            <option value="1">1
            <option value="2" selected>2
            <option value="3">3
            <option value="4">4
            <option value="5">5
            <option value="6">6
            <option value="7">7
            </select>
            </div>
            <IFRAME id="EditFrame" width="100%" height=400px frameborder="0" style="border-width:1px; border-color:#000000; border-style: solid;" contenteditable="true"></IFRAME>         
            <script>
               var Content; 
                Content="<? OUT(str_replace("\r\n","",addslashes($text))) ?>";
            </script>
            <SCRIPT src="js/editor.js"></SCRIPT>          
            </td></tr> 
            <tr><td align=left>
            ��������: | <b>HTML</b> | <a href="<? OUT("?p=$p&act=$act&a=$a&type=$type&topic=$topic&id=$id") ?>">�������</a>
            <br>(<small><b>��������!</b> ��� ������� �� ��� ������ �������� ��� ������������ ������!</small>) 
            </td></tr>           
            </table>
            <div align=center><input type="button" class="button" value="���������" onclick="Save()"></div>
            <?                          
             }
             else
             {
            ?>            
             <textarea style="width:100%" rows=30 name=text><? OUT($text) ?></textarea>
            </td></tr> 
            <tr><td align=left>
            ��������: | <a href="<? OUT("?p=$p&act=$act&a=$a&editor=html&type=$type&topic=$topic&id=$id") ?>">HTML</a> | <b>�������</b>
            <br>(<small><b>��������!</b> ��� ������� �� ��� ������ �������� ��� ������������ ������!</small>)</div>
            </td></tr><tr><td>
            <input type=checkbox name=nb unchecked>���������� ������� �� ����� ������ � &lt;br&gt;? <br>  
            <input type=checkbox name=kt unchecked>��������� HTML-���� ?                    
            </td></tr></table>
            <div align=center><input type=submit value="���������" class=button></div>              
            <?

             }
            ?>   
            <br><br><div align=center><a href="<? OUT("?p=$p&act=$act") ?>">�����</a></div>                   
            <?
          }
         
         break;
         
         default:
         
         ?>
         <br><br><div align=center>
         <a href="?p=<? OUT($p) ?>&act=<? OUT($act) ?>&a=cat">�������� ���������</a><br>
         <a href="?p=<? OUT($p) ?>&act=<? OUT($act) ?>&a=add">�������� ������</a><br>         
         </div> <br><br>
          <div align=center>�������� ��������� ��� �������������� ������:</div>   
         <table width=100%>
           <tr><td class=tbl2 width=30% align=center><b>��������</b></td>
           <td class=tbl2 width=50% align=center><b>��������</b></td>
           <td class=tbl2 width=20% align=center><b>������</b></td>
           </tr>
         <?
          for($i=0;$i<$tcnt;++$i)
           {
            $data=$ART->GetTopicData($i);
            ?>
            <tr><td class=tbl1 width=30% align=center>
            <a href="<? OUT("?p=$p&act=$act&a=view&topic=".$data["id"]."") ?>"><? OUT($data["title"]) ?></a>
            </td><td class=tbl1 width=50% align=center>
            <small><? OUT($data["descr"]) ?></small>
            </td><td class=tbl1 width=20% align=center>
            <small><? OUT(count($data["articles"])) ?></small>
            </td>
            </tr>
            <?        
           }
         ?>        
         </table>
         <?
         
         };     
     }
    else
      include(SK_DIR."/articles_admin.php");
  }
//================================================
// ������ ��� ����
//================================================
 elseif($_MODULE) 
if($_NOTBAR)
  if(!file_exists(SK_DIR."/articles.php"))
     {
    
     global $DIRS,$p,$PDIV,$MDL;        
     if(isset($act) && $act=="admin")$MDL->LoadAdminPage("articles");
     else
     {        
     $ART=new CArticles($DIRS["arts_data"],$DIRS["arts_comments"],$DIRS["arts_list"]);
     $ART->SetSeparators($GV["sep1"],$GV["sep2"],$GV["sep3"]);
     $tcnt=$ART->GetTopicsCount();
     ?>
     <div align=center><center><big><b>������</b></big></center></div>
  
    <?php
          
    if(isset($act)) // ���� ���-�� ��� ������� (����� ���������� ������ ���)
    {  
    //..............................................//
    //�������� ����           
    if($act=="viewtopic" && isset($topic))
     {
     $data=$ART->GetTopicDataById($topic);
     ?> 
     <table width=100%><tr>
     <td class=tdarttopic>
     <div align=center><b><?php OUT($data["title"]); ?></b> (<?php OUT(count($data["articles"])); ?>)</div><br>
     <div align=right>�����:<?php $ud=get_user_data($data["author"]); OUT("<a href=?p=users&act=userinfo&id=".$data["author"].">".$ud["nick"]."</a>"); ?></div>
     <font class="font_small"><?php OUT($data["descr"]); ?>
     </font>
     </td><tr>
     <td>
     <?php
     $alist=$data["articles"];
     $pcnt=$PDIV->GetPagesCount($alist);
     if(!isset($page))$page=1;
     elseif($page>$pcnt) $page=$pcnt;

     echo("��������: ");
     for($i=0;$i<$pcnt;++$i)
      {
       if($i+1!=$page)echo("<a href=\"?p=$p&act=viewtopic&topic=$topic&page=".($i+1)."\">".($i+1)."</a>");
       else echo("".($i+1)."");
       if($i<$pcnt-1)echo(", ");
      }   
      ?>
      <table width=100%><tr>
      <td class=tdarttopic>���������</td>
      <td class=tdarttopic>�����</td>
      <td class=tdarttopic>����</td>
      <td class=tdarttopic>��������</td></tr><tr>    
      <?php   
      $alist=$PDIV->GetPage($data["articles"],$page);
      for($i=0;$i<count($alist);++$i)
       {
       $adata=$ART->GetArticleData($data["articles"][$i]);
       ?>
       <td class=tdarttopic align=center><a href="<? OUT("?p=$p&topic=$topic&act=view&page=$page&art=".$data["articles"][$i]."\">".$adata["title"]) ?></a>
       <br>(<a href="<? OUT("?p=$p&topic=$topic&act=viewcmnts&page=$page&art=".$data["articles"][$i]) ?>"><small>������������</small></a><small>:<? OUT ("".($adata["ccount"])) ?>)</small></td>
       <td class=tdarttopic align=center><? $ud=get_user_data($adata["author"]); OUT("<a href=?p=users&act=userinfo&id=".$data["author"].">".$ud["nick"]."</a>"); ?></td>
       <td class=tdarttopic align=center><? OUT(norm_date($adata["date"])) ?></td>         
       <td class=tdarttopic align=center><SMALL><? OUT($adata["descr"]) ?></SMALL></td>       
       <?php
       if($i<count($alist)-1)OUT("<tr>");
       }        
       ?>
      </table>
      <?php
  
      echo("��������: ");
      for($i=0;$i<$pcnt;++$i)
      {
      if($i+1!=$page)echo("<a href=\"?p=$p&act=viewtopic&topic=$topic&page=".($i+1)."\">".($i+1)."</a>");
      else echo("".($i+1)."");
      if($i<$pcnt-1)echo(", ");
      }   
      ?><p><div align=center>  <a href="<? OUT("?p=$p") ?>">�����</a></div>
          </table>
      <?php
      }
      //..............................................//
      // ���������� ������������
      elseif($act=="addcmnt" && isset($art))
      { 
      include "config.php";
        if(check_auth())
         {
         $new_nick=$CURRENT_USER["id"];
         $new_email=$CURRENT_USER["email"];
         $new_url=$CURRENT_USER["url"];         
         }
         else
         {
         $new_nick=$FLTR->DirectProcessString($nick,1);
         $new_email=$FLTR->DirectProcessString($email,1);
         $new_url=$FLTR->DirectProcessString($url,1);
         }
         $new_comment=$FLTR->DirectProcessText($comment_text,1,1);
         $error="";
         if(strlen($new_nick)<2)$error.="<b>��� ������ ���� �� ������ 2-� �������</b><br>";         
         if(strlen($new_comment)<3)$error.="<b>����� �������� �����!</b><br>"; 
         if($error=="")      
           {
           $ART->AddComment($art,$new_nick,$new_email,$new_url,$new_comment);
           ?>
           ���������!
           <?
           }           
         else
           echo($error);

      if(isset($page) && isset($topic))
       {
        ?>
        <p><div align=center>  <a href="<? OUT("?p=$p&act=viewtopic&topic=$topic&page=$page") ?>">�����</a></div></p>
        <?
       }
       else
       {
        ?>
        <p><div align=center>  <a href="<? OUT("?p=$p&act=viewcmnts&art=$art") ?>">�����</a></div></p>
        <?
       }
      }
      //..............................................//
      // �������� ������������
      elseif($act=="viewcmnts" && isset($art))
      {   
      include "config.php";
      $data=$ART->GetArticleData($art);
      ?>
      <table align=center width=100%><tr><td>
      <div align=center><b><? OUT($data["title"]); ?></b></div><br>
      <div align=left><small><? $ud=get_user_data($data["author"]); OUT($ud["nick"]) ?>, <? OUT(norm_date($data["date"])) ?></small></div>
      </td></tr><tr><td>
      <? OUT($data["descr"]) ?>      
      </td></tr></table> 
      <?    
    
    
      $clist=$ART->GetComments($art);
      $pcnt=$PDIV->GetPagesCount($clist);

      if(!isset($cpage) || $cpage<0 || $cpage>$pcnt)$cpage=1;   
      if($cpage!="all")$clist=$PDIV->GetPage($clist,$cpage);             
      $pagescode="";
      for($i=0;$i<$pcnt;++$i)
        {
        if(isset($page) && isset($topic))
          {if($cpage!=$i+1)$pagescode.="<a href=\"?p=$p&act=$act&topic=$topic&art=$art&page=$page&cpage=".($i+1)."\">".($i+1)."</a>, ";
            else $pagescode.="".($i+1).", ";}
        else
          {if($cpage!=$i+1)$pagescode.="<a href=\"?p=$p&act=$act&art=$art&cpage=".($i+1)."\">".($i+1)."</a>, ";
            else $pagescode.="".($i+1).", ";}          
        } 
        if($cpage!="all")$pagescode.="<a href=\"?p=$p&act=$act&topic=$topic&art=$art&cpage=all\">���</a>";      
      

     if(count($clist))
     {     
      ?>                                                                            
      <br>��������: <? OUT($pagescode) ?><br>  
      <?  
      for($i=0;$i<count($clist);++$i)
       {
       $data=$clist[$i];
       $uid=$data["nick"];
       if(is_user_exists($uid))
         {       
         $udata=get_user_data($uid);
         $nick="<a href=?p=users&act=userinfo&id=".$udata["id"].">".$udata["nick"]."</a>";
         $email=make_email_str($udata["email"]);
         $url=make_url_str($udata["url"]);
         }
        else 
         {$nick=$data["nick"];$email=make_email_str($data["email"]);$url=make_url_str($data["url"]);}
      
       ?>
       <table width=100%>
       <tr><td class=tbl1 width=100%>
       <? OUT($nick) ?>, <? OUT(norm_date($data["date"])) ?>, <? OUT($email) ?>, <? OUT($url) ?>
       </td><tr>
       <td width=100% class=tbl2>
       <? OUT($data["text"]) ?>
       </td>
       </table> <br>
       <? } ?>
       ��������: <? OUT($pagescode) ?><br> 
       <?     
       }
       else OUT("<br><br><div align=center><b>��� ������������ � ������ ������</b></div><br><br>");      
       ?>
       <form action="<? OUT("?p=$p&act=addcmnt&art=$art") ?>" method=post>
       <? 
       if(check_auth())
         {
         ?>
         <table align=center width=100%>
        <tr><td width=30%>���:</td><td><input class=inputbox type=text name=nick value="<? OUT($CURRENT_USER["nick"]) ?>" readonly style="width:100%"></td>
         <tr><td width=30%>Email:</td><td><input class=inputbox type=text name=email value="<? OUT($CURRENT_USER["email"]) ?>" readonly style="width:100%"></td>
         <tr><td width=30%>URL:</td><td><input class=inputbox type=text name=url value="<? OUT($CURRENT_USER["url"]) ?>" readonly style="width:100%"></td>
         </table> 
         <?
         }
         else
         {
         ?>
         <table align=center width=100%>
         <tr><td width=50%>���:</td><td><input class=inputbox type=text name=nick style="width:100%"></td>
         <tr><td width=50%>Email:</td><td><input class=inputbox type=text name=email style="width:100%"></td>
         <tr><td width=50%>URL:</td><td><input class=inputbox type=text name=url style="width:100%"></td>
         </table>
         <?   
         }   
         ?>
         <table width=100%><td>
         �����������:<Br>
         <textarea class=inputbox style="width:100%" rows=6 name=comment_text></textarea>
         </td></table>
         <div align=center><input type=submit class=button value="��������"></div>
       </form>                                                                            
       <?
      if(isset($page) && isset($topic))
       {
        ?>
        <p><div align=center>  <a href="<? OUT("?p=$p&act=viewtopic&topic=$topic&page=$page") ?>">�����</a></div></p>
        <?
       }
       else
       {
        ?>
        <p><div align=center>  <a href="<? OUT("?p=$p&act=view&art=$art") ?>">�����</a></div></p>
        <?
       }          
                 
      }
      //..............................................//      
      //�������� ������
      elseif($act=="view" && isset($art))
      {
      $data=$ART->GetArticleData($art);
      $text=$ART->GetArticleText($art);
      ?>
      <?
      if(isset($page) && isset($topic)){
      ?>      
      <a href="<? OUT("?p=$p&topic=$topic&act=viewtopic&page=$page") ?>">�����</a>
      <?
      }else{
      ?>
      <a href="<? OUT("?p=$p") ?>">�����</a>
      <?
      }
      $ud=get_user_data($data["author"]);
      ?>       
      <br><br><div align=center>
      <a href="<? OUT("?p=$p&act=viewcmnts&art=$art") ?>">������������</a>:<? OUT ($data["ccount"]) ?><br>        
   
      
      <br><br></div>      
      <table align=center width=100%><td>
      <div align=center><b><? OUT($data["title"]); ?></b></div><br>
      <div align=right><small><a href="?p=users&act=userinfo&id=<? OUT($ud["id"]) ?>"><? OUT($ud["nick"]) ?></a>, <? OUT(norm_date($data["date"])) ?></small></div>
      <font class="text">
      <? OUT($text) ?>
      </font>
      </td><tr><td align=center>
      <br><a href="<? OUT("?p=$p&act=viewcmnts&art=$art") ?>">������������</a>:<? OUT ($data["ccount"]) ?><br><br>
      <?
      if(isset($page) && isset($topic)){
      ?>      
      <a href="<? OUT("?p=$p&topic=$topic&act=viewtopic&page=$page") ?>">�����</a>
      <?
      }else{
      ?>
      <a href="<? OUT("?p=$p") ?>">�����</a>
      <?
      }
      ?>
      </td>
          </table>      
      <?php
      }    
    }
    //..............................................//
    //����� - ����� ����        
    else
    {
    ?>
    <table align=center><td>�������� ���������:</td></table>
     <table width=100%>
     <?php
     for($i=0;$i<$tcnt;++$i)
      {
      $data=$ART->GetTopicData($i);
      if($i/2.0==round($i/2.0))OUT("<tr>");
      ?>
      <td class=tdarttopic width=50%>
      <div align=center><b><a href="<? OUT("?p=$p&act=viewtopic&topic=".$data["id"]."") ?>"><? OUT($data["title"]) ?></a></b> (<?php OUT(count($data["articles"])); ?>)</div><br>
      <div align=right>�����:<? $ud=get_user_data($data["author"]); OUT("<a href=?p=users&act=userinfo&id=".$data["author"].">".$ud["nick"]."</a>"); ?></div>
      <font class="font_small"><? OUT($data["descr"]) ?>
      </font>
      </td>
      <?php
      }
      ?>
          </table>
      <?
    }        
    ?>

    <?
    if(check_auth() && $CURRENT_USER["level"]>=5)
    {
    ?>
    <div align=center>
    <a href="?p=<? OUT($p) ?>&act=admin">�����������������</a>
    </div>
    <?
    }
   }      
   
   }
  else
    include(SK_DIR."/articles.php");
else
  if(!file_exists(SK_DIR."/articlesbar.php"))
   {
     $ART=new CArticles($DIRS["arts_data"],$DIRS["arts_comments"],$DIRS["arts_list"]);
     $ART->SetSeparators($GV["sep1"],$GV["sep2"],$GV["sep3"]);
     $list=$ART->GetNLastArticles(3);
      if(!count($list))echo("<div align=center>��� ������</div>");
	for($i=0;$i<count($list);$i++){
   	       $ud=get_user_data($list[$i]["author"]);
                ?>
                <div align=center><a style="font-size:9px;" href="?p=articles&topic=<? OUT($list[$i]["tid"]) ?>&act=view&page=1&art=<? OUT($list[$i]["id"]) ?>"><b><? OUT($list[$i]["title"]) ?></a></b></div>
                <? OUT(substr($list[$i]["descr"],0,100)."...") ?><br>(<a style="font-size:9px;" href="?p=articles&topic=<? OUT($list[$i]["tid"]) ?>&act=view&page=1&art=<? OUT($list[$i]["id"]) ?>">������ �������</a>)<br>                
                <font color=gray style="font-size:8.5px"><a style="font-size:8.5px;" href="?p=users&act=userinfo&id=<? OUT($ud["id"]) ?>"><? OUT($ud["nick"]) ?></a> /<? OUT(norm_date($list[$i]["date"])) ?></font><br>
                <? }     
   
   }
  else
    include(SK_DIR."/articlesbar.php");
}


