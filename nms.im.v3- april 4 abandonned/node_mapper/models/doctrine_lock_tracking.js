/* jshint indent: 1 */

module.exports = function(sequelize, DataTypes) {
	return sequelize.define('doctrine_lock_tracking', {
		object_type: {
			type: DataTypes.STRING,
			allowNull: false,
			primaryKey: true
		},
		object_key: {
			type: DataTypes.STRING,
			allowNull: false,
			primaryKey: true
		},
		user_ident: {
			type: DataTypes.STRING,
			allowNull: false
		},
		timestamp_obtained: {
			type: DataTypes.BIGINT,
			allowNull: false
		}
	}, {
		tableName: 'doctrine_lock_tracking'
	});
};
