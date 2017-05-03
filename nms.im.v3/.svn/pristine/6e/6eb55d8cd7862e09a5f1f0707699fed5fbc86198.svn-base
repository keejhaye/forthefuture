/* jshint indent: 1 */

module.exports = function(sequelize, DataTypes) {
	return sequelize.define('tbl_autoreminded_messages', {
		id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			primaryKey: true,
			autoIncrement: true
		},
		message_id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			references: {
				model: 'tbl_messages',
				key: 'id'
			}
		},
		autoreminder_id: {
			type: DataTypes.BIGINT,
			allowNull: false
		},
		time_reminded: {
			type: DataTypes.DATE,
			allowNull: true
		},
		date_created: {
			type: DataTypes.DATE,
			allowNull: false,
			defaultValue: '0000-00-00 00:00:00'
		},
		persona_id: {
			type: DataTypes.BIGINT,
			allowNull: true,
			references: {
				model: 'tbl_personas',
				key: 'id'
			}
		},
		subscriber_id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			references: {
				model: 'tbl_subscribers',
				key: 'id'
			}
		},
		last_outbound: {
			type: DataTypes.DATE,
			allowNull: true
		},
		status: {
			type: DataTypes.STRING,
			allowNull: true,
			defaultValue: 'pending'
		}
	}, {
		tableName: 'tbl_autoreminded_messages'
	});
};
