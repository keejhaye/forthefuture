/* jshint indent: 1 */

module.exports = function(sequelize, DataTypes) {
	return sequelize.define('tbl_user_service', {
		id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			primaryKey: true,
			autoIncrement: true
		},
		user_id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			references: {
				model: 'tbl_users',
				key: 'id'
			}
		},
		service_id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			references: {
				model: 'tbl_services',
				key: 'id'
			}
		},
		date_created: {
			type: DataTypes.DATE,
			allowNull: true,
			defaultValue: '0000-00-00 00:00:00'
		}
	}, {
		tableName: 'tbl_user_service'
	});
};
