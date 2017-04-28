/* jshint indent: 1 */

module.exports = function(sequelize, DataTypes) {
	return sequelize.define('rpt_user_logs', {
		id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			primaryKey: true,
			autoIncrement: true
		},
		user_log_id: {
			type: DataTypes.BIGINT,
			allowNull: true,
			references: {
				model: 'tbl_user_logs',
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
		status: {
			type: DataTypes.STRING,
			allowNull: false
		},
		start_time: {
			type: DataTypes.DATE,
			allowNull: true
		},
		end_time: {
			type: DataTypes.DATE,
			allowNull: true
		},
		date_created: {
			type: DataTypes.DATE,
			allowNull: false
		},
		active: {
			type: DataTypes.BOOLEAN,
			allowNull: true,
			defaultValue: '1'
		}
	}, {
		tableName: 'rpt_user_logs'
	});
};
