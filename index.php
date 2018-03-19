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



//$listData =$chimp->getList('144e46989e');

//print_r($listData);



//$deletedList = $chimp->deleteList('144e46989e');

//print_r($deletedList);


//$chimp->updateList('aed5c3930d');


//$result = $chimp->createMember('ab6d6ea698',[]);

//print_r($result);


$result = $chimp->updateMember('ab6d6ea698','ali@gmail.com');


var_dump($result);
