/* jshint indent: 1 */

module.exports = function(sequelize, DataTypes) {
	return sequelize.define('utl_test_messages', {
		id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			primaryKey: true,
			autoIncrement: true
		},
		message: {
			type: DataTypes.STRING,
			allowNull: true
		}
	}, {
		tableName: 'utl_test_messages'
	});
};
