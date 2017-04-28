/* jshint indent: 1 */

module.exports = function(sequelize, DataTypes) {
	return sequelize.define('tbl_settings_logs', {
		id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			primaryKey: true,
			autoIncrement: true
		},
		user_id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			references: {
				model: 'tbl_users',
				key: 'id'
			}
		},
		setting: {
			type: DataTypes.STRING,
			allowNull: false
		},
		old_value: {
			type: DataTypes.STRING,
			allowNull: false
		},
		new_value: {
			type: DataTypes.STRING,
			allowNull: false
		},
		date_created: {
			type: DataTypes.DATE,
			allowNull: false
		},
	}, {
		tableName: 'tbl_settings_logs',
		timestamps : false
	});
};
