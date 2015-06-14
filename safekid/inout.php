<?php

// set up current date and time
date_default_timezone_set('Asia/Kuala_Lumpur');
$currentdate = date('d/m/Y');
$currenttime = date('H:i');

$sio = new studentInOut();


if (isset($_GET['submit'])){

    // sid parameter get from java
    $sid = $_GET['sid'];

    $result = $sio->checkEnter($sid, $currentdate, $currenttime);

    // check if the student entered successfully
    if ($result == "entered"){
        echo "Student entered successfully<br>";
    }

    // if the student already entered at least once
//    if ($result == "existed"){
//        echo "Existed<br>";
//    }

    // if there is error during inserting to database
    if ($result == "error"){
        echo "Error entering/exiting<br>";
    }

    // check if the student reentered
    if ($result == 'reentered'){
        echo "Reentered successfully<br>";
    }

    // check if error during reentering
    if ($result == 'reerror'){
        echo "Error rentering<br>";
    }
}

//    if ($result == "existed"){
//        echo "Existed<br>";
//        $result2 = $sio->checkExit($sid, $currentdate, $currenttime);
//        if ($result2 == true){
//            echo "Student exited successfully<br>";
//        } else {
//            echo "Error exiting<br>";
//        }
//    }

?>

<?php
class studentInOut{
    function __construct(){
        // start up database
        include_once("database.php");
        $this->db = new database();
    }

    // check and create record to keep track for student check in and out
    function checkEnter($sid, $currentdate, $currenttime){
        $sql = "SELECT * FROM timestamp_student WHERE sid='$sid' AND date='$currentdate'";
        // check if there is record before
        // if there is record
        if ($this->db->checkDuplicate($sql)){
            $result2 = $this->checkExit($sid, $currentdate, $currenttime);
            // check if the students check out the gate successfully
            if ($result2 == "exited"){
                echo "Student exited successfully<br>";
            }
            // check if the student haven't has entering record yet
            elseif ($result2 == "noenter") {
                echo "Student haven't enter yet<br>";
            }
            // check if the student reentered
            elseif ($result2 == "reenter") {
                // insert another record into the database
                $sqlinsert = "INSERT INTO timestamp_student VALUES('', '$sid', '$currentdate','$currenttime',false)";
                // check whether reentered successfully
                if ($this->db->query($sqlinsert)){
                    return "reentered";
                } else {
                    return "reerror";
                }
            }
//            echo "Existed<br>";
//            $result2 = checkExit($sid, $currentdate, $currenttime);
//            if ($result2 == true){
//                echo "Student exited successfully<br>";
//            } else {
//                echo "Error exiting<br>";
//            }
        } else {
            // insert into database
            $sqlinsert = "INSERT INTO timestamp_student VALUES('', '$sid', '$currentdate','$currenttime',false)";
            // check if insert is successful
            if ($this->db->query($sqlinsert)){
                return "entered";
            } else {
                return "error";
            }
        }
    }

    // check and update the exit time of students
    function checkExit($sid, $currentdate, $time){
        // sql to get the latest record of checking in
        $sql = "SELECT * FROM timestamp_student WHERE sid='$sid' AND date='$currentdate' ORDER BY tid DESC LIMIT 1";

        // check if the student entered already
        if ($this->db->checkDuplicate($sql)){
            echo "Student Entered<br>";
        } else {
            echo "Student haven't enter<br>";
            return "noenter";
        }

        // get existing result
        $result = $this->db->getResult($sql);
        $row = $result->fetch_assoc();

        // if the time is not set/default (00:00)
        if (date ('H:i',strtotime($row['exitTime'])) == date ('H:i',strtotime("00:00"))){
            // update the exit time to current time
            $sqlinsert = "UPDATE timestamp_student SET exitTime = '$time' WHERE tid ='".$row['tid']."' ";

            // check if the exit insert is successful and send message to parents
            if ($this->db->query($sqlinsert)){
                $this->informGuardians($sid, $time);
                return "exited";
            }
        }
        // if the student already exited
        else {
            echo "Student already exited<br>";
            return "reenter";
        }
    }

    // function to output information to guardians
    function informGuardians($sid,$exitTime){
        $sql = "SELECT * FROM guardian WHERE sid='$sid'";
        $sql2 = "SELECT * FROM student WHERE sid='$sid'"; echo "$sql2";

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
                $message = "Dear Mr./Ms. ".$row['name'].",Your child, ".$row2['name'].", was checked out from the school at $exitTime.<br><br>
                        Contact Details:+60122345678<br>";
                echo $message;
                $phone = $row['phone'];
				
				// uncomment this line to send sms
//                $this->sendSMS($message, $phone);
            }

        } else {
            echo "Error sending message<br>";
        }

    }

    // *******

    function sendSMS($message, $phone){
        require('textmagic-sms-api-php/TextMagicAPI.php');

        $username = 'joanne.hwan';
        $password = '6PcQfA7vXt';

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