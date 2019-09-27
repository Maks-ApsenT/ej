<?php 

abstract class Security {

    public static function code()
    {
        return str_shuffle(md5(rand()));
    }


    public static function email_code()
    {
        return substr(self::code(), 17);
    }


    public static function rand_str()
    {
    	$code = self::code();
    	$_SESSION['S_Code'] = $code;
    	return $code;
    }


    public static function verify_str($type = 1)
    {
        if($type == 1){
            if(empty($_REQUEST['S_Code']) or $_SESSION['S_Code'] != $_REQUEST['S_Code']){
                return 'Попытка подмены токена, повторите попытку отправки формы!';
            }
        }
        elseif($type == 'json'){
            if(empty($_REQUEST['S_Code']) or $_SESSION['S_Code'] != $_REQUEST['S_Code']){
                $out = array('type' => 'error', 'code' => '0', 'description' => 'Попытка подмены токена, повторите попытку отправки формы!');
                return $out;
            }
        }
    }


    public static function make_email($mod, $code, $us, $text, $tema, $email)
    {
        global $db;
        $db->query("INSERT INTO `users_emails` SET `text` = '".$db->escape($text)."', `code` = '".$db->escape($code)."', `us` = '".$db->escape($us)."', `valid` = '1', `time` = '".time()."', `time_end` = '".(time()+3600)."', `module` = '".$mod."'");
        Core::email($email, $tema, $text);
        return null;
    }

}