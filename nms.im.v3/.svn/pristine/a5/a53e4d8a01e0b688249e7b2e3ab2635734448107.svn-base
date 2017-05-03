/* jshint indent: 1 */

module.exports = function(sequelize, DataTypes) {
	return sequelize.define('rpt_message_interval', {
		id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			primaryKey: true,
			autoIncrement: true
		},
		message_interval_id: {
			type: DataTypes.BIGINT,
			allowNull: true,
			references: {
				model: 'tbl_message_interval',
				key: 'id'
			}
		},
		conversation_id: {
			type: DataTypes.BIGINT,
			allowNull: true,
			references: {
				model: 'tbl_conversations',
				key: 'id'
			}
		},
		inbound_id: {
			type: DataTypes.BIGINT,
			allowNull: true,
			references: {
				model: 'tbl_messages',
				key: 'id'
			}
		},
		outbound_id: {
			type: DataTypes.BIGINT,
			allowNull: true,
			references: {
				model: 'tbl_messages',
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
		fullname: {
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
		inbound_time: {
			type: DataTypes.DATE,
			allowNull: true
		},
		assigned_time: {
			type: DataTypes.DATE,
			allowNull: true
		},
		outbound_time: {
			type: DataTypes.DATE,
			allowNull: true
		},
		response_interval: {
			type: DataTypes.INTEGER(11),
			allowNull: true
		},
		inbound_message: {
			type: DataTypes.STRING,
			allowNull: true
		},
		outbound_message: {
			type: DataTypes.STRING,
			allowNull: true
		},
		date_created: {
			type: DataTypes.DATE,
			allowNull: true
		}
	}, {
		tableName: 'rpt_message_interval'
	});
};
