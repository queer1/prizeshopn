<?php 	
require_once("sessionhandle.php");
if(isset($_COOKIE['prizeshopn'])) {
  unset($_COOKIE['prizeshopn']);
  setcookie('prizeshopn', '', time() - 3600); // empty value and old timestamp
}
sleep(1);
$type=$_POST['type'];
$name=$_POST['name'];
$fname=$_POST['fname'];
$lname=$_POST['lname'];
$email=$_POST['email'];
$userName=$_POST['userName'];
$gender=$_POST['gender'];
$age=$_POST['age'];
$birthday=$_POST['birthday'];
$location=$_POST['location'];
$games=$_POST['games'];
$friendsGames=$_POST['friendsGames'];
$timezone=$_POST['timezone'];
$verified=$_POST['verified'];
$devices=$_POST['devices'];
$provider=$_POST['provider'];
$socialId=$_POST['socialId'];
$id=$_POST['id'];

require_once 'firebaseLib.php';

// --- set up your own database here
$url = 'https://prizeshopn.firebaseio.com';
$token = 'shC3Et2vaD4WC0w8OyTjZXPBlj3NvFZvoBhBt4eg';

$fb = new fireBase($url, $token);

if($type!=2):
$rand = substr(md5(microtime()),rand(0,26),5);
$seed = str_split('abcdefghijklmnopqrstuvwxyz'
                 .'0123456789'); // and any other characters
shuffle($seed); // probably optional since array_is randomized; this may be redundant
$rand = '';
foreach (array_rand($seed, 5) as $k) $id .= $seed[$k];

$todo = array(
  'name' => $name,
  'firstName' => $fname,
  'lastName' => $lname,
  'email' => $email,
  'userName' => $userName,
  'gender' => $gender,
  'age' => $age,
  'birthday' => $birthday,
  'location'=>$location,
  'games'=>$games,
  'friendsGames' => $friendsGames,
  'timezone' => $timezone,
  'verified' => $verified,
  'devices'=>$devices,
  'provider'=>$provider,
  'id'=>$id,
);
$todoPath = 'users/active/'.$socialId.'';
$response = $fb->set($todoPath, $todo);

//add balance to users account
$todo = array(
  'tokens' => 2,
  'cash' => 0,
  'freeTokens' => 0,
);
$todoPath = 'users/active/'.$socialId.'/balance/';
$response = $fb->set($todoPath, $todo);

//note the last time the user logged in
$todo = array(
  'socialID' => $socialId,
  'joinDate' => date("Y-m-d H:i:s"),
  'type'=>$type,
);
$todoPath = 'users/active/IDs/'.$id.'';
$response = $fb->set($todoPath, $todo);


//reset cookie
$expire=time()+60*60*24*30;
setcookie("prizeshopn", $id, $expire);
setcookie("prizeshopnName", $_POST['userName'], $expire);


$_SESSION["frontuser"]=$id;
$_SESSION["frontname"]=$userName;

$return= $_POST['id'];
echo json_encode($return);

else:
//note the last time the user logged in
$todo = array(
  'date' => date("Y-m-d H:i:s"),
);
$todoPath = 'users/active/'.$socialId.'/logins/';
$response = $fb->push($todoPath, $todo);

$expire=time()+60*60*24*30;
setcookie("prizeshopn", $id, $expire);
setcookie("prizeshopnName", $_POST['userName'], $expire);

$_SESSION["frontuser"]=$_POST['id'];
$_SESSION["frontname"]=$_POST['userName'];

$return= $_POST['id'];
echo json_encode($return);
endif;

?>
