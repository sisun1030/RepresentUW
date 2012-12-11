<?php

require 'twitter/tmhOAuth.php';
require 'twitter/tmhUtilities.php';

function tweet($message)
{
    $tmhOAuth = new tmhOAuth(array(
        'consumer_key' => 'cWtVx1mwR1fxLQ7ivPC2Q',
        'consumer_secret' => 'wslr04k2VhLT0A6iguP2Jryl5XVgl7djWoqrkULw',
        'user_token' => '976353642-nhRFnplCwuWZ3JqZwiwwgefdE6mlMUS2bf03gHom',
        'user_secret' => 'hKZMSUPOyaC0erGG7piINg2hlEIjMH7t6xzOv6swHVc'
    ));
    
    $code = $tmhOAuth->request('POST', $tmhOAuth->url('1/statuses/update'), array(
        'status' => $message
    ));
    
    if ($code == 200) {
        tmhUtilities::pr(json_decode($tmhOAuth->response['response']));
        return "Tweet Posted";
    } else {
        tmhUtilities::pr(json_decode($tmhOAuth->response['response']));
        return "Failed";
    }
    
}

?>