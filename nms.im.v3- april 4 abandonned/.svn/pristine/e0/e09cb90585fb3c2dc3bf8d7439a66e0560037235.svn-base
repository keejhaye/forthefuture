/* jshint indent: 1 */

module.exports = function(sequelize, DataTypes) {
	return sequelize.define('utl_report_lock', {
		object_key: {
			type: DataTypes.STRING,
			allowNull: false,
			primaryKey: true
		},
		user_id: {
			type: DataTypes.BIGINT,
			allowNull: true,
			references: {
				model: 'tbl_users',
				key: 'id'
			}
		},
		username: {
			type: DataTypes.STRING,
			allowNull: true
		},
		report: {
			type: DataTypes.STRING,
			allowNull: true
		},
		date_created: {
			type: DataTypes.DATE,
			allowNull: true
		}
	}, {
		tableName: 'utl_report_lock'
	});
};
