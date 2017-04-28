/* jshint indent: 1 */

module.exports = function(sequelize, DataTypes) {
	return sequelize.define('tbl_blacklist', {
		id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			primaryKey: true,
			autoIncrement: true
		},
		subscriber_id: {
			type: DataTypes.BIGINT,
			allowNull: true,
			references: {
				model: 'tbl_subscribers',
				key: 'id'
			}
		},
		user_id: {
			type: DataTypes.BIGINT,
			allowNull: true,
			references: {
				model: 'tbl_users',
				key: 'id'
			}
		},
		date_created: {
			type: DataTypes.DATE,
			allowNull: true,
			defaultValue: '0000-00-00 00:00:00'
		}
	}, {
		tableName: 'tbl_blacklist'
	});
};
