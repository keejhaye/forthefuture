/* jshint indent: 1 */

module.exports = function(sequelize, DataTypes) {
	return sequelize.define('tbl_inbound_message_attachment', {
		id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			primaryKey: true,
			autoIncrement: true
		},
		path: {
			type: DataTypes.STRING,
			allowNull: true
		},
		message_id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			defaultValue: '0',
			references: {
				model: 'tbl_messages',
				key: 'id'
			}
		},
		date_created: {
			type: DataTypes.DATE,
			allowNull: true
		},
		expire_on: {
			type: DataTypes.DATE,
			allowNull: true
		},
		file: {
			type: DataTypes.STRING,
			allowNull: true,
			defaultValue: ''
		}
	}, {
		tableName: 'tbl_inbound_message_attachment'
	});
};
