/* jshint indent: 1 */

module.exports = function(sequelize, DataTypes) {
	return sequelize.define('tbl_whitelist', {
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
		ip_address: {
			type: DataTypes.STRING,
			allowNull: true
		},
		date_created: {
			type: DataTypes.DATE,
			allowNull: true,
			defaultValue: '0000-00-00 00:00:00'
		}
	}, {
		tableName: 'tbl_whitelist'
	});
};
