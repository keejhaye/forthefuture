var Config = {
    max_operator_conversation : 0,
    max_message_per_conversation : 20,
    max_parking_loop : 5,
    max_logout_loop : 5,
    wait_time_to_unmap_from_operator : 0,
    wait_time_to_unmap_on_park : 1000,
    wait_time_to_handle_subscriber_conversation : 30000,
    wait_time_to_handle_subscriber_conversation_with_attachment : 3000,
    db_timezone : 'GMT',
    default_timezone : 'Asia/Magadan',
    default_timezone_name : '(GMT +11:00) Magadan, Solomon Islands, New Caledonia',
    switch_database_date : '2016-11-01 00:00:00',
    stats_update_interval : 2000,
    stats_reset_tz : 'Asia/Magadan',
    stats_reset_time : '23:59:59',

    //disconnection messages
    prompt: {
        double_park: "<strong>Unable to park!</strong><p>You are already parked in another tab or browser.</p>",
        double_chat: "<strong>Chatting disabled!</strong><p>Kindly check your browsers or tabs that are open using the chat page.</p>",
        disable_park: "<strong>You have been disconnected on this page!</strong><p>Your current status is on chat. Reload page to park and disconnect from chat page</p>",
        disable_chat: "<strong>You have been disconnected from the server!</strong><p>Chatting and parking at the same time is not allowed.</p>",
        user_logout: "<strong>You have been disconnected from the server!</strong><p>User logged out of the system.</p>",
    },

    //dev configs
    enable_connection : true,
}
module.exports = Config