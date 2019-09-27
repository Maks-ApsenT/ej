<?
require_once('classes/core.php');
$title = "Преподавательская";
require_once('classes/head.php');
mode('admin');
?>

<div id="ajax-block"></div>

<script>
$(function(){
		get('templates/teacher_journal.php');
});
</script>

<?
require_once('classes/foot.php');
?>