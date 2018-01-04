<?php
require_once("includes/intialize.php");

require("header.php");
echo "<h2>Process Auction Bids</h2>";

//Find (from the database) all auctions that have ended and have not yet been notified. Return all items in an array of item objects
$itemObjs = item::findByCond("dateends < NOW() AND endnotified = 0");

foreach($itemObjs as $itemObj) {
    
    //Load Bid objects into item
    $itemObj->getBids();
    //Get the item's owner user object
	$itemOwnerObj = user::findFirstCond( array ( "id" => $itemObj->getMemberVar("user_id")));

	$itemOwnerName = $itemOwnerObj->getMemberVar('username');
    $itemOwnerEmail = $itemOwnerObj->getMemberVar('email');	
	
    $itemName = $itemObj->getMemberVar('name');	
	
	if(count($itemObj->getMemberVar("bidObjs")) == 0) {

$mail_body=<<<_OWNER_

Hi $itemOwnerName,

Sorry, but your item '$itemName', did not have any bids placed with it.

_OWNER_;
        
        //Call the sendmail static function of the mail class to send the email to the item owner
		mail::sendMail($itemOwnerEmail, "Your item '" . $itemName . "' did not sell", $mail_body);
        
        echo nl2br($mail_body);
        
	} else {
        
        //Get the item's winner bid object
        $temp = $itemObj->getMemberVar("bidObjs");
		$itemWinnerBidObj = array_shift($temp);
        $itemHighestBid = $itemWinnerBidObj->getMemberVar("amount");
        
        //Get the item's winner user object
        $itemWinnerUserObj	= user::findFirstCond( array( "id" => $itemObj->getMemberVar("user_id")));
		$itemWinnerName = $itemWinnerUserObj->getMemberVar("username");	
		$itemWinnerEmail = $itemWinnerUserObj->getMemberVar("email");
		
// ************ (A) code goes here! *****************************
// ** Create body of email to Item owner about winning auction **


$owner_body =<<<_OWNER_

Hi $itemOwnerName,

Congratulations! The auction for your item '$itemName', has completed with 
a winning bid 
of $config_currency$itemHighestBid bidded by {$itemWinnerName}!

Bid details:

Item: $itemName
Account: $config_currency$itemHighestBid
Winning Bidder: $itemWinnerName ({$itemWinnerEmail})

It is recommended that you contact the winning bidder within 3 days.

_OWNER_;

$winner_body=<<<_WINNER_
		
Hi $itemWinnerName,

Congratulations! Your bid of $config_currency$itemHighestBid for
the item '$itemName' was the highest bid!

Bid details:

Item: $itemName
Amount: $config_currency$itemHighestBid
Owner: $itemOwnerName ($itemOwnerEmail)

Click here to pay for your item:
{$generateButton($itemObj->getMemberVar('id'))}

It is recommended that you contact the owner of the item within 3 days.

_WINNER_;
		
        //Call the sendmail static function of the mail class to send the email to the item owner and winner  
		mail::sendMail($itemOwnerEmail, "Your item '" . $itemName . "' has sold", $owner_body);
		mail::sendMail($itemWinnerEmail, "You won item '" . $itemOwnerName . "'!", $winner_body);
        
        echo nl2br($owner_body);
        echo nl2br($winner_body);
	}
	
	// ************ (B) code goes here! *****************************
	// ** Update the endnotified property for the item and update the database **

	$itemObj->setMemberVar("endnotified", 1);
	$itemObj->updateDB();
}

require("footer.php");

?>