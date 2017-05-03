/* jshint indent: 1 */

module.exports = function(sequelize, DataTypes) {
	return sequelize.define('utl_migration_v1_status', {
		id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			primaryKey: true,
			autoIncrement: true
		},
		offset_start: {
			type: DataTypes.INTEGER(11),
			allowNull: true
		},
		offset_end: {
			type: DataTypes.INTEGER(11),
			allowNull: true
		},
		limit: {
			type: DataTypes.INTEGER(11),
			allowNull: true,
			defaultValue: '100'
		},
		service_id: {
			type: DataTypes.BIGINT,
			allowNull: true
		},
		status: {
			type: DataTypes.STRING,
			allowNull: true,
			defaultValue: 'waiting'
		},
		date_started: {
			type: DataTypes.DATE,
			allowNull: true
		},
		iteration: {
			type: DataTypes.INTEGER(11),
			allowNull: true,
			defaultValue: '0'
		}
	}, {
		tableName: 'utl_migration_v1_status'
	});
};
