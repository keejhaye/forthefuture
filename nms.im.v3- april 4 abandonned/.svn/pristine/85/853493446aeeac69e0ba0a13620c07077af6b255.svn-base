/* jshint indent: 1 */

module.exports = function(sequelize, DataTypes) {
	return sequelize.define('tbl_autoresponse_queue', {
		id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			primaryKey: true,
			autoIncrement: true
		},
		library_message_id: {
			type: DataTypes.BIGINT,
			allowNull: true
		},
		message_id: {
			type: DataTypes.BIGINT,
			allowNull: true,
			references: {
				model: 'tbl_messages',
				key: 'id'
			}
		},
		execution: {
			type: DataTypes.DATE,
			allowNull: true
		},
		executed: {
			type: DataTypes.DATE,
			allowNull: true
		},
		status: {
			type: DataTypes.STRING,
			allowNull: true,
			defaultValue: 'pending'
		},
		notes: {
			type: DataTypes.TEXT,
			allowNull: true
		},
		date_created: {
			type: DataTypes.DATE,
			allowNull: true
		}
	}, {
		tableName: 'tbl_autoresponse_queue'
	});
};
