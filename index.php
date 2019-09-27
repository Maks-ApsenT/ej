<?
require_once('classes/core.php');
require_once('classes/head.php');

if (isset($_GET['logout'])) {
	session_destroy();
	header('Location: index.php');
}
?>
<div id="ajax-block"></div>

<?
require_once('classes/foot.php');
?>

<script>
$(function(){
	get('templates/login_parent.php');
});
</script>