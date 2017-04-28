/* jshint indent: 1 */

module.exports = function(sequelize, DataTypes) {
	return sequelize.define('tbl_flagged_message_deductions', {
		id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			primaryKey: true,
			autoIncrement: true
		},
		flagged_message_id: {
			type: DataTypes.BIGINT,
			allowNull: true,
			references: {
				model: 'tbl_flagged_messages',
				key: 'id'
			}
		},
		deduction: {
			type: DataTypes.INTEGER(11),
			allowNull: true,
			defaultValue: '0'
		},
		date_moderated: {
			type: DataTypes.DATE,
			allowNull: true
		}
	}, {
		tableName: 'tbl_flagged_message_deductions'
	});
};
