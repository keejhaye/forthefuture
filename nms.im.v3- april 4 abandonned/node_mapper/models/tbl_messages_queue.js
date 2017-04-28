/* jshint indent: 1 */

module.exports = function(sequelize, DataTypes) {
	return sequelize.define('tbl_messages_queue', {
		id: {
			type: DataTypes.INTEGER(11),
			allowNull: false,
			primaryKey: true,
			autoIncrement: true
		},
		details: {
			type: DataTypes.TEXT,
			allowNull: true
		},
		status: {
			type: DataTypes.STRING,
			allowNull: true,
			defaultValue: 'pending'
		},
		inbound_time: {
			type: DataTypes.DATE,
			allowNull: true
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
		date_created: {
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
		},
		attempt: {
			type: DataTypes.INTEGER(5),
			allowNull: true,
			defaultValue: '0'
		},
		service_id: {
			type: DataTypes.INTEGER(11),
			allowNull: true
		}
	}, {
		tableName: 'tbl_messages_queue'
	});
};
