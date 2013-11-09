<?
session_start();
include_once('../config.php'); 
include_once('../sessionhandle.php'); 

//get the user id
$userId=$_COOKIE['grbbit'];

//get game id
if(isset($_GET['id']))
{
$gameId=$_GET['id'];
}	

//load game table
include_once("gameTable.php");
include_once("gameUserGrabs.php");


if (strlen($title) > 30):
   $title = substr($title, 0, 29) . '...</a>';
endif;
if($retailPrice<25):
	$maxSaves=1;
endif;

if($retailPrice>=25 && $retailPrice<50):
	$maxSaves=2;
endif;

if($retailPrice >=50 && $retailPrice<100):
	$max_save=3;
endif;

if($rprice >=100):
	$maxSaves=3;
endif;


// if there is a stripe deposit pending
if($_SESSION['amount']<500):
$_SESSION['amount']=$_SESSION['amount']+50;
endif;
include_once ('../stripe/lib/Stripe.php');

// Create the charge on Stripe's servers - this will charge the user's card
if ($_POST) {
Stripe::setApiKey("08SxAVFWynuz1Jo1Ny4qYqgsE87pj7bx");
$error = '';
$success = '';
try {
if (!isset($_POST['stripeToken']))
throw new Exception("The Stripe Token was not generated correctly");
	  
$charge=Stripe_Charge::create(array(
"amount" => $_SESSION['amount'],
"currency" => "usd",
"card" => $_POST['stripeToken'],
));
$success="$$amount added to your account";
$success2=1;
	
if($success2==1){
$tokens=$charge->amount/100;
$tokens=$tokens/.25;
//$add1=$charge->add1;
//$add2=$charge->add2;
//$city=$charge->city;
//$state=$charge->state;
//$zip=$charge->zip;

//update the users table with new tokens
$st = $db->prepare("SELECT * FROM `users` SET tokens=(tokens+:tokens) WHERE `id` = :userId");
$st->bindParam(':userId', $userId); // filter
$st->bindParam(':tokens', $tokens); // filter
$st->execute();

//  insert into deposits table
$stmt = $db->prepare("INSERT INTO `userDeposit`(`userId`,`tokens`,`date`,`cash`) 
VALUES (:userId, :tokens, :date, :cash)");
$stmt->bindParam(':userId', $userId);
$stmt->bindParam(':tokens', $tokens);
$stmt->bindParam(':date', $date);
$stmt->bindParam(':cash', $cash);

$date = date('Y-m-d h:i:s');
$cash=1;
$stmt->execute();

$mail_To = "anari@gamerholic.com";
$mail_Subject = "Grbbit token purchase";
$mail_Body = "Anari,"."\r\n" ."Your idea to convert the site to tokens is working. You just got another $tokens purchased"."\r\n" ."Customer Service
"."\r" ."Gamerholic.com";
//start $headers
$headers = "MIME-Version: 1.0" . "\r\n"; 
$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n"; //adds content type to headers
$headers .= "From: Gamerholic.com" . "\r\n"; //adds the sender details
mail($mail_To,$mail_Subject,$mail_Body,$headers); //sends the email

$mail_To = $myEmail;
$mail_Subject = "Token purchase";
$mail_Body = "$myName how are you,"."\r\n" ."We recieved your purchase of $tokens. Your account has been credited"."\r\n" ."Customer Service
"."\r" ."Gamerholic.com";
//start $headers
$headers = "MIME-Version: 1.0" . "\r\n"; 
$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n"; //adds content type to headers
$headers .= "From: Grbbit.com" . "\r\n"; //adds the sender details
mail($mail_To,$mail_Subject,$mail_Body,$headers); //sends the email

unset($_SESSION['amount']);	

	}
  }
catch (Exception $e) {
$error = $e->getMessage();
unset($_SESSION['amount']);	

  }
}
$gameStarted=0;
?>
<script>
// Tabs
$('.demoTabs a, .demoTabs2 a').click(function (e) {
e.preventDefault();
$(this).tab('show');
})
</script>
<article class="span9 primary data-block">
<header>
<h2> Grabbit & It's Yours!</h2>
<ul class="data-header-actions">
<li class="demoTabs active"><a href="#gameTab">Grbbit</a></li>
<li class="demoTabs"><a href="#howtoplay">How to play</a></li>
<li class="demoTabs"><a href="#freetokens">Get FREE tokens</a></li>
<li class="demotabs"> 
<p style="margin-top:-13px">
											<div class="btn-group">
												<button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Tools  <span class="caret"></span></button>
												<ul class="dropdown-menu">
													<li style="margin:3px"><a href="#loadTokensTab" data-toggle="tab" id="loadTokens">Get More Tokens</a></li>
													<li style="margin:3px"><a href="#blockPlayersTab" data-toggle="tab" id="blockPlayers">Block Player</a></li>
													<li style="margin:3px"><a href="#addGrabbitTab" data-toggle="tab" id="addGrabbit">Add Your Own Grbbit</a></li>
													
													
													
												</ul>
											</div>
										</p>
										<script>
										
                                         $("#loadTokens").click(function(){
                                         	$( "#gameTab" ).tabs({ active: 0 });
                                         	$( "#loadTokensTab" ).tabs({ active: 1 });

                                         })
                                          $("#blockPlayers").click(function(){
                                         	$( "#gameTab" ).tabs({ active: 0 });
                                         	$( "#blockPlayersTab" ).tabs({ active: 1 });

                                         })
                                           $("#addGrabbit").click(function(){
                                         	$( "#gameTab" ).tabs({ active: 0 });
                                         	$( "#addGrabbitTab" ).tabs({ active: 1 });

                                         })
										</script>
</li>

</ul>
</header>
<section class="tab-content">

<!-- Tab #game -->
<div class="tab-pane active" id="gameTab">

<div style="width:100%; min-height:250px">

<div class="span6" style="text-align:center;" id="gameTimer">

</div>
<script>
var refreshId = setInterval(function()
{
     $('#gameTimer').load('game/gameTimer.php?id=<?php echo $gameId ?>').fadeIn("slow");
}, 1000);

</script>

<p style="text-align:center" class="span2">
<a href="#joinTab" data-toggle="tab" class="btn btn-alt btn-info btn-large" id="join"><br /><span><img src="../img/grbbitJoin.png" /></span><br /> Join Game</a><br /><br />
<a href="#grabFriendsTab" data-toggle="tab" class="btn btn-alt btn-primary btn-large"><br /><span><img src="../img/grbbitFriends.png" /></span><br /><span style="font-size:14px"> Grab Friends +10 tokens each friend each game</span></a>
</p>
</div>

</div>
							<!-- /Tab #game -->
							
							<!-- Tab #howtoplay -->
							<div class="tab-pane" id="howtoplay">

							how to play

							</div>
							<!-- /Tab #howtoplay -->

                                <!-- Tab #freetokens -->
							<div class="tab-pane" id="freetokens">

							free tokens

							</div>
							<!-- /Tab #freetokens -->
                            
                                <!-- Tab #freetokens -->
							<div class="tab-pane" id="joinTab">

<div style="width:100%; min-height:250px">

<div class="span6" style="text-align:center;">
<p class="well">
By joining you confirm you know how grbbit.com works. The tokens you use to join this game are none refund-able, even if you don't click the grabbit button when the game starts.<br /><br />
Were you referred to this game? if so we owe the player who reffered you 10 tokens for this game. Enter their name or email below
</p>
<form>
<input type="hidden" id="gameId" value="<? echo $gameId ?>" />
<input type="text" value="" id="referred" />
<a href="#" class="btn btn-alt btn-primary btn-large" id="joinGame">Join game, I'm ready to grabbit</a>
<div id="joinMessage" style="display: none; color:#333; text-align:center; padding:13px">
</div>
<div id="joinWaiting" style="display: none; font-size:16px; color:#333; font-weight:bolder; text-align:center; padding:13px" >
Please wait
</div>
<script>
$('#joinGame').click(function() {
$('#joinWaiting').show(500);
$('#joinMessage').hide(0);
$.ajax({
type : 'POST',
url : 'game/gameJoin.php',
dataType : 'json',
data: {
gameid : $('#gameId').val()
},
success : function(data){
$('#joinWaiting').hide(1000);
$('#joinMessage').removeClass().addClass((data.error === true) ? 'error' : 'success')
.text(data.msg).show(500);
if (data.error === true)
$('#loginForm').show(500);
else
$('#join').hide(1000); // Members Area
$('#joinGame').hide(1000); // Members Area
},
error : function(XMLHttpRequest, textStatus, errorThrown) {
$('#joinWaiting').hide(1000);
$('#joinMessage').removeClass().addClass('error')
.text('There was an error.').show(500);
}
});
return false;
});
  </script>

</form>
</div>

<p style="text-align:center" class="span2">
<a href="#gameTab" data-toggle="tab" class="btn btn-alt btn-success btn-large" ><br /><span><img src="../img/grbbitBackButton.png" /></span><br /> Go Back</a><br /><br />
<a href="#grabFriendsTab" data-toggle="tab" class="btn btn-alt btn-primary btn-large"><br /><span><img src="../img/grbbitFriends.png" /></span><br /><span style="font-size:14px"> Grab Friends +10 tokens each friend each game</span></a>
</p>
</div>

							</div>
							<!-- /Tab #freetokens -->
                            
                            <!-- Tab #grabbitHoleTab -->
<div class="tab-pane" id="grabbitHoleTab">

<div style="width:100%; min-height:407px">

<div class="span6" style="text-align:center;">
<img src="../images/grabbitHoleExplained.jpg" />
</div>

<p style="text-align:center" class="span2">
<a href="#gameTab" data-toggle="tab" class="btn btn-alt btn-info btn-large"><br /><span><img src="../img/grbbitBackButton.png" /></span><br /> Go Back</a><br /><br />
<a href="#grabFriendsTab" data-toggle="tab" class="btn btn-alt btn-primary btn-large"><br /><span><img src="../img/grbbitFriends.png" /></span><br /><span style="font-size:14px"> Grab Friends +10 tokens each friend each game</span></a>
</p>
</div>

</div>
							<!-- /Tab #grabbitHoleTab -->
                            <!-- Tab #grabbitTab -->
<div class="tab-pane" id="grabbitTab">

<div style="width:100%; min-height:407px">

<div class="span6" style="text-align:center;">
<img src="../images/grabbitExplained.jpg" />
</div>

<p style="text-align:center" class="span2">
<a href="#gameTab" data-toggle="tab" class="btn btn-alt btn-info btn-large"><br /><span><img src="../img/grbbitBackButton.png" /></span><br /> Go Back</a><br /><br />
<a href="#grabFriendsTab" data-toggle="tab" class="btn btn-alt btn-primary btn-large"><br /><span><img src="../img/grbbitFriends.png" /></span><br /><span style="font-size:14px"> Grab Friends +10 tokens each friend each game</span></a>
</p>
</div>

</div>
							<!-- /Tab #grabbitTab -->
                            <!-- Tab #grabbitSlapTab -->
<div class="tab-pane" id="grabbitSlapTab">

<div style="width:100%; min-height:407px">

<div class="span6" style="text-align:center;">
<img src="../images/grabbitSlapExplained.jpg" />
</div>

<p style="text-align:center" class="span2">
<a href="#gameTab" data-toggle="tab" class="btn btn-alt btn-info btn-large"><br /><span><img src="../img/grbbitBackButton.png" /></span><br /> Go Back</a><br /><br />
<a href="#grabFriendsTab" data-toggle="tab" class="btn btn-alt btn-primary btn-large"><br /><span><img src="../img/grbbitFriends.png" /></span><br /><span style="font-size:14px"> Grab Friends +10 tokens each friend each game</span></a>
</p>
</div>

</div>
							<!-- /Tab #grabbitTab -->
                            <!-- Tab #grabDealTab -->
<div class="tab-pane" id="grabDealTab">

<div style="width:100%; min-height:345px">

<div class="span6" style="text-align:center;">
<img src="../images/grabDealExplained.jpg" />
</div>

<p style="text-align:center" class="span2">
<a href="#gameTab" data-toggle="tab" class="btn btn-alt btn-info btn-large"><br /><span><img src="../img/grbbitBackButton.png" /></span><br /> Go Back</a><br /><br />
<a href="#grabFriendsTab" data-toggle="tab" class="btn btn-alt btn-primary btn-large"><br /><span><img src="../img/grbbitFriends.png" /></span><br /><span style="font-size:14px"> Grab Friends +10 tokens each friend each game</span></a>
</p>
</div>

</div>
							<!-- /Tab #grabbitTab -->
                            <!-- Tab #grabDealTab -->
<div class="tab-pane" id="grabFriendsTab">

<div style="width:100%; min-height:250px">

<div class="span6" style="text-align:center;">
<p class="well">
Each game needs a minimum number of players to start. Invite your friends to join the game you are in and you'll get 10 tokens to use
for that game. Just have them enter your user name or your email when they join the game.
</p>
</div>

<p style="text-align:center" class="span2">
<a href="#gameTab" data-toggle="tab" class="btn btn-alt btn-info btn-large"><br /><span><img src="../img/grbbitBackButton.png" /></span><br /> Go Back</a><br /><br />
<a href="#grabFriendsTab" data-toggle="tab" class="btn btn-alt btn-primary btn-large"><br /><span><img src="../img/grbbitFriends.png" /></span><br /><span style="font-size:14px"> Grab Friends +10 tokens each friend each game</span></a>
</p>
</div>

</div>
							<!-- /Tab #grabbitTab -->

							 <!-- Tab #loadTokensTab -->
<div class="tab-pane" id="loadTokensTab">

<div style="width:100%; min-height:250px">

<div class="span6" style="text-align:center;">
<p class="well">
Grbbit.com uses Gamerholic tokens. You can get piles of tokens in the <a href="http://www.gamerholic.com" target="_blank">Gamerholic arcade</a>, or you can load tokens to your account.
Each token is $0.25. You can also get free tokens in the Gamerholic <a href="http://www.m.gamerholic.com/store.php" target="_blank">app store</a>.
</p>
</div>

<p style="text-align:center" class="span2">
<a href="#gameTab" data-toggle="tab" class="btn btn-alt btn-info btn-large"><br /><span><img src="../img/grbbitBackButton.png" /></span><br /> Go Back</a><br /><br />
<a href="#grabFriendsTab" data-toggle="tab" class="btn btn-alt btn-primary btn-large"><br /><span><img src="../img/grbbitFriends.png" /></span><br /><span style="font-size:14px"> Grab Friends +10 tokens each friend each game</span></a>
</p>
</div>

</div>
							<!-- /Tab #loadTokensTab -->

							<!-- Tab #blockPlayersTab -->
<div class="tab-pane" id="blockPlayersTab">

<div style="width:100%; min-height:250px">

<div class="span6" style="text-align:center;">
<p class="well">
Worried about a really skilled player joining your game? You can block them from joining the game
if they are not already in the game. It will cost as many tokens as you needed to join the game, to block to a player.
</p>
</div>

<p style="text-align:center" class="span2">
<a href="#gameTab" data-toggle="tab" class="btn btn-alt btn-info btn-large"><br /><span><img src="../img/grbbitBackButton.png" /></span><br /> Go Back</a><br /><br />
<a href="#grabFriendsTab" data-toggle="tab" class="btn btn-alt btn-primary btn-large"><br /><span><img src="../img/grbbitFriends.png" /></span><br /><span style="font-size:14px"> Grab Friends +10 tokens each friend each game</span></a>
</p>
</div>

</div>
							<!-- /Tab #blockPlayersTab -->

						</section><br />
						<footer>
<p style="text-align:center" id="buttonInstructions">
<input type="hidden" value="<? echo $gameId ?>" id="id" /> 
<a href="#grabbitHoleTab" data-toggle="tab" class="btn btn-alt btn-info btn-large"><span><img src="../img/grbbitHoleBig.png" /></span> <br />Grabbit Hole</a>

&nbsp;&nbsp;&nbsp;&nbsp;<a href="#grabbitTab" data-toggle="tab" class="btn btn-alt btn-primary btn-large"><span><img src="../img/grbbit.png" /></span> <br />Grabbit</a>
&nbsp;&nbsp;&nbsp;&nbsp;<a href="#grabbitSlapTab" data-toggle="tab" class="btn btn-alt btn-info btn-large"><span><img src="../img/grbbitSlapBig.png" /></span> <br />Slap Grabber</a>
&nbsp;&nbsp;&nbsp;&nbsp;<a href="#grabDealTab" data-toggle="tab" class="btn btn-alt btn-danger btn-large"><span><img src="../img/grbbitDeal.png" /></span> <br />Grab Deal</a>


                            </p>
<p style="text-align:center; display:none" id="gamePlay">
<input type="hidden" value="<? echo $gameId ?>" id="id" /> 
<a href="#" id="grabbitHoleButton" class="btn btn-alt btn-info btn-large"><span><img src="../img/grbbitHoleBig.png" /></span> <br />Grabbit Hole</a>

&nbsp;&nbsp;&nbsp;&nbsp;<a href="grabbitButton" id="bid_placed" class="btn btn-alt btn-primary btn-large"><span><img src="../img/grbbit.png" /></span> <br />Grabbit</a>
&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" id="grabbitSlapButton" class="btn btn-alt btn-info btn-large"><span><img src="../img/grbbitSlapBig.png" /></span> <br />Slap Grabber</a>
&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" id="grabDealButton" class="btn btn-alt btn-danger btn-large"><span><img src="../img/grbbitDeal.png" /></span> <br />Grab Deal</a>


                            </p>                            
						</footer>
					</article>