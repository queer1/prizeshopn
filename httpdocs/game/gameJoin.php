<?php
sleep(2);
require_once("../config.php");
require_once("../sessionhandle.php");

$auctionId=$_POST['gameId'];

if($userId!=""):
	
//get prize info
$st = $db->prepare("SELECT * FROM `users` WHERE `id` = :userId"); // need to filter for next auction
$st->bindParam(':userId', $userId); // filter
$st->execute();
$r = $st->fetch(PDO::FETCH_ASSOC);
$tokens= $r['tokens'];

//get auction details		
include_once("auctionTable.php");

//see if this gamer is blocked from joining this auction
$st = $db->prepare("SELECT * FROM `dreamyAuctionBlock` WHERE `auctionId` = :auctionId AND `userId`=:userId"); // need to filter for next auction
$st->bindParam(':auctionId', $auctionId); // filter
$st->bindParam(':userId', $userId); // filter
$st->execute();
$r = $st->fetch(PDO::FETCH_ASSOC);
$blocked= $r['id'];
$blockedBy= $r['blockedBy'];

if($blocked>0):
//get the user name of the player who blocked him
$st = $db->prepare("SELECT username FROM `users` WHERE `id` = :blockedBy"); // need to filter for next auction
$st->bindParam(':blockedBy', $blockedBy); // filter
$st->execute();
$r = $st->fetch(PDO::FETCH_ASSOC);
$blockedByName= $r['username'];

endif;

include_once("auctionUserBids.php");

// if bids are less than the fee throw error
if($tokens-$fee<0):
$return['error'] = true;
$return['msg'] = "You need more tokens to join this game";
$error=1;
endif;

// if user is already seated in this auction throw error
if($playerSeated>0):
$return['error'] = true;
$return['msg'] = "You are already seated in this game";
$error=1;
endif;

// if this auction is already completed throw error
if($completed>0):
$return['error'] = true;
$return['msg'] = "This game is over";
$error=1;
endif;

// if user is blocked from joining this auctin throw error
if($blocked!=""):
$return['error'] = true;
$return['msg'] = "ouch..looks like you were blocked from joining this game by $blockedByName";
$error=1;
endif;

if($vetPlayers>0):
$return['error'] = true;
$return['msg'] = "This is a privat game, you'll need a code to join";
$error=1;
endif;


if($host==$userId):
$return['error'] = true;
$return['msg'] = "come on.... did you really just try to join your own game?";
$error=1;
endif;


if($error!=1):

//set game to start
$seatsTaken=$seatsTaken+1;
if($seatsTaken==$seats):
//if the auction is full with the player taking a seat, move the start time to 2 mins.
$st = $db->prepare("UPDATE `dreamyAuctions` SET `startDate`=:startDate WHERE `id` = :auctionId"); // need to filter for next auction
$st->bindParam(':startDate', $sdate); // filter
$st->bindParam(':auctionId', $auctionId); // filter
$st->execute();
endif;

//if the user is not in the table insert them in the table
if($playerSeated<1):
// get free bids since it's not picking it up
$st = $db->prepare("SELECT * FROM `dreamyAuctions` WHERE `id` = :auctionId"); // need to filter for next auction
$st->bindParam(':auctionId', $auctionId); // filter
$st->execute();
$r = $st->fetch(PDO::FETCH_ASSOC);
$freeBids= $r['freeTokens'];
$fee=$r['fee'];
$saves=$r['saves'];

$bids=$fee+$freeBids;
//insert into the players table
$st = $db->prepare("INSERT INTO `dreamyPlayers` (`auctionId`,`userId`,`bids`,`freeBids`,`saves`,`timeSeated`,`balanceBefore`,`balanceAfter`,`active`) values (:auctionId,:userId,:bids,:freeBids,:saves,:timeSeated,:balanceBefore,:balanceAfter,:active)"); // need to filter for next auction
$st->bindParam(':auctionId', $auctionId); // filter
$st->bindParam(':userId', $userId); // filter
$st->bindParam(':bids', $bids); // filter
$st->bindParam(':freeBids', $freeBids); // filter
$st->bindParam(':saves', $saves); // filter
$st->bindParam(':timeSeated', $now); // filter
$st->bindParam(':balanceBefore', $tokens); // filter
$st->bindParam(':balanceAfter', $balanceAfter); // filter
$st->bindParam(':active', $one); // filter

$now=date("Y-m-d H:i:s");
$one=1;
$balanceAfter=$tokens-$fee;
$st->execute();
endif;//if id==""

//if user is in the table, update the last record
if($playerSeated>0):
$st = $db->prepare("UPDATE `dreamyPlayers` SET `timeSeated`=now, `active`=:one WHERE `id` =:id"); // need to filter for next auction
$st->bindParam(':now', $now); // filter
$st->bindParam(':one', $one); // filter
$st->bindParam(':id', $id); // filter
$now=date('Y-m-d h:i:s');
$one=1;
$st->execute();
endif;//if id!=""

$st = $db->prepare("UPDATE users SET `tokens`=(tokens-:fee) WHERE `id` =:userId"); // need to filter for next auction
$st->bindParam(':fee', $fee); // filter
$st->bindParam(':userId', $userId); // filter
$st->execute();

if($full>0):
$st = $db->prepare("UPDATE `dreamyAuctions` SET `seatsTaken`=(seatsTaken+:one),`full`=:one WHERE `id` =:auctionId"); // need to filter for next auction
$st->bindParam(':one', $one); // filter
$st->bindParam(':auctionId', $auctionId); // filter
$st->execute();

else:
$st = $db->prepare("UPDATE `dreamyAuctions` SET `seatsTaken`=(seatsTaken+:one) WHERE `id` =:auctionId"); // need to filter for next auction
$st->bindParam(':one', $one); // filter
$st->bindParam(':auctionId', $auctionId); // filter
$st->execute();

endif;

$return['error'] = false;
$return['msg'] = 'You are in';


endif;

else:
$return['error'] = true;
$return['msg'] = 'Please Log In';	
endif;
echo json_encode($return);
unset($_SESSION['showJoin']);
					