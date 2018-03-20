<?php
/**
 * Created by PhpStorm.
 * User: Ali
 * Date: 19/03/2018
 * Time: 8:08 PM
 */

class APIClient
{
    /*change this if your datacenter is different*/
    private $url = "https://us12.api.mailchimp.com/3.0";
    private $httpClient;
    private $api_key ;

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
    }

    /*gets all available lists*/
    public function getLists()
    {
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
    public function createListItem($data = "")
    {
        /*sample data*/
        if($data=="")
        {
            $data = array('name'=>'create a new list - test again',
                'contact'=>array(
                    'company' => "MailChimp",
                    'address1' => "675 Ponce De Leon Ave NE",
                    'address2' => "Suite 5000",
                    'city' => "zip",
                    'state' => "GA",
                    'zip' => "30308",
                    'country' => "US",
                    'phone' => ""
                ),

                'permission_reminder'=>'this is a test -----ignore',
                'campaign_defaults'=>array(
                    'from_name'=>"ali fareh",
                    'from_email'=>"alifareh_test@gmail.com",
                    'subject'=>"ali test",
                    'language'=>"en",
                ),

                'email_type_option'=>true,
            );
        }


        $bodyParam = json_encode($data);
        $url = $this->url.'/lists/';
        try
        {
            $response = $this->API_Request('POST',$url,$bodyParam);
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

    public function getSampleListObject($title = "")
    {
        if($title=='')
            $title = "This is the name of the list - test ";
        $data = array('name'=>$title,

            'contact'=>array(
                'company' => "MailChimp",
                'address1' => "675 Ponce De Leon Ave NE",
                'address2' => "Suite 5000",
                'city' => "zip",
                'state' => "GA",
                'zip' => "30308",
                'country' => "US",
                'phone' => ""
            ),

            'permission_reminder'=>'this is a test, please ignore if you recieve this',


            'campaign_defaults'=>array(

                'from_name'=>"ali",
                'from_email'=>"ali@gmail.com",
                'subject'=>"ali test",
                'language'=>"en",
            ),

            'email_type_option'=>false,

        );

        return json_encode($data);
    }


    /*update a specific list*/
    public function updateList($listID, $data = null)
    {

        if($data==null)
            $bodyParam = $this->getSampleListObject();
        $url = $this->url.'/lists/'.$listID;
        try {
            $response = $this->API_Request('PATCH', $url,$bodyParam);
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
            echo "ERROR...Something went wrong updating member.";
            print_r($e);
        }

    }


    public function updateMember($listID, $email, $bodyParams =array())
    {


        $subscriber_hash =$this->subscriberHash($email);
        $url = $this->url.'/lists/'.$listID.'/members/'.$subscriber_hash;

        try {
            $response = $this->API_Request('PATCH', $url,$bodyParams);
            if ($response->getStatusCode() == 200) {
                /*newly created member data*/
                return $response->getBody()->getContents();

            }
            return false;
        }
        catch (Exception $e)
        {
            echo "ERROR...Something went wrong updating member.";
            print_r($e);
            exit();
        }
    }

    public function removeMember($listID, $email)
    {

        $subscriber_hash =$this->subscriberHash($email);
        $url = $this->url.'/lists/'.$listID.'/members/'.$subscriber_hash;
        try {
            $response = $this->API_Request('DELETE', $url);
            if ($response->getStatusCode() == 204) {
                return true;
            }
            return false;
        }
        catch (Exception $e)
        {
            echo "ERROR...Something went wrong updating member.";
            print_r($e);
            exit();
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
                return $this->httpClient->request($method, $url,[
                    'auth' => ['username', $this->api_key],
                    'body'=>$args ]);
            }
        }
        catch (Exception $e)
        {
            echo "ERROR...Something went wrong updating member.\n\n\n";
            print_r($e);
        }
    }



}