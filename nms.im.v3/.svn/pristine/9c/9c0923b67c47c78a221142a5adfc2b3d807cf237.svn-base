/* jshint indent: 1 */

module.exports = function(sequelize, DataTypes) {
	return sequelize.define('rpt_conversation_duration', {
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
		conversation_duration_id: {
			type: DataTypes.BIGINT,
			allowNull: true,
			references: {
				model: 'tbl_conversation_duration',
				key: 'id'
			}
		},
		user_id: {
			type: DataTypes.BIGINT,
			allowNull: true,
			defaultValue: '0',
			references: {
				model: 'tbl_users',
				key: 'id'
			}
		},
		fullname: {
			type: DataTypes.STRING,
			allowNull: true
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
		persona_id: {
			type: DataTypes.BIGINT,
			allowNull: true,
			references: {
				model: 'tbl_personas',
				key: 'id'
			}
		},
		persona_name: {
			type: DataTypes.STRING,
			allowNull: true
		},
		subscriber_id: {
			type: DataTypes.BIGINT,
			allowNull: true,
			references: {
				model: 'tbl_subscribers',
				key: 'id'
			}
		},
		subscriber_name: {
			type: DataTypes.STRING,
			allowNull: true
		},
		service_id: {
			type: DataTypes.BIGINT,
			allowNull: true,
			references: {
				model: 'tbl_services',
				key: 'id'
			}
		},
		service_name: {
			type: DataTypes.STRING,
			allowNull: true
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
		tableName: 'rpt_conversation_duration',
		classMethods : {
			updateLatestEntry : function(data,callback){
				sequelize.query('UPDATE rpt_conversation_duration '+
									'SET last_inbound_message = ?, '+
									   'last_inbound_time = ?, '+
									   'status = "ongoing-in", '+
									   'last_message_id = ? '+
									'WHERE conversation_id = ? '+
									'ORDER BY id DESC '+
									'LIMIT 1;',
									{replacements: [data.message, data.now, data.last_message_id, data.conversation_id]}
				)
				.then(function (result) { 
				}, function(rejectedPromiseError){
					console.log('rpt_conversation_duration rejectedPromiseError')
					console.log(rejectedPromiseError)
				})
			}
		}
	});
};
