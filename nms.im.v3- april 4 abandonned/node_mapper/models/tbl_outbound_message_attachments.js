/* jshint indent: 1 */

module.exports = function(sequelize, DataTypes) {
	var TblOutboundMessageAttachments = sequelize.define('tbl_outbound_message_attachments', {
		id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			primaryKey: true,
			autoIncrement: true
		},
		file: {
			type: DataTypes.STRING,
			allowNull: true
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
		}
	}, {
		tableName: 'tbl_outbound_message_attachments',
		timestamps : false
	});

	return TblOutboundMessageAttachments
};
