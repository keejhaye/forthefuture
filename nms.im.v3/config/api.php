<?php
use Illuminate\Support\Facades\Config;
return[
    'request_limit' => 1500, // 1500 production mode
    'request_timeout' => 60, // in seconds, when do we reset the counter?
    'day_to_seconds' => 86400, // in seconds, when do we reset the counter?
    'month_to_seconds' => 2678400, // in seconds, when do we reset the counter?
    'message_parameters' => array(
        "to",
        "from",
        "message",
        "username",
        "password",
        "service_code"
    ),
    'allow_requests' => 'false',
    'send_request_to_v2' => false,
    // 'im2_url' => 'http://localhost/nms.im.v2/api/inbound/message'
    'im2_url' => 'http://im2.nmsloop.com/api/inbound/message',
    'im2_users_url' => 'http://im2.nmsloop.com/api/users/',
    'platform_code_prody' => 'loopim3',
    'production_reports_url' => 'http://staging.prody.nmsapps.com:5303/api/upload_report',

];
