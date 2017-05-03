/* jshint indent: 1 */

module.exports = function(sequelize, DataTypes) {
	return sequelize.define('utl_statistics', {
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
		time_executed: {
			type: DataTypes.DATE,
			allowNull: false
		},
		executed: {
			type: DataTypes.INTEGER(1),
			allowNull: false,
			defaultValue: 1
		},
	}, {
		tableName: 'utl_statistics'
	});
};
