/* jshint indent: 1 */

module.exports = function(sequelize, DataTypes) {
	return sequelize.define('tbl_actions', {
		id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			primaryKey: true,
			autoIncrement: true
		},
		rule_id: {
			type: DataTypes.BIGINT,
			allowNull: false
		},
		library_id: {
			type: DataTypes.BIGINT,
			allowNull: true,
			references: {
				model: 'tbl_libraries',
				key: 'id'
			}
		},
		operator_id: {
			type: DataTypes.BIGINT,
			allowNull: true,
			references: {
				model: 'tbl_users',
				key: 'id'
			}
		},
		type: {
			type: DataTypes.STRING,
			allowNull: true
		},
		meta: {
			type: DataTypes.TEXT,
			allowNull: true
		},
		assign_delay: {
			type: DataTypes.INTEGER(11),
			allowNull: true,
			defaultValue: '0'
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
		tableName: 'tbl_actions'
	});
};
