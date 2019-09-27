<?

/*
* Список функций:
* YCS() - Фильтрация входных и выходных данный
* mode() - определение прав пользователей
* ErrorNoExit() - вывод ошибки без прекращения выполнения скрипта
* error() - вывод ошибки с прекращением выполнения скрипта
* SuccessNoExit() - вывод оповещания без прекращения выполнения скрипта
* success() - вывод уведомления об успешном действии
* level() - определение прав админа
*/

// Фильтрация входных и выходных данный
function YCS($var) {
  return trim(htmlspecialchars($var, ENT_QUOTES, 'UTF-8'));
}

function OCS($var) {
  return htmlspecialchars(nl2br($var, ENT_QUOTES));
}

// определение прав пользователей
function mode($mode) {
	global $user, $admin;
	if($mode=='admin'){
		if(!isset($admin['id'])){
			error('Данная страница доступна для просмотра только админам.');
		}
	}elseif($mode=='user'){
		if(!isset($user['id'])){
      header("Location:/ej/index.php"); exit();
		}
	}
}

// вывод ошибки без прекращения выполнения скрипта
function ErrorNoExit($text) {
	echo '<div class="alert alert-danger">'.$text.'</div>';
}

// вывод ошибки с прекращением выполнения скрипта
function error($text) {
	echo '<div class="alert alert-danger">'.$text.'</div>';
	exit();
}

// вывод оповещания без прекращения выполнения скрипта
function SuccessNoExit($text) {
	echo "<div class='success'>$text</div>";
}

// вывод уведомления об успешном действии
function success($text) {
	echo "<div class='success'>$text</div>";
	include_once($_SERVER["DOCUMENT_ROOT"].'/classes/foot.php');
	exit();
}

// определение прав админа
function level($level) {
	global $user;
  if($user['lvl']<$level){
		error('У Вас нет прав для просмотра данной страницы.');
	}
}

##########################################################################################################

function getCount($w,$tab){
  return DB::$dbs->querySingle("SELECT count(".$w.") from ".$tab."");
}

##########################################################################################################

if (isset($_SESSION['parent']['id'])) {
  $Ylogin = YCS($_SESSION['parent']['id']);
  $user = DB::$dbs->queryFetch("SELECT concat_ws(' ',students.f,students.i,students.o) name, groups.id group_id, groups.group_name, students.id FROM students LEFT JOIN groups on groups.id=students.group_id where students.id=?", $Ylogin);
}elseif (isset($_SESSION['teacher']['id'])) {
  $id = YCS($_SESSION['teacher']['id']);
  $admin = DB::$dbs->queryFetch("SELECT `id`,`login`,`role` FROM `journal_users` WHERE id={$id}");
}

##########################################################################################################

function gradient( $string, $from = '', $to = '' ) {
  $out    = null;
  $string = iconv( 'utf-8', 'windows-1251', $string );

  $to     = array(
  hexdec( $to[ 0 ] . $to[ 1 ] ), // r
  hexdec( $to[ 2 ] . $to[ 3 ] ), // g
  hexdec( $to[ 4 ] . $to[ 5 ] ) // b
);

  $from   = array(
  hexdec( $from[ 0 ] . $from[ 1 ] ), // r
  hexdec( $from[ 2 ] . $from[ 3 ] ), // g
  hexdec( $from[ 4 ] . $from[ 5 ] ) // b
);
  $levels = strlen( $string );
  for ( $i = 1; $i <= $levels; $i++ ) {
    for ( $ii = 0; $ii < 3; $ii++ ) {
      $tmp[ $ii ] = $from[ $ii ] - $to[ $ii ];
      $tmp[ $ii ] = floor( $tmp[ $ii ] / $levels );
      $rgb[ $ii ] = $from[ $ii ] - ( $tmp[ $ii ] * $i );
      if ( $rgb[ $ii ] > 255 ) {
        $rgb[ $ii ] = 255;
      }
      $rgb[ $ii ] = dechex( $rgb[ $ii ] );
      if ( strlen( $rgb[ $ii ] ) < 2 ) {
        $rgb[ $ii ] = '0' . $rgb[ $ii ];
      }
    }
    $out .= '<span style="color: #' . $rgb[ 0 ] . $rgb[ 1 ] . $rgb[ 2 ] . '">' . $string[ $i - 1 ] . '</span>';
  }
  return iconv( 'windows-1251', 'utf-8', $out );
}

##########################################################################################################

function name($id){

  $users = DB::$dbs->queryFetch("SELECT * FROM `users` WHERE `id` = '".$id."' LIMIT 1");

  if(isset($users)){
    $nicks = DB::$dbs->query("SELECT * FROM `nicks` where `who` = '".$users['id']."' ORDER BY `id` DESC LIMIT 1");
    while($nicks2 = $nicks -> fetch()){
      $users['login'] = $nicks2['new'];
    }

    $e = DB::$dbs->querySingle("SELECT count(id) from `opt` where `kto` = ? and `chto` = ?",array($id,'vip'));
    $m = DB::$dbs->querySingle("SELECT count(id) from `opt` where `kto` = ? and `chto` = ?",array($id,'mash'));
    $gradient = DB::$dbs->queryFetch('select * from `gradient` where `who` = "'.$id.'" limit 1');
    $ban = DB::$dbs->queryFetch("SELECT * FROM `ban` WHERE `who` = '".$id."'");
    $mig = DB::$dbs->querySingle("SELECT count(id) from `opt` where `kto` = ? and `chto` = ?",array($id,'mig'));

    if($m == 1){
      $mash = '[<font color="red"><b>!</b></font>]';
    }else{ $mash = NULL;}

    if($e == 1){
      $vip = '<img src="/images/vip.png" alt="vip"/>';
    }else{ $vip = NULL;}

    if($users['lvl'] >= 1){
      $lvl = '<span data-toggle="tooltip" title="Команда Fremas"><img src="/assets/img/support.png"style="width:16px;height:16px;"></span>';
    }else{ $lvl = NULL;}

    if($ban['end'] > time()){
      $ban2 = '[<font color="red">Бан</font>]';
    }else{ $ban2 = NULL;}

    if($gradient['start'] != NULL and $gradient['end'] != NULL){
      $grad = '<b>'.gradient($users['login'],$gradient['start'],$gradient['end']).'</b>';
    }else{ $grad = '<b>'.$users['login'].'</b>';}

    if($users['onl'] > time() - 120){
      $online = deviceIcon($users['ua']);
    }else{
      $online = NULL; 
    }

    if($mig == 1){
      $result = '<span class="blink"><b>'.$grad.'</b></span>';
    }else{
      $result = $grad;
    }

  }
  return (empty($users)?'<b>[Удалён]</b>':''.$result.' '. $lvl.' '. $ban2 .' '.$mash.' '.$vip.' '.$online.'');
}

##########################################################################################################

function aname($id){

  $users = DB::$dbs->queryFetch("SELECT * FROM `users` WHERE `id` = '".$id."' LIMIT 1");

  if(isset($users)){
    $nicks = DB::$dbs->query("SELECT * FROM `nicks` where `who` = '".$users['id']."' ORDER BY `id` DESC LIMIT 1");
    while($nicks2 = $nicks -> fetch()){
      $users['login'] = $nicks2['new'];
    }

    $e = DB::$dbs->querySingle("SELECT count(id) from `opt` where `kto` = ? and `chto` = ?",array($id,'vip'));
    $m = DB::$dbs->querySingle("SELECT count(id) from `opt` where `kto` = ? and `chto` = ?",array($id,'mash'));
    $gradient = DB::$dbs->queryFetch('SELECT * from `gradient` where `who` = "'.$id.'" limit 1');
    $ban = DB::$dbs->queryFetch("SELECT * FROM `ban` WHERE `who` = '".$id."'");
    $mig = DB::$dbs->querySingle("SELECT count(id) from `opt` where `kto` = ? and `chto` = ?",array($id,'mig'));

    if($m == 1){
      $mash = '[<font color="red"><b>!</b></font>]';
    }else{ $mash = NULL;}

    if($e == 1){
      $vip = '<img src="/images/vip.png" alt="vip"/>';
    }else{ $vip = NULL;}

    if($users['lvl'] >= 1){
      $lvl = '<span data-toggle="tooltip" title="Команда Fremas"><img src="/assets/img/support.png"style="width:16px;height:16px;"></span>';
    }else{ $lvl = NULL;}

    if($ban['end'] > time()){
      $ban2 = '[<font color="red">Бан</font>]';
    }else{ $ban2 = NULL;}

    if($gradient['start'] != NULL and $gradient['end'] != NULL){
      $grad = '<b>'.gradient($users['login'],$gradient['start'],$gradient['end']).'</b>';
    }else{ $grad = '<b>'.$users['login'].'</b>';}

    if($users['onl'] > time() - 120){
      $online = deviceIcon($users['ua']);
    }else{
      $online = NULL; 
    }

    if($mig == 1){
      $result = '<span class="blink"><b>'.$grad.'</b></span>';
    }else{
      $result = $grad;
    }

  }
  return (empty($users)?'<b>[Удалён]</b>':'<a href="/us'.$users['id'].'">'.$result.'</a> '. $ban2 .' '. $lvl.' '.$mash.' '.$vip.' '.$online.'');
}

##########################################################################################################

function is_ked($id) {
  $m = DB::$dbs->querySingle("SELECT count(id) from `ced` where `login` = '".$id."' and `mode` = '1'");
  if ($m == 1)
    return true;
  else
    return false;
}

##########################################################################################################

function page($k_page=1) {
  $page = 1;
  $page = YCS($page);
  $k_page = YCS($k_page);
  if(isset($_GET['p'])) {
    if ($_GET['p']=='end')
      $page = YCS(intval($k_page));
    elseif(is_numeric($_GET['p'])) 
      $page = YCS(intval($_GET['p']));
  }
  if ($page<1)$page=1;
  if ($page>$k_page)$page=$k_page;
  return $page;
}

##########################################################################################################

function k_page($k_post = 0,$k_p_str = 10) {
  if ($k_post != 0) {
    $v_pages = ceil($k_post/$k_p_str);
    return $v_pages;
  }
  else return 1;
}

##########################################################################################################

function str($link='?',$k_page=1,$page=1){
  if ($page<1)$page=1;
  $page = YCS($page);
  $k_page = YCS($k_page);
  echo '</br><center>';

  if ($page>1)echo '<a href="'.$link.'p='.($page-1).'">';
  if ($page<$k_page)echo '<a href="'.$link.'p='.($page+1).'" ></a>';
  if ($page != 1)
    echo '<a href="'.$link.'p=1" ><span class="btn btn-info">1</span></a>';
  else echo '<span class="btn btn-info"><b>[1]</b></span> ';
  for ($ot=-3; $ot<=3; $ot++){
    if ($page+$ot>1 && $page+$ot<$k_page){
      if ($ot==-3 && $page+$ot>2)echo " ";
      if ($ot!=0)echo ' <a href="'.$link.'p='.($page+$ot).'" ><span class="btn btn-info">'.($page+$ot).'</span></a>';
      else echo ' <b><span class="btn btn-info"> ['.($page+$ot).'] </span></b>';
      if ($ot==3 && $page+$ot<$k_page-1)echo " ";}}
  if ($page!=$k_page)echo ' <a href="'.$link.'p=end" ><span class="btn btn-info">'.$k_page.'</span></a>';
  elseif ($k_page>1)echo ' <span class="btn btn-info"><b>['.$k_page.']</b></span> ';
  echo '</center></br>';
}

##########################################################################################################

function lin($id){
global $dbs;
$a = DB::$dbs->queryFetch("SELECT * FROM `users` WHERE `id` = '".$id."' LIMIT 1");

if(preg_match('/msg/i', $a['self'])){
$ll = 'Диалог';
}elseif(preg_match('/mail/i', $a['self'])){
$ll = 'Почта';
}elseif(preg_match('/forum/i', $a['self'])){
$ll = 'Форум';
}elseif(preg_match('/zc/', $a['self'])){
$ll = 'Загруз-Центр';
}elseif(preg_match('/pc/i', $a['self'])){
$ll = 'Коды';
}elseif(preg_match('/online/i', $a['self'])){
$ll = 'Кто онлайн?';
}elseif(preg_match('/kab/i', $a['self'])){
$ll = 'Личный кабинет';
}elseif(preg_match('/money/i', $a['self'])){
$ll = 'Биллинг';
}elseif(preg_match('/action/i', $a['self'])){
$ll = 'Смотрит оповещения';
}elseif(preg_match('/feed/i', $a['self'])){
$ll = 'Новости пользователей';
}elseif(preg_match('/news/i', $a['self'])){
$ll = 'Новости сайта';
}elseif(preg_match('/index/i', $a['self'])){
$ll = 'На главной';
}elseif($a['self'] == '/'){
$ll = 'На главной';
}elseif(preg_match('/us/i', $a['self'])){
$ll = 'Страница пользователя';
}elseif(preg_match('/arb/i', $a['self'])){
$ll = 'Арбитраж';
}elseif(preg_match('/str/i', $a['self'])){
$ll = 'Переходит по ссылке';
}elseif(preg_match('/ban/i', $a['self'])){
$ll = 'В бане';
}elseif(preg_match('/port/i', $a['self'])){
$ll = 'Портфолио';
}elseif(preg_match('/servis/i', $a['self'])){
$ll = 'Сервисы';
}elseif(preg_match('/stat/i', $a['self'])){
$ll = 'Статистика';
}elseif(preg_match('/masters/i', $a['self'])){
$ll = 'Список пользователей';
}elseif(preg_match('/search/i', $a['self'])){
$ll = 'Поиск';
}elseif(preg_match('/tovar/i', $a['self'])){
$ll = 'Магазин';
}elseif(preg_match('/ticket/i', $a['self'])){
$ll = 'Тикеты';
}elseif(preg_match('/senk/i', $a['self'])){
$ll = 'Спасибки';
}elseif(preg_match('/Нарушения/i', $a['self'])){
$ll = 'nar';
}elseif(preg_match('/files/i', $a['self'])){
$ll = 'Файлы';
}elseif(preg_match('/guest/i', $a['self'])){
$ll = 'Гостевая';
}elseif(empty($ll)){
$ll = 'Неизвестно';
}
return $ll;
}

##########################################################################################################

function slv($str,$msg1,$msg2,$msg3) {
  $str = (int)$str;
  $str1 = abs($str) % 100;
  $str2 = $str % 10;
  if ($str1 > 10 && $str1 < 20) return $str .' '. $msg3;
  if ($str2 > 1 && $str2 < 5) return $str .' '. $msg2;
  if ($str2 == 1) return $str .' '. $msg1;
  return $str .' '. $msg3;
}


function Ti($times = NULL) {


  $time = time();
  if(($time-$times)<=60){
  $timesp = slv((($time-$times)),'секунду','секунды','секунд').' назад';
  return $timesp;
  }else if(($time-$times)<=3600){$timesp = slv((($time-$times)/60),'минуту','минуты','минут').' назад';
  return $timesp;
  }else{
  $today = date("j M Y", $time);
  $today = date("j M Y", $time);
  $yesterday = date("j M Y", strtotime("-1 day"));
  $timesp=date("j M Y  в H:i", $times);
  $timesp = str_replace($today, 'Сегодня', $timesp);
  $timesp = str_replace($yesterday, 'Вчера', $timesp);
  $timesp = strtr($timesp, array ("Jan" => "Янв","Feb" => "Фев","Mar" => "Марта","May" => "Мая","Apr" => "Апр","Jun" => "Июня","Jul" => "Июля","Aug" => "Авг","Sep" => "Сент","Oct" => "Окт","Nov" => "Ноября","Dec" => "Дек",));
  return $timesp;}
}

function Ti2($string) {

  $day=floor($string/86400); 
  $hours=floor(($string/3600)-$day*24); 
  $min=floor(($string-$hours*3600-$day*86400)/60); 
  $sec=floor($string-($min*60+$hours*3600+$day*86400));

  if($day > 0){ $day2 = $day.' дней';}else{$day2 = '';}
  if($hours > 0 ){ $hours2 = $hours.' часов';}else{$hours2 = '';}
  if($min > 0 ){ $min2 = $min.' минут';}else{$min2 = '';}
  if($sec > 0 ){ $secc2 = $sec.' секунд';}else{$secc2 = '';}

  return $day2.' '.$hours2.' '.$min2.' '.$secc2; 
}

/////////////////////////////////////////////////////////////

function fsize($file)
{
    if (!file_exists($file))
        return "Файл не найден";
    $filesize = filesize($file);
    $size = array('б','Кб','Мб','Гб');
    if ($filesize > pow(1024, 3))
    {
        $n = 3;
    } elseif ($filesize > pow(1024, 2))
    {
        $n = 2;
    } elseif ($filesize > 1024)
    {
        $n = 1;
    } else
    {
        $n = 0;
    }
    $filesize = ($filesize / pow(1024, $n));
    $filesize = round($filesize, 1);
    return $filesize.' '.$size[$n];
}

/////////////////////////////////////////////////////////////

function endi( $count, $msg = array( ) ) {
    $count = (int) $count;
    $max   = $count % 100;
    $min   = $count % 10;
    if ( $max > 10 && $max < 20 ) {
        return $count . ' ' . $msg[ 2 ];
    }
    if ( $min > 1 && $min < 5 ) {
        return $count . ' ' . $msg[ 1 ];
    }
    if ( $min == 1 ) {
        return $count . ' ' . $msg[ 0 ];
    }
    return $count . ' ' . $msg[ 2 ];
}

  function highlight($code)
{
$code = strtr($code, array('<br />' => "\r\n", '<br/>' => "\r\n", '&lt;' => '<', '&gt;' => '>', '&amp;' => '&', '&#36;' => '$', '&quot;' => '"', '&#039;' => "'", '&#92;' => '\\', '&#96;' => '`', '&#37;' => '%', '&#94;' => '^'));

if (!strpos($code, '<?') && mb_substr($code, 0, 2, 'UTF-8') != '<?')
{
	$code = "<?php\r\n" . trim($code) . "\r\n?>";
}

$code = highlight_string($code, true);
$code = strtr($code, array('<br />' => "\r\n", '<br/>' => "\r\n"));
$code = preg_replace('|(&nbsp;{2,})|', "\r\n\1", $code);
        $codeline = explode("\r\n", $code);
        $ret = '';

              foreach($codeline as $line => $string)
			  {
                    if ($string != '') {
         				$ret .= '<li>&nbsp;' . trim($string) . '</li>';
                    }
              }

$code = strtr($ret, array('$' => '&#36;', "'" => '&#039;', '\\' => '&#92;', '`' => '&#96;', '%' => '&#37;', '^' => '&#94;'));
return '<div class="code"><ol>' . $code . '</ol></div>';
}

function title($page){
echo '<div class="tile"><div class="t-header th-alt bg-teal"><div class="th-title"><i class="zmdi zmdi-widgets"></i><a href="/"style="text-decoration:none; color:white;"> Главная</a> :: '.$page.'</div></div></div>';
}

function err($page){
echo '<div class="list-group-item media">'.$page.'</div>';
}

function GenCode()
{
    $code = strtoupper(substr(md5(rand(1, 9999) + time() + rand(1, 9999)), -10));
    return $code;
}

function write_ini_file($filename, $config) {

    if (is_readable($filename)) {

        $res = NULL;

        foreach ($config as $section => $values) {

            $res .= '[' . $section . ']' . PHP_EOL;

            foreach ($values as $key => $value) {

                $res .= $key . ' = ' . (is_numeric($value) ? $value : '"' . $value . '"') . PHP_EOL;

            }

            $res .= PHP_EOL;

        }

        return file_put_contents($filename, $res);

    } else
        return false;

}

?>