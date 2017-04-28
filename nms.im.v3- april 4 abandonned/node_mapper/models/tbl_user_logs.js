/* jshint indent: 1 */

module.exports = function(sequelize, DataTypes) {
	return sequelize.define('tbl_user_logs', {
		id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			primaryKey: true,
			autoIncrement: true
		},
		user_id: {
			type: DataTypes.BIGINT,
			allowNull: false
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
			allowNull: true,
			defaultValue: '0000-00-00 00:00:00'
		},
		active: {
			type: DataTypes.BOOLEAN,
			allowNull: true,
			defaultValue: '1'
		}
	}, {
		tableName: 'tbl_user_logs'
	});
};
