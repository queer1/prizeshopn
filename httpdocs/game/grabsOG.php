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
<div class="span9">

<!-- Grid row -->
<div class="row">
<!-- Data block -->
<article class="span9 data-block">
<header>
<h2><span class="awe-legal"></span> Grbbit For <? echo $title ?> <?= $gameid ?></h2>
<ul class="data-header-actions">
<li class="demoTabs active"><a href="#one" id="oneControl">Grbbit</a></li>
<li class="demoTabs"><a href="#two" id="twoControl">How To Play</a></li>
<li class="demoTabs"><a href="#three" id="threeControl">Get FREE Tokens</a></li>
</ul>
</header>
<section class="tab-content">
						
<!-- Tab #one -->
<div class="tab-pane active" id="one">
<!-- Second level tabs -->
<div class="tabbable tabs-left">
<ul class="nav nav-tabs">
<li class="active"><a href="#tab1" data-toggle="tab">Game</a></li>
<li><a href="#tab5" data-toggle="tab"><span class="awe-money orange"></span>Load Tokens</a></li>
<li><a href="#tab1a" data-toggle="tab">Details</a></li>

<li><a href="#tab2" data-toggle="tab" id="jtab">Join Game</a></li>
<li><a href="#tab4" data-toggle="tab">Block Players</a></li>
<li style="display:none"><a href="#tab6" data-toggle="tab">Load Saves</a></li>
<li><a href="#tab7" data-toggle="tab">Grab history</a></li>
<li><a href="#tab8a" data-toggle="tab">Referred</a></li>
<? if($video!=""){?>
<li><a href="#tab8b" data-toggle="tab">Video</a></li>
<? }?>

</ul>
<div class="tab-content">
<div class="tab-pane active" id="tab1">
<div id="gameTimer">
test
</div>
</div>
<div class="tab-pane" id="tab1a">
<p class="alert alert-inverse" style="text-align:center">
<? if($hidePrice>0){
$retailPrice="XXXX";	
}
?>
<strong><?php echo $title ?>:</strong> <?php echo $description2 ?><br><br>
<p style="text-align:center; font-size:16px; padding:2px; margin-top:7px"><strong>
<? if($adListing<1){?>
<? if ($hidePrice<1){?> Retail Price: $<?php echo number_format ($retailPrice,2); ?><? }?><? if ($hidePrice>0){?> Retail Price: <? echo $retailPrice ?><? }?></strong>
<? }?>

<? if($adListing>0){?>
<p id="contest_flex" style="text-align:center"></p>
<? }?>
</p>
<p style="text-align:center; font-size:12px; padding:2px">Delivery: <?php echo $delivery ?><br></p>
<p style="text-align:center; font-size:12px; padding:2px; margin-bottom:7px">Delivery Time: Within 7 Business days<br> </p>

</p>
</div>
<div class="tab-pane" id="tab2">
<h3>Join Game</h3>
<div class="well">
<h6>By Clicking the "Join" button you confirm the following</h6>
<p>
<strong>1.</strong> You are aware the <?php echo number_format ($fee) ?> tokens used to join this game is nonrefundable, even if you never click the grab button when the game starts.
<br />
<strong>2.</strong> You are fully aware of how Grbbit.com works.<br />
<strong>3.</strong> If the game is canceled your tokens will be returned to your account. <br />
<strong>4.</strong> If the game is rescheduled 2 or more times after you take a seat, you'll be automatically withdrawn from the game with tokens credited back to your account.<br />
<? if($started<1 && $vet<1){?>
<p style="text-align:center; margin-top:19px">
<input type="hidden" id="id" value="<?php echo $gameId?>" />
<a href="#" id="asend" class="btn btn-alt btn-success btn-large">Join Game</a></p>
<? }?>

<? if($started=='Y'){?>
<p class="alert alert-danger">Sorry this game started already, you won't be able to join. </p>
<? }?>
<div id="amessage" style="display: none; color:#F60; text-align:center">
</div>
<div id="awaiting" style="display: none; font-size:16px; color:#F60; font-weight:bolder; text-align:center" >
Please wait
</div>
<script>
$('#asend').click(function() {
$('#awaiting').show(500);
$('#demoForm').hide(0);
$('#amessage').hide(0);
$.ajax({
type : 'POST',
url : 'game/gameJoin.php',
dataType : 'json',
data: {
id : $('#id').val()
},
success : function(data){
$('#awaiting').hide(1000);
$('#amessage').removeClass().addClass((data.error === true) ? 'error' : 'success')
.text(data.msg).show(500);
if (data.error === true)
$('#loginForm').show(500);
else
$('#asend').hide(1000); // Members Area
$('#join').hide(1000); // Members Area
},
error : function(XMLHttpRequest, textStatus, errorThrown) {
$('#awaiting').hide(1000);
$('#amessage').removeClass().addClass('error')
.text('There was an error.').show(500);
$('#demoForm').show(500);
}
});
return false;
});
  </script>



</div>

</div>
<!--end div-->
<div class="tab-pane" id="tab4">
<h3>Follow this game</h3>
<div class="well">
<p>
Block a Player from joining this game. Blocking a player will cost you <?php echo number_format ($tokens,2); ?> Tokens. You can only block 1 player. You must block them before they take a seat in the game.</p>

<form>
<p style="text-align:center; margin-top:19px">
<input type="text" class="nice-i" id="ename" value="" placeholder="Players Name" />
<input type="hidden" id="pid" name="pid" value="<?php echo $pid ?>" />
<input type="hidden" id="tokens" name="tokens" value="<?php echo $tokens ?>" />

</p>
<p style="text-align:center; margin-top:11px; color:#F30; font-weight:bolder" id="messagef"></p>

<p style="text-align:center;">
<a href="#" id="block" class="btn btn-alt btn-large btn-success">Block Player</a>
</p>
</form>
<script>
$('#block').click(function(event) {
$('#waitingf').show(500);
$('#demoForm').hide(0);
$('#messagef').hide(0);
$.ajax({
type : 'POST',
url : 'gameBlock_hwnd.php',
dataType : 'json',
data: {
pid : $('#pid').val(),tokens : $('#tokens').val(),ename : $('#ename').val()
},
success : function(data){
$('#waitingf').hide(1000);
$('#messagef').removeClass().addClass((data.error === true) ? 'error' : 'success')
.text(data.msg).show(500);
if (data.error === true)
$('#loginForm').show(500);
else
$('#messagef').show(1000); // Members Area
},
error : function(XMLHttpRequest, textStatus, errorThrown) {
$('#waitingf').hide(1000);
$('#messagef').removeClass().addClass('error')
.text('There was an error.').show(500);
$('#demoForm').show(500);
}
});
event.preventDefault();
});
  </script>

</div>
<!--end well-->
</div>
<!--end tab -->


<div class="tab-pane" id="tab5">
<h3 style="display:none">Add tokens with Paypal</h3>
<div class="well" style="display:none">
<p class="alert alert-inverse" style="text-align:center"> Each bid is $0.25</p>
<form id="paypal_form" method="post">
<p style="text-align:center">
<select class="nice-i" id="pamount" style="color:#333">
<option value="">Select Tokens</option>
<option value="2.75">5 Tokens- $2.75</option>
<option value="5.50">10 Tokens- $5.50</option>
<option value="13.75">25 Tokens- $13.75</option>
<option value="27.50">50 Tokens- $27.50</option>
<option value="55">100 Tokens- $55.00</option>
<option value="82.50">150 Tokens- $82.50</option>
<option value="110">200 Tokens- $110.00</option>
<option value="137.50">250 Tokens- $137.50</option>
<option value="255">500 Tokens- $275.00</option>

</select>
</p>
<p style="text-align:center">
<a href="#" class="btn btn-large btn-primary" id="pay">Add Tokens</a>
</p>
<div id="messagep" style="display: none; color:#F90; text-align:center; padding:13px"></div>
<div id="waitingp" style="display: none; color:#000; text-align:center; font-weight:bolder; margin-bottom:5px; padding:13px">
Please wait</div>
</form>
<script type="text/javascript">
$('#pay').click(function(event) {

$('#waitingp').show(500);
$('#lginForm').hide(0);
$('#messagep').hide(0);

$.ajax({
type : 'POST',
url : 'tokensAdd_hwnd.php',
dataType : 'json',
data: {
pamount: $('#pamount').val(),memid: $('#memid').val()
},
success : function(data){
$('#waitingp').hide(500);
$('#messagep').removeClass().addClass((data.error === true) ? 'error' : 'success')
.text(data.msg).show(500);
if (data.error === true)
$('#loginForm').show(500);
else
parent.location = 'tokensAddPaypal.php'; // Members Area
},
error : function(XMLHttpRequest, textStatus, errorThrown) {
$('#waitingp').hide(500);
$('#messagep').removeClass().addClass('error')
.text('There was an error.').show(500);
$('#loginForm').show(500);
}
});

event.preventDefault();
});
</script>

</div>
<!--end well-->

<h3>Add tokens with Bitcoin</h3>
<div class="well">
<p class="alert alert-inverse" style="text-align:center"> Each token =$0.25</p>
<p style="text-align:center">

<a class="coinbase-button" data-code="b363a9b107da3bbc61a35b872776d8c3" data-custom="D<? echo $userId ?>" href="https://coinbase.com/checkouts/b363a9b107da3bbc61a35b872776d8c3">Pay With Bitcoin</a><script src="https://coinbase.com/assets/button.js" type="text/javascript"></script>

</p>

</div>
<!--end well-->


<h3>Add tokens with a credit card <img src="http://www.gamerholic.com/img/glossy.png" /></h3>
<div class="well">
<p class="alert alert-inverse"><strong>PLEASE NOTE:</strong> 
Tokens are $0.25 each. A $.50 merchant service fee will apply when you add less than $5 in tokens.</p>
<div style="text-align:center">
<form class="form-horizontal no-margin" id="deposit" method="POST">
<div class="form-controls">
<div class="input-prepend input-append">
<span class="add-on">I'm going to add </span><input id="tamount" class="span1" type="text" placeholder="5" value="<?= $amount ?>"><span class="add-on">tokens to my account</span>
</div>
</div>
<p style="text-align:center; font-weight:bolder; color:#FF0000; padding-left:65px"><? echo $error ?></p>
<p style="text-align:center; font-weight:bolder; color:#090; padding-left:65px"><? echo $success ?></p>
<button id="load">Load Account</button>
</form>
<script src="https://checkout.stripe.com/v2/checkout.js"></script>
<script>
$(function(){
	$('#load').addClass('btn btn-alt btn-large btn-success');
})
</script>
<script>
$('#load').click(function(){
      var token = function(res){
        var $input = $('<input type=hidden name=stripeToken />').val(res.id);
        $('form').append($input).submit();
      };
      var amount=(document.getElementById('tamount').value*.25)*100;
	  if((document.getElementById('tamount').value*100)<500){
		  var newAmount=((document.getElementById('tamount').value*.25)*100)+50;
	  }
	  if((document.getElementById('tamount').value*100)>=500){
		  var newAmount=(document.getElementById('tamount').value*.25)*100;
	  }
	  var message=myname + ' ' + 'bid purchase';
	  var url="deposit_stripe2.php?amount="+newAmount;
	  
      $('#payForm').load(url)
      
      StripeCheckout.open({
        key:'pk_SOcVcxfKfxOQX7QsGbriRZDDKJAia',
        address:     true,
        amount:      newAmount,
        currency:    'usd',
        name:        'Grbbit.com',
        description: message,
        panelLabel:  'Load Account',
		image: 'img/icon.png',
        token:       token
      });

      return false;
    });
  </script>
<div id="payForm" style="display:none"></div>

</div>
</div>

</div>


<div class="tab-pane" id="tab6" style="display:none">
<h3>Load Saves</h3>
<div class="well">
<p class="alert alert-inverse">
Save yourself from that last 10th of a second late bid. Use saves to register your bid for you. You can load up to 15 saves per game depending on the value of the item up for game. Each save cost's 1 bid. Minimum 5</p>
<p class="alert alert-danger" style="text-align:center">Please Note: Saves kick in ONLY in the very last second. <br />Saves are debited from the tokens in your account not the game tokens.<br />Max saves for this game: <?php echo $max_save ?>
</p>
<p style="text-align:center">
<form>
<input type="hidden" id="pid" value="<?php echo $pid ?>" />
<input type="hidden" id="max_saves" value="<?php echo $max_save ?>" />
<p style="text-align:center; padding:15px">

<input type="text" id="saves" placeholder="How many saves?" />

</p> 

<p style="text-align:center; padding:5px; margin-top:-15px"><a href="#" class="btn btn-alt btn-large btn-success" id="savesButton">Load Saves</a></p> 
<div id="messagesaves" style="display: none; color:#000; text-align:center; padding-top:15px">
</div>
<div id="waitingsaves" style="display: none; font-size:14px; color:#333; font-weight:bolder; text-align:center; padding-top:12px" >
Please Wait..
</div>

</form>
<script type="text/javascript">
$('#savesButton').click(function(event) {

$('#waitingsaves').show(500);
$('#loginForm').hide(0);
$('#messagesaves').hide(0);

$.ajax({
type : 'POST',
url : 'contest_saves_hwnd.php',
dataType : 'json',
data: {
pid: $('#pid').val(),saves: $('#saves').val(),max_saves: $('#max_saves').val()
},
success : function(data){
$('#waitingsaves').hide(500);
$('#messagesaves').removeClass().addClass((data.error === true) ? 'error' : 'success')
.text(data.msg).show(500);
if (data.error === true)
$('#loginForm').show(500);
else
$('#savesButton').fadeOut("fast"); // Members Area

},
error : function(XMLHttpRequest, textStatus, errorThrown) {
$('#waitingsaves').hide(500);
$('#messagesaves').removeClass().addClass('error')
.text('There was an error.').show(500);
$('#loginForm').show(500);
}
});

event.preventDefault();
});
</script>
</div>
<!--end well-->
</div>


<div class="tab-pane" id="tab7">
<h3>Grab history for this game</h3>

<div class="well" id="recent_tokens" style="max-height:400px; overflow:auto"></div>
<script>
$('#recent_tokens').load('../gameBidHistory.php?id=<?php echo $pid ?>');

var refreshId = setInterval(function()
{
     $('#recent_tokens').load('../gameBidHistory.php?id=<?php echo $pid ?>').fadeIn("slow");
}, 1000);

</script>
</div>

<div class="tab-pane" id="tab8a">
<h3>Were you referred to this game?</h3>
<p class="well">
If so, the player who referred you will get 10 additional free tokens for this game. The player who referred you must already be seated in the game. <br /><br />You too can get 10 free tokens each time you refer a friend to join an game you are in. You can refer the same friend over and over.
</p>
<form>
<input type="hidden" id="pid" value="<?php echo $pid ?>" />
<p style="text-align:center;">
<input type="text" id="uname" placeholder="Name of your referrer" />
</p>
<p style="text-align:center; padding:5px;"><a href="#" class="btn btn-alt btn-large btn-success" id="sendref">Update</a></p> 
<div id="message" style="display: none; color:#000; text-align:center; padding-top:15px">
</div>
<div id="waiting" style="display: none; font-size:14px; color:#333; font-weight:bolder; text-align:center; padding-top:12px" >
Please Wait..
</div>

</form>
<script type="text/javascript">
$('#sendref').click(function(event) {

$('#waiting').show(500);
$('#loginForm').hide(0);
$('#message').hide(0);

$.ajax({
type : 'POST',
url : 'gameReferred_hwnd.php',
dataType : 'json',
data: {
pid: $('#pid').val(),uname: $('#uname').val()
},
success : function(data){
$('#waiting').hide(500);
$('#message').removeClass().addClass((data.error === true) ? 'error' : 'success')
.text(data.msg).show(500);
if (data.error === true)
$('#loginForm').show(500);
else
$('#sendr').fadeOut("fast"); // Members Area

},
error : function(XMLHttpRequest, textStatus, errorThrown) {
$('#waiting').hide(500);
$('#message').removeClass().addClass('error')
.text('There was an error.').show(500);
$('#loginForm').show(500);
}
});

event.preventDefault();
});
</script>


</div>
<!--video-->
<div class="tab-pane" id="tab8b">
<p style="text-align:center">
<?= $video ?></p>
</div>


</div>
</div>
</div>
<!--end tab one-->
<div class="tab-pane" id="three">
<p class="alert alert-inverse" style="font-size:17px; text-align:left; padding:11px">
There are lots of awesome ways to get more tokens FREE. 1st you get 2 free tokens when you sign up.
Head over to the <a href="http://www.gamerholic.com" target="_blank">Gamerholic arcade</a>, get on the leaderboard and you'll earn tokens each time another player
fails to beat your high score. <br /><br />
You can also download any of the apps below and earn tokens.
</p>
<p class="well" style="text-align:center">
List available soon
</p>
</div>
<!-- Tab #two -->
<div class="tab-pane" id="two">
<!-- Second level tabs -->
<div class="tabbable tabs-left">
<ul class="nav nav-tabs">
<li class="active"><a href="#tab9" data-toggle="tab">Quick Tips</a></li>
<li><a href="#tab13" data-toggle="tab"><span><img src="../img/grbbitSmall.png" /></span> How to grbbit</a></li>
<li><a href="#tab10" data-toggle="tab"><span><img src="../img/grbbitHoleSmall.png" /></span> Grbbit Hole</a></li>
<li><a href="#tab11" data-toggle="tab"><span><img src="../img/grbbitSlapSmall.png" /></span> Slap grbber</a></li>
<li><a href="#tab12" data-toggle="tab">Block Players</a></li>
</ul>
<div class="tab-content">
<div class="tab-pane active" id="tab9">
<p class="alert alert-inverse" style="font-size:17px; text-align:center; padding:11px">Want tokens? <br />
Go here and get more tokens -> <a href="http://www.gamerholic.com" target="_blank">Gamerholic.com</a>. Get on the leaderboard and win tokens each time another player fails to beat your score. You can use the tokens you win in the arcade, right away. Your dreamytokens account is linked to our parent company Gamerholic.com</p>

<h3>Grbbits are quick</h3>
<p>Games start within 3 minutes and are over just as fast.</p>
<h3>Joining a game</h3>
<p>You will need tokens in your account to join an game, unless it's a free game. You can buy tokens, or win tokens in the Gamerholic arcade. Just set the high score and you'll earn tokens when others fail to beat your high score.</p>
<h3>Tokens</h3>
<p>The tokens you use to join a game are non refundable, even if you never click the grab button when the game starts.</p>
<h3>Referral Tokens</h3>
<p>You'll get 10 tokens for each friend you invite to join a game. You can keep referring the same friend over and over and you'll still get 10 free tokens. Your free tokens can only be used for that game.</p>
</div>
<div class="tab-pane" id="tab13">
<h3>Grbbit</h3>
<p class="alert alert-inverse">At the start of the game, grab the prize before the timer expires. The timer will reset to 11 or 3 seconds with each grab. Each grab costs you one token. Use a little strategy, go through the grabbit hole and or slap other grabbers away to win. The last player to grab the item when the timer expires wins. 
 </p>
</div>

<div class="tab-pane" id="tab11">
<h3>Slap</h3>
<p class="alert alert-inverse">Slap the next grabber who tries to grab your prize. Each time you slap a grapper it costs you one token.
</p>
</div>

<div class="tab-pane" id="tab10">
<h3>Grabbit hole</h3>
<p class="alert alert-inverse">Make a quick escape through the grabbit hole. On your next grab the timer will reset to 3 seconds, instead of 11. This is a great way to catch other players off guard. It will cost you 1 token each time you go through the grabbit hole. 
 </p>
</div>

<div class="tab-pane" id="tab12">
<h3>Block Players</h3>
<p class="alert alert-inverse">Worried about power players? You can block them from joining your game (IF they are not already
seated in the game). It will cost you the total tokens needed to join the game to block a player. You can only
block one player per game.</p>
</div>


</div>
</div>
<!-- Second level tabs -->
</div>
<!-- /Tab #two -->


</section>
<footer style="padding-bottom:-25px">
<div style="text-align:center" id="BID_BUTTONS">
<? if($gameStarted>0){?>
<p style="text-align:center">
<input type="hidden" value="<? echo $gameId ?>" id="id" /> 
<a href="#" id="buytimer" class="btn btn-alt btn-info btn-large"><span><img src="../img/grbbitHoleBig.png" /></span> <br />Grbbit Hole</a>

&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" id="bid_placed" class="btn btn-alt btn-primary btn-large"><span><img src="../img/grbbit.png" /></span> <br />Grbbit</a>
&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" id="freeze" class="btn btn-alt btn-danger btn-large"><span><img src="../img/grbbitSlapBig.png" /></span> <br />Slap Grbbit</a>
</p>
<? }?>
<? if($gameStarted<1){?>

<p style="text-align:center; margin-top:13px"> 
<? if($playerSeated<1){?>
&nbsp;&nbsp;&nbsp;&nbsp;<a href="#tab2" data-toggle="tab" class="btn btn-alt btn-success btn-large" id="join"><br /><span><img src="../img/grbbitJoin.png" /></span><br /> Join Game</a>
<? }?>
&nbsp;&nbsp;&nbsp;&nbsp;<a href="#tab2" data-toggle="tab" class="btn btn-alt btn-primary btn-large" id="grabFriends"><br /><span><img src="../img/grbbitFriends.png" /></span><br /> Grb Friends</a>


<script>
$('#join').click(function(event){
    $('.tab-pane.tabbable.nav.li').removeClass('active');
});
</script>
</p>
<? }?>
</div>
<div style="text-align:center; min-height:30px; font-weight:bolder; color:#8e44ad; background-color:#bdc3c7; margin-top:7px"><p id="bidmessage2">&nbsp;</p></div>
<script>
$(function() {

    $("#BID_BUTTONS").bind("click", function(event) {

        if(event.target.id == 'bid_placed')

        {

$.ajax({
type : 'POST',
url : 'game/gameRegister.php',
dataType : 'json',
data: {
id : $('#id').val()
},
success : function(data){
$('#bidmessage2').fadeIn();
$('#bidmessage2').removeClass().addClass((data.error === true) ? 'error' : 'success')
.text(data.msg).show(500);
if (data.error === true)
$('#loginForm').show(500);
else
$('#bidmessage2').fadeOut(100); // Members Area
},
error : function(XMLHttpRequest, textStatus, errorThrown) {
$('#bidmessage2').hide(1000);
$('#bidmessage2').removeClass().addClass('error')
.text('There was an error.').show(500);
$('#demoForm').show(500);
}
});
event.preventDefault();

		}


    });

});


  </script>

<!--Freeze Script-->
<script>
$(function() {

    $("#BID_BUTTONS").bind("click", function(event) {

        if(event.target.id == 'freeze')

        {

$.ajax({
type : 'POST',
url : 'game/freeze.php',
dataType : 'json',
data: {
id : $('#id').val()
},
success : function(data){
$('#bidmessage2').fadeIn();
$('#bidmessage2').removeClass().addClass((data.error === true) ? 'error' : 'success')
.text(data.msg).show(500);
if (data.error === true)
$('#loginForm').show(500);
else
$('#bidmessage2').fadeOut(100); // Members Area
},
error : function(XMLHttpRequest, textStatus, errorThrown) {
$('#bidmessage2').hide(1000);
$('#bidmessage2').removeClass().addClass('error')
.text('There was an error.').show(500);
$('#demoForm').show(500);
}
});
event.preventDefault();

		}


    });

});


  </script>

<!--End Freeze Script -->

<!--Buy Time Script-->
<script>
$(function() {

    $("#BID_BUTTONS").bind("click", function(event) {

        if(event.target.id == 'buytimer')

        {

$.ajax({
type : 'POST',
url : 'game/buyTime.php',
dataType : 'json',
data: {
id : $('#id').val()
},
success : function(data){
$('#bidmessage2').fadeIn();
$('#bidmessage2').removeClass().addClass((data.error === true) ? 'error' : 'success')
.text(data.msg).show(500);
if (data.error === true)
$('#loginForm').show(500);
else
$('#bidmessage2').fadeOut(100); // Members Area
},
error : function(XMLHttpRequest, textStatus, errorThrown) {
$('#bidmessage2').hide(1000);
$('#bidmessage2').removeClass().addClass('error')
.text('There was an error.').show(500);
$('#demoForm').show(500);
}
});
event.preventDefault();

		}


    });

});


  </script>
<!-- end buy time -->

</footer>
</article>
<!-- /Data block -->

</div>
<!-- /Grid row -->

</div>