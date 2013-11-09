<?php
error_reporting (E_ALL ^ E_NOTICE);
require_once("../sessionhandle.php");
require_once("../config.php");
$userId=$_COOKIE['grbbit'];
$myname=$_SESSION['myname'];

if(isset($_GET['id']))
{
$gameId=$_GET['id'];
}	
include_once("gameTable.php");

if($seatsTaken>0):
include_once("gameUserBids.php");
endif;

if($hidePrice>0):
$retail="XXXX";	
endif;

//set game start time
if($started<1 && $completed<1 && $full<1):
$start= date('Y-m-d H:i:s');
$now = new DateTime("$start");
$ref = new DateTime("$startDate");
$diff = $now->diff($ref);
$sec=$diff->s;
$sec2=$diff->s;
$min=$diff->i;

//if timer has expired reset timer
if($now>$ref):
$newStartTime= date('Y-m-d H:i:s', strtotime('+3 minutes', time()));;
$st = $db->prepare("UPDATE `dreamyAuctions` SET `startDate`= :newStartTime WHERE `id`=:gameId"); // need to filter for next auction
$st->bindParam(':newStartTime', $newStartTime); // filter
$st->bindParam(':gameId', $gameId); // filter
$st->execute();

endif;

if($sec<10){
	$sec="0$sec";
          }
$startTimer="$min:$sec"; 

endif;

//see if we need to withdraw anyone from the auction
if($seatsTaken>0):

//withdraw players seated longer than 20 mins


$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
$st = $db->prepare("SELECT * FROM `dreamyPlayers` WHERE `auctionId`=:auctionId AND (TIMESTAMPDIFF(MINUTE,now(),`timeSeated`)+20)<0 AND `active`=:active ORDER BY `id` DESC"); // need to filter for next auction
$st->bindParam(':auctionId', $auctionId); // filter
$st->bindParam(':active', $active); // filter
$limit=1;
$active=1;
$st->execute();
$r = $st->fetch(PDO::FETCH_ASSOC);

if($r>0):
do{
$ppid=$r['id'];
$UserId=$r['userId'];
$bids=$r['bids'];
$freeBids=$r['freeTokens'];
$timeSeated=$r['timeSeated'];

$bids=$bids-$freeBids;
$stw = $db->prepare("UPDATE `users` SET `tokens`=(tokens+:bids) WHERE `id`=:userId"); // need to filter for next auction
$stw->bindParam(':bids', $bids); // filter
$stw->bindParam(':userId', $UserId); // filter
$stw->execute();

//if user id= current user show join button again
if($UserId==$userId):
$_SESSION['showJoin']=1;
endif;

$stq = $db->prepare("UPDATE `dreamyAuctions` SET `seatsTaken`=(seatsTaken-:one), full=:zero WHERE `id`=:auctionId"); // need to filter for next auction
$stq->bindParam(':one', $one); // filter
$stq->bindParam(':zero', $zero); // filter
$stq->bindParam(':auctionId', $auctionId); // filter
$one=1;
$zero=0;
$stq->execute();

$st = $db->prepare("SELECT email FROM `users` WHERE `id`=:userId"); // need to filter for next auction
$st->bindParam(':userId', $UserId); // filter
$st->execute();
$r = $st->fetch(PDO::FETCH_ASSOC);
$email=$r['email'];

$mail_To = "$email";
$mail_Subject = "Seat withdrawn";
$mail_Body = "$myname,"."\r\n" ."Your were seated in this auction for $title (http://www.dreamybids.com?id=$auctionId. If the auction resets 2 times players seated in the auction for 20 mins or more are automatically withdrawn from the auction. If you'd like to take a seat in the auction again you can.
"."\r\n" ."Customer Service
"."\r" ."Dreamybids.com";
//start $headers
$headers = "MIME-Version: 1.0" . "\r\n"; 
$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n"; //adds content type to headers
$headers .= "From: Dreamybids.com" . "\r\n"; //adds the sender details
mail($mail_To,$mail_Subject,$mail_Body,$headers); //sends the email

$ste = $db->prepare("UPDATE `dreamyPlayers` SET `active`=:zero WHERE `id`=:id"); // need to filter for next auction
$ste->bindParam(':zero', $zero); // filter
$ste->bindParam(':id', $ppid); // filter
$zero=0;
$ste->execute();

} while ( $r = $st->fetch(PDO::FETCH_ASSOC));
endif;
endif;

//end see if we need to withdraw anyone from the auction

//if the auction is full and it hasn't started, get the 2 mintute timer
if($full>0 && $started<1 && $completed<1):

$start= date('Y-m-d H:i:s');
$now = new DateTime("$start");
$ref = new DateTime("$startDate");
$diff = $now->diff($ref);
$sec=$diff->s;
$sec2=$diff->s;
$min=$diff->i;

if($sec<10){
	$sec="0$sec";
          }
$timer="$min:$sec"; 

//if seats are full and countdown timer has expired, start auction
if((time() > strtotime($startDate))):

$st = $db->prepare("UPDATE `dreamyAuctions` SET `started`=:yes WHERE `id`=:auctionId"); // need to filter for next auction
$st->bindParam(':yes', $yes); // filter
$st->bindParam(':auctionId', $auctionId); // filter
$yes=1;
$st->execute();

$st = $db->prepare("UPDATE `dreamyPlayers` SET `started`=:yes WHERE `auctionId`=:auctionId AND `active`=:yes"); // need to filter for next auction
$st->bindParam(':auctionId', $auctionId); // filter
$st->bindParam(':yes', $yes); // filter
$yes=1;
$st->execute();

endif;//time()>strtotime

endif;//full and hasn't started

// if the auction is full and it hasn started get auction timer
if($full>0 && $started>0 && $completed<1):

//get the last person to place a bid
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
$st = $db->prepare("SELECT * FROM `dreamyBids` WHERE `auctionId`=:auctionId AND `bid`=:one ORDER BY `id` DESC LIMIT :limit"); // need to filter for next auction
$st->bindParam(':auctionId', $auctionId); // filter
$st->bindParam(':one', $one); // filter
$st->bindParam(':limit', $limit); // filter

$limit=1;
$st->execute();
$r = $st->fetch(PDO::FETCH_ASSOC);
$bidId=$r['id'];
$lastBidderId=$r['userId'];
$lastBidderName=$r['userName'];
$finalPrice=$r['bidAmount'];
$lastBidTime=$r['bidTime'];

$st = $db->prepare("SELECT TIMESTAMPDIFF(SECOND, `bidTime`, NOW()) AS mtime FROM `dreamyBids` WHERE id=:id"); // need to filter for next auction
$st->bindParam(':id', $bidId); // filter
$st->execute();
$r = $st->fetch(PDO::FETCH_ASSOC);
$time=$r['mtime'];
$otime=$time-1;

//if bid hasn't been placed, keep the timer at 11 seconds, last bidder as none and price at $0
if($bidId==""):
$timer="11";
$lastBidderName="No one yet";
else:
$timer=$timer-$time;
endif;				   
//if the auction timer is less than 2 seconds get the next person to save
if($timer>=0 && $timer<=1 && $completed<1):

//get the next person to save
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
$st = $db->prepare("SELECT * FROM `dreamyPlayers` WHERE `auctionId`=:auctionId AND `saves`>:zero AND userId<>:lastBidderId ORDER BY `id` DESC LIMIT :limit"); // need to filter for next auction
$st->bindParam(':auctionId', $auctionId); // filter
$st->bindParam(':zero', $zero); // filter
$st->bindParam(':lastBidderId', $lastBidderId); // filter
$st->bindParam(':limit', $limit); // filter

$zero=0;
$limit=1;
$st->execute();
$r = $st->fetch(PDO::FETCH_ASSOC);
$nextToSave=$r['userId'];

//get the user name of the next person to save
$st = $db->prepare("SELECT * FROM `users` WHERE `id`=:nextToSave"); // need to filter for next auction
$st->bindParam(':nextToSave', $nextToSave); // filter
$st->execute();
$r = $st->fetch(PDO::FETCH_ASSOC);
$nextToSaveName=$r['username'];

//if there is someone to save, save them
if($nextToSave>0):
//insert into the bids table
$st = $db->prepare("INSERT INTO `dreamyBids`(`auctionId`,`userId`,userName,`bidTime`,`bidAmount`,`bid`) VALUES (:auctionId,:userId,:userName,:now,:bidAmount,:one)"); // need to filter for next auction
$st->bindParam(':auctionId', $auctionId); // filter
$st->bindParam(':userId', $nextToSave); // filter
$st->bindParam(':userName', $nextToSaveName); // filter
$st->bindParam(':now', $now); // filter
$st->bindParam(':bidAmount', $bidAmount); // filter
$st->bindParam(':one', $one); // filter

$now=date('Y-m-d h:i:s');
$one=1;
$bidAmount=$finalPrice+$bidFee;
$st->execute();

//update the dreamyplayers table to reflect the one bid used
$st = $db->prepare("UPDATE `dreamyPlayers` SET `bids`=(bids-:one),`saves`=(saves-:ones)
WHERE `auctionId`= :auctionId AND userId=:nextToSave"); // need to filter for next auction
$st->bindParam(':one', $one); // filter
$st->bindParam(':ones', $one); // filter
$st->bindParam(':auctionId', $auctionId); // filter
$st->bindParam(':nextToSave', $nextToSave); // filter
$one=1;
$st->execute();
endif;
//if timer was bought down reset to 11 seconds
if($OGtimer==3):
$st = $db->prepare("UPDATE `dreamyAuctions` SET `timer`=:timer WHERE `id`=:auctionId"); // need to filter for next auction
$st->bindParam(':auctionId', $auctionId); // filter
$st->bindParam(':timer', $eleven); // filter
$eleven=11;
$st->execute();
endif;
endif;//end if auction timer is less than 2 seconds

//if time has expired do this
if($timer<0 && $completed<1):

$st = $db->prepare("UPDATE `dreamyAuctions` SET `completed`= :one,`active`= :zero WHERE `id`=:auctionId"); // need to filter for next auction
$st->bindParam(':one', $one); // filter
$st->bindParam(':zero', $zero); // filter
$st->bindParam(':auctionId', $auctionId); // filter
$one=1;
$zero=0;
$st->execute();

$st = $db->prepare("UPDATE `dreamyPlayers` SET `completed`= :one,`active`= :zero WHERE `id`=:auctionId"); // need to filter for next auction
$st->bindParam(':one', $one); // filter
$st->bindParam(':zero', $zero); // filter
$st->bindParam(':auctionId', $auctionId); // filter
$one=1;
$zero=0;
$st->execute();

//get the last person to place a bid
$st = $db->prepare("SELECT * FROM `users` WHERE `id`=:lastBidderId"); // need to filter for next auction
$st->bindParam(':lastBidderId', $lastBidderId); // filter
$st->execute();
$lastBidderEmail=$r['email'];
$lastBidderName=$r['username'];
//if this auction is set to repost, repost auction
$st = $db->prepare("SELECT * FROM `dreamyAuctions` WHERE `id` = :auctionId"); // need to filter for next auction
$st->bindParam(':auctionId', $auctionId); // filter
$st->execute();
$r = $st->fetch(PDO::FETCH_ASSOC);
 
$repost= $r['repost'];

if($repost>0):

$st = $db->prepare("INSERT INTO `dreamyAuctions`(`adListing`,`active`,`bidFee`,`buyPrice`,`completed`,`description`,`fee`,`free`,`freeBids`,`hidePrice`,`host`,`hostName`,`prizeId`,`repost`,`salePrice`,`saves`,`startDate`,`started`,`seats`,`seatsTaken`,`title`,`timer`,`vetPlayers`,`video`,`showOnHomePage`,`full`) 
SELECT
`adListing`,:active,`bidFee`,`buyPrice`,:completed,`description`,`fee`,`free`,`freeBids`,`hidePrice`,`host`,`hostName`,`prizeId`,`repost`,`salePrice`,`saves`,`startDate`,:started,`seats`,:seatsTaken,`title`,`timer`,`vetPlayers`,`video`,:showOnHomePage,:full
FROM `dreamyAuctions` WHERE id=:auctionId"); // need to filter for next auction
$st->bindParam(':auctionId', $auctionId); // filter
$st->bindParam(':started', $zero); // filter
$st->bindParam(':seatsTaken', $zero); // filter
$st->bindParam(':showOnHomePage', $one); // filter
$st->bindParam(':full', $zero); // filter
$st->bindParam(':completed', $zero); // filter
$st->bindParam(':active', $one); // filter
$zero=0;
$one=1;
$st->execute();
endif;//end auction repost

$mail_To = $lastBidderEmail;
$mail_Subject = "You won!";
$mail_Body = "$leaderName how are you?"."\r\n" ."You must be excited, congrats on your win. Your item will be delivered
to your via $delivery within 7 business days"."\r\n" ."Customer Service
"."\r\n" ."Dreamybids.com";
//start $headers
$headers = "MIME-Version: 1.0" . "\r\n"; 
$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n"; //adds content type to headers
$headers .= "From: Dreamybids.com" . "\r\n"; //adds the sender details
mail($mail_To,$mail_Subject,$mail_Body,$headers); //sends the email


endif; //end if timer has expired

endif;// full and has started

$text="Bids Left";
if($bidsLeft<1):
	
$st = $db->prepare("SELECT * FROM `users` WHERE `id`=:userId"); // need to filter for next auction
$st->bindParam(':userId', $userId); // filter
$st->execute();
$myBalance=$r['tokens'];


if($myBalance<1):
$bidsLeft=0;
endif;

endif;

if($type>0):
	$text="Reserve Bids";
endif;
if($type<1):
	$text="Your Bids";
endif;
$balance=$bidsleft;

?>

<? if($completed>0):
	$timer="Finished";
	$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
$st = $db->prepare("SELECT * FROM `dreamyBids` WHERE `auctionId`=:auctionId AND `bid`=:one ORDER BY `id` DESC LIMIT :limit"); // need to filter for next auction
$st->bindParam(':auctionId', $auctionId); // filter
$st->bindParam(':one', $one); // filter
$st->bindParam(':limit', $limit); // filter

$limit=1;
$st->execute();
$r = $st->fetch(PDO::FETCH_ASSOC);
$bidId=$r['id'];
$lastBidderId=$r['userId'];
$lastBidderName=$r['userName'];
$finalPrice=$r['bidAmount'];
$lastBidTime=$r['bidTime'];

endif;
?>

<!--if auction has yet to start-->
<div class="row">

<style>
.thumbnails2 {
    text-align: center;
}
.thumbnails2 li {
    width: 150px;
    height: 100px;
    float: none !important; /* to overwrite the default property on the bootstrap stylesheet */
    display: inline-block;
    *display: inline; /* ie7 support */
    zoom: 1;
}
</style>
<div style="text-align:center">
<div>
<p style="font-size:13; margin-top:-1px">Game starts in </p>
<p style="font-size:19px; font-weight:bolder; margin-top:-19px"><? echo $startTimer ?> </p>
<? if($hidePrice>0){
$retailPrice="XXXX";	
}
?>
<p style="font-size:13px; line-height:19px; margin-top:-19px">
<? if($tokensNeeded>0){?>
Tokens needed to join: <? echo $tokensNeeded ?><br />
<? }?>
<? if($tokensNeeded<1){?>
This game is FREE to join<br />
<? }?>
Free Tokens: <? echo $freeTokens ?><br />
Players needed: <? echo $seats ?> <br />
Players ready: <? echo $seatsTaken ?> <br />
<? if($tokensNeeded>0){?>
Delivery: <?php echo $delivery ?><br>
Delivery Time: <? echo $deliverTime ?>
<? }?></p>
</div>

</div>

