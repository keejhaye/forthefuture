/* jshint indent: 1 */

module.exports = function(sequelize, DataTypes) {
	return sequelize.define('tbl_pending_conversations', {
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
		status: {
			type: DataTypes.STRING,
			allowNull: true,
			defaultValue: 'pending'
		},
		assigned_time: {
			type: DataTypes.DATE,
			allowNull: true
		},
		last_message: {
			type: DataTypes.DATE,
			allowNull: true
		},
		time_fetched: {
			type: DataTypes.DATE,
			allowNull: true
		},
		conversation_duration_id: {
			type: DataTypes.BIGINT,
			allowNull: true,
			references: {
				model: 'tbl_conversation_duration',
				key: 'id'
			}
		}
	}, {
		tableName: 'tbl_pending_conversations'
	});
};
