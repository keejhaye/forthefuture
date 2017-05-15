
<?php
/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the controller to call when that URI is requested.
  |
 */

/* Home | Login */
Route::get('/', 'Auth\AuthController@index');
Route::get('auth/login', 'Auth\AuthController@index');
Route::post('auth/login', 'Auth\AuthController@authenticate');
Route::get('auth/forgot_password', 'Auth\AuthController@forgot_password');
Route::get('auth/logout', 'Auth\AuthController@logout');



/* APIs */
Route::group([], function () {
    Route::post('api/inbound', 'Api\Inbound@message');
    Route::get('api/users', 'Api\Users@index');
    Route::post('api/users/add', 'Api\Users@add');
    Route::post('api/users/update', 'Api\Users@update');
    Route::get('api/image/thumb', 'Api\Image@thumb');
    Route::get('api/operators', 'Api\Operators@index');
    Route::post('api/operators/add', 'Api\Operators@add');
    Route::post('api/operators/update', 'Api\Operators@update');
    Route::post('api/operators/delete', 'Api\Operators@delete');

});
        
/*
  |
  | Allows requests to proceed further into the application if the user is logged in;
  | else redirects the user to the login page.
  |
 */
Route::group(['middleware' => 'auth'], function () {

   
    /* Panel */
    Route::get('panel/park', 'Panel\Park@park_user');
    Route::get('panel/history', 'Panel\History@index');
    Route::get('panel/history/fetch_conversations', 'Panel\History@fetch_conversations');
    Route::post('panel/history/search', array('as' => 'search', 'uses' => 'Panel\History@search'));
    Route::get('panel/history/service', array('as' => 'service', 'uses' => 'Panel\History@service'));
    Route::get('panel/history/user', array('as' => 'user', 'uses' => 'Panel\History@user'));
    
    Route::get('panel/online', 'Panel\OnlineUsers@index');
    Route::get('panel/online/fetch', 'Panel\OnlineUsers@fetch');
    Route::get('panel/online/services', 'Panel\OnlineUsers@get_services');
    Route::get('panel/online/kick/{id?}', 'Panel\OnlineUsers@kick');

    Route::get('panel/chat', 'Panel\Chat@index');
    Route::get('panel/chat/status', 'Panel\Chat@status');
    Route::get('panel/chat/status2', 'Panel\Chat@status2');
    Route::get('panel/chat/admin', 'Panel\Chat@admin');
    Route::post('panel/chat/reply', 'Panel\Chat@reply');
    Route::post('panel/chat/flag_message', 'Panel\Chat@flag_message');
    Route::post('panel/chat/blacklist', 'Panel\Chat@blacklist');
     Route::post('panel/chat/discard', 'Panel\Chat@discard');
    Route::post('panel/chat/add_note', 'Panel\Chat@add_note');
    Route::post('panel/chat/delete_note', 'Panel\Chat@delete_note');

    Route::get('panel/users', 'Panel\Users@index');
    Route::get('panel/users/get_users/{id?}', 'Panel\Users@users');
    Route::post('panel/users/add', 'Panel\Users@add');
    Route::post('panel/users/update/{id}', 'Panel\Users@update');

    Route::get('panel/context', 'Panel\Context@index');
    Route::get('panel/context/get_services/{id?}', 'Panel\Context@services');
    Route::post('panel/context/search', 'Panel\Context@search');
    Route::post('panel/context/add', 'Panel\Context@add');
    Route::post('panel/context/update/{id}', 'Panel\Context@update');

    Route::get('panel/personas', 'Panel\Personas@index');
    Route::get('panel/personas/get_personas/{id?}', 'Panel\Personas@personas');
    Route::post('panel/personas/add', 'Panel\Personas@add');
    Route::post('panel/personas/update/{id}', 'Panel\Personas@update');
    // -- Kris' changes 5/9/2017
    Route::get('panel/personas/get_paginated_personas/{offset}/{limit}/{pname?}', 'Panel\Personas@getPersonasByPage');
    Route::get('panel/personas/count_all_personas', 'Panel\Personas@countAllPersonas');
    Route::get('panel/personas/get_search_users/{searchKey}/{searchCol}/{searchParams?}', 'Panel\Personas@getSearchPersonas');
    // -- Kris' changes 5/9/2017

    Route::get('panel/subscribers', 'Panel\Subscribers@index');
    Route::get('panel/subscribers/get_subscribers/{id?}', 'Panel\Subscribers@subscribers');
    Route::post('panel/subscribers/add', 'Panel\Subscribers@add');
    Route::post('panel/subscribers/update/{id}', 'Panel\Subscribers@update');
    Route::get('panel/subscribers/get_conversation_history/{id}', 'Panel\Subscribers@get_conversation_history');
    Route::get('panel/subscribers/get_messages/{id}', 'Panel\Subscribers@get_messages');
    Route::get('panel/subscribers/add_to_blacklist/{id}', 'Panel\Subscribers@add_to_blacklist');
    // -- Kris' changes 5/9/2017
    Route::get('panel/subscribers/get_paganated_subscribers/{offset}/{limit}', 'Panel\Subscribers@getSubscribersByPage');
    Route::get('panel/subscribers/count_all_subscribers', 'Panel\Subscribers@countAllSubscribers');
    // -- Kris' changes 5/9/2017

    Route::get('panel/user_activity', 'Panel\UserActivity@index');
    Route::get('panel/user_activity/get_logs/{id?}', 'Panel\UserActivity@logs');

    Route::get('panel/blacklist', 'Panel\Blacklist@index');
    Route::get('panel/blacklist/subscribers/{id?}', 'Panel\Blacklist@subscribers');
    Route::get('panel/blacklist/remove/{id}', 'Panel\Blacklist@destroy');

    Route::get('panel/profile', 'Panel\Profile@index');
    Route::get('panel/profile/user', 'Panel\Profile@user');
    Route::post('panel/profile/update', 'Panel\Profile@update');
    Route::get('panel/profile/get_services', 'Panel\Profile@get_services');
    
    Route::get('panel/flagged_messages', 'Panel\FlaggedMessages@index');
    Route::get('panel/flagged_messages/get_flagged/{id?}', 'Panel\FlaggedMessages@flagged');
    Route::get('panel/flagged_messages/fetch', 'Panel\FlaggedMessages@fetch_flagged');
    Route::post('panel/flagged_messages/update/{id}/{action}', 'Panel\FlaggedMessages@update');
    Route::get('panel/flagged_messages/get_messages_history/{conversationId}/{messageId}', 'Panel\FlaggedMessages@get_messages_history');
    
    Route::get('panel/reports', 'Panel\Reports@index');
    Route::get('panel/reports/status', 'Panel\Reports@status');
    Route::get('panel/reports/filter/{entity?}', 'Panel\Reports@filter');

    Route::get('panel/batch', 'Panel\Batch@index');
    Route::get('panel/user_context/', 'Panel\UserContext@index');
    Route::get('panel/user_context/search/{filter?}', 'Panel\UserContext@search');
    Route::get('panel/user_context/search_assigned/{filter?}', 'Panel\UserContext@search_assigned');
    Route::post('panel/user_context/save_service_users', 'Panel\UserContext@save_service_users');
    Route::post('panel/user_context/delete', 'Panel\UserContext@delete');

    Route::get('panel/user_context/service_users/{id}', 'Panel\UserContext@service_users');
    Route::get('panel/user_context/unassigned_users/{id}', 'Panel\UserContext@unassigned_users');
    Route::post('panel/user_context/delete_assigned_users/{id}', 'Panel\UserContext@delete_assigned_users');
    Route::post('panel/user_context/assign_users/{id}', 'Panel\UserContext@assign_users');

 Route::get('panel/user_context/user_services/{id}', 'Panel\UserContext@user_services');
    Route::get('panel/user_context/unassigned_services/{id}', 'Panel\UserContext@unassigned_services');
    Route::post('panel/user_context/delete_assigned_services/{id}', 'Panel\UserContext@delete_assigned_services');
    Route::post('panel/user_context/assign_services/{id}', 'Panel\UserContext@assign_services');

    /* Canned Messages */
    Route::get('panel/user_context/get_canned_messages/{filter?}', 'Panel\UserContext@get_canned_messages');
    Route::post('panel/user_context/delete_canned_message', 'Panel\UserContext@delete_canned_message');
    Route::post('panel/user_context/save_canned_message/{id}', 'Panel\UserContext@save_canned_message');

    /* Auto Reminders */
    Route::get('panel/user_context/get_libraries/{filter?}', 'Panel\UserContext@get_libraries');
    Route::get('panel/user_context/get_auto_reminders/{id}', 'Panel\UserContext@get_auto_reminders');
    Route::post('panel/user_context/save_auto_reminder/{id}', 'Panel\UserContext@save_auto_reminder');
    Route::post('panel/user_context/delete_auto_reminder', 'Panel\UserContext@delete_auto_reminder');

    /* Auto Responder */
    Route::get('panel/user_context/get_personas/{filter?}', 'Panel\UserContext@get_personas');
    Route::get('panel/user_context/get_rules/{id}', 'Panel\UserContext@get_rules');
    Route::get('panel/user_context/get_rule_by_id/{ruleId}', 'Panel\UserContext@get_rule_by_id');
    Route::post('panel/user_context/save_rule/{id}', 'Panel\UserContext@save_rule');
    Route::post('panel/user_context/edit_rule/{id}/{ruleId}', 'Panel\UserContext@edit_rule');
    Route::post('panel/user_context/delete_rule', 'Panel\UserContext@delete_rule');

    /* Route */
    Route::get('panel/user_context/get_service/{filter?}', 'Panel\UserContext@get_service');
    Route::post('panel/user_context/save_route/{id}', 'Panel\UserContext@save_route');
    
    /* Limit Message/Persona */
    Route::post('panel/user_context/save_message_limit/{id}', 'Panel\UserContext@save_message_limit');
    Route::post('panel/user_context/save_persona_limit/{id}', 'Panel\UserContext@save_persona_limit');
    
    /* Subscriber Billing */
    Route::post('panel/user_context/save_subscriber_billing/{id}', 'Panel\UserContext@save_subscriber_billing');
    
    /* Auto Discard */
    Route::get('panel/user_context/get_auto_discard/{filter?}', 'Panel\UserContext@get_auto_discard');
    Route::post('panel/user_context/delete_auto_discard', 'Panel\UserContext@delete_auto_discard');
    Route::post('panel/user_context/save_auto_discard/{id}', 'Panel\UserContext@save_auto_discard');

    /* Settings */
       Route::get('panel/settings', 'Panel\Settings@index');
       Route::get('panel/settings/maximum_conversation', 'Panel\Settings@maximum_conversation');
       Route::get('panel/settings/unmap_time_from_operator', 'Panel\Settings@unmap_time_from_operator');
       Route::post('panel/settings/set_max_conversation', 'Panel\Settings@set_max_conversation');
    Route::post('panel/settings/set_unmap_time', 'Panel\Settings@set_unmap_time');
  
    
    /* Bulletin Page */
    Route::get('panel/bulletin', 'Panel\Bulletin@index');
    Route::get('panel/bulletin/get_bulletin/{id?}', 'Panel\Bulletin@get_bulletin');
    Route::post('panel/bulletin/search_bulletin', 'Panel\Bulletin@search_bulletin');
    Route::get('panel/bulletin/get_users', 'Panel\Bulletin@get_users');
    Route::get('panel/bulletin/get_bulletin_info/{id?}', 'Panel\Bulletin@get_bulletin_info');
    Route::get('panel/bulletin/get_bulletin_info_users/{id?}', 'Panel\Bulletin@get_bulletin_info_users');
    Route::get('panel/bulletin/get_bulletin_info_services/{id?}', 'Panel\Bulletin@get_bulletin_info_services');
    Route::get('panel/bulletin/get_bulletin_info_images/{id?}', 'Panel\Bulletin@get_bulletin_info_images');
    Route::get('panel/bulletin/get_services', 'Panel\Bulletin@get_services');
    Route::post('panel/bulletin/add', 'Panel\Bulletin@add');
    Route::post('panel/bulletin/update/{id}', 'Panel\Bulletin@update');
    Route::post('panel/bulletin/upload', 'Panel\Bulletin@upload');

    Route::post('panel/bulletin/get_bulletin_board', 'Panel\Bulletin@get_bulletin_board');
    Route::post('panel/bulletin/update_bulletin_seen', 'Panel\Bulletin@update_bulletin_seen');
/* Autocomplete(Conversation history)*/
    Route::get('panel/autocomplete', 'Panel\Autocomplete@index');   
    Route::get('panel/autocomplete/search_service{filter?}', 'Panel\Autocomplete@search_service');
    Route::get('panel/autocomplete/search_user{filter?}', 'Panel\Autocomplete@search_user');
/*Traces (Conversation History)*/
    Route::get('panel/history/get_traces/{id}', 'Panel\History@get_traces');
    /*Logs(Conversation History)*/
    Route::get('panel/history/get_logs/{id}', 'Panel\History@get_logs');
    Route::get('panel/history/get_comments/{id}', 'Panel\History@get_conversation_comments');
    /* Mock\Dev */
    Route::get('mock/dev/permissions', 'Mock\Dev\Permissions@overview');
    Route::post('mock/dev/permissions/process_overview', 'Mock\Dev\Permissions@process_overview');
});

/* Routine */
Route::group([], function () {
    Route::get('panel/routine/autoresponse_queue/execute', 'Panel\Routine\AutoresponseQueue@execute');
    Route::get('panel/routine/aggregator_responses/execute/{service_id}', 'Panel\Routine\AggregatorResponses@execute');
    Route::get('panel/routine/aggregator_responses/execute_by_group/{group_name}', 'Panel\Routine\AggregatorResponses@execute_by_group');
    Route::get('panel/routine/aggregator_responses/execute_by_group_with_ssl/{group_name}', 'Panel\Routine\AggregatorResponses@execute_by_group_with_ssl');
    Route::get('panel/routine/statistics/current', 'Panel\Routine\Statistics@current');
    Route::get('panel/routine/statistics/reset', 'Panel\Routine\Statistics@reset');
    Route::get('panel/routine/statistics/reset_user_stats', 'Panel\Routine\Statistics@reset_user_stats');
    Route::get('panel/routine/request_limit/reset', 'Panel\Routine\RequestLimit@reset');
    Route::get('panel/routine/subscriber_limit/reset_messages_counter', 'Panel\Routine\SubscriberLimit@reset_messages_counter');
    Route::get('panel/routine/subscriber_limit/reset_personas_counter', 'Panel\Routine\SubscriberLimit@reset_personas_counter');
    Route::get('panel/routine/subscriber_limit/reset_messages_counter_monthly', 'Panel\Routine\SubscriberLimit@reset_messages_counter_monthly');
    Route::get('panel/routine/subscriber_limit/reset_personas_counter_monthly', 'Panel\Routine\SubscriberLimit@reset_personas_counter_monthly');
    Route::get('panel/routine/report/extract_operator_reports', 'Panel\Routine\Report@extract_operator_reports');
    Route::get('panel/routine/users/kick_idle_users', 'Panel\Routine\Users@kick_inactive_users');
     Route::get('panel/routine/users/update_last_ping', 'Panel\Routine\Users@update_last_ping');
});

/* Test */
Route::get('sampletest/log_user_activity_modified', 'Test\SampleTestController@log_user_activity_modified');
Route::get('sampletest/add_user_log', 'Test\SampleTestController@add_user_log');
Route::get('sampletest/get_role_name', 'Test\SampleTestController@get_role_name');
Route::get('sampletest/get_set_cookie', 'Test\SampleTestController@get_set_cookie');
Route::get('test/message', 'Test\Message@index');
Route::get('test/message/duration', 'Test\Message@duration');
Route::get('test/message/getmessageids', 'Test\Message@getmessageids');
Route::get('test/batch', 'Test\Batch@index');
Route::get('test/switch', 'Test\SwitchDB@index');
Route::get('test/users/session_test', 'Test\Users@session_test');
Route::get('test/users/redis_stats', 'Test\Users@redis_stats');
Route::get('test/users/piczelle', 'Test\Users@piczelle');
Route::get('test/users/url', 'Test\Users@url');
Route::get('test/migrate', 'Test\Migrate@index');
Route::get('test/migrate/notes', 'Test\Migrate@notes');
Route::get('test/message/send_curl', 'Test\Message@send_curl');

Route::get('mock/dev/redisdata', 'Mock\Dev\RedisData@index');
Route::get('mock/dev/redisdata/reset_statistics', 'Mock\Dev\RedisData@resetStatistics');
Route::get('mock/dev/redisdata/update_statistics', 'Mock\Dev\RedisData@updateStatistics');
Route::get('mock/dev/redisdata/delete_services_statistics', 'Mock\Dev\RedisData@deleteServicesStatistics');
Route::get('mock/dev/redisdata/current_services_statistics', 'Mock\Dev\RedisData@currentServicesStatistics');
Route::get('mock/dev/redisdata/setrolenames', 'Mock\Dev\RedisData@setRoleNames');
Route::get('mock/dev/redisdata/getrolenames', 'Mock\Dev\RedisData@getRoleNames');
Route::get('mock/dev/redisdata/setpermissions/{rolename?}', 'Mock\Dev\RedisData@setPermissions');
Route::get('mock/dev/redisdata/getpermissions/{rolename?}', 'Mock\Dev\RedisData@getPermissions');
Route::get('mock/dev/redisdata/setonlineuserservices', 'Mock\Dev\RedisData@set_online_user_services');
Route::get('mock/dev/outbound', 'Mock\Dev\Outbound@index');
Route::post('mock/dev/outbound/receive', 'Mock\Dev\Outbound@receive');
Route::get('mock/dev/monitor', 'Mock\Dev\Monitor@index');
Route::get('mock/dev/monitor/status', 'Mock\Dev\Monitor@status');
Route::get('mock/dev/monitor/get_online', 'Mock\Dev\Monitor@get_online');
Route::get('mock/dev/status/chat', 'Mock\Dev\Status@index');
Route::get('mock/dev/status/all', 'Mock\Dev\Status@all');
Route::get('mock/dev/users', 'Mock\Dev\Users@index');
Route::get('mock/dev/users/disconnect', 'Mock\Dev\Users@disconnect');
Route::get('mock/dev/users/connect', 'Mock\Dev\Users@enableConnection');
Route::get('mock/dev/users/reloadpage', 'Mock\Dev\Users@reloadpage');
Route::get('mock/dev/users/removeRepeatedService', 'Mock\Dev\Users@remove_repeated_service');
Route::get('mock/dev/conversations/changeStatus/{status}/{id}', 'Mock\Dev\Conversations@change_object_status');

Route::get('test/generate/personas', 'Test\Generate@personas');
Route::get('test/generate/subscribers', 'Test\Generate@subscribers');
Route::get('test/generate/conversations', 'Test\Generate@conversations');
Route::get('test/generate/messages', 'Test\Generate@messages');
//Route::get('test/users/add', 'Test\Users@add');


//batch assign work, refs #3211
Route::get('panel/batchAssign', 'Panel\AssignService@index');
Route::get('panel/getAllOperatorById/{servId}', 'Panel\AssignService@getAllOperatorById');
Route::get('panel/getAllServById/{operId}', 'Panel\AssignService@getAllServById');
Route::post('panel/saveUserService', 'Panel\AssignService@store');
Route::post('panel/deleteUserService/{ifServiceOrOperator}', 'Panel\AssignService@deleteUserService');
//end batch assign work, refs #3211

