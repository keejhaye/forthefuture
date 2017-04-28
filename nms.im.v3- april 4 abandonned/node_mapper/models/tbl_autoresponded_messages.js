/* jshint indent: 1 */

module.exports = function(sequelize, DataTypes) {
	return sequelize.define('tbl_autoresponded_messages', {
		id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			primaryKey: true,
			autoIncrement: true
		},
		message_id: {
			type: DataTypes.BIGINT,
			allowNull: true,
			references: {
				model: 'tbl_messages',
				key: 'id'
			}
		},
		library_message_id: {
			type: DataTypes.BIGINT,
			allowNull: true,
			references: {
				model: 'tbl_library_messages',
				key: 'id'
			}
		},
		subscriber_id: {
			type: DataTypes.BIGINT,
			allowNull: true,
			references: {
				model: 'tbl_subscribers',
				key: 'id'
			}
		},
		date_created: {
			type: DataTypes.DATE,
			allowNull: true
		},
		date_executed: {
			type: DataTypes.DATE,
			allowNull: true
		}
	}, {
		tableName: 'tbl_autoresponded_messages'
	});
};
