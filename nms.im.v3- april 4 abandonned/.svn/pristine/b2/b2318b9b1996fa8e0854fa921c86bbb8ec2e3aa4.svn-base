/* jshint indent: 1 */

module.exports = function(sequelize, DataTypes) {
	return sequelize.define('tbl_user_system_logs', {
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
		status: {
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
		date_created: {
			type: DataTypes.DATE,
			allowNull: true
		},
		ip_address: {
			type: DataTypes.STRING,
			allowNull: true
		},
		active: {
			type: DataTypes.BOOLEAN,
			allowNull: true,
			defaultValue: '1'
		},
		remarks: {
			type: DataTypes.STRING,
			allowNull: true
		}
	}, {
		tableName: 'tbl_user_system_logs'
	});
};
