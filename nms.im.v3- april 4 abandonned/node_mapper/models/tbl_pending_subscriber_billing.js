/* jshint indent: 1 */

module.exports = function(sequelize, DataTypes) {
	return sequelize.define('tbl_pending_subscriber_billing', {
		id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			primaryKey: true,
			autoIncrement: true
		},
		subscriber_billing_id: {
			type: DataTypes.BIGINT,
			allowNull: true,
			references: {
				model: 'tbl_subscriber_billing',
				key: 'id'
			}
		},
		status: {
			type: DataTypes.STRING,
			allowNull: true,
			defaultValue: 'ongoing'
		},
		date_created: {
			type: DataTypes.DATE,
			allowNull: true
		}
	}, {
		tableName: 'tbl_pending_subscriber_billing'
	});
};
