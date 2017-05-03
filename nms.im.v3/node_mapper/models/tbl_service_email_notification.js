/* jshint indent: 1 */

module.exports = function(sequelize, DataTypes) {
	return sequelize.define('tbl_service_email_notification', {
		id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			primaryKey: true,
			autoIncrement: true
		},
		email_data: {
			type: DataTypes.TEXT,
			allowNull: true
		},
		date_created: {
			type: DataTypes.DATE,
			allowNull: true
		},
		is_success: {
			type: DataTypes.BOOLEAN,
			allowNull: true
		}
	}, {
		tableName: 'tbl_service_email_notification'
	});
};
