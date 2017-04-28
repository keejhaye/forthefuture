const Sequelize = require('sequelize')
// var sequelize = new Sequelize('db_imv3', 'loop', 'GytkpH4@tFrBNk6t', {
//   host: 'MES-SERVER',
//   port : 50004,
//   pool: {
//     max: 10,
//     min: 0,
//     idle: 10000
//   },
//   benchmark : true,
//   logging: false
// })
var sequelize = new Sequelize('db_imv3', 'root', '', {
  host: 'localhost',
  port : 3306,
  pool: {
    max: 10,
    min: 0,
    idle: 10000
  },
  benchmark : true,
  logging: false
})

models = {
  RptConversationDuration : sequelize.import('../models/rpt_conversation_duration.js'),
  RptMessageInterval : sequelize.import('../models/rpt_message_interval.js'),
  RptMessages : sequelize.import('../models/rpt_messages.js'),
  RptUserLogs : sequelize.import('../models/rpt_user_logs.js'),
  RptOperatorResponseLogs : sequelize.import('../models/rpt_operator_response_logs.js'),
  RptSubscriberBilling : sequelize.import('../models/rpt_subscriber_billing.js'),
  RptUserConversationLogs : sequelize.import('../models/rpt_user_conversation_logs.js'),
  RptUserLogs : sequelize.import('../models/rpt_user_logs.js'),
  RptUserSystemLogs : sequelize.import('../models/rpt_user_system_logs.js'),
  TblActions : sequelize.import('../models/tbl_actions.js'),
  TblActionsQueue : sequelize.import('../models/tbl_actions_queue.js'),
  TblAggregatorResponses : sequelize.import('../models/tbl_aggregator_responses.js'),
  TblAutoDiscard : sequelize.import('../models/tbl_auto_discard.js'),
  TblAutoRemindedMessages : sequelize.import('../models/tbl_autoreminded_messages.js'),
  TblAutoReminders : sequelize.import('../models/tbl_autoreminders.js'),
  TblAutoRemindersQueue : sequelize.import('../models/tbl_autoreminders_queue.js'),
  TblAutoResponses : sequelize.import('../models/tbl_autoresponses.js'),
  TblBlacklist : sequelize.import('../models/tbl_blacklist.js'),
  TblBulletinService : sequelize.import('../models/tbl_bulletin_service.js'),
  TblBulletinUser : sequelize.import('../models/tbl_bulletin_user.js'),
  TblBulletins : sequelize.import('../models/tbl_bulletins.js'),
  TblCannedMessages : sequelize.import('../models/tbl_canned_messages.js'),
  TblConditions : sequelize.import('../models/tbl_conditions.js'),
  TblConversationCommentUsers : sequelize.import('../models/tbl_conversation_comment_users.js'),
  TblConversationComments : sequelize.import('../models/tbl_conversation_comments.js'),
  TblConversationDuration : sequelize.import('../models/tbl_conversation_duration.js'),
  TblConversationLogs : sequelize.import('../models/tbl_conversation_logs.js'),
  TblConversationMessagesLogs : sequelize.import('../models/tbl_conversation_messages_logs.js'),
  TblConversationNotes : sequelize.import('../models/tbl_conversation_notes.js'),
  TblConversations : sequelize.import('../models/tbl_conversations.js'),
  TblDelayedMessages : sequelize.import('../models/tbl_delayed_messages.js'),
  TblDiscardedConversations : sequelize.import('../models/tbl_discarded_conversations.js'),
  tblFlaggedMessageDeductions : sequelize.import('../models/tbl_flagged_message_deductions.js'),
  tblFlaggedMessages : sequelize.import('../models/tbl_flagged_messages.js'),
  tblFlaggedMessagesStatistics : sequelize.import('../models/tbl_flagged_messages_statistics.js'),
  TblGroups : sequelize.import('../models/tbl_groups.js'),
  TblIgnoredConversationLogs : sequelize.import('../models/tbl_ignored_conversation_logs.js'),
  TblInboundMessageAttachment : sequelize.import('../models/tbl_inbound_message_attachment.js'),
  TblLibraries : sequelize.import('../models/tbl_libraries.js'),
  TblLibrariesServices : sequelize.import('../models/tbl_libraries_services.js'),
  TblLibraryMessages : sequelize.import('../models/tbl_library_messages.js'),
  TblLoggedInUsers : sequelize.import('../models/tbl_logged_in_users.js'),
  TblMessageInterval : sequelize.import('../models/tbl_message_interval.js'),
  TblMessages : sequelize.import('../models/tbl_messages.js'),
  TblMessagesQueue : sequelize.import('../models/tbl_messages_queue.js'),
  TblOperatorResponseLogs : sequelize.import('../models/tbl_operator_response_logs.js'),
  TblOutboundMessageAttachments : sequelize.import('../models/tbl_outbound_message_attachments.js'),
  TblOutboundQueue : sequelize.import('../models/tbl_outbound_queue.js'),
  TblPendingConversations : sequelize.import('../models/tbl_pending_conversations.js'),
  TblPendingSubsciberBilling : sequelize.import('../models/tbl_pending_subscriber_billing.js'),
  TblPersonaFiles : sequelize.import('../models/tbl_persona_files.js'),
  TblPersonas : sequelize.import('../models/tbl_personas.js'),
  TblRoles : sequelize.import('../models/tbl_roles.js'),
  TblRules : sequelize.import('../models/tbl_rules.js'),
  TblServiceEmailNotification : sequelize.import('../models/tbl_service_email_notification.js'),
  TblServiceGroup : sequelize.import('../models/tbl_service_group.js'),
  TblServiceSubscriberLimit : sequelize.import('../models/tbl_service_subscriber_limit.js'),
  TblServices : sequelize.import('../models/tbl_services.js'),
  TblSettings : sequelize.import('../models/tbl_settings.js'),
  TblSettingsLogs : sequelize.import('../models/tbl_settings_logs.js'),
  TblStatistics : sequelize.import('../models/tbl_statistics.js'),
  TblStatisticsRealtime : sequelize.import('../models/tbl_statistics_realtime.js'),
  TblSubscriberBilling : sequelize.import('../models/tbl_subscriber_billing.js'),
  TblSubscriberMessageLimit : sequelize.import('../models/tbl_subscriber_message_limit.js'),
  TblSubscriberPersonaLimit : sequelize.import('../models/tbl_subscriber_persona_limit.js'),
  TblSubscribers : sequelize.import('../models/tbl_subscribers.js'),
  TblTimedOutConversations :  sequelize.import('../models/tbl_timed_out_conversations.js'),
  TblUserActivities :  sequelize.import('../models/tbl_user_activities.js'),
  TblUserConversationLogs :  sequelize.import('../models/tbl_user_conversation_logs.js'),
  TblUserLogs : sequelize.import('../models/tbl_user_logs.js'),
  TblUserService :  sequelize.import('../models/tbl_user_service.js'),
  TblUserSystemLogs :  sequelize.import('../models/tbl_user_system_logs.js'),
  TblUsers : sequelize.import('../models/tbl_users.js'),
  TblWhitelist : sequelize.import('../models/tbl_whitelist.js'),
  UtlInboundRequestLimiter : sequelize.import('../models/utl_inbound_request_limiter.js'),
  UtlInboundRequests : sequelize.import('../models/utl_inbound_requests.js'),
  UtlOperatorCounter : sequelize.import('../models/utl_operator_counter.js'),
  UtlOutboundReceiver : sequelize.import('../models/utl_outbound_receiver.js'),
  UtlReportLock : sequelize.import('../models/utl_report_lock.js'),
  UtlReportLogs : sequelize.import('../models/utl_report_logs.js'),
  UtlReportsTable : sequelize.import('../models/utl_reports_table.js'),
  UtlRequestLimiter : sequelize.import('../models/utl_request_limiter.js'),
  UtlRoutine : sequelize.import('../models/utl_routine.js'),
  UtlStatistics : sequelize.import('../models/utl_statistics.js'),
}

models.TblServices
  .hasMany(models.TblCannedMessages,
    {foreignKey: 'service_id',
      as: 'CannedMessages'})

models.TblCannedMessages
  .belongsTo(models.TblServices,
    {foreignKey: 'service_id'})

models.TblMessages
  .hasMany(models.TblOutboundMessageAttachments,
    {foreignKey: 'message_id',
      as: 'attachments'})

models.TblMessages
  .belongsTo(models.TblUsers,
    {foreignKey: 'user_id',
      as: 'tbl_users'})

models.TblOutboundMessageAttachments
  .belongsTo(models.TblMessages,
    {foreignKey: 'message_id'})

models.TblUsers
  .belongsTo(models.TblRoles,
    {foreignKey: 'role_id',
      as: 'role'})

models.TblRoles
  .hasMany(models.TblUsers,
    {foreignKey: 'id',
      as: 'role'})


module.exports = models