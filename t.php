<?
require_once('classes/core.php');
$title = "Вход для преподавателей";
require_once('classes/head.php');
?>
<div id="ajax-block"></div>

<?
require_once('classes/foot.php');
?>

<script>
$(function(){
	get('templates/login_teacher.php');
});
</script>