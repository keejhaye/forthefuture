/* jshint indent: 1 */

module.exports = function(sequelize, DataTypes) {
	return sequelize.define('rpt_messages', {
		id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			primaryKey: true,
			autoIncrement: true
		},
		conversation_id: {
			type: DataTypes.BIGINT,
			allowNull: true,
			references: {
				model: 'tbl_conversations',
				key: 'id'
			}
		},
		message_id: {
			type: DataTypes.BIGINT,
			allowNull: true,
			references: {
				model: 'tbl_messages',
				key: 'id'
			}
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
			defaultValue: 'normal'
		},
		bound_time: {
			type: DataTypes.DATE,
			allowNull: true
		},
		direction: {
			type: DataTypes.STRING,
			allowNull: true
		},
		assigned_time: {
			type: DataTypes.DATE,
			allowNull: true
		},
		duration: {
			type: DataTypes.INTEGER(5),
			allowNull: true,
			defaultValue: '0'
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
		date_created: {
			type: DataTypes.DATE,
			allowNull: true,
			defaultValue: '0000-00-00 00:00:00'
		},
		fetched: {
			type: DataTypes.BOOLEAN,
			allowNull: true,
			defaultValue: '0'
		},
		is_billed: {
			type: DataTypes.BOOLEAN,
			allowNull: true,
			defaultValue: '1'
		}
	}, {
		tableName: 'rpt_messages'
	});
};
