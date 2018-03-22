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

        return $this->conn;
    }

    public function truncateDB()
    {
        if (!$this->isConnected())
            exit('Could not connect to DB');

//        $sql = "truncate $"

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


        $sql = "INSERT INTO $this->database.`lists` (`id`,`name`) ";
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

            $f = $sql." values('$id','$name'sdfd)";
            $this->conn->query("$f");

//            $sql.= "values('$id','$name');";

        }
        if(!$this->isConnected())
            exit('not connected to db');

//        exit($sql);

    }

    private function newLine()
    {
        echo "<br>\n";
    }


}