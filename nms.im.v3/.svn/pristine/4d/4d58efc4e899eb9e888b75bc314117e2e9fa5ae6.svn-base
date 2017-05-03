/* jshint indent: 1 */

module.exports = function(sequelize, DataTypes) {
	return sequelize.define('utl_request_limiter', {
		id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			primaryKey: true,
			autoIncrement: true
		},
		ip_address: {
			type: DataTypes.STRING,
			allowNull: true
		},
		type: {
			type: DataTypes.STRING,
			allowNull: true
		},
		requests: {
			type: DataTypes.INTEGER(5),
			allowNull: true
		},
		last_execution: {
			type: DataTypes.DATE,
			allowNull: true
		},
		reset_on: {
			type: DataTypes.DATE,
			allowNull: true
		}
	}, {
		tableName: 'utl_request_limiter'
	});
};
