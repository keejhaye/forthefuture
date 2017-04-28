/* jshint indent: 1 */

module.exports = function(sequelize, DataTypes) {
	return sequelize.define('tbl_user_activities', {
		id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			primaryKey: true,
			autoIncrement: true
		},
		user_id: {
			type: DataTypes.BIGINT,
			allowNull: true,
			references: {
				model: 'tbl_users',
				key: 'id'
			}
		},
		old_data: {
			type: DataTypes.TEXT,
			allowNull: true
		},
		new_data: {
			type: DataTypes.TEXT,
			allowNull: true
		},
		section: {
			type: DataTypes.STRING,
			allowNull: true
		},
		changed_fields: {
			type: DataTypes.TEXT,
			allowNull: true
		},
		is_new: {
			type: DataTypes.BOOLEAN,
			allowNull: true,
			defaultValue: '0'
		},
		record_changed: {
			type: DataTypes.STRING,
			allowNull: true
		},
		remarks: {
			type: DataTypes.TEXT,
			allowNull: true
		},
		date_created: {
			type: DataTypes.DATE,
			allowNull: true,
			defaultValue: '0000-00-00 00:00:00'
		}
	}, {
		tableName: 'tbl_user_activities'
	});
};
