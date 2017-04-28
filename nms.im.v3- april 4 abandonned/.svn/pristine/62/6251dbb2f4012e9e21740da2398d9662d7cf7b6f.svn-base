/* jshint indent: 1 */

module.exports = function(sequelize, DataTypes) {
	return sequelize.define('tbl_flagged_messages', {
		id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			primaryKey: true,
			autoIncrement: true
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
		operator_id: {
			type: DataTypes.BIGINT,
			allowNull: true,
			defaultValue: '0',
			references: {
				model: 'tbl_users',
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
		status: {
			type: DataTypes.STRING,
			allowNull: true,
			defaultValue: 'pending'
		},
		comments: {
			type: DataTypes.TEXT,
			allowNull: true
		},
		date_flagged: {
			type: DataTypes.DATE,
			allowNull: true
		},
		date_moderated: {
			type: DataTypes.DATE,
			allowNull: true
		}
	}, {
		tableName: 'tbl_flagged_messages'
	});
};
