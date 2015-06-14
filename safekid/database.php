<?php
/**
 * Created by PhpStorm.
 * User: joannehwan
 * Date: 6/13/15
 * Time: 10:42 AM
 */

$db = new database();

class database
{
    public $server = "localhost";
    public $user = "root";
    public $pw = "root";
    public $db = "safekid";

//    // initialise the parameters for database connection
//    private $server = "localhost";
//    private $user = "oasoluti_joanne";
//    private $pw = "{Kl!xh@P0=Ml";
//    private $db = "oasoluti_qq";

    function __construct()
    {
        // connect to database
        $this->mysqli = new mysqli($this->server, $this->user, $this->pw, $this->db);

        // check if any error occurs
        if ($this->mysqli->connect_errno) {
            echo "Failed to connect to MySQL: (" . $this->mysqli->connect_errno . ") " .
                $this->mysqli->connect_error;
        }
		
		// Uncomment the following lines to setup the database 
		//$this->setupDb();
		//$this->setupTables();
    }

    function setupDb(){
        if (!mysqli_select_db($this->mysqli,$this->db)) {
            $sqldb = "CREATE DATABASE $this->db";
            if ($this->query($sqldb) === TRUE) {
                echo "<br>Database created successfully";
            } else {
                echo "<br>Error creating database: " . $this->mysqli->error;
            }
        }

        //connect to database
        $this->mysqli = new mysqli($this->server, $this->user, $this->pw, $this->db);
    }

    function setupTables()
    {
        // check if class table exists
        if (mysqli_num_rows($this->mysqli->query("SHOW TABLES LIKE 'student'")) == 1) {
            echo "Table :student exists<br>";
        } else {
            echo "Table :student does not exist, creating table... <br>";
            // create class table
            $this->query("CREATE TABLE student (
                        sid INT(6) NOT NULL,
                        name VARCHAR(50) NOT NULL,
                        exitTime time(4) NOT NULL,
                       PRIMARY KEY (sid))");
        }

        // check if class table exists
        if (mysqli_num_rows($this->mysqli->query("SHOW TABLES LIKE 'guardian'")) == 1) {
            echo "Table :guardian exists<br>";
        } else {
            echo "Table :guardian does not exist, creating table... <br>";
            // create class table
            $this->query("CREATE TABLE guardian (
                        gid INT(6) NOT NULL,
                        name VARCHAR(50) NOT NULL,
                        sid INT(6) NOT NULL,
                        phone INT(12) NOT NULL,
                        PRIMARY KEY (gid))");
        }

        // check if class table exists
        if (mysqli_num_rows($this->mysqli->query("SHOW TABLES LIKE 'timestamp_student'")) == 1) {
            echo "Table :timestamp exists<br>";
        } else {
            echo "Table :timestamp does not exist, creating table... <br>";
            // create class table
            $this->query("CREATE TABLE timestamp_student (
                        tid INT(6) NOT NULL AUTO_INCREMENT,
                        sid INT(6) NOT NULL,
                        date VARCHAR(10),
                        enterTime time(4),
                        exitTime time(4),
                        PRIMARY KEY (tid))");
        }

        // check if class table exists
        if (mysqli_num_rows($this->mysqli->query("SHOW TABLES LIKE 'credit_student'")) == 1) {
            echo "Table :credit_student exists<br>";
        } else {
            echo "Table :credit_student does not exist, creating table... <br>";
            // create class table
            $this->query("CREATE TABLE credit_student (
                        sid INT(6) NOT NULL,
                        credit VARCHAR(10),
                        PRIMARY KEY (sid))");
        }

        // check if class table exists
        if (mysqli_num_rows($this->mysqli->query("SHOW TABLES LIKE 'current_sid'")) == 1) {
            echo "Table :current_sid exists<br>";
        } else {
            echo "Table :current_sid does not exist, creating table... <br>";
            // create class table
            $this->query("CREATE TABLE current_sid (
                        current_id INT(6) NOT NULL AUTO_INCREMENT,
                        sid int(6),
                        PRIMARY KEY (current_id))");
        }

        // check if class table exists
        if (mysqli_num_rows($this->mysqli->query("SHOW TABLES LIKE 'transaction'")) == 1) {
            echo "Table :transaction exists<br>";
        } else {
            echo "Table :transaction does not exist, creating table... <br>";
            // create class table
            $this->query("CREATE TABLE transaction (
                        trans_id VARCHAR(6) NOT NULL,
                        date VARCHAR(6),
                        time time(4),
                        sid INT(6),
                        total int(6),
                        PRIMARY KEY (trans_id))");
        }

        // check if class table exists
        if (mysqli_num_rows($this->mysqli->query("SHOW TABLES LIKE 'transaction_items'")) == 1) {
            echo "Table :transaction_items exists<br>";
        } else {
            echo "Table :transaction_items does not exist, creating table... <br>";
            // create class table
            $this->query("CREATE TABLE transaction_items (
                        trans_id VARCHAR(6) NOT NULL,
                        item varchar(50),
                        amount int(11))");
        }
    }

    // function to query and return result
    function getResult($sql){
        $result = $this->mysqli->query($sql) or die($this->mysqli->error);
        return $result;
    }

    // function to execute insert/update/delete queries
    function query($sql){
        return $this->mysqli->query($sql);
    }

    // function to check if there is duplication of information
    function checkDuplicate($sql){
        $result = $this->getResult($sql);

        if (mysqli_num_rows($result) == 0){
            return false;
        } else {
            return true;
        }
    }

}