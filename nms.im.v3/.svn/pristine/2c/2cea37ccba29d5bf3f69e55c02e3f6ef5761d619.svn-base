/* jshint indent: 1 */

module.exports = function(sequelize, DataTypes) {
	return sequelize.define('tbl_autoreminders', {
		id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			primaryKey: true,
			autoIncrement: true
		},
		service_id: {
			type: DataTypes.BIGINT,
			allowNull: false
		},
		library_id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			references: {
				model: 'tbl_libraries',
				key: 'id'
			}
		},
		idle_time: {
			type: DataTypes.INTEGER(11),
			allowNull: false,
			defaultValue: '0'
		},
		schedule: {
			type: DataTypes.DATE,
			allowNull: true
		},
		timezone: {
			type: DataTypes.STRING,
			allowNull: true,
			defaultValue: '0.0'
		},
		date_created: {
			type: DataTypes.DATE,
			allowNull: false,
			defaultValue: '0000-00-00 00:00:00'
		},
		status: {
			type: DataTypes.STRING,
			allowNull: true,
			defaultValue: 'enabled'
		}
	}, {
		tableName: 'tbl_autoreminders'
	});
};
