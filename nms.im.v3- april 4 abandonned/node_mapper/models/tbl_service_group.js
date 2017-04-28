/* jshint indent: 1 */

module.exports = function(sequelize, DataTypes) {
	return sequelize.define('tbl_service_group', {
		id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			primaryKey: true,
			autoIncrement: true
		},
		group_id: {
			type: DataTypes.BIGINT,
			allowNull: true,
			references: {
				model: 'tbl_groups',
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
		}
	}, {
		tableName: 'tbl_service_group'
	});
};
