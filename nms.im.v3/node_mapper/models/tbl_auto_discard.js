/* jshint indent: 1 */

module.exports = function(sequelize, DataTypes) {
	return sequelize.define('tbl_auto_discard', {
		id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			primaryKey: true,
			autoIncrement: true
		},
		service_id: {
			type: DataTypes.BIGINT,
			allowNull: true,
			references: {
				model: 'tbl_services',
				key: 'id'
			}
		},
		start_time: {
			type: DataTypes.STRING,
			allowNull: true
		},
		end_time: {
			type: DataTypes.STRING,
			allowNull: true
		}
	}, {
		tableName: 'tbl_auto_discard'
	});
};
