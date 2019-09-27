<?

function komblock($id,$text,$mode){
  global $user;

if(DB::$dbs->querySingle("SELECT count(id) from `opt` where `kto` = ? and `chto` = ?",array($user['id'],'block'))>=1){
  $res = 'Вы не можете писать комментарии пока ваше подозрительное сообщение не пройдёт модерацию!';
}else{
  
if(preg_match ("/.ru|.biz|.com|.org|.su|http|www|. r u|. ru|mirmas|homewm|mastera-kh|bymas|profiwm|mast-wap|.tk|tk|.тк|nezwap|delowap|.net|бля|пизд|хуй/i", $text) or preg_match ("/phpzona.ru|dcms.biz|xakerka.ru|xak.pablu.ru|nafermi.ru/i", $text)>1){

  if($mode == 1){ $mod = 'Личное сообщение';}
  elseif($mode == 2){ $mod = '(Файл)Комментарий';}
  elseif($mode == 3){ $mod = '(Коды)Комментарий';}
  elseif($mode == 4){ $mod = '(Новости)Комментарий';}

  $options = ''.$mod.'-'.$user['id'].'-'.$id.'-'.time();
  DB::$dbs->query("INSERT INTO `blocks` set `text` = ?, `options` = ?",array($text,$options));
  DB::$dbs->query("INSERT INTO `opt` set `kto` = '".$user['id']."', `chto` = 'block'");
  $res = 'Ваше сообщение показалось нам подозрительным, дождитесь модерации администратором.';
}else{
  $res = NULL;
}

}

return $res;
}


// Проверка URL на валидацию
function checkUrls($url) {
$list = [
'fremas.ru',
'google.ru',
'google.com',
'yandex.ru',
'goodtop.ru'
];

$_url = parse_url($url);
$_url = str_replace('www.', '', $_url['host']);

if (in_array($_url, $list))
return true;
else
return false;
}

// Первый CallBack для обрабочика ссылок
function links_preg1($arr){
if (preg_match("/(http)/i", $arr['1'])) {
if (checkUrls($arr['1']))
return '<a href="/all/str.php?url='.$arr['1'].'" class="urlSuccess">'.$arr['2'].'</a>';
else
return '<a href="/all/str.php?url='.$arr['1'].'" class="urlFail">'.$arr['2'].'</a>';
}else{
if (checkUrls($arr['1']))
return '<a href="/all/str.php?url='.$arr['1'].'" class="urlSuccess">'.$arr['2'].'</a>';
else
return '<a href="/all/str.php?url='.$arr['1'].'" class="urlFail">'.$arr['2'].'</a>';
}
}

// Второй CallBack для обрабочика ссылок
function links_preg2($arr){
if (preg_match("/(http)/i", $arr['2'])) {
if (checkUrls($arr['2']))
return $arr['1'].'<a href="/all/str.php?url='.$arr['2'].'" class="urlSuccess">'.$arr['2'].'</a>'.$arr['4'];
else
return $arr['1'].'<a href="/all/str.php?url='.$arr['2'].'" class="urlFail">'.$arr['2'].'</a>'.$arr['4'];
}else{
if (checkUrls($arr['2']))
return $arr['1'].'<a href="/all/str.php?url='.$arr['2'].'" class="urlSuccess">'.$arr['2'].'</a>'.$arr['4'];
else
return $arr['1'].'<a href="/all/str.php?url='.$arr['2'].'" class="urlFail">'.$arr['2'].'</a>'.$arr['4'];
}
}


function zc_file($id)
{
	global $dbs;
	if (is_array ($id)) {
                $id = $id[1];
        }else {
                $id = $id;
        }
    $zc =  DB::$dbs->queryFetch("SELECT * FROM `zc_file` WHERE `id` = '".$id."' LIMIT 1");
  return (empty($zc)?'[Файл не найден]':'<i class="zmdi zmdi-file-text"></i> <b> <a href="/zc/file'.$zc['id'].'">'.$zc['name'].'</b></a> ');
}

function forum_tema($id)
{
	global $dbs;
	if (is_array($id)) {
                $id = $id[1];
        }else{
                $id = $id;
        }
    $t =  DB::$dbs->queryFetch("SELECT * FROM `forum_t` WHERE `id` = '".$id."' LIMIT 1");
  return (empty($t)?'[Тема не найдена]':'<i class="zmdi zmdi-comments"></i> <b> <a href="/forum/t'.$t['id'].'">'.$t['name'].' </b></a> (Автор : '.aname($t['author']).') ');
}

function bbcode($mes){
  global $user;
  //$mes=htmlspecialchars($mes);
  $mes=stripslashes($mes);
  $mes = preg_replace('#\[url=(.*?)\](.*?)\[/url\]#si', '<a href="\1">\2</a>', $mes);
  $mes = preg_replace('#\[i\](.*?)\[/i\]#si', '<i>\1</i>', $mes);
  $mes = preg_replace('#\[b\](.*?)\[/b\]#si', '<b>\1</b>', $mes);
  $mes = preg_replace('#\[u\](.*?)\[/u\]#si', '<u>\1</u>', $mes);
  $mes = preg_replace('#\[ut\](.*?)\[/ut\]#si', '<span style="border-bottom: 1px dotted;">\1</span>', $mes);
  $mes = preg_replace('#\[small\](.*?)\[/small\]#si', '<span style="font-size:xx-small;">\1</span>', $mes);
  $mes = preg_replace('#\[das\](.*?)\[/das\]#si', '<span style="border:1px dashed;">\1</span>', $mes);
  $mes = preg_replace('#\[marq\](.*?)\[/marq\]#si', '<marquee>\1</marquee>', $mes);
  $mes = preg_replace('#\[c\](.*?)\[/c\]#si', '<center>\1</center>', $mes);
  $mes = preg_replace('#\[right\](.*?)\[/right\]#si', '<span style="text-align: right; display: block;">\1</span>', $mes);
  $mes = preg_replace('#\[sol\](.*?)\[/sol\]#si', '<span style="border:1px solid;">\1</span>', $mes);
  $mes = preg_replace('#\[ex\](.*?)\[/ex\]#si', '<span style="text-decoration:line-through;">\1</span>', $mes);
  $mes = preg_replace('#\[dot\](.*?)\[/dot\]#si', '<span style="border:1px dotted;">\1</span>', $mes);
  $mes = preg_replace('#\[dou\](.*?)\[/dou\]#si', '<span style="border:3px double black;">\1</span>', $mes);
  $mes = preg_replace('#\[big\](.*?)\[/big\]#si', '<span style="font-size:large;">\1</span>', $mes);
  $mes = preg_replace('#\[code\](.*?)\[/code\]#si', '<code>\1</code>', $mes);
  $mes = preg_replace('#\[red\](.*?)\[\/red\]#si', '<span style="color:#FF0000;">\1</span>', $mes);
  $mes = preg_replace('#\[white\](.*?)\[\/white\]#si', '<span style="color:#ffffff;">\1</span>', $mes);
  $mes = preg_replace('#\[blue\](.*?)\[\/blue\]#si', '<span style="color:#00008b;">\1</span>', $mes);
  $mes = preg_replace('#\[green\](.*?)\[\/green\]#si', '<span style="color:#006400;">\1</span>', $mes);
  $mes = preg_replace('#\[cit\](.*?)\[/cit\]#si', '<div class="citat">\1</div>', $mes);
  $mes = preg_replace('#\[img\](.*?)\[/img\]#si', '<a href="\1"><img src="\1" style="max-width:300px;" alt="*"/></a>', $mes);
  $mes = preg_replace('#\[wm\](.*?)\[\/wm\]#si', '<a href="http://passport.webmoney.ru/asp/CertView.asp?wmid=\1"><img src="/images/wmid.png"> WMID: \1</a> (BL : <img src="http://bl.wmtransfer.com/img/bl/\1?w=45&h=18&bg=0XDBE2E9">)', $mes);
  $mes = preg_replace('|us{(\d*)}|sU', aname('\1'), $mes);
  $mes = preg_replace_callback('/file{(\d*)}/', 'zc_file', $mes);
  $mes = preg_replace_callback('/theme{(\d*)}/', 'forum_tema', $mes);
  $mes = preg_replace('#us{this}#si', '<a href="/us'.$user['id'].'">'.name($user['id']).'</a>', $mes);
  
  $mes = preg_replace_callback('~\[url=([a-z]+://[^ \r\n\t`\'"]+)\](.*?)\[/url\]~iu', 'links_preg1', $mes);
  $mes = preg_replace_callback('~(^|\s)([a-z]+://([^ \r\n\t`\'"]+))(\s|$)~iu', 'links_preg2', $mes);

  //$mes = us($mes);
  


  
  
  $mes=str_replace("\r\n","<br/>",$mes);
  $mes=str_replace("[br]","<br/>",$mes);
  $mes=str_replace("
  ","<br/>",$mes);

  return $mes; 

  }
  
  
function smiles($text){
  $text = trim($text);
  $smiles = DB::$dbs->query("SELECT * FROM `smiles` ORDER BY `id` DESC");
  while($smiles2 = $smiles -> fetch()){
  $text = str_replace($smiles2['name'],' <img src="/files/smiles/'.$smiles2['img'].'" alt="'.$smiles2['name'].'"/> ',$text);
  }
  return $text;

  }
  
  
  function quickpaste($form) {
?><script language="JavaScript" type="text/javascript">
jQuery(function() {
    $('form[class!=ajax]').on('keypress',function(e){
        if((e.which == 13||e.which == 10) && e.ctrlKey){
            this.submit();
        }
    }); 
    $('.smiles img').on('click',function(){
        var alt = $('#'+this.id).attr('alt');
        var text = $("textarea")[0].value+' '+alt;
        $("textarea")[0].value = text;
    });
    $('.bb span').on('click',function(){
        var alt;
        if($('#'+this.id).attr('title')==null){
            alt = $('#'+this.id).attr('tooltip');
        }else{
            alt = $('#'+this.id).attr('title');
        }
        var text = $("textarea")[0].value+' '+alt;
        $("textarea")[0].value = text;
    });
    $(".Sopen").on('click',function(){
        if(this.id==''){
            $(this).attr('id', 'openbutton'+Math.floor(Math.random()*1001));
        }     
        var parId;
        var openid = this.id; 
        $("*:has(#"+openid+")").each(function(){
            if(this.id==''){
                $(this).attr('id', 'randomid'+Math.floor(Math.random()*1001));
            }
            parId = this.id; 
        });
        $("#"+parId+">.close").toggle("fast");
    });
});
function remove_message(id){
    $("#"+id).remove();
}
    
function hide_message(id){
    $("#"+id).hide("slow");
    setTimeout('remove_message("'+id+'")', 1000);
}
function message(message, liveTime){
    var id = 'js_message_'+parseInt(Math.random()*1000);
    $("body").append('<div id="'+id+'" class="js_message"><a class="close" href="">[X]</a>'+message+'</div>');
    $("#"+id).show("slow");
    $("#"+id+" a.close").click(function(){
        hide_message(id);
        return false;
    });
        
    if(liveTime!=null){
        if(liveTime ==-1){
            liveTime = (5+parseInt(message.length/10))*500;
        }
        setTimeout('hide_message("'+id+'")', liveTime);
    }
}
</script>
<style type="text/css">
.Sopen{border:solid 1px #cccccc;padding:5px;margin-top:20px;border-radius: 6px; cursor: pointer;}
.Sopen:hover{background-color: #B7D6F0;}
.open:hover{background-color: #141414;}
.close{display: none;border:solid 1px #cccccc;padding:5px;margin:20px;border-radius: 6px;}
.smiles img{cursor: pointer;}
.bb span{cursor:pointer;}
</style><?
}


function quickpanel() {
?>

<br/><span class="smiles"><span class="Sopen">Смайлы</span><div class="close">

<?
$smiles =DB::$dbs->query("SELECT * FROM `smiles` where `c` = '1' ORDER BY `id` DESC");
while($smiles2 = $smiles-> fetch())
{
?><img id="sm<?=$smiles2['id']?>" src="/files/smiles/<?=$smiles2['img']?>" alt="<?=$smiles2['name']?>" /> <?
}
?>

</div></span>


<span><span class="Sopen">BB коды</span><div class="close"><div class="bb">
<span id="bb1" title="[url=][/url]">[Url]</span> 
<span id="bb2" title="[i][/i]">[<em>Курс</em>]</span> 
<span id="bb3" title="[b][/b]">[<strong>Жир</strong>]</span> 
<span id="bb4" title="[u][/u]">[<text style="text-decoration:underline;">Подч</text>]</span> 
<span id="bb5" title="[ut][/ut]">[<text style="border-bottom: 1px dotted;">Пун линия</text>]</span> 
<span id="bb6" title="[small][/small]">[<text style="font-size:xx-small;">Мелк</text>]</span> 
<span id="bb7" title="[das][/das]">[<text style="border:1px dashed;">Пунк табличка</text>]</span> 
<span id="bb8" title="[marq][/marq]">[Бег строка]</span> 
<span id="bb9" title="[c][/c]">[Центр]</span> 
<span id="bb10" title="[right][/right]">[Справа]</span> 
<span id="bb11" title="[sol][/sol]">[<text style="border:1px solid;">Табличка</text>]</span>
<span id="bb12" title="[ex][/ex]">[<text style="text-decoration:line-through;">Зачеркн</text>]</span>
<span id="bb13" title="[dot][/dot]">[<text style="border:1px dotted;">Пунктир</text>]</span>
<span id="bb14" title="[dou][/dou]">[<text style="border:3px double black;">Двойная табл</text>]</span>
<span id="bb15" title="[big][/big]">[<text style="font-size:large;">Больш</text>]</span>
<span id="bb16" title="[code][/code]">[PHP]</span>
<span id="bb17" title="[red][/red]">[<text style="color:#ff0000;">Красн</text>]</span>
<span id="bb18" title="[white][/white]">[Белый]</span>
<span id="bb19" title="[blue][/blue]">[<text style="color:#0000bb;">Синий</text>]</span>
<span id="bb20" title="[green][/green]">[<text style="color:#00bb00;">Зелен</text>]</span>
<span id="bb21" title="[cit][/cit]">[Цитата]</span>
<span id="bb22" title="[img][/img]">[Изобр]</span>

</div></div><br/><br/>
<?
}

function fast_bb($var){
    global $dbs;?>
<a class="open_window" href="#" title="Смайлы"><img src="/images/bb/smile.png" alt=""></a> | 
<script charset="utf-8" src="/js/bbcode.js" type="text/javascript"></script>   
<a href="javascript:bb_b('<?=$var?>');" title="Жирный"><img src="/images/bb/b.gif" alt=""></a>   
<a href="javascript:bb_i('<?=$var?>');" title="Курсив"><img src="/images/bb/i.gif" alt=""></a>   
<a href="javascript:bb_u('<?=$var?>');" title="Подчеркивание"><img src="/images/bb/u.gif" alt=""></a>   
<a href="javascript:bb_code('<?=$var?>');" title="Код"><img src="/images/bb/code.gif" alt=""></a>
<a href="javascript:bb_url('<?=$var?>');" title="Ссылка"><img src="/images/bb/url.gif" alt=""></a>   
<a href="javascript:bb_q('<?=$var?>');" title="Цитата"><img src="/images/bb/q.gif" alt=""></a>   
<a href="javascript:bb_img('<?=$var?>');" title="Картинкa"><img src="/images/bb/img.gif" alt=""></a>   
<a href="javascript:bb_red('<?=$var?>');" title="Красный"><img src="/images/bb/red.gif" alt=""></a>   
<a href="javascript:bb_green('<?=$var?>');" title="Зеленый"><img src="/images/bb/green.gif" alt=""></a>   
<a href="javascript:bb_blue('<?=$var?>');" title="Синий"><img src="/images/bb/blue.gif" alt=""></a><br>  
<div class="overlay"></div>
<div class="popup">
 <div id="cont" class="cont">

<?
$smiles = DB::$dbs->query("SELECT * FROM `smiles` where `c` = '1' ORDER BY `id` DESC");
while($smiles2 = $smiles-> fetch())
{?>
<img class="close_window" style="max-width: 50px;" onclick="smile('<?=$smiles2['name']?>')" src="/files/smiles/<?=$smiles2['img']?>" alt="<?=$smiles2['name']?>">
<?}?> 
</div>
</div>

<script type="text/javascript">
$('.popup .close_window, .overlay').click(function (){
$('.popup, .overlay').css({'opacity': 0, 'visibility': 'hidden'});
});
$('a.open_window').click(function (e){
$('.popup, .overlay').css({'opacity': 1, 'visibility': 'visible'});
e.preventDefault();
});
</script>

<script>
function smile(sm){
document.getElementById('<?=$var?>').value+=''+sm+' ';
}
</script>
<?}

?>