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


//
//$listData =$chimp->getLists();
//print_r($listData);
//return;
//$listData = $chimp->getList('81537a55b2');

//$listData = $chimp->createListItem();

//$listData = $chimp->updateList('2e3abfc3f2');
//print_r($listData);


$listData = $chimp->deleteList('aed5c3930d');

print_r($listData);
return;



//$deletedList = $chimp->deleteList('144e46989e');

//print_r($deletedList);


//$chimp->updateList('aed5c3930d');


//$result = $chimp->createMember('ab6d6ea698',[]);

//print_r($result);


//$result = $chimp->updateMember('ab6d6ea698','ali@gmail.com');
$data = array(

    "email_address"=>"property@gmail.com",
    "status"=>"subscribed",
    'merge_fields'=>array('FNAME'=>'test first name',
                            'LNAME'=>'test last name')

);
//$jsonData = json_encode($data);
//
//
//$result = $chimp->addMember('ab6d6ea698', "$jsonData");
//
//print_r($result);
//
//return;



$data = array(

    "email_address"=>"whatisthis@gmail.com",
    "status"=>"pending",
    'merge_fields'=>array('FNAME'=>'test first name - updated - final',
        'LNAME'=>'test last name - updated - final')

);

$jsonData = json_encode($data);

//$result = $chimp->updateMember('ab6d6ea698',"whatisthis@gmail.com",$jsonData);


$result = $chimp->removeMember('ab6d6ea698','adlfjdf+3@freddiesjokes.com');


var_dump($result);