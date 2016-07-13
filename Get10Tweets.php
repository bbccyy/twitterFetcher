<?php
require_once( 'TwitterAPIExchange.php');
$settings = array(
    'oauth_access_token' => "751501782006763520-NMcLdqNBfwurxmmidPCnvp4vVZytsiy",
    'oauth_access_token_secret' => "tbG9UNcQes4V0AthMrBYvxR57vCEfxBjKwhku8DKs9zHx",
    'consumer_key' => "n7I8bDFRfG3k6kqyVqtIDYwpR",
    'consumer_secret' => "wGnpQyXL3VlRzeGoOUWZzNAzEfBPdqrxI80yoclBC4yecaP3bB"
);
         
if(is_ajax()){
    if(isset($_POST['action']) && !empty($_POST['action'])){
        $action = $_POST['action'];
        switch($action) {
            case "update" : update_function($settings); 
                            break;
        }
    }
}


//Function to check if the request is an AJAX 
function is_ajax(){
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}


//return data
function update_function($settings){
    $count=10;
    $url = "https://api.twitter.com/1.1/statuses/user_timeline.json";
    $requestMethod = "GET";
    $screen_name = "salesforce";
    $getfield = '?screen_name='.$screen_name.'&count='.$count;

    $twitter = new TwitterAPIExchange($settings);
    $data = $twitter->setGetfield($getfield)
                 ->buildOauth($url, $requestMethod)
                 ->performRequest();
    echo parseData($data);
}

function parseData($raw_data){
    $data = json_decode($raw_data, $assoc=true);
    $ct = 0;
    $tweets = array();
    foreach($data as $record){
        $tweet = array(
                       'name'=>$record['user']['name'],
                       'screen_name'=>$record['user']['screen_name'],
                       'profile_image'=>$record['user']['profile_image_url'],
                       'content'=>$record['text'],
                       'retweet_ct'=>$record['retweet_count']
        );
        $tweets[$ct++] = $tweet;
    }
    return json_encode($tweets);
}

?>
