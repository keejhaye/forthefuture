/* jshint indent: 1 */

module.exports = function(sequelize, DataTypes) {
	return sequelize.define('tbl_persona_files', {
		id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			primaryKey: true,
			autoIncrement: true
		},
		persona_id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			references: {
				model: 'tbl_personas',
				key: 'id'
			}
		},
		path: {
			type: DataTypes.STRING,
			allowNull: true
		},
		path_thumb: {
			type: DataTypes.STRING,
			allowNull: true
		},
		file: {
			type: DataTypes.STRING,
			allowNull: false
		},
		description: {
			type: DataTypes.STRING,
			allowNull: false
		},
		date_created: {
			type: DataTypes.DATE,
			allowNull: true,
			defaultValue: '0000-00-00 00:00:00'
		}
	}, {
		tableName: 'tbl_persona_files'
	});
};
