<?php
/**
 * Created by PhpStorm.
 * User: joannehwan
 * Date: 6/13/15
 * Time: 11:38 PM
 */
 
class purchasing{
    function __construct(){
        // start up database
        include_once("database.php");
        $this->db = new database();
    }

    // function to get ID from database (the one sent from Java)
    function getID(){
        $sql="SELECT * FROM current_sid ORDER BY current_id DESC LIMIT 1";

        $result = $this->db->getResult($sql);
        $row = $result->fetch_assoc();

        return $row['sid'];
    }

    // get the credit balance of the student
    function getCredit($sid){
        $sql="SELECT * FROM credit_student WHERE sid='$sid'";

        $result = $this->db->getResult($sql);
        $row = $result->fetch_assoc();

        return $row['credit'];
    }

    // calculate the total amount of the selected item
    function calTotal($amounts){
        $prices = array(
            "eraser" => 1.5,
            "ruler" => 2,
            "pencil" => 1
        );

        $total = 0;

        // calculate amount by looping each items in array
        foreach ($amounts as $key => $value){
            $total = $total + ($prices["$key"] * $value);
        }
        return $total;
    }

    // function to check out and update student's credit balance
    function purchase($sid, $total){
        // set the limit according to the student's credit balance
        $limit = $this->getCredit($sid);

        // if the limit is more than the total amount selected
        if ($total <= $limit) {
            // calculate new balance
            $balance = $limit - $total;
            echo "<div class='alert alert-success text-center' role='alert'>
                Purchase successful.
            </div>";

            // update student's credit balance
            $sql="UPDATE credit_student SET credit='$balance' WHERE sid ='$sid' ";

            $this->db->query($sql);
            return true;
        }
        else {
            echo "<div class='alert alert-danger text-center' role='alert'>
                Insufficient credit.
            </div>";
            return false;
        }
    }

    // CHANGED HERE
    function insertPurchased($sid, $currentdate, $currenttime, $amounts, $total){
        $prices = array(
            "eraser" => 1.5,
            "ruler" => 2,
            "pencil" => 1
        );


        $result = true;
        WHILE ($result){
            $code = $this->genCode();
            $result = $this->checkCode($code);
        }

        $sqlinsert = "INSERT INTO transaction VALUES('$code','$currentdate',
        '$currenttime', '$sid', '$total')";

        $html = "<div class='well text-center' style='margin:-20px 0 0 0'>
                    <h4>Transaction ID: $code</h4>
                    Date: $currentdate        Time:$currenttime<br>";

        $resultInsert = $this->db->query($sqlinsert);

        $listofitems = "";

        if ($resultInsert){
            $html .= "<table cellspacing='10' style='margin:auto'>";
            // calculate amount by looping each items in array
            foreach ($amounts as $key => $value){
                if ($value > 0) {
                    $sqlinsert2 = "INSERT INTO transaction_items VALUES('$code', '$key', '$value')";

                    $price = $prices["$key"];
                    $subtotal = $value * $prices["$key"];

                    $this->db->query($sqlinsert2);
                    $html .= "<tr><td>$key - RM".sprintf('%0.2f', $price)."</td><td>x $value</td><td>= ".sprintf('%0.2f', $subtotal)."</td></tr>";
                    $listofitems .= "$key x $value = ".sprintf('%0.2f', $subtotal).",";
                }
            }

            $html .= "<tr><td><H4>Total: </td><td><h4>RM".sprintf('%0.2f', $total)."</h4></td></tr>
                    <tr><td><H4>Current Balance: </H4></td><td><h4>RM".sprintf('%0.2f', $this->getCredit($sid))."</h4></td></tr>
                    </table> </div>";
            $listofitems .= "(Total: RM$total)";



            $this->informGuardians($sid, $listofitems);

            return $html;
        } else {
            return false;
        }
    }

    function genCode(){
        // Random characters
        $characters = array("1","2","3","4","5",
            "6","7","8","9","0");

        // set the array
        $keys = array();

        // set length
        $length = 6;

        // loop to generate random keys and assign to an array
        while(count($keys) < $length) {
            $x = mt_rand(0, count($characters)-1);
            if(!in_array($x, $keys)) {
                $keys[] = $x;
            }
        }

        $random_chars = "";

        // extract each key from array
        foreach($keys as $key){
            $random_chars .= $characters[$key];
        }

        // display random key
        return $random_chars;
    }

    // check if the code entered is valid
    function checkCode($code){
        return $this->db->checkDuplicate("SELECT * FROM transaction WHERE trans_id='$code'");
    }

    // function to output information to guardians
    function informGuardians($sid, $listofitems){
        $sql = "SELECT * FROM guardian WHERE sid='$sid'";
        $sql2 = "SELECT * FROM student WHERE sid='$sid'";

        $result2 = $this->db->getResult($sql2);

        // check if there is only one student record
        if (mysqli_num_rows($result2) == 1){
            $result = $this->db->getResult($sql);

            // check if there is any guardians recorded for the student
            if (mysqli_num_rows($result) <= 0){
                echo "No guardian";
                return;
            }

            // get guardians info
            $row2 = $result2->fetch_assoc();

            // send info for each guardian attached to the student
            while ($row = $result->fetch_assoc()) {
                $message = "Dear Mr./Ms. " . $row['name'] . ",Your child, " . $row2['name'] . ", had purchased the following item(s):$listofitems.Current Balance: RM".$this->getCredit($sid)."<br>";
//                echo $message;
                $phone = $row['phone'];
                //                echo $phone."<br>";
				
				// uncomment this line to send sms
//                $this->sendSMS($message, $phone);
            }
        } else {
            echo "Error sending message<br>";
        }
    }

    function sendSMS($message, $phone){
        require('textmagic-sms-api-php/TextMagicAPI.php');

        $username = 'wei.xiang.lim';
        $password = 'qnOJMTLIHO';

        $router = new TextMagicAPI((array(
            'username' => $username,
            'password' => $password
        )));

        $result = $router->send($message, array($phone), true);

        if ($result){
            echo "SMS send successfully";
        } else {
            echo "Something wrong";
        }
    }
}

