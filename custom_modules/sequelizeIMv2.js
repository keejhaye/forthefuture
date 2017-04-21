const Sequelize = require('sequelize')
var sequelize = new Sequelize('db_imv2', 'dev', 'XTDCVGK2HDL2', {
  host: '184.173.80.165',
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
  RptUserSystemLogs : sequelize.import('../models/rpt_user_system_logs.js'),
  TblServices : sequelize.import('../models/tbl_services.js'),
  TblConversations : sequelize.import('../models/tbl_conversations.js'),
  TblMessages : sequelize.import('../models/tbl_messages.js'),
  TblUserService :  sequelize.import('../models/tbl_user_service.js'),
  TblConversationDuration : sequelize.import('../models/tbl_conversation_duration.js'),
  TblSettings : sequelize.import('../models/tbl_settings.js'),
  TblSettingsLogs : sequelize.import('../models/tbl_settings_logs.js'),
  TblCannedMessages : sequelize.import('../models/tbl_canned_messages.js'),
  TblOutboundMessageAttachments : sequelize.import('../models/tbl_outbound_message_attachments.js'),
  TblUsers : sequelize.import('../models/tbl_users.js'),
  TblRoles : sequelize.import('../models/tbl_roles.js'),
  UtlReportLogs : sequelize.import('../models/utl_report_logs.js'),
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

models.TblOutboundMessageAttachments
  .belongsTo(models.TblMessages,
    {foreignKey: 'message_id'})

models.TblUsers
  .belongsTo(models.TblRoles,
    {foreignKey: 'role_id', as:'user'})

models.TblRoles
  .hasMany(models.TblUsers,
    {foreignKey: 'role_id', as:'role'})

module.exports = models