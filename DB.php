<?php
/**
 * Created by PhpStorm.
 * User: Bozo
 * Date: 22/03/2018
 * Time: 9:06 PM
 */

class DB
{

    private $host;
    private $user;
    private $password;
    private $database;

    private $conn;

    /**
     * DB constructor.
     * @param $host
     * @param $user
     * @param $password
     * @param $databaseName
     */
    public function __construct($host, $user, $password, $databaseName)
    {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->database = $databaseName;

        // Create connection
        $this->conn = mysqli_connect($host, $user, $password,$databaseName);
        // Check connection
        if (!$this->conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $this->listsTableSetUp();
        $this->membersTableSetUp();


    }

    public function truncateDB()
    {
        if (!$this->isConnected())
            exit('Could not connect to DB');

//        $sql = "truncate $"

    }

    public function listsTableSetUp(){

        if (!$this->isConnected())
            exit('Could not connect to DB');

        $this->conn->query("drop table if exists lists");

        $sql=   " create table if not exists lists
                (
                    id varchar(255) primary key unique,
                    name varchar(255) not null
                );";

        if($this->conn->query($sql)==true)
            echo "List table reset";
        else
            echo $this->conn->error;

    }

    public function membersTableSetUp(){

        if (!$this->isConnected())
            exit('Could not connect to DB');

        $this->conn->query("drop table if exists members");

        $sql= "create table if not exists members
                (
                    id varchar(255) primary key unique,
                    list_id varchar(255),
                    email varchar(255) unique,
                    FOREIGN KEY (list_id) REFERENCES lists(id)    
  
                );";

        if($this->conn->query($sql)==true)
            echo "members table reset<br>";
        else
            echo $this->conn->error;

    }



    public function getConnection()
    {
        return $this->conn;
    }



    public function getDBList()
    {
        if (!$this->isConnected())
            exit('Could not connect to DB');

        $sql = "Select id, name from lists";
        $result = $this->conn->query($sql);

        $list = array();
        if($result->num_rows > 0)
        {
            while($row= $result->fetch_assoc())
            {
                $list[] = $row;
            }
        }

        return $list;
    }

    public function dropTables()
    {
        $this->conn->query("drop table if exists members");
        $this->conn->query("drop table if exists lists");
    }


    public function setUpTables()
    {
        $this->listsTableSetUp();
        $this->membersTableSetUp();

    }
    public function getDBListItem($listID)
    {
        if (!$this->isConnected())
            exit('Could not connect to DB');

        $id = $this->conn->real_escape_string($listID);
        $sql = "Select id, name from lists where id = '$id' limit 1";
        $result = $this->conn->query($sql);

        $item = array();
        if($result->num_rows > 0)
        {
            $item= $result->fetch_assoc();
        }
        return $item;
    }

    public function isConnected()
    {
        if($this->conn!=false)
            return true;
        return false;
    }


    public function syncList($list)
    {

        $this->setUpTables();
        $values = "";
        $keys = "INSERT INTO `lists`(id,name) values";
        foreach ($list as $index=>$item)
        {
            if(!isset($item['id']) || !isset($item['name']))
            {

               echo 'list is not formated correctly for this item', "<br>\n";
                $this->newLine();
                print_r($item);
                exit();
            }
            $id = $item['id'];
            $name = $item['name'];
            $values.="('$id','$name'),";

        }

        $sql = rtrim($keys.' '.$values,",");
        if(!$this->isConnected())
            exit('not connected to db');

        if ($this->conn->query($sql) === TRUE)
        {
            return true;
        }
        else
        {
            echo "Error: " . $sql . "<br>" . $this->conn->error;
            return false;
        }

    }

    private function newLine()
    {
        echo "<br>\n";
    }


}