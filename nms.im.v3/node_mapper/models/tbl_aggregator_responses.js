/* jshint indent: 1 */

module.exports = function(sequelize, DataTypes) {
	return sequelize.define('tbl_aggregator_responses', {
		id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			primaryKey: true,
			autoIncrement: true
		},
		service_id: {
			type: DataTypes.BIGINT,
			allowNull: true,
			defaultValue: '0',
			references: {
				model: 'tbl_services',
				key: 'id'
			}
		},
		message_id: {
			type: DataTypes.BIGINT,
			allowNull: true,
			defaultValue: '0',
			references: {
				model: 'tbl_messages',
				key: 'id'
			}
		},
		status: {
			type: DataTypes.STRING,
			allowNull: true,
			defaultValue: 'pending'
		},
		target_response: {
			type: DataTypes.STRING,
			allowNull: true
		},
		target_url: {
			type: DataTypes.STRING,
			allowNull: true
		},
		meta: {
			type: DataTypes.TEXT,
			allowNull: true
		},
		last_attempt: {
			type: DataTypes.DATE,
			allowNull: true
		},
		attempts: {
			type: DataTypes.INTEGER(5),
			allowNull: true,
			defaultValue: '0'
		},
		execution: {
			type: DataTypes.DATE,
			allowNull: true
		},
		executed: {
			type: DataTypes.DATE,
			allowNull: true
		},
		timeout: {
			type: DataTypes.INTEGER(5),
			allowNull: true,
			defaultValue: '0'
		},
		date_created: {
			type: DataTypes.DATE,
			allowNull: true,
			defaultValue: '0000-00-00 00:00:00'
		}
	}, {
		tableName: 'tbl_aggregator_responses',
		timestamps: false
	});
};
