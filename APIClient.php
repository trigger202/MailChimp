<?php
/**
 * Created by PhpStorm.
 * User: Bozo
 * Date: 19/03/2018
 * Time: 8:08 PM
 */


class APIClient
{


    private $url = "https://us12.api.mailchimp.com/3.0";
    private $httpClient;

    /**
     * APIClient constructor.
     * @param $httpClient
     */
    public function __construct($api_key)
    {
        $this->httpClient = new GuzzleHttp\Client(  ['auth' => ['username', $api_key]]);
    }

    /*gets all available lists*/
    public function getLists()
    {
            $url = $this->url.'/lists/';
            try {
                $response = $this->httpClient->request('GET', $url);

                if ($response->getStatusCode() == 200) {
                    $lists = $response->getBody()->getContents();
                    return $lists;
                }
            }
            catch (Exception $e)
            {
                print_r($e);
                exit();
            }
    }


    /*returns singe list if exists */
    public function getList($listID)
    {
        $url = $this->url.'/lists/'.$listID;
        try {
            $response = $this->httpClient->request('GET', $url);
            if ($response->getStatusCode() == 200) {
                return $response->getBody()->getContents();
                return $lists;
            }
        }
        catch (Exception $e)
        {
            print_r($e);
            exit();
        }
    }


    public function deleteList($listID)
    {
        $url = $this->url.'/lists/'.$listID;
        try {
            $response = $this->httpClient->request('Delete', $url);

            if ($response->getStatusCode() == 200) {
                $lists = $response->getBody()->getContents();
                return $lists;
            }
        }
        catch (Exception $e)
        {
            print_r($e);
            exit();
        }
    }

    /*create a new list. expectation is data object will have all the necessary arguments*/
    public function createListItem($jsonData)
    {

        $data = array('name'=>'create a new list - test',

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

        $bodyParam = json_encode($data);



        $url = $this->url.'/lists/';
        try {
            $response = $this->httpClient->request('POST', $url, ['body'=>$bodyParam]);

            print_r($response->getStatusCode());
            if ($response->getStatusCode() == 200) {
                return true;
            }
            return false;
        }
        catch (Exception $e)
        {
            print_r($e);
            exit();
        }
    }


    public function getSampleListObject()
    {


        $data = array('name'=>'create a new list - test - UPDATED',

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

        $jsonData = json_encode($data);

        return $jsonData;

    }



    /*update a specific list*/
    public function updateList($listID)
    {
        $bodyParam = $this->getSampleListObject();
        $url = $this->url.'/lists/'.$listID;
        try {
            $response = $this->httpClient->request('PATCH', $url,['body'=>$bodyParam]);
            if ($response->getStatusCode() == 200) {
                return true;
            }
            return false;
        }
        catch (Exception $e)
        {
            print_r($e);
            exit();
        }
    }



    /*member functions*/

    /*creates a single member wthin a list*/
    public function createMember($listID, $memberJsonData)
    {
        $bodyParam = '{"email_address":"alifareh@gmail.com", "status":"pending"}';
        $url = $this->url.'/lists/'.$listID.'/members';
        try {
            $response = $this->httpClient->request('POST', $url,['body'=>$bodyParam]);
            if ($response->getStatusCode() == 200) {
                /*newly created member data*/
                return $response->getBody()->getContents();

            }
            return false;
        }
        catch (Exception $e)
        {
            echo "ERROR...Something went wrong creating a new member.";
//            print_r($e);
            exit();
        }
    }

    public function updateMember($listID, $email, $bodyParam ="")
    {

        if($bodyParam=="")
        {
            $merge_fields = array(
                'merge_fields'=>array(
                    'FNAME'=>"test User - updated",
                    'LNAME'=>'Last Name - updated'
                ),
            );
            $bodyParam = json_encode($merge_fields);
        }
        $subscriber_hash =md5($email);
        $url = $this->url.'/lists/'.$listID.'/members/'.$subscriber_hash;
        try {
            $response = $this->httpClient->request('PATCH', $url,['body'=>$bodyParam]);
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



}