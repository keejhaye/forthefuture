/* jshint indent: 1 */

module.exports = function(sequelize, DataTypes) {
	return sequelize.define('tbl_subscribers', {
		id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			primaryKey: true,
			autoIncrement: true
		},
		name: {
			type: DataTypes.STRING,
			allowNull: true
		},
		service_id: {
			type: DataTypes.BIGINT,
			allowNull: true,
			references: {
				model: 'tbl_services',
				key: 'id'
			}
		},
		status: {
			type: DataTypes.STRING,
			allowNull: true,
			defaultValue: 'active'
		},
		profile: {
			type: DataTypes.TEXT,
			allowNull: true
		},
		date_created: {
			type: DataTypes.DATE,
			allowNull: true,
			defaultValue: '0000-00-00 00:00:00'
		},
		additional_info: {
			type: DataTypes.TEXT,
			allowNull: true
		},
		membership_type: {
			type: DataTypes.STRING,
			allowNull: true,
			defaultValue: 'normal'
		},
		date_of_first_message: {
			type: DataTypes.DATE,
			allowNull: true
		}
	}, {
		tableName: 'tbl_subscribers',
		timestamps : false
	});
};
