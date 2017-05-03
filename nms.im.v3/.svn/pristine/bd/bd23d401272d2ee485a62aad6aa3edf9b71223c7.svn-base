/* jshint indent: 1 */

module.exports = function(sequelize, DataTypes) {
	return sequelize.define('tbl_autoresponses', {
		id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			primaryKey: true,
			autoIncrement: true
		},
		service_id: {
			type: DataTypes.BIGINT,
			allowNull: true,
			references: {
				model: 'tbl_services',
				key: 'id'
			}
		},
		persona_id: {
			type: DataTypes.BIGINT,
			allowNull: true,
			references: {
				model: 'tbl_personas',
				key: 'id'
			}
		},
		delay: {
			type: DataTypes.INTEGER(11),
			allowNull: true,
			defaultValue: '0'
		},
		message_condition: {
			type: DataTypes.STRING,
			allowNull: true,
			defaultValue: 'first message'
		},
		keyword: {
			type: DataTypes.STRING,
			allowNull: true
		},
		date_created: {
			type: DataTypes.DATE,
			allowNull: true
		},
		library_id: {
			type: DataTypes.BIGINT,
			allowNull: true,
			references: {
				model: 'tbl_libraries',
				key: 'id'
			}
		}
	}, {
		tableName: 'tbl_autoresponses'
	});
};
