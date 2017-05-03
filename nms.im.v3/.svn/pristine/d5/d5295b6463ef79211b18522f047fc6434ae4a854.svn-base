/* jshint indent: 1 */

module.exports = function(sequelize, DataTypes) {
	return sequelize.define('tbl_library_messages', {
		id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			primaryKey: true,
			autoIncrement: true
		},
		library_id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			references: {
				model: 'tbl_libraries',
				key: 'id'
			}
		},
		type: {
			type: DataTypes.STRING,
			allowNull: true
		},
		message: {
			type: DataTypes.TEXT,
			allowNull: true
		},
		date_created: {
			type: DataTypes.DATE,
			allowNull: false,
			defaultValue: '0000-00-00 00:00:00'
		},
		position: {
			type: DataTypes.INTEGER(4),
			allowNull: false
		}
	}, {
		tableName: 'tbl_library_messages'
	});
};
