/* jshint indent: 1 */

module.exports = function(sequelize, DataTypes) {
	return sequelize.define('tbl_service_subscriber_limit', {
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
		limit_type: {
			type: DataTypes.STRING,
			allowNull: true
		},
		limit_count: {
			type: DataTypes.INTEGER(11),
			allowNull: true
		},
		limit_action: {
			type: DataTypes.STRING,
			allowNull: true,
			defaultValue: 'discard'
		},
		reset_period: {
			type: DataTypes.STRING,
			allowNull: true
		},
		in_seconds: {
			type: DataTypes.INTEGER(11),
			allowNull: true
		}
	}, {
		tableName: 'tbl_service_subscriber_limit'
	});
};
