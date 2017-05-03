/* jshint indent: 1 */

module.exports = function(sequelize, DataTypes) {
	return sequelize.define('tbl_logged_in_users', {
		id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			primaryKey: true,
			autoIncrement: true
		},
		user_id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			references: {
				model: 'tbl_users',
				key: 'id'
			}
		},
		status: {
			type: DataTypes.STRING,
			allowNull: false,
			defaultValue: 'parked'
		},
		time_parked: {
			type: DataTypes.DATE,
			allowNull: true
		},
		chat_time: {
			type: DataTypes.DATE,
			allowNull: true
		},
		last_ping: {
			type: DataTypes.DATE,
			allowNull: true
		},
		url: {
			type: DataTypes.STRING,
			allowNull: true
		},
		last_outbound: {
			type: DataTypes.DATE,
			allowNull: true
		},
		assigned: {
			type: DataTypes.INTEGER(2),
			allowNull: true,
			defaultValue: '0'
		},
		is_selected: {
			type: DataTypes.BOOLEAN,
			allowNull: true,
			defaultValue: '0'
		}
	}, {
		tableName: 'tbl_logged_in_users',
		timestamps: false,
		classMethods : {
			totalOnline : function(services, callback){
				service_query = ""
				if(services != undefined)
					service_query = " LEFT JOIN tbl_user_service us ON us.user_id = l.user_id WHERE us.service_id IN ("+services+")"

				query_string = "SELECT COUNT(DISTINCT l.id) as online FROM tbl_logged_in_users l" + service_query
				sequelize.query( query_string, { replacements: [], type: sequelize.QueryTypes.SELECT } )
					.then(function(rows){
						callback(rows)
					})
			}
		}
	});
};
