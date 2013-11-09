<?
//get total grabs placed
$st = $db->prepare("SELECT * FROM `dreamyPlayers` WHERE `gameId`=:gameId AND `userId` = :userId AND `active`=:one"); 
$st->bindParam(':gameId', $gameId); // filter
$st->bindParam(':userId', $userId); // filter
$st->bindParam(':one', $one); // filter
$one=1;
$st->execute();
$r = $st->fetch(PDO::FETCH_ASSOC);
$tokensLeft= $r['tokens'];
$playerSeated= $r['id'];
$buyTime= $r['buyTime'];
$freeBids= $r['freeTokens'];
$savesLeft= $r['saves'];

//see if there is an active freeze by another member
$st = $db->prepare("SELECT * FROM `dreamyPlayers` WHERE `userId`<> :userId AND `gameId`=:gameId AND `active`=:one AND `freeze`>:zero"); 
$st->bindParam(':userId', $userId); // filter
$st->bindParam(':gameId', $gameId); // filter
$st->bindParam(':one', $one); // filter
$st->bindParam(':zero', $zero); // filter
$one=1;
$zero=0;
$st->execute();
$r = $st->fetch(PDO::FETCH_ASSOC);
$freeze= $r['id'];