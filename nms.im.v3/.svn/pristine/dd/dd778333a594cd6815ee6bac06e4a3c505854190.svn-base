/* jshint indent: 1 */

module.exports = function(sequelize, DataTypes) {
	return sequelize.define('utl_outbound_receiver', {
		id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			primaryKey: true,
			autoIncrement: true
		},
		url: {
			type: DataTypes.STRING,
			allowNull: true
		},
		request: {
			type: DataTypes.TEXT,
			allowNull: true
		},
		ip_address: {
			type: DataTypes.STRING,
			allowNull: true
		},
		date_created: {
			type: DataTypes.DATE,
			allowNull: true
		},
		reference: {
			type: DataTypes.STRING,
			allowNull: true
		},
		response: {
			type: DataTypes.TEXT,
			allowNull: true
		},
		failed: {
			type: DataTypes.BOOLEAN,
			allowNull: true,
			defaultValue: '0'
		},
		service_code: {
			type: DataTypes.STRING,
			allowNull: true
		},
		type: {
			type: DataTypes.STRING,
			allowNull: true
		},
		execution_info: {
			type: DataTypes.TEXT,
			allowNull: true
		}
	}, {
		tableName: 'utl_outbound_receiver'
	});
};
