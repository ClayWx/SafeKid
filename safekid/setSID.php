<?php
// dummy data
//$sid = "100001";

$sid = $_GET['sid'];

// set up current date and time
date_default_timezone_set('Asia/Kuala_Lumpur');
$currentdate = date('d/m/Y');
$currenttime = date('H:i');

$id = new setSID();

$id->setCurrentID($sid);

class setSID
{
    function __construct(){
        // start up database
        include_once("database.php");
        $this->db = new database();
    }

    // function to set the data get from java
    function setCurrentID($sid){
        $sql = "INSERT INTO current_sid VALUES('','$sid')";
        $this->db->query($sql);
		echo "Updated";
    }

}
