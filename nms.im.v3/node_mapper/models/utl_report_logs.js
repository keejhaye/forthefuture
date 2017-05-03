/* jshint indent: 1 */

module.exports = function(sequelize, DataTypes) {
	UtlReportLogs = sequelize.define('utl_report_logs', {
		id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			primaryKey: true,
			autoIncrement: true
		},
		user_id: {
			type: DataTypes.BIGINT,
			allowNull: true,
			references: {
				model: 'tbl_users',
				key: 'id'
			}
		},
		username: {
			type: DataTypes.STRING,
			allowNull: true
		},
		report: {
			type: DataTypes.STRING,
			allowNull: true
		},
		time_started: {
			type: DataTypes.DATE,
			allowNull: true
		},
		time_ended: {
			type: DataTypes.DATE,
			allowNull: true
		},
		date_created: {
			type: DataTypes.DATE,
			allowNull: true
		},
		from_date: {
			type: DataTypes.DATE,
			allowNull: true
		},
		to_date: {
			type: DataTypes.DATE,
			allowNull: true
		},
		timezone: {
			type: DataTypes.STRING,
			allowNull: true
		}
	}, {
		tableName: 'utl_report_logs',
		timestamps: false,
		classMethods: {
			addLog: function(data){
				this.create({
					user_id: data.user_id,
					username: data.username,
					report: data.display,
					time_started: data.log_start,
					time_ended: data.log_end,
					date_created: data.date_created,
					from_date: data.sdate,
					to_date: data.edate,
					timezone: data.timezone,
				})
			}
		}
	});
	return UtlReportLogs
};
