

# MailChimp

Overview:
This little library interacts with MailChimp API. In this code you can do the following ONLY.
---------------------------------------------------------------------------------------------------
1) create a list.
2) Retrieve all the avaiable lists
3) Retrieve informatino about specific list
4) Update a list
5) Delete a list item
6) Create a a member within a list
7) Update a member within a list
8) Remove a member within a list.


REQUIREMENTS
=======================
1) Composer (so you can use to to download guzzle but other methods are ok).
1) Guzzle http client version 6.3
2) MailChimp API KEY


Assumptions 
=============
its *ASSUMED* that when you call the methods in ClientAP.php, that you provide correct arguments/formats.
Its very important that any body parameters are converted to json format before its fed to the methods.



*** For example ***
-----

```
// fields to update with the new values
$data = array(
    "email_address"=>"property@gmail.com",
    "status"=>"subscribed",
    'merge_fields'=>array('FNAME'=>'test first name',
                            'LNAME'=>'test last name')
);
$jsonData = json_encode($data);
$result = $chimp->addMember('ab6d6ea698', $jsonData);


```

See how the $dta is converted to json before its fed to the method.

How to get started
-------------------------------------------

1) Sign up with Mailchimp and get API Key if you dont have one already.
2) run this command  - Composer require "guzzlehttp/guzzle"
3) instantiate APIClient object and inject the API key.

You are ready to go!





