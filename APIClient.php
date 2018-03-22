<?php
/**
 * Created by PhpStorm.
 * User: Ali
 * Date: 19/03/2018
 * Time: 8:08 PM
 */

include_once ('DB.php');


class APIClient
{
    /*change this if your datacenter is different*/
    private $url = "https://us12.api.mailchimp.com/3.0";
    private $httpClient;
    private $api_key ;
    public  $listCount =0;
    public $isSynced = false;

    private $db_conn;
    /**
     * APIClient constructor.
     * @param $httpClient
     *
     */

    /* pass the api key in the constructor  */
    public function __construct($api_key)
    {
        $this->api_key = $api_key;
        $this->httpClient = new GuzzleHttp\Client();
        $this->db_conn = new DB('localhost','root','','loyalty');
        $this->isSynced = $this->synDBandMailchimp();

    }

    /*get all the available lists in mailchimp and insert them into db.
    will use this list for deletion or updating */
    private function synDBandMailchimp()
    {

        echo '<br>syncing data <br>';

        $this->db_conn->dropTables();

        $mailChimpList = json_decode($this->getLists(),true);
        $newList = array();
        if(isset($mailChimpList['lists']))
        {
            foreach ($mailChimpList['lists'] as $item)
            {
                $newList[] = array('id'=>$item['id'], 'name'=>$item['name']);
            }
        }
        $this->setListCount(count($newList));
        return $this->db_conn->syncList($newList);
    }

    public function setListCount($count)
    {
        $this->listCount = $count;
    }


    /*gets all available lists*/
    public function getLists()
    {


        if($this->listCount>0 && $this->isSynced)
        {
            echo 'chcking the db';
            return json_encode($this->db_conn->getDBList());
        }
        if($this->listCount==0 && $this->isSynced)
            return array();

        $url = $this->url.'/lists/';
        try
        {
            $response = $this->API_Request('GET',$url);
            if($response->getStatusCode()==200)
                return $response->getBody()->getContents();
            return false;
        }
        catch (Exception $e)
        {
            $e->getMessage();
        }
    }


    /*returns singe list if exists */
    public function getList($listID)
    {
        $url = $this->url.'/lists/'.$listID;
        try
        {
            $response = $this->API_Request('GET',$url);
            if($response->getStatusCode()==200)
                return $response->getBody()->getContents();
            return false;
        }
        catch (Exception $e)
        {
            $e->getMessage();
        }
    }


    /*create a new list. */
    public function createListItem($jsonData)
    {
        $url = $this->url.'/lists/';
        try
        {
            $response = $this->API_Request('POST',$url,$jsonData);
            if ($response->getStatusCode() == 200)
            {
                return $response->getBody()->getContents();
            }
            return false;
        }
        catch (Exception $e)
        {
            $e->getMessage();
        }
    }


    /*-- deletes a list item */
    /**
     * @param $listID
     * @return bool
     */
    public function deleteList($listID)
    {
        $url = $this->url.'/lists/'.$listID;
        try
        {
            $response = $this->API_Request('Delete', $url);
            if ($response->getStatusCode() == 204)
            {
                return true;
            }
            return false;
        }
        catch (Exception $e)
        {
            $e->getMessage();
        }
    }

   /*update a specific list*/
    public function updateList($listID, $jsonData = null)
    {
        if($jsonData==null)
        {
            throw new Exception("Request body paramters cannot be empty. e.g name field must have a valude");
        }
        $url = $this->url.'/lists/'.$listID;
        try {
            $response = $this->API_Request('PATCH', $url,$jsonData);
            if ($response->getStatusCode() == 200) {
                return true;
            }
            return false;
        }
        catch (Exception $e)
        {
            $e->getMessage();
        }
    }


    /*member functions*/
    public function subscriberHash($email)
    {
        return md5(strtolower($email));
    }

    /*	Add a member to list*/
    public function addMember($listID, $data)
    {
        $url = $this->url.'/lists/'.$listID.'/members';

        try {

            $response = $this->API_Request('POST', $url,$data);
            if ($response->getStatusCode() == 200) {
                /*newly created member data*/
                return $response->getBody()->getContents();
            }
            return $response->getStatusCode();
        }
        catch (Exception $e)
        {
            $e->getMessage();
        }

    }


    public function updateMember($listID, $email, $bodyParams =array())
    {
        $subscriber_hash =$this->subscriberHash($email);
        $url = $this->url.'/lists/'.$listID.'/members/'.$subscriber_hash;

        try
        {
            $response = $this->API_Request('PATCH', $url,$bodyParams);
            if ($response->getStatusCode() == 200) {
                /*newly created member data*/
                return $response->getBody()->getContents();

            }
            return $response->getStatusCode();
        }
        catch (Exception $e)
        {
            echo "ERROR...Something went wrong updating member.";
            $e->getMessage();
        }
    }

    public function removeMember($listID, $email)
    {

        $subscriber_hash =$this->subscriberHash($email);
        $url = $this->url.'/lists/'.$listID.'/members/'.$subscriber_hash;
        try
        {
            $response = $this->API_Request('DELETE', $url);
            if ($response->getStatusCode() == 204)
            {
                return true;
            }
            return false;
        }
        catch (Exception $e)
        {
            echo "ERROR...Something went wrong updating member.";
            $e->getMessage();
        }
    }

    /*
     * Main function that handles all the calls
     * returns http response from the api*/
    private function API_Request($method, $url, $args= array() )
    {
        try
        {
            if(is_array($args) && count($args)==0)
            {
                return  $this->httpClient->request($method, $url, ['auth' => ['username', $this->api_key]] );
            }
            else
            {
                return $this->httpClient->request($method, $url,
                    [
                        'auth' => ['username', $this->api_key],
                                'body'=>$args
                    ]);
            }
        }
        catch (Exception $e)
        {
            echo "ERROR...Something went wrong.\n\n\n";
            print_r($e);
            exit();
        }
    }



}