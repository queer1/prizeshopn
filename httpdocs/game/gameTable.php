<?
if($gameId>0):
//get game info
$st = $db->prepare("SELECT * FROM `dreamyAuctions` WHERE `id` = :gameId"); // need to filter for next game
$st->bindParam(':gameId', $gameId); // filter
$st->execute();
$r = $st->fetch(PDO::FETCH_ASSOC);
 
$gameId= $r['id'];
$adListing= $r['adListing'];
$active= $r['active'];
$bidFee= $r['bidFee'];
$buyPrice= $r['buyPrice'];
$city= $r['city'];
$completed= $r['completed'];
$description= $r['description'];
$fee= $r['fee'];
$free= $r['free'];
$freeTokens= $r['freeTokens'];
$hidePrice= $r['hidePrice'];
$host= $r['host'];
$hostName= $r['hostName'];
$prizeId= $r['prizeId'];
$repost= $r['repost'];
$salePrice= $r['salePrice'];
$saves= $r['saves'];
$startDate= $r['startDate'];
$started= $r['started'];
$seats= $r['seats'];
$seatsTaken= $r['seatsTaken'];
$title= $r['title'];
$timer= $r['timer'];
$type= $r['type'];
$vetPlayers= $r['vetPlayers'];
$video= $r['video'];

$description2=$description;
$tokensNeeded=$fee;
if($tokensNeeded==""):
$tokensNeeded=0;
endif;

if($freeTokens==""):
$freeTokens=0;
endif;

if($active<1):
$completed=1;
endif;

if($userId==$host){
	$host=1;
}
$OGtimer=$timer;
$gameType=$type;

if($seatsTaken>=$seats):
$full=1;
endif;
//get prize info
$st = $db->prepare("SELECT * FROM `dreamyPrizes` WHERE `id` = :prizeId"); // need to filter for next game
$st->bindParam(':prizeId', $prizeId); // filter
$st->execute();
$r = $st->fetch(PDO::FETCH_ASSOC);
 
$retailPrice= $r['retailPrice'];
$description= $r['description'];
$delivery= $r['delivery'];
$image1= $r['image1'];
$image2= $r['image2'];
$image3= $r['image3'];
$image4= $r['image4'];

if($image2==0):
$image2="";
endif;
if($image3==0):
$image3="";
endif;
if($image4==0):
$image4="";
endif;

endif;

if(($gameId<1) || ($gameId=="")):
//get game info
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
$st = $db->prepare("SELECT * FROM `dreamyAuctions` WHERE `active` = :active AND `showOnHomePage`=:one ORDER BY RAND() LIMIT :limit"); // need to filter for next game
$st->bindParam(':active', $active); // filter
$st->bindParam(':limit', $limit); // filter
$st->bindParam(':one', $one); // filter
$limit=1;
$active=1;
$one=1;
$st->execute();
$r = $st->fetch(PDO::FETCH_ASSOC);

$gameId= $r['id'];
$adListing= $r['adListing'];
$active= $r['active'];
$bidFee= $r['bidFee'];
$buyPrice= $r['buyPrice'];
$city= $r['city'];
$completed= $r['completed'];
$description= $r['description'];
$fee= $r['fee'];
$free= $r['free'];
$freeTokens= $r['freeTokens'];
$hidePrice= $r['hidePrice'];
$host= $r['host'];
$hostName= $r['hostName'];
$prizeId= $r['prizeId'];
$repost= $r['repost'];
$salePrice= $r['salePrice'];
$saves= $r['saves'];
$startDate= $r['startDate'];
$started= $r['started'];
$seats= $r['seats'];
$seatsTaken= $r['seatsTaken'];
$title= $r['title'];
$timer= $r['timer'];
$type= $r['type'];
$vetPlayers= $r['vetPlayers'];
$video= $r['video'];

$OGtimer=$timer;
$gameType=$type;


//get prize info
$st = $db->prepare("SELECT * FROM `dreamyPrizes` WHERE `id` = :prizeId"); // need to filter for next game
$st->bindParam(':prizeId', $prizeId); // filter
$st->execute();
$r = $st->fetch(PDO::FETCH_ASSOC);
 
$retailPrice= $r['retailPrice'];
$description= $r['description'];
$delivery= $r['delivery'];
$image1= $r['image1'];
$image2= $r['image2'];
$image3= $r['image3'];
$image4= $r['image4'];

endif;

?>