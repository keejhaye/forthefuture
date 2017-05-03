/* jshint indent: 1 */
Models = require('../custom_modules/sequelize.js')
module.exports = function(sequelize, DataTypes) {
	return sequelize.define('tbl_conversation_duration', {
		id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			primaryKey: true,
			autoIncrement: true
		},
		conversation_id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			references: {
				model: 'tbl_conversations',
				key: 'id'
			}
		},
		user_id: {
			type: DataTypes.BIGINT,
			allowNull: true,
			references: {
				model: 'tbl_users',
				key: 'id'
			}
		},
		assigned_time: {
			type: DataTypes.DATE,
			allowNull: true
		},
		time_started: {
			type: DataTypes.DATE,
			allowNull: true
		},
		time_ended: {
			type: DataTypes.DATE,
			allowNull: true
		},
		duration: {
			type: DataTypes.INTEGER(11),
			allowNull: true,
			defaultValue: '0'
		},
		status: {
			type: DataTypes.STRING,
			allowNull: true
		},
		time_fetched: {
			type: DataTypes.DATE,
			allowNull: true
		},
		fetch_count: {
			type: DataTypes.INTEGER(11),
			allowNull: true,
			defaultValue: '0'
		},
		last_inbound_message: {
			type: DataTypes.STRING,
			allowNull: true
		},
		last_inbound_time: {
			type: DataTypes.DATE,
			allowNull: true
		},
		last_outbound_message: {
			type: DataTypes.STRING,
			allowNull: true
		},
		last_outbound_time: {
			type: DataTypes.DATE,
			allowNull: true
		},
		first_message_id: {
			type: DataTypes.BIGINT,
			allowNull: true,
			references: {
				model: 'tbl_messages',
				key: 'id'
			}
		},
		last_message_id: {
			type: DataTypes.BIGINT,
			allowNull: true,
			references: {
				model: 'tbl_messages',
				key: 'id'
			}
		}
	}, {
		tableName: 'tbl_conversation_duration',
		timestamps: false,
		classMethods : {
			updateLatestEntry : function(data,callback){
				//UPDATE LATEST ENTRY FOR BOTH tbl_conversation_duration and rpt_conversation_duration
				console.log("UPDATING LATEST DURATION RECORD")

				statement = 'UPDATE tbl_conversation_duration '+
											'SET '+
												'assigned_time = IF(assigned_time IS NULL, ?, assigned_time), '+
											   'status = "ongoing-in", '+
											   'time_fetched = ?, '+
										   	 'fetch_count = fetch_count + 1, '+
											   'user_id = ? '+
											'WHERE conversation_id = ? AND status != "ended" '+
											'ORDER BY id DESC '+
											'LIMIT 1'

				values = [data.new_date, data.new_date, data.user_id, data.conversation_id]

				if(data.isnew){
					statement = 'UPDATE tbl_conversation_duration '+
											'SET last_inbound_message = ?, '+
											   'last_inbound_time = ?, '+
											   'status = "ongoing-in", '+
											   'last_message_id = ? ,'+
											   'user_id = ? '+
											'WHERE conversation_id = ? AND status != "ended" '+
											'ORDER BY id DESC '+
											'LIMIT 1'

					values = [data.message, data.message_bound_time, data.last_message_id, data.user_id, data.conversation_id]
				}

				if(data.unmapped){
					statement = 'UPDATE tbl_conversation_duration '+
										'SET ' +
										   'assigned_time = IF(assigned_time IS NULL, ?, assigned_time), '+
										   'status = "ongoing-in", '+
										   'time_fetched = ?, '+
										   'fetch_count = fetch_count + 1, '+
										   'user_id = ? '+
										'WHERE conversation_id = ? AND status != "ended" '+
										'ORDER BY id DESC '+
										'LIMIT 1'

					values = [data.new_date, data.new_date, data.user_id, data.conversation_id]
				}

				sequelize.query(statement,{replacements: values}).then(function (result) { 
					if(result[0].affectedRows == 0){
						//IF NOTHING IS UPDATED, MEANS THAT RECORD IS NOT CREATED. CREATE IT
						Models.TblConversationDuration.new_duration_record(data,callback)
					}
					else{
						rptStatement = 'UPDATE rpt_conversation_duration '+
													'SET '+
														'assigned_time = IF(assigned_time IS NULL, ?, assigned_time), '+
													   'status = "ongoing-in", '+
													   'time_fetched = ?, '+
												   	 'fetch_count = fetch_count + 1, '+
													   'user_id = ? '+
													'WHERE conversation_id = ? AND status != "ended" '+
													'ORDER BY id DESC '+
													'LIMIT 1'

						values = [data.new_date, data.new_date, data.user_id, data.conversation_id]

						if(data.isnew){
							rptStatement = 'UPDATE rpt_conversation_duration '+
													'SET last_inbound_message = ?, '+
													   'last_inbound_time = ?, '+
													   'status = "ongoing-in", '+
													   'last_message_id = ? ,'+
													   'user_id = ? '+
													'WHERE conversation_id = ? AND status != "ended" '+
													'ORDER BY id DESC '+
													'LIMIT 1'

							values = [data.message, data.message_bound_time, data.last_message_id, data.user_id, data.conversation_id]
						}

						if(data.unmapped){
							rptStatement = 'UPDATE rpt_conversation_duration '+
												'SET status = "ongoing-in", '+
												   'assigned_time = IF(assigned_time IS NULL, ?, assigned_time), '+
												   'time_fetched = ?, '+
												   'fetch_count = fetch_count + 1, '+
												   'user_id = ? '+
												'WHERE conversation_id = ? AND status != "ended" '+
												'ORDER BY id DESC '+
												'LIMIT 1'

							values = [data.new_date, data.new_date, data.user_id, data.conversation_id]
						}

						sequelize.query(rptStatement,{replacements: values})
						.then(function (result) { 
						}, function(rejectedPromiseError){
							console.log('rpt_conversation_duration rejectedPromiseError')
							console.log(rejectedPromiseError)
						})
					}
				}, function(rejectedPromiseError){
					console.log('tbl_conversation_duration rejectedPromiseError')
					console.log(rejectedPromiseError)
				})
			},
			new_duration_record: function(data,callback){
				console.log("NOTHING IS UPDATED; CREATING NEW RECORD")
				newStmt = 'INSERT INTO tbl_conversation_duration '+
									'SET last_inbound_message = ?, '+
									   'last_inbound_time = ?, '+
									   'status = "ongoing-in", '+
									   'last_message_id = ? ,'+
									   'first_message_id = ? ,'+
									   'user_id = ? , '+
									   'conversation_id = ? ,'+
									   'assigned_time = ? ,'+
									   'time_started = ? ,' +
									   'time_fetched = ? ,' +
									   'fetch_count = fetch_count + 1 '

		   		newValues = [data.message, data.message_bound_time, data.last_message_id, data.last_message_id, data.user_id, data.conversation_id, data.assigned_time, data.message_bound_time, data.new_date]

			   	if(data.user_id == undefined){
		   			newStmt = 'INSERT INTO tbl_conversation_duration '+
											'SET last_inbound_message = ?, '+
											   'last_inbound_time = ?, '+
											   'status = "ongoing-in", '+
											   'last_message_id = ? ,'+
											   'first_message_id = ? ,'+
											   'conversation_id = ? ,'+
											   'time_started = ?'

			   		newValues = [data.message, data.message_bound_time, data.last_message_id, data.last_message_id, data.conversation_id, data.message_bound_time]
			   	}

				sequelize.query(newStmt, {replacements: newValues}).then(function(result) { 
					callback(result[0].insertId)
					newStmt = 'INSERT INTO rpt_conversation_duration '+
										'SET ' + 
											'conversation_duration_id = ?, '+
											'date_created = ?, '+
											'time_fetched = ?, '+
											'fetch_count = fetch_count + 1, '+
											'last_inbound_message = ?, '+
										   'last_inbound_time = ?, '+
										   'status = "ongoing-in", '+
										   'last_message_id = ? ,'+
										   'first_message_id = ? ,'+
										   'user_id = ? ,'+
										   'conversation_id = ? ,'+
										   'assigned_time = ? ,'+
										   'time_started = ? '

				   	newValues = [result[0].insertId, data.new_date, data.new_date, data.message, data.message_bound_time, data.last_message_id, data.last_message_id, data.user_id, data.conversation_id, data.new_date, data.message_bound_time]

				   	if(data.user_id == undefined){
			   			newStmt = 'INSERT INTO rpt_conversation_duration '+
												'SET conversation_duration_id = ?, ' +
													'date_created = ?, '+
													'last_inbound_message = ?, '+
												   'last_inbound_time = ?, '+
												   'status = "ongoing-in", '+
												   'last_message_id = ? ,'+
												   'first_message_id = ? ,'+
												   'conversation_id = ? ,'+
												   'time_started = ?'

				   		newValues = [result[0].insertId, data.new_date, data.message, data.message_bound_time, data.last_message_id, data.last_message_id, data.conversation_id, data.message_bound_time]
				   	}

					sequelize.query(newStmt, {replacements: newValues})
					.then(function (result) { 
					}, function(rejectedPromiseError){
						console.log('new rpt_conversation_duration rejectedPromiseError')
						console.log(rejectedPromiseError)
					})
				}, function(rejectedPromiseError){
					console.log('new tbl_conversation_duration rejectedPromiseError')
					console.log(rejectedPromiseError)
				})
			},
			end_conversation : function(data, date, callback){
				sequelize.query('UPDATE tbl_conversation_duration '+
									'SET time_ended = ?, ' +
										'status = "ended", ' +
										'duration = TIMESTAMPDIFF(SECOND, time_started, ?) ' +
									'WHERE conversation_id = ? ' +
									'ORDER BY id DESC ' +
									'LIMIT 1',
									{replacements: [date, date, data.conversation_id]}
				)

				sequelize.query('UPDATE rpt_conversation_duration '+
									'SET time_ended = ?, '+
									'status = "ended", '+
									'duration = TIMESTAMPDIFF(SECOND, time_started, ?), '+
									'fullname = ?, '+
									'persona_id = ?, '+
									'persona_name = ?, '+
									'subscriber_id = ?, '+
									'subscriber_name = ?, '+
									'service_id = ?, '+
									'service_name = ? '+
									'WHERE conversation_id = ? '+
									'ORDER BY id DESC '+
									'LIMIT 1',
									{
										replacements: [date, date, data.fullname, data.persona_id, data.persona_name, data.subscriber_id, data.subscriber_name, data.service_id, data.service_name, data.conversation_id]})
			}
		},
		timeStamps : false
	});
};
