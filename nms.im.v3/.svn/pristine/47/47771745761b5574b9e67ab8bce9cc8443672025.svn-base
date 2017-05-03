/* jshint indent: 1 */

module.exports = function(sequelize, DataTypes) {
	return sequelize.define('tbl_conversation_logs', {
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
		user_id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			defaultValue: '0',
			primaryKey: true,
			references: {
				model: 'tbl_users',
				key: 'id'
			}
		},
		comment: {
			type: DataTypes.STRING,
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
		tableName: 'tbl_conversation_logs'
	});
};
