/* jshint indent: 1 */

module.exports = function(sequelize, DataTypes) {
	return sequelize.define('tbl_rules', {
		id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			primaryKey: true,
			autoIncrement: true
		},
		service_id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			references: {
				model: 'tbl_services',
				key: 'id'
			}
		},
		name: {
			type: DataTypes.STRING,
			allowNull: true
		},
		delay: {
			type: DataTypes.INTEGER(11),
			allowNull: true,
			defaultValue: '0'
		},
		run_once: {
			type: DataTypes.BOOLEAN,
			allowNull: true,
			defaultValue: '0'
		},
		date_created: {
			type: DataTypes.DATE,
			allowNull: false,
			defaultValue: '0000-00-00 00:00:00'
		},
		priority: {
			type: DataTypes.INTEGER(3),
			allowNull: true,
			defaultValue: '0'
		},
		status: {
			type: DataTypes.STRING,
			allowNull: true,
			defaultValue: 'enabled'
		}
	}, {
		tableName: 'tbl_rules'
	});
};
