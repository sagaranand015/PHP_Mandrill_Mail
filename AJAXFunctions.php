<?php 

// for mandrill mail sending API.
require_once 'mandrill/Mandrill.php'; 

$from = $_POST["txt-from"];
$txtEmails = $_POST["txt-emails"];
$subject = $_POST["txt-subject"];
$message = $_POST["txt-message"];

$emails = array();
$emails = split(',', $txtEmails);

$i = 0;
for($i = 0;$i<count($emails);$i++) {
	$emails[$i] = trim($emails[$i]);
}

$sent = 0;
$queued = 0;
$error = 0;

// now, send the mails here.
$j = 0;
for($j = 0;$j<count($emails);$j++) {

	$mandrill = new Mandrill('J99JDcmNNMQLw32QJGDadQ');
	$message = array(
        'html' => $message,
        'subject' => $subject,
        'from_email' => $from,
        'from_name' => 'Mentored-Research',
        'to' => array(
            array(
                'email' => $emails[$j],
                'name' => $emails[$j],
                'type' => 'to'
            )
        )
    );
    $async = false;
    $ip_pool = 'Main Pool';
    $send_at = null;
    $result = $mandrill->messages->send($message, $async, $ip_pool, $send_at);

	$status = $result[0]['status'];

	if($status == 'sent') {
		$sent++;
	}
	else if($status == 'queued' || $status == 'scheduled') {
		$queued++;
	}
	else if($status == 'rejected' || $status == 'invalid') {
		$error++;
	}

}  // end of loop.

echo $i . " --> <br />";

echo "Final Result: <br />";
echo "1. Success: " . $sent . " Mail successful. <br />";
echo "2. Queued: " . $queued . " Queued successful. Will be sent soon. <br />";
echo "3. Error: " . $error . " could not be sent. Please try again. <br />";

?>