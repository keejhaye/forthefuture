/* jshint indent: 1 */

module.exports = function(sequelize, DataTypes) {
	var TblUsers =  sequelize.define('tbl_users', {
		id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			primaryKey: true,
			autoIncrement: true
		},
		username: {
			type: DataTypes.STRING,
			allowNull: true
		},
		password: {
			type: DataTypes.STRING,
			allowNull: true
		},
		passwordencrypt: {
			type: DataTypes.STRING,
			allowNull: true
		},
		email: {
			type: DataTypes.STRING,
			allowNull: true
		},
		role_id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			references: {
				model: 'tbl_roles',
				key: 'id'
			}
		},
		firstname: {
			type: DataTypes.STRING,
			allowNull: true
		},
		lastname: {
			type: DataTypes.STRING,
			allowNull: true
		},
		status: {
			type: DataTypes.STRING,
			allowNull: true,
			defaultValue: 'active'
		},
		logins: {
			type: DataTypes.INTEGER(11),
			allowNull: true,
			defaultValue: '0'
		},
		last_login: {
			type: DataTypes.DATE,
			allowNull: true
		},
		date_created: {
			type: DataTypes.DATE,
			allowNull: true
		},
		remember_token: {
			type: DataTypes.STRING,
			allowNull: true
		},
		platform: {
			type: DataTypes.STRING(11),
			allowNull: true,
			defaultValue: 'im'
		}
	}, {
		tableName: 'tbl_users',
		timestamps: false,
		classMethods : {
			user_permission : function(permission, uid){
				this.findOne({
					where : {id: uid},
					include: {
		        model: 'tbl_roles',
		        as: 'role'
		      }
				})
				.then(function(data){
					console.log(data)
				})
			}
		}
	}); // end of tabke definition

	return TblUsers
}; // end of module
