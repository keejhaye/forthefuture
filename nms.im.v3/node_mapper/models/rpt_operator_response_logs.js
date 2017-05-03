/* jshint indent: 1 */

module.exports = function(sequelize, DataTypes) {
	return sequelize.define('rpt_operator_response_logs', {
		id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			primaryKey: true,
			autoIncrement: true
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
		conversation_id: {
			type: DataTypes.BIGINT,
			allowNull: true,
			references: {
				model: 'tbl_conversations',
				key: 'id'
			}
		},
		service_id: {
			type: DataTypes.BIGINT,
			allowNull: true,
			references: {
				model: 'tbl_services',
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
		inbound_time: {
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
		date_created: {
			type: DataTypes.DATE,
			allowNull: true
		},
		operator_response_log_id: {
			type: DataTypes.BIGINT,
			allowNull: true,
			references: {
				model: 'tbl_operator_response_logs',
				key: 'id'
			}
		}
	}, {
		tableName: 'rpt_operator_response_logs'
	});
};
