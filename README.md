# Laravel 4 Mailjet Integration #

- **Laravel**: 4
- **Author**: Parvez Rahaman
- **Author Homepage**: https://github.com/prspace

This package extends the Laravel 4 MailService to enable Mailjet integration, based on Mailjet API v3.

## Installation ##

Firstly you want to include this package in your composer.json file.
```javascript
"require": {
    "prspace/mailjet" : "1.0.*"
}
```
    
Now you'll want to update or install via composer.
```
composer update
```

Next you open up app/config/app.php and replace the MailServiceProvider with
```php
'Prspace\Mailjet\MailjetServiceProvider'
```

**NOTE** It is very important that you replace the default service providers to avoid conflicts. You don't lose any original functionality regarding mail drivers, they are still available.

Configuration is pretty easy: add the following entry to your config/services.php:
```php
'mailjet' => [
    'key' => 'APIKEY',
    'secret' => 'APISECRET',
],
```  
 
And replace the values with your respective api key and secret key.

Next, change the mail driver in your config/mail.php or your .env file to 'mailjet', and make sure you have a valid and authorised from-address configured.

That's all and you're good to go. For usage, check the [Laravel 4 mail documentation](http://laravel.com/docs/4.0/mail)

##API access##

I have also integrated direct access to Mailtjet's API, based on their [example code](https://github.com/mailjet/mailjet-apiv3-php-simple).

To install, append the aliases in config/app.php with
```php
'Mailjet'   => 'Prspace\Mailjet\Facades\Mailjet',
```
    
To use the API in your code, add 
```php 
use Mailjet;
```

### Examples
_Based on https://github.com/mailjet/mailjet-apiv3-php-simple/blob/master/README.md_

#### SendAPI

- A function to send an email :
```php
function sendEmail() {
    $params = [
        "method" => "POST",
        "from" => "ms.mailjet@example.com",
        "to" => "mr.mailjet@example.com",
        "subject" => "Hello World!",
        "text" => "Greetings from Mailjet."
    ];

    $result = Mailjet::sendEmail($params);

    if (Mailjet::getResponseCode() == 200)
       echo "success - email sent";
    else
       echo "error - ".Mailjet::getResponseCode();

    return $result;
}
```

- A function to send an email with some attachments (absolute paths on your computer) :
```php
function sendEmailWithAttachments() {
    $params = [
        "method" => "POST",
        "from" => "ms.mailjet@example.com",
        "to" => "mr.mailjet@example.com",
        "subject" => "Hello World!",
        "text" => "Greetings from Mailjet.",
        "attachment" => ["@/path/to/first/file.txt", "@/path/to/second/file.txt"]
    ];

    $result = Mailjet::sendEmail($params);

    if (Mailjet::getResponseCode() == 200)
       echo "success - email sent";
    else
       echo "error - ".Mailjet::getResponseCode();

    return $result;
}
```

- A function to send an email with some inline attachments (absolute paths on your computer) :
```php
function sendEmailWithInlineAttachments() {
    
    $params = [
        "method" => "POST",
        "from" => "ms.mailjet@example.com",
        "to" => "mr.mailjet@example.com",
        "subject" => "Hello World!",
        "html" => "<html>Greetings from Mailjet <img src=\"cid:photo1.jpg\"><img src=\"cid:photo2.jpg\"></html>",
    "inlineattachment" => ["@/path/to/photo1.jpg", "@/path/to/photo2.jpg"]
    ];

    $result = Mailjet::sendEmail($params);

    if (Mailjet::getResponseCode() == 200)
       echo "success - email sent";
    else
       echo "error - ".Mailjet::getResponseCode();

    return $result;
}
```

#### Account Settings

- A function to get your profile information :
```php
function viewProfileInfo() {
    
    $result = Mailjet::myprofile();

    if (Mailjet::getResponseCode() == 200)
       echo "success - got profile information";
    else
       echo "error - ".Mailjet::getResponseCode();
}
```

- A function to update the field ```AddressCity``` of your profile :
```php
function updateProfileInfo() {
    
    $params = [
        "method" => "PUT",
        "AddressCity" => "New York"
    ];

    $result = Mailjet::myprofile($params);

    if (Mailjet::getResponseCode() == 200)
       echo "success - field AddressCity changed";
    else
       echo "error - ".Mailjet::getResponseCode();

    return $result;
}
```

#### Contact Lists

- A function to print the list of your contacts :
```php
function listContacts()
{
    
    $result = Mailjet::contact();

    if (Mailjet::getResponseCode() == 200)
       echo "success - listed contacts";
    else
       echo "error - ".Mailjet::getResponseCode();

    return $result;
}
```

- A function to update your contactData resource with ID ```$id```, using arrays :
```php
function updateContactData($id) {
    
    $data = [
        ['Name' => 'lastname', 'Value' => 'Jet'], 
        ['Name' => 'firstname', 'Value' => 'Mail']
    ];
    $params = [
        'ID' => $id,
        'Data' => $data,
        'method' => 'PUT'
    ];

    $result = Mailjet::contactdata($params);

    if (Mailjet::getResponseCode() == 200)
       echo "success - data changed";
    else
       echo "error - ".Mailjet::getResponseCode();

    return $result;
}
```

- A function to create a list with name ```$Lname``` :
```php
function createList($Lname) {
    
    $params = [
        "method" => "POST",
        "Name" => $Lname
    ];

    $result = Mailjet::contactslist($params);

    if (Mailjet::getResponseCode() == 201)
       echo "success - created list ".$Lname;
    else
       echo "error - ".Mailjet::getResponseCode();

    return $result;
}
```

- A function to get a list with ID ```$listID``` :
```php
function getList($listID) {
    
    $params = [
        "method" => "VIEW",
        "ID" => $listID
    ];

    $result = Mailjet::contactslist($params);

    if (Mailjet::getResponseCode() == 200)
       echo "success - got list ".$listID;
    else
       echo "error - ".Mailjet::getResponseCode();

    return $result;
}
```
Note : You can use unique fields of resources instead of IDs, like
```"unique" => "test@gmail.com"``` in your ```params``` array for this example

- A function to create a contact with email ```$Cemail``` :
```php
function createContact($Cemail) {
    
    $params = [
        "method" => "POST",
        "Email" => $Cemail
    ];

    $result = Mailjet::contact($params);

    if (Mailjet::getResponseCode() == 201)
       echo "success - created contact ".$Cname;
    else
       echo "error - ".Mailjet::getResponseCode();

    return $result;
}
```

- A function to add the contact which ID is ```$contactID``` to the list which ID is ```$listID``` :
```php
function addContactToList($contactID, $listID) {
    
    $params = [
        "method" => "POST",
        "ContactID" => $contactID,
        "ListID" => $listID,
        "IsActive" => "True"
    ];

    $result = Mailjet::listrecipient($params);

    if (Mailjet::getResponseCode() == 201)
       echo "success - contact ".$contactID." added to the list ".$listID;
    else
       echo "error - ".Mailjet::getResponseCode();

    return $result;
}
```

- A function to delete the list which ID is ```$listID``` :
```php
function deleteList($listID) {
    
    $params = [
        "method" => "DELETE",
        "ID" => $listID
    ];

    $result = Mailjet::contactslist($params);

    if (Mailjet::getResponseCode() == 204)
       echo "success - deleted list";
    else
       echo "error - ".Mailjet::getResponseCode();

    return $result;
}
```

- A function to get unsubscribed contact(s) from a list with ID ```$listID``` :
```php
function getUnsubscribedContactsFromList($listID) {
	
	
	$params = [
		"method" => "GET",
		"ContactsList" => $listID,
		"Unsub" => true
	];
	
	$result = Mailjet::listrecipient($params);
	
    if (Mailjet::getResponseCode() == 200)
       echo "success - got unsubscribed contact(s) ";
    else
       echo "error - ".Mailjet::getResponseCode();
   
	return $result;   
}
```

- A function to get a contact with ID ```$contactID``` :
```php
function getContact($contactID) {
    
    $params = [
        "method" => "VIEW",
        "ID" => $contactID
    ];

    $result = Mailjet::contact($params);

    if (Mailjet::getResponseCode() == 200)
       echo "success - got contact ".$contactID;
    else
       echo "error - ".Mailjet::getResponseCode();

    return $result;
}
```
Note : You can use unique fields of resources instead of IDs, like
```"unique" => "test@gmail.com"``` in your ```params``` array for this example

#### Newsletters

You can use the ```DetailContent``` action to manage the content of a newsletter, in Text and Html.
It has two properties : ```Text-part``` and ```Html-part```.
You can use ```GET```, ```POST```, ```PUT``` and ```DELETE``` both  requests on this action :
* ```GET``` : you get the ```Text-part``` and ```Html-part``` properties of a newsletter
* ```POST``` : update the content of ```Text-part``` and ```Html-part```. If you specify only one, the other will be emptied
* ```PUT``` : update the content of ```Text-part``` and ```Html-part```. You can specify only one, it will not empty the other one
* ```DELETE``` : update the content of ```Text-part``` and ```Html-part``` and put both to empty.

Example with a ```GET``` on ```DetailContent``` :

```php
function getNewsletterDetailcontent($newsletter_id) {
    $params = [
        "method" => "GET",
        "ID" => $newsletter_id
    ];

    $result = Mailjet::newsletterDetailContent($params);

    if (Mailjet::getResponseCode() == 200)
        echo "success - got content for the newsletter ". $newsletter_id;
    else
        echo "error - ".Mailjet::getResponseCode();
    
    return $result;
}
```

Use the ```schedule``` action to send a newsletter later.
You just need to perform a ```POST``` request to schedule a new sending and to fill the ```date``` property with a Timestamp format in ISO 8601 : http://www.iso.org/iso/home/standards/iso8601.htm
You can also ```DELETE``` a schedule
Here is an example :

```php
function scheduleNewsletter($newsletter_id) {
    $params = [
        "method" => "POST",
        "ID" => $newsletter_id,
        "date" => "2014-11-25T10:12:59Z"
    );

    $result = Mailjet::newsletterSchedule($params);

    if (Mailjet::getResponseCode() == 201)
        echo "success - schedule done for the newsletter ". $newsletter_id;
    else
        echo "error - ".Mailjet::getResponseCode();
    
    return $result;
}
```

To send a newsletter immediately, you have two possibilities :
* ```POST``` a new schedule with a Timestamp which value is ```NOW```
* use send (only ```POST``` is supported)
For the second case, here is an example :

```php
function sendNewsletter($newsletter_id) {
    
    $params = [
        "method" => "POST",
        "ID" => $newsletter_id
    ];

    $result = Mailjet::newsletterSend($params);

    if (Mailjet::getResponseCode() == 201)
        echo "success - newsletter ". $newsletter_id . " has been sent";
    else
        echo "error - ".Mailjet::getResponseCode();
    
    return $result;
}
```

You can also test a newsletter by sending it to some specified recipients before making the real sending.
To do so, you have to perform a ```POST``` request on a newsletter with action ```test``` like in the following example :

```php
function testNewsletter($newsletter_id) {
    
    $recipients = [
        ['Email' => 'mailjet@example.org', 'Name' => 'Mailjet']
    ];
    $params = [
        "method" => "POST",
        "ID" => $newsletter_id,
        "Recipients" => $recipients
    ];

    $result = Mailjet::newsletterTest($params);

    if (Mailjet::getResponseCode() == 201)
        echo "success - newsletter ". $newsletter_id . " has been sent";
    else
        echo "error - ".Mailjet::getResponseCode();
    
    return $result;
}
```
