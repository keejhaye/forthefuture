/* jshint indent: 1 */

module.exports = function(sequelize, DataTypes) {
	UtlReportsTable =  sequelize.define('utl_reports_table', {
		id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			primaryKey: true,
			autoIncrement: true
		},
		name: {
			type: DataTypes.TEXT,
			allowNull: true
		},
		status: {
			type: DataTypes.STRING,
			allowNull: true
		},
		date_started: {
			type: DataTypes.DATE,
			allowNull: true
		}
	}, {
		tableName: 'utl_reports_table',
		timestamps: false,
	});

	return UtlReportsTable
};
