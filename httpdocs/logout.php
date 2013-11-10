<?
session_start();
if(isset($_COOKIE['prizeshopn'])) {
  unset($_COOKIE['prizeshopn']);
  setcookie('prizeshopn', '', time() - 3600); // empty value and old timestamp
}
$_SESSION = array();    
session_destroy();

//re-direct to login screen (or any other you like):
header( "Location:index.php");
exit;	   
?>