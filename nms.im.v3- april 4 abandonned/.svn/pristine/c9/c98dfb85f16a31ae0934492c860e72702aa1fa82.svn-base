/* jshint indent: 1 */

module.exports = function(sequelize, DataTypes) {
	return sequelize.define('utl_migration', {
		id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			primaryKey: true,
			autoIncrement: true
		},
		table_name: {
			type: DataTypes.STRING,
			allowNull: true
		},
		details: {
			type: DataTypes.TEXT,
			allowNull: true
		},
		source_file: {
			type: DataTypes.STRING,
			allowNull: true
		},
		line_no: {
			type: DataTypes.INTEGER(11),
			allowNull: true
		},
		status: {
			type: DataTypes.STRING,
			allowNull: true,
			defaultValue: 'pending'
		},
		execution: {
			type: DataTypes.DATE,
			allowNull: true
		},
		executed: {
			type: DataTypes.DATE,
			allowNull: true
		},
		execution_info: {
			type: DataTypes.TEXT,
			allowNull: true
		},
		date_added: {
			type: DataTypes.DATE,
			allowNull: true
		},
		remarks: {
			type: DataTypes.TEXT,
			allowNull: true
		},
		is_failed: {
			type: DataTypes.INTEGER(1),
			allowNull: true,
			defaultValue: '0'
		}
	}, {
		tableName: 'utl_migration'
	});
};
