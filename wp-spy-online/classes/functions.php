<?php
class functions
{
    public  $sql="";
    public  $links;
    //this will create  a connection to our databaes
    //clean the data before inserting to database to prevent mysql injection
   
     
     #build a connection to our database server.
     function connect()
     {
        global $CFG;
        $this->links=mysql_connect($CFG["Database"]["host"],$CFG["Database"]["username"],$CFG["Database"]["password"]) 
            or die("Cannot connect to server. Please check your configurations.".mysql_error());
                mysql_select_db($CFG["Database"]["databasename"],$this->links) 
            or  die("Cannot connect to database server. Please check your configurations.");
     }
    // function connect(){
    //     global $CFG;
    //     mysql_connect($CFG["Database"]["host"], $CFG["Database"]["username"], $CFG["Database"]["password"])
    //         or  die("Cannot connect to server. Please check your configurations.".mysql_error());
    //     mysql_select_db($CFG["Database"]["databasename"])  or
    //         die("Cannot connect to database server. Please check your configurations.");
    // }
    
     #Execute query to database
     function query($sql)
     {
        $this->sql= $sql;
        $this->connect();
        
        $return = mysql_query($sql,$this->links);
        return  $return;

     }
     
     #execute query and return ARRAY_A values 
     function fetch($sql,$isone=false, $assoc=false)
     {
       $this->sql= $sql;
       $aResult = array();
       $this->connect();
       $pResult = mysql_query($sql,$this->links);
       if($pResult){
            if (mysql_num_rows($pResult) > 0) {
                if( $assoc == true ){
                    while ($aRow = mysql_fetch_assoc($pResult)) {
                        $aResult[] = $aRow;
                    }
                }else{
                    while ($aRow = mysql_fetch_array($pResult)) {
                        $aResult[] = $aRow;
                    }
                }
            } else {
                return false;
            }
            
            if($isone == true)
            {
                $aResult=$aResult[0];
            }
            
            return $aResult;
        
        }else{ return false; }
     }
     

     #return only one variable
     function get_var($sql)
     {
         $this->sql= $sql;
         $r=$this->fetch($sql);
         if(!$r)
         {
              return false;
         }else
         {
            return $r[0][0];
         }
     }
     
     #try to clean the submitted Data from user
     #this is to prevent  XSS attack.
     function clean($string)
     {
       return mysql_real_escape_string($string);
     }
     
     #Simple form to update a table values
     # $table = STRING
     # $values = ARRAY that corresponds to the table fields
     # $conditions = ARRAY that corresponds to the primary key or any identifier
     function update($table,$values,$condition)
     {
         $f="";
         $c="";
         if(is_array($values) and is_array($condition))
         {
            foreach($values as $k=>$v)
            {
                 $f.=$k."='".$this->clean($v)."', ";
            }
            $f= substr($f,0,strlen($f)-2);
            foreach($condition as $k=>$v)
            {
                $c.=$k."='".$this->clean($v)."' and "; 
            }
            $c = substr($c,0,strlen($c)-4);
            $sql="update ".$table." set ".$f." where ".$c;
            $this->sql = $sql;
             if($this->query($sql))
             {
                 return true;
             }else
             {
                 return false;
             }
         }else
         {
             return false;
         }
     }
     
     #Simple form to update a table values
     # $table = STRING
     # $values = ARRAY that corresponds to the table fields
     function insert($table,$values)
     {
         $f="";
         if(is_array($values))
         {
            if( $allow_html == false ){
                foreach($values as $k=>$v)
                {
                    $f.=$k."='".$this->clean($v)."', ";
                }
                $f= substr($f,0,strlen($f)-2);
            }
             
            $sql="insert into ".$table." set ".$f;
            $this->sql = $sql;
             if($this->query($sql))
             {
                 return true;
             }else
             {
                 return false;
             }
         }else
         {
             return false;
         }
     }
     
     #return affted rows
     function affected_rows()
     {
         return mysql_affected_rows();
     }
     
     public function last_inserted_id()
     {
        return mysql_insert_id();
     }
     
     public function get_sql()
     {
       return $this->sql;
     }
     public function get_mysql_error()
     {
        return mysql_errno($this->links).' : '.mysql_error($this->links);
     }
     public function numrows($sql)
     {
        return mysql_num_rows($this->query($sql));
     }

    public function pre($data)
    {
        echo'<pre>';
        print_r($data);
        echo'</pre>';
    }
    public function alert($message)
    {
        echo'<script type="text/javascript"> alert(\''.$message.'\');</script>';
    }
    public function prompt($message)
    {
         echo'<script type="text/javascript"> prompt(\''.$message.'\');</script>';
    }
    public function windowLocation($location){
        echo '<script type="text/javascript">window.location="'.$location.'";</script>';
    }
    
    #This is to generate a hash password 
    public function encrypt($string)
    {
        return md5($string);
    }
     #This function will send an email to user.
      # $args["to"] = email
      # $args["subject"] = subject
      # $args["content"] = content
      # $args["name"] = name of the receiver
      public function process_mail($args)
      {
        
            global $CFG;
            $mail = new PHPMailer();  // create a new object
        
            if($CFG["mail"]['use_SMTP']==true){
                $mail->Host       = "gmail.com";
                $mail->IsSMTP(); // enable SMTP
                $mail->SMTPDebug = 0;  // debugging: 1 = errors and messages, 2 = messages only
                $mail->SMTPAuth = true;  // authentication enabled
                $mail->SMTPSecure = 'tls'; // secure transfer enabled REQUIRED for Gmail
                $mail->Host = 'smtp.gmail.com'; // SMTP server
                $mail->Port = 465; 
                $mail->Username = $CFG["mail"]['gmail_username'];  
                $mail->Password = $CFG["mail"]['gmail_password'];    
            }
            $mailer->FromName = $CFG["mail"]['from_name'];
            $mailer->From = $CFG["mail"]['from_email'];
            $mail->Subject = $args["subject"];
            $mail->Body = $args["content"];
            $mail->AddAddress($args["to"],$args["name"]);
            if(!$mail->Send()) {
                return 'Mail error: '.$mail->ErrorInfo;
            } else {
                return true;
            }
      }
      
      # $args["to"] = email
      # $args["subject"] = subject
      # $args["content"] = content
      # $args["name"] = name of the receiver
      # $args["use_template"] = integer and default to 1
      public function send_mail($args)
      {
        global $CFG;
        if($args["use_template"]=="" || $args["use_template"]=="1"){
            $content='<div style="text-align:left">
                        '.$args["content"].'
                   <p><br><br>
                    <div style="font-size:12px; 
                     font-family: lucida grande,tahoma,verdana,arial,sans-serif; color:#919191">
                      Regards,
                    </div>
                    <div style="font-size:12px; margin-top:10px;
                     font-family: lucida grande,tahoma,verdana,arial,sans-serif; color:#919191">
                     '.SYS_NAME.'
                    </div>
                    <div style="font-size:12px; margin-top:15px;
                     font-family: lucida grande,tahoma,verdana,arial,sans-serif; color:#919191">
                     '.$_SERVER['SERVER_NAME'].'
                    </div>
                    <div style="font-size:12px; margin-top:3px;
                     font-family: lucida grande,tahoma,verdana,arial,sans-serif; color:#919191">
                     '.$CFG["mail"]['from_email'].'
                    </div>
                    <br><br><br>
                  <div style="border:1px solid #B7B7B7; padding:5px; font-size:9px; 
                  font-family: lucida grande,tahoma,verdana,arial,sans-serif; color:#919191">
                   If you receive this in error  or you are not a person mention above, please ignore and delete this Email immediately
                  or forward this Email to the appropriate receipient.
                  </div>
                  <br>
                  </p>
                  </div>';
                 $args["content"] = $content;
         }
         
         return $this->process_mail($args); #send mail
              
      }
}   
?> 