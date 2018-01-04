<?php
include_once("includes/intialize.php");

$validid = pf_validate_number($_GET['id'], "redirect", $config_basedir);

if(isset($_POST['submit'])) {
    if(is_numeric($_POST['bid']) == FALSE) {
        header("Location: itemdetails.php?id=" . $validid . "&error=letter");
    }

    $itemObj = item::findFirstCond(array( "id" => "$validid"));

    $itemObj->getImages();
    $itemObj->getBids();

    $validbid = 0;

    if(count($itemObj->getMemberVar('bidObjs')) == 0) {
        $startingPrice = intval($itemObj->getMemberVar('startingprice'));
        $postedBid = intval($_POST['bid']);

        if($postedBid >= $startingPrice) {
            $validbid = 1;
        }
    } else {
        $itemBidObjs = $itemObj->getMemberVar('bidObjs');
        $highestBidObj = array_shift($itemBidObjs);
        $highestBid = intval($highestBidObj->getMemberVar('amount'));
        $postedBid = intval($_POST['bid']);
        if($postedBid > $highestBid) {
            $validbid = 1;
        }
    }

    if($validbid == FALSE) {
        header("Location: itemdetails.php?id=" . $validid . "&error=lowprice#bidbox");
    } else {
        $newbid = bid::createDB( [
            "id" => 0,
            "item_id" => $itemObj->getMemberVar('id'),
            "amount" => $_POST['bid'],
            "user_id" => $session->getUserObj()->getMemberVar('id')
        ]);
        header("Location: itemdetails.php?id=" . $validid);
    }
} else {

    require("header.php");

    $itemObj = item::findFirstCond(["id" => $validid]);
    $itemObj->getImages();
    $itemObj->getBids();

    $nowepoch = time();
    $rowepoch = strtotime($itemObj->getMemberVar('dateends'));

    if($rowepoch > $nowepoch) {
        $VALIDAUCTION = 1;
    } else {
        $VALIDAUCTION = 0;
    }

    echo "<h1>" . $itemObj->getMemberVar('$config_auctionname') . "</h1>";
    echo "<p>";

    $itemObj->getBids();
    $temp = $itemObj->getMemberVar("bidObjs");
    $highestBidObj = array_shift($temp);

    if($itemObj->getMemberVar("bidObjs") == false) {
        echo "<strong>This item has had no bids</strong> - <strong>Starting Price</strong>: " . $config_currency . sprintf('%.2f', $itemObj->getMemberVar('startingPrice'));
    } else {
        $highestBid = intval($highestBidObj->getMemberVar('amount'));
        echo "<strong>Number Of Bids</strong>: " . count($itemObj->getMemberVar('bidObjs')) . " - <strong>Current Price</strong>: " . $config_currency . sprintf('%.2f', $highestBidObj->getMemberVar('amount'));
    }
    echo " - <strong>Auction ends</strong>: " . date("D jS F Y g.iA", $rowepoch);
    echo "</p>";

    $stuff = $itemObj->getMemberVar("imageObj");
    $imgObj = array_shift($stuff);

    if($imgObj == false) {
        echo "No Images.";
    } else {
        echo "<img src='images/" . $imgObj->getMemberVar('name') . "' width='200'>";
    }

    echo "<p>" . nl2br($itemObj->getMemberVar('description')) . "</p>";

    echo "<a name='bidbox'></a>";
    echo "<h2>Bid for this item</h2>";

    if(!$session->getLoggedInStatus()) {
        echo "To bid, you need to log in. Login <a href='login.php?id=" . $validid .  "&ref=addbid'>here</a>.";
    } else {
        if($VALIDAUCTION == 1) {
            echo "Enter the bid amount into the box below.";
            echo "<p>";

            if(isset($_GET['error'])) {
                $errorMsg = item::displayError($_GET['error']);
                if($errorMsg != false) {
                    echo $errorMsg;
                }
            }

            ?>
            <form action="<?php echo pf_script_with_get($_SERVER['SCRIPT_NAME']); ?>" method="post">
                <table>
                    <tr>
                        <td><input type="number" name="bid"></td>
                        <td><input type="submit" name="submit" id="submit" value="Bid!"></td>
                    </tr>
                </table>
            </form>
            <?php
        } else {
            echo "This auction has now ended.";
        }

        if(count($itemObj->getMemberVar('bidObjs')) > 0) {
            echo "<h2>Bid History</h2>";
            echo "<ul>";

            $bidObjs = $itemObj->getMemberVar('bidObjs');

            foreach ($bidObjs as $bid) {
                $id = $bid->getMemberVar('user_id');
                echo "<li>" . user::findFirstCond(array ("id" => "$id"))->getMemberVar('username') . " - " . $config_currency . sprintf('%.2f', $bid->getMemberVar('amount')) . "</li>";
            }
            echo "</ul>";
        }

    }
}

require("footer.php");

?>
