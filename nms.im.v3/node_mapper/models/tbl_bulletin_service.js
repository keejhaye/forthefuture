/* jshint indent: 1 */

module.exports = function(sequelize, DataTypes) {
	return sequelize.define('tbl_bulletin_service', {
		id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			primaryKey: true,
			autoIncrement: true
		},
		bulletin_id: {
			type: DataTypes.BIGINT,
			allowNull: true,
			references: {
				model: 'tbl_bulletins',
				key: 'id'
			}
		},
		service_id: {
			type: DataTypes.BIGINT,
			allowNull: true,
			references: {
				model: 'tbl_services',
				key: 'id'
			}
		},
		date_created: {
			type: DataTypes.DATE,
			allowNull: false,
			defaultValue: '0000-00-00 00:00:00'
		}
	}, {
		tableName: 'tbl_bulletin_service'
	});
};
