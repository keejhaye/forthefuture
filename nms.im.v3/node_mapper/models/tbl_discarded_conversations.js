/* jshint indent: 1 */

module.exports = function(sequelize, DataTypes) {
	return sequelize.define('tbl_discarded_conversations', {
		id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			primaryKey: true,
			autoIncrement: true
		},
		conversation_id: {
			type: DataTypes.BIGINT,
			allowNull: true,
			references: {
				model: 'tbl_conversations',
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
			allowNull: true
		}
	}, {
		tableName: 'tbl_discarded_conversations'
	});
};
