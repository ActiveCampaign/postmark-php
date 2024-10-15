<?php

// Import the Postmark Client Class:
require_once('vendor/autoload.php');
use Postmark\PostmarkClient;


$client = new PostmarkClient("b19c9a23-f5f6-4921-ac30-fbe0a6c24277");

$templateModel["product_url"] = "product_url_Value";
$templateModel["product_name"] = "product_name_Value";
$templateModel["name"] = "name_Value";
$templateModel["action_url"] = "action_url_Value";
$templateModel["operating_system"] = "operating_system_Value";
$templateModel["browser_name"] = "browser_name_Value";
$templateModel["support_url"] = "support_url_Value";
$templateModel["company_name"] = "company_name_Value";
$templateModel["company_address"] = "company_address_Value";

$temp["From"] = 'test <ecards@123cards.com<postmark-engineering+library-tests@activecampaign.com>';
$temp["MessageStream"] =  'outbound';

$temp["Tag"] = 'ecard-sender';
$temp["TemplateAlias"] = 'password-reset';
$temp["templateModel"] = $templateModel; //"{

$temp["To"] = 'eli.sinclair.wood@gmail.com';

$templateEmails[] = $temp;


$return = $client->getSuppressions("php-test");

echo print_r($return, false);

//echo "Test " . print_r($templateEmails, false);
//$return = $client->sendEmailBatchWithTemplate($templateEmails);
//
//echo "Test2-2 " . print_r($return, false);
//$bounces = $client->getBounces(20, 0);
//////echo print_r($bounces, false);
//$temp = $bounces->getBounces();
////echo "\n Bounces " . print_r($temp, false);
////
//$id = 0;
//echo "Setup complete\n";
//sleep(60);
//echo "On to the next step\n";
//foreach ($temp as $bounce)
//{
//    $mid = $bounce->getMessageID();
//    echo "\n Bounce Message Id $mid";
//
//    if ('678e5a2c-efbf-4b38-afd2-827330ef43f5' === $mid)
//    {
//        $id = $bounce->getID();
//        break;
//    }
//}
//
//echo "\nTest value: $id";

// make sure that this email is not suppressed
// generate a bounces
//$fromEmail = "andrew+client-testing@wildbit.com";
//$toEmail = "hardbounce@bounce-testing.postmarkapp.com"; // special email to generate bounce
//$subject = "Hello from Postmark!";
//$htmlBody = "<strong>Hello</strong> dear Postmark user.";
//$textBody = "Hello dear Postmark user.";
//$tag = "example-email-tag";
//$trackOpens = true;
//$trackLinks = "None";
//
//$sendResult = $client->sendEmail(
//    $fromEmail,
//    $toEmail,
//    $subject,
//    $htmlBody,
//    $textBody,
//    $tag,
//    $trackOpens,
//    NULL, // Reply To
//    NULL, // CC
//    NULL, // BCC
//    NULL, // Header array
//    NULL, // Attachment array
//    $trackLinks,
//    NULL // Metadata array
//);
//
//// make sure there is enough time for the bounce to take place.
//sleep(10);
//
//$bounces = $client->getBounces(10, 0);
//$id = 0;
//$sentId = $sendResult->getMessageID();
//
//echo "Bounces " . print_r($bounces->getBounces(), false);
//
//foreach ($bounces->getBounces() as $bounce)
//{
//    echo "Bounce Message Id " . print_r($bounce->getMessageID(), false);
////    if ($sentId == $bounce->getMessageID())
////    {
////        $id = $bounce->getID();
////        break;
////    }
//}


//echo "Test " . print_r($id, false);
//$bounceActivation = $client->activateBounce($id);
//$bounce = $bounceActivation->getBounce();

//echo "More test " . print_r($bounceActivation, false);
//echo "even More test " . print_r($bounce, false);

//$fromEmail = "andrew+client-testing@wildbit.com";
//$replyTo = "andrew+client-testing@wildbit.com";
//$toEmail = "hardbounce@bounce-testing.postmarkapp.com";
//$subject = "Hello from Postmark!";
//$htmlBody = "<strong>Hello</strong> dear Postmark user.";
//$textBody = "Hello dear Postmark user.";
//$tag = "example-email-tag";
//$trackOpens = true;
//$trackLinks = "None";
//$templateId = "php-testing";
//$templateModel = ['subjectValue' => 'Hello!'];
//$messageStream = "php-test2";
//
//$doesStreamExist = $client->getMessageStream($messageStream);

// Send an email:
//$sendResult = $client->sendEmail(
//    $fromEmail,
//    $toEmail,
//    $subject,
//    $textBody
//);

//$sendResult = $client->sendEmail(
//    $fromEmail,
//    $toEmail,
//    $subject,
//    $htmlBody,
//    $textBody,
//    $tag,
//    $trackOpens,
//    NULL, // Reply To
//    NULL, // CC
//    NULL, // BCC
//    NULL, // Header array
//    NULL, // Attachment array
//    $trackLinks,
//    NULL, // Metadata array
//    $messageStream
//);
//
//echo print_r($sendResult, false);

//$sendResult = $client->sendEmailWithTemplate(
//    $fromEmail,
//    $toEmail,
//    $templateId,
//    $templateModel,
//    true, // InlineCss
//    $tag, // Tag
//    true, // TrackOpens
//    $replyTo, // ReplyTo
//    NULL, // Cc
//    NULL, // Bcc
//    NULL, // Header array
//    NULL, // Attachments
//    false, // TrackLinks
//    NULL, // Metadata
//    $messageStream // message stream
//);

//$sendResult = $client->activateBounce(2422805943);
//
//echo print_r($sendResult, false);