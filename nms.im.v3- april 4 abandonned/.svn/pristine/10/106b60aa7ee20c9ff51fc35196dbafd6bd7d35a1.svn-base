/* jshint indent: 1 */

module.exports = function(sequelize, DataTypes) {
	return sequelize.define('tbl_statistics_realtime', {
		id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			primaryKey: true,
			autoIncrement: true
		},
		online: {
			type: DataTypes.INTEGER(11),
			allowNull: true
		},
		inbound: {
			type: DataTypes.INTEGER(11),
			allowNull: true
		},
		outbound: {
			type: DataTypes.INTEGER(11),
			allowNull: true
		},
		pending: {
			type: DataTypes.INTEGER(11),
			allowNull: true
		},
		assigned: {
			type: DataTypes.INTEGER(11),
			allowNull: true
		},
		last_update: {
			type: DataTypes.DATE,
			allowNull: true
		},
		last_reset: {
			type: DataTypes.DATE,
			allowNull: true
		},
		timezone: {
			type: DataTypes.STRING,
			allowNull: true
		}
	}, {
		tableName: 'tbl_statistics_realtime'
	});
};
