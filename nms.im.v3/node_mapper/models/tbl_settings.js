/* jshint indent: 1 */

module.exports = function(sequelize, DataTypes) {
	return sequelize.define('tbl_settings', {
		id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			primaryKey: true,
			autoIncrement: true
		},
		name: {
			type: DataTypes.STRING,
			allowNull: false
		},
		value: {
			type: DataTypes.STRING,
			allowNull: false
		},
	}, {
		tableName: 'tbl_settings',
		timestamps : false
	});
};
