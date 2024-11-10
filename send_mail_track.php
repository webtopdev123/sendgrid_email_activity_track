<?php

// Your SendGrid API key
// developer document url = https://www.twilio.com/docs/sendgrid/for-developers/sending-email/getting-started-email-activity-api
$sendgrid_apikey = 'YOUR_SENDGRID_API_KEY';  // Replace with your actual SendGrid API key

// Filter by undelivered emails
$sendgrid_url = 'https://api.sendgrid.com/v3/messages?limit=10&query=status="not_delivered"';

//Filter by recipient email

$sendgrid_url = 'https://api.sendgrid.com/v3/messages?limit=10&query=to_email="example@example.com"';

//Filter by a recipient email that was undelivered

$sendgrid_url = 'https://api.sendgrid.com/v3/messages?limit=10&query=status="not_delivered" AND to_email="example@example.com"';

//Filter by date range
$sendgrid_url = 'https://api.sendgrid.com/v3/messages?limit=10&query=status="not_delivered" AND last_event_time BETWEEN TIMESTAMP "2018-10-01 00:00:00" AND TIMESTAMP "2019-03-30 12:00:00"';

//Filter by a recipient and a date range
$sendgrid_url = 'https://api.sendgrid.com/v3/messages?limit=10&query=last_event_time BETWEEN TIMESTAMP "2018-10-01 00:00:00" AND TIMESTAMP "2019-03-30 12:00:00"AND to_email="example.com"';


// $sendgrid_url = 'https://api.sendgrid.com/v3/suppression/bounces'; 
// Blocks: https://api.sendgrid.com/v3/suppression/blocks
// Spam Reports: https://api.sendgrid.com/v3/suppression/spam_reports
// Unsubscribes: https://api.sendgrid.com/v3/suppression/unsubscribes


// Initialize cURL session
$ch = curl_init($sendgrid_url);

// Set cURL options
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Authorization: Bearer ' . $sendgrid_apikey,
    'Content-Type: application/json'
));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  // Return the response as string
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification (useful for local testing, not recommended for production)

// Execute cURL request
$response = curl_exec($ch);

// Check for cURL errors
if(curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
    exit;
}

// Close cURL session
curl_close($ch);

// Decode JSON response
$response_data = json_decode($response, true);

// Check if any data was returned
if (!empty($response_data)) {
    echo "Undelivered emails (bounces):\n";
    // Loop through and display undelivered emails
    foreach ($response_data as $bounce) {
        echo "Email: " . $bounce['email'] . "\n";
        echo "Reason: " . $bounce['reason'] . "\n";
        echo "Created: " . $bounce['created'] . "\n\n";
    }
} else {
    echo "No undelivered emails found.\n";
}


?>
