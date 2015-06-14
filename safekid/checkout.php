<?php
/**
 * Created by PhpStorm.
 * User: joannehwan
 * Date: 6/13/15
 * Time: 5:36 PM
 */

ob_start();

date_default_timezone_set('Asia/Kuala_Lumpur');
$currentdate = date('d/m/Y');
$currenttime = date('H:i');

include_once("purchasing.php");

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<title>Stationery Shop </title>
	<meta name="description" content="Stationery Shop">
	<!-- Copy this link-->
	<!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">

</head>

<!--padding for table cells-->
<style>
td{
padding-top: 20px;
padding-bottom: 20px;
padding-left:30px;
padding-right:30px;
}
</style>

<body>
<nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="home.html" class="btn btn-info navbar-btn" style="margin-top:-5px"><img src="SmartKid.png" width="100px" height="30px"></a>
            </div> <!-- end navbar-header -->

            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
					<li><a href="balance.display.php" class="navbar-brand">Balance</a>
					<li><a href="checkout.php" class="navbar-brand">Purchase</a> 
  				</ul>
            </div> <!-- end navbar content -->
        </div> <!-- end container -->
    </nav>

<!--Content-->
<div class="container">
<table style="margin-left:200px">
<!--Content-->
<div class="container" style="margin-top:100px; width:900px">

    <table style="margin-left:200px">
        <!-- PHP -->
        <form class="form-signin" action="checkout.php" method="post">
            <tr>
                <td><img src="ruler.jpg" alt="Ruler" width="55px" height="55px"></td>
                <td><p>Ruler</p></td>
                <td><input type="text" id="inputRuler" class="form-control" name="ruler" value="0"></td>
            </tr>

            <tr style="margin-left:30px; margin-right:30px;">
                <td><img src="pencil.jpg" alt="Pencil" width="60px" height="60px"></td>
                <td><p>Pencil</p></td>
                <td><input type="text" id="inputPencil" class="form-control" name="pencil" value="0"></td>
            </tr>

            <tr style="margin-left:30px; margin-right:30px;">
                <td><img src="eraser.jpg" alt="Eraser" width="60px" height="60px"></td>
                <td><p>Eraser</p></td>
                <td><input type="text" id="inputEraser" class="form-control" name="eraser" value="0"></td>
            </tr>

        </form>
    </table>
	<button class="btn btn-lg btn-success" type="submit" name="submit" style="margin-left:800px; margin-bottom:20px">Confirm</button>
</div>

<?php
// check if anything was submitted
if (isset($_POST['submit'])){
    $pur = new purchasing();

    $sid = $pur->getID();

    // dummy data
//    $sid = "100001";

    // check if the fields entered with proper value to purchase
    if ($_POST['eraser']==0 && $_POST['ruler']==0 && $_POST['pencil']==0){
        echo "Please insert valid amount to purchase";
        return;
    }
    if ($_POST['eraser']=="" && $_POST['ruler']=="" && $_POST['pencil']==""){
        echo "Please insert valid amount to purchase";
        return;
    }

    // store amounts into array
    $amounts = array(
        "eraser" => $_POST['eraser'],
        "ruler" => $_POST['ruler'],
        "pencil" => $_POST['pencil']
    );

    // calculate and return total price
    $total = $pur->calTotal($amounts);

    // check out the deduct credit balance
    if ($pur->purchase($sid, $total)){
        $html = $pur->insertPurchased($sid, $currentdate, $currenttime, $amounts, $total);
        echo $html;
    }
}
?>
</div>

<!-- Copy this link-->
<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
  
</body>	
</html>


