/* jshint indent: 1 */

module.exports = function(sequelize, DataTypes) {
	return sequelize.define('tbl_conversation_messages_logs', {
		id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			primaryKey: true,
			autoIncrement: true
		},
		conversation_id: {
			type: DataTypes.BIGINT,
			allowNull: true,
			defaultValue: '0',
			references: {
				model: 'tbl_conversations',
				key: 'id'
			}
		},
		conversation_duration_id: {
			type: DataTypes.BIGINT,
			allowNull: true,
			defaultValue: '0',
			references: {
				model: 'tbl_conversation_duration',
				key: 'id'
			}
		},
		message_id: {
			type: DataTypes.BIGINT,
			allowNull: true,
			defaultValue: '0',
			references: {
				model: 'tbl_messages',
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
		comments: {
			type: DataTypes.TEXT,
			allowNull: true
		},
		date_created: {
			type: DataTypes.DATE,
			allowNull: true,
			defaultValue: '0000-00-00 00:00:00'
		},
		url: {
			type: DataTypes.STRING,
			allowNull: true
		}
	}, {
		tableName: 'tbl_conversation_messages_logs'
	});
};
