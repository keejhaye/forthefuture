/* jshint indent: 1 */

module.exports = function(sequelize, DataTypes) {
	return sequelize.define('rpt_user_conversation_logs', {
		id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			primaryKey: true,
			autoIncrement: true
		},
		user_conversation_logs_id: {
			type: DataTypes.BIGINT,
			allowNull: true,
			references: {
				model: 'tbl_user_conversation_logs',
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
		start_time: {
			type: DataTypes.DATE,
			allowNull: true
		},
		end_time: {
			type: DataTypes.DATE,
			allowNull: true
		},
		status: {
			type: DataTypes.STRING,
			allowNull: true,
			defaultValue: 'ongoing'
		},
		duration: {
			type: DataTypes.INTEGER(11),
			allowNull: true,
			defaultValue: '0'
		},
		date_created: {
			type: DataTypes.DATE,
			allowNull: true
		}
	}, {
		tableName: 'rpt_user_conversation_logs'
	});
};
