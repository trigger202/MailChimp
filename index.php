<?php
/**
 * Created by PhpStorm.
 * User: Ali
 * Date: 19/03/2018
 * Time: 7:51 PM
 */


require_once('vendor/autoload.php');
require_once ('APIClient.php');
require_once ('config.php');


$api_key = $config['API_Key'];
$chimp = new APIClient($api_key);


// ===============list functions ==============

/* create a list */

$data = array('name'=>'my first list item',

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
//$newItemInfo = $chimp->createListItem($jsonData);
//print_r($newItemInfo);
//return;



/*get all lists*/

//$listData =$chimp->getLists();
//print_r($listData);
//return;


/*get a specific list */
//$listData = $chimp->getList('81537a55b2');
//print_r($listData);
//return;




/*update a list */

$data = array('name'=>'my first list item - updated',

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


//$newItem= $chimp->updateList('81537a55b2', json_encode($data));
//print_r($newItem);
//return


///*delete a list item*/

//$listData = $chimp->deleteList('81537a55b2');
//print_r($listData);
//return;



/*======================create a member within a list =======================*/

$data = array(

    "email_address"=>"property@gmaicl.com",
    "status"=>"subscribed",
    'merge_fields'=>array('FNAME'=>'test first name',
                            'LNAME'=>'test last name')

);



//$result = $chimp->addMember('5d7331918b', json_encode($data));
//print_r($result);
//return;



//===================================================Update a member =============================

$data = array(

    "email_address"=>"property@gmail.com",
    "status"=>"subscribed",
    'merge_fields'=>array('FNAME'=>'test first name',
        'LNAME'=>'test last name')

);

$result = $chimp->updateMember('ab6d6ea698',"thisisadfdatest@gmail.com", json_encode($data));
print_r($result);

return;



//========================Remove a member from a list =======================
//$result = $chimp->removeMember('ab6d6ea698','urist.mcvankab+3@freddiesjokes.com');
//var_dump($result);