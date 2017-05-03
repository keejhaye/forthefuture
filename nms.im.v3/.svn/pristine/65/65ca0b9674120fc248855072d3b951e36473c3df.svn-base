/* jshint indent: 1 */

module.exports = function(sequelize, DataTypes) {
	moment = require('moment')
	gmdate = require('phpdate-js').gmdate
	Models = require('../custom_modules/sequelize.js')

	var TblConversations = sequelize.define('tbl_conversations', {
		id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			primaryKey: true,
			autoIncrement: true
		},
		persona_id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			references: {
				model: 'tbl_personas',
				key: 'id'
			}
		},
		subscriber_id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			references: {
				model: 'tbl_subscribers',
				key: 'id'
			}
		},
		service_id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			references: {
				model: 'tbl_services',
				key: 'id'
			}
		},
		status: {
			type: DataTypes.STRING,
			allowNull: true,
			defaultValue: 'pending'
		},
		date_created: {
			type: DataTypes.DATE,
			allowNull: true
		},
		user_id: {
			type: DataTypes.BIGINT,
			allowNull: true
		},
		assigned_latest: {
			type: DataTypes.DATE,
			allowNull: true
		},
	}, {
		tableName: 'tbl_conversations',
		timestamps: false,
		classMethods : {
			getMetadata : function(conversation_id, callback){
				sequelize.query('SELECT ' +
	                                'p.name AS persona_name,p.`profile` AS persona_profile,p.`additional_info` AS persona_add_info,' +
	                                's.`name` AS subscriber_name,s.`profile` AS subscriber_profile,s.`additional_info`AS subscriber_add_info ' +
	                              'FROM tbl_conversations c ' +
	                                'LEFT JOIN tbl_personas p ON c.`persona_id` = p.`id` ' +
	                                'LEFT JOIN tbl_subscribers s ON s.id = c.`subscriber_id`' +
	                              'WHERE c.id = ?',
				  { replacements: [conversation_id], type: sequelize.QueryTypes.SELECT }
				)
				.then(function(rows) {
	              callback(rows)
				})
			},
			getMetadataByConversationIds : function(conv_ids, query_callback) {
	          sequelize.query('SELECT ' +
	                                'c.id as conversation_id,' +
	                                'p.name AS persona_name,p.`profile` AS persona_profile,p.`additional_info` AS persona_add_info,' +
	                                's.`name` AS subscriber_name,s.`profile` AS subscriber_profile,s.`additional_info`AS subscriber_add_info ' +
	                              'FROM tbl_conversations c ' +
	                                'LEFT JOIN tbl_personas p ON c.`persona_id` = p.`id` ' +
	                                'LEFT JOIN tbl_subscribers s ON s.id = c.`subscriber_id`' +
	                              'WHERE c.id IN(?)', 
	                              { replacements: [conv_ids], type: sequelize.QueryTypes.SELECT }
                              )
	          				  .then(function(rows,metadata){
	          				  	query_callback(rows)
	          				  }, 
	          				  function(rejectedPromiseError){
									console.log('tbl_conversations rejectedPromiseError')
									console.log(rejectedPromiseError)
							  })

			},
			syncData : function(conversation_id, user_id, status){
				console.log('update conversation_id [' + conversation_id + '] | status to [' + status + ']')
				this.update({
				    status: status,
				    user_id : user_id,
				    assigned_latest : moment().format('YYYY-MM-DD HH:mm:ss')
				  },
				  {
				    where: { id : conversation_id }
				  })
				  .then(function (result) { 
				  	 console.log('conversation ['+ conversation_id +'] sync update success! Status: ['+ status +']')
				  },
				  function(rejectedPromiseError){
						console.log('tbl_conversations rejectedPromiseError2')
						console.log(rejectedPromiseError)
				  });
			},
			getUnhandledConversations : function(callback){
	          sequelize.query('SELECT ' +
								  'c.id,c.persona_id,c.subscriber_id,c.service_id,c.status,c.user_id,c.assigned_latest, MAX(cd.id) AS `cdid`, ' +
								  'm.id as `message_id`, m.message, m.status as `message_status`,m.bound_time as `date`, m.direction as `type`, m.assigned_time as `assigned_start`, ' +
								  '(SELECT bound_time FROM tbl_messages where conversation_id = c.id LIMIT 1) as first_message_bound_time ' +
								'FROM ' +
								  'tbl_conversations c ' +
								  'LEFT JOIN tbl_messages m ' +
								    'ON c.id = m.conversation_id ' +								  
								  'LEFT JOIN tbl_conversation_duration cd ' +
								    'ON c.id = cd.`conversation_id` ' +
								'WHERE ' +
								  'c.status IN ("assigned","pending") ' +
								  'AND m.id = ' +
								  '(SELECT ' +
								    'MAX(m2.id) ' +
								  'FROM ' +
								    'tbl_messages m2 ' +
								  'WHERE m2.conversation_id = c.id) ' + 
								  'GROUP BY c.id', 
	                              { type: sequelize.QueryTypes.SELECT }
                              )
	          				  .then(function(rows,metadata){
	          				  	callback(rows)
	          				  }, 
	          				  function(rejectedPromiseError){
									console.log('tbl_conversations getUnhandledConversations rejectedPromiseError')
									console.log(rejectedPromiseError)
							  })
			},
			saveLogs : function(logs, conversation_id, date, cdid, Models, callback){
			  if(cdid == undefined){
			  	 Models.TblConversationDuration.findOne({
			  	 	where : {
			  	 		conversation_id : conversation_id
			  	 	},
			  	 	order : "id DESC"
			  	 }).then(function(data){
			  	 	cdid = data.dataValues.id
			  	 	saveLogs()
			  	 })
			  }
			  else{
			  	saveLogs()
			  }
			  function saveLogs(){
		          sequelize.query('INSERT INTO tbl_conversation_logs(conversation_id,logs,date_created,conversation_duration_id) VALUES (?,?,?,?);', 
		                              { 
		                              	type: sequelize.QueryTypes.INSERT,
		                              	replacements : [ conversation_id, JSON.stringify(logs), date, cdid]
		                              }
	                              )
		          				  .then(function(rows,metadata){
		          				  	callback()
		          				  }, 
		          				  function(rejectedPromiseError){
										console.log('tbl_conversations getUnhandledConversations rejectedPromiseError')
										console.log(rejectedPromiseError)
								  })
			  }
			},
			getStatsUpdate : function(callback){
				var query_string = "SELECT s.id," +
										"(SELECT COUNT(id) FROM tbl_conversations WHERE `status` = 'pending' AND service_id = s.id) AS pending," +
										"(SELECT COUNT(id) FROM tbl_conversations WHERE `status` = 'assigned' AND service_id = s.id) AS assigned," +
										"(SELECT COUNT(l.id) FROM tbl_logged_in_users l LEFT JOIN tbl_user_service us ON us.user_id = l.user_id WHERE us.service_id = s.id) AS logged_in, " +
										"(SELECT COUNT(l1.id) FROM tbl_logged_in_users l1 LEFT JOIN tbl_user_service us ON us.user_id = l1.user_id WHERE us.service_id = s.id AND l1.status = 'chat') AS chatting " +
										"FROM tbl_services s " +
										"WHERE s.`status` = 'active' " +
										"ORDER BY s.id";

				sequelize.query( query_string, { replacements: [], type: sequelize.QueryTypes.SELECT } )
					.then(function(rows){
						callback(rows)
					})
			}, 

			getLastMessageType : function(cid, data, logs){
				console.log("getLastMessageType() QUERY")

				if(data != undefined){
					var message = "SELECT direction FROM tbl_messages where conversation_id = ? ORDER BY id DESC LIMIT 1;"
					sequelize.query( message, { replacements: [cid], type: sequelize.QueryTypes.SELECT } )
						.then(function(row){
							if(row.length > 0 && row[0].direction == 'outbound'){
								time_ended = gmdate('Y-m-d H:i:s')

								TblConversations.syncData(cid, data.user_id, 'handled')
								Models.TblConversationDuration.end_conversation(data, time_ended)
								TblConversations.saveLogs(logs, cid, time_ended, data.cdid, Models, function(){
									console.log("manual conversation end success!")
								})
							}
						}, 
						function(rejectedPromiseError){
							console.log("getLastMessageType() rejectedPromiseError")
						})
				}
			}
		}
	});
	return TblConversations
};
