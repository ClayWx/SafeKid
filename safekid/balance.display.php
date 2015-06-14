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

    <style>
        .marketing .col-lg-4 {
            margin-bottom: 20px;
            text-align: center;
        }
        .marketing h2 {
            font-weight: normal;
        }
        .marketing .col-lg-4 p {
            margin-right: 10px;
            margin-left: 10px;
        }
    </style>

	<body>
<nav class="navbar navbar-default navbar-fixed-top" id="my-navbar">
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


    <?php // check if anything was submitted
        $pur = new purchasing();
        $sid = $pur->getID();

        $limit = $pur->getCredit($sid);

        ?>

        <!--Content-->
        <div class="container marketing" style="margin-top:250px; background-color:#FFFFFF; padding:auto;">

            <div class="container text-center" style="margin:auto">
                <h1>Balance</h1>
                <!-- PHP -->
                <form>
                    <h2>RM<?php echo sprintf('%0.2f', $limit);?> </h2>
                </form>
            </div>
        </div>

    <!-- Copy this link-->
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>

    </body>
</html>