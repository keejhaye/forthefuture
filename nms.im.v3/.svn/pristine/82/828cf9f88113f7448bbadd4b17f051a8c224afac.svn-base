/* jshint indent: 1 */

module.exports = function(sequelize, DataTypes) {
	TblRoles = sequelize.define('tbl_roles', {
		id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			primaryKey: true,
			autoIncrement: true,
		},
		name: {
			type: DataTypes.STRING,
			allowNull: false
		},
		description: {
			type: DataTypes.STRING,
			allowNull: false
		},
		permissions: {
			type: DataTypes.TEXT,
			allowNull: true
		},
		level: {
			type: DataTypes.INTEGER(3),
			allowNull: true
		},
		reportable: {
			type: DataTypes.BOOLEAN,
			allowNull: true,
			defaultValue: '1'
		}
	}, {
		tableName: 'tbl_roles',
		timestamps: false
	});

	return TblRoles
};
