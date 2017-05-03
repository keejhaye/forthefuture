/* jshint indent: 1 */

module.exports = function(sequelize, DataTypes) {
	return sequelize.define('tbl_subscriber_persona_limit', {
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
		persona_id: {
			type: DataTypes.BIGINT,
			allowNull: true,
			references: {
				model: 'tbl_personas',
				key: 'id'
			}
		},
		reset_on: {
			type: DataTypes.DATE,
			allowNull: true
		},
		date_created: {
			type: DataTypes.DATE,
			allowNull: true,
			defaultValue: '0000-00-00 00:00:00'
		},
		reset_period: {
			type: DataTypes.STRING,
			allowNull: true,
			defaultValue: 'day'
		}
	}, {
		tableName: 'tbl_subscriber_persona_limit'
	});
};
