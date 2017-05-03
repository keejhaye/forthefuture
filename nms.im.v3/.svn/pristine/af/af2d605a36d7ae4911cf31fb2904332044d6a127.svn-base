/* jshint indent: 1 */

module.exports = function(sequelize, DataTypes) {
	return sequelize.define('tbl_conditions', {
		id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			primaryKey: true,
			autoIncrement: true
		},
		rule_id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			references: {
				model: 'tbl_rules',
				key: 'id'
			}
		},
		field: {
			type: DataTypes.STRING,
			allowNull: true
		},
		operator: {
			type: DataTypes.STRING,
			allowNull: true
		},
		value: {
			type: DataTypes.STRING,
			allowNull: true
		},
		status: {
			type: DataTypes.STRING,
			allowNull: true,
			defaultValue: 'enabled'
		},
		date_created: {
			type: DataTypes.DATE,
			allowNull: false,
			defaultValue: '0000-00-00 00:00:00'
		},
		conditional_operator: {
			type: DataTypes.STRING,
			allowNull: true
		}
	}, {
		tableName: 'tbl_conditions'
	});
};
