/* jshint indent: 1 */

module.exports = function(sequelize, DataTypes) {
	var TblOutboundMessageAttachments = sequelize.import('tbl_outbound_message_attachments.js')
	var TblUsers = sequelize.import('tbl_users.js')

	var TblMessages = sequelize.define('tbl_messages', {
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
		conversation_id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			references: {
				model: 'tbl_conversations',
				key: 'id'
			}
		},
		status: {
			type: DataTypes.STRING,
			allowNull: true,
			defaultValue: 'normal'
		},
		message: {
			type: DataTypes.STRING,
			allowNull: true
		},
		bound_time: {
			type: DataTypes.DATE,
			allowNull: true
		},
		direction: {
			type: DataTypes.STRING,
			allowNull: true
		},
		assigned_time: {
			type: DataTypes.DATE,
			allowNull: true
		},
		duration: {
			type: DataTypes.INTEGER(11),
			allowNull: true,
			defaultValue: '0'
		},
		date_created: {
			type: DataTypes.DATE,
			allowNull: true,
			defaultValue: '0000-00-00 00:00:00'
		},
		fetched: {
			type: DataTypes.BOOLEAN,
			allowNull: true,
			defaultValue: '0'
		},
		additional_info: {
			type: DataTypes.TEXT,
			allowNull: true
		},
		is_billed: {
			type: DataTypes.BOOLEAN,
			allowNull: true,
			defaultValue: '1'
		},
		ip_address: {
			type: DataTypes.STRING,
			allowNull: true,
			defaultValue: '127.0.0.1'
		}
	}, 
	{
		tableName: 'tbl_messages',
		timestamps : false,
		classMethods : {
			assignMessageToUser : function(id, user_id, now, now_moment){
				console.log('assign msg to user message_id->' + id + ' to user_id->' + user_id)
				this.update({
				    status : 'assigned',
				    user_id : user_id,
				    assigned_time : now_moment,
				    fetched : 1
				  },
				  { where: { id : id } }
			  	)
				.then(function (result) { 
					// console.log('assignMessageToUser sync update success!')
				},
				function(rejectedPromiseError){
					console.log('assignMessageToUser rejectedPromiseError2')
					console.log(rejectedPromiseError)
				})

				rptStatement = 'UPDATE rpt_messages '+
													'SET assigned_time = ?, '+
													   'user_id = ?, '+
													   'fetched = 1 '+
													'WHERE message_id IN (?) ' +
													'ORDER BY id DESC '+
													'LIMIT 1'

				sequelize.query(rptStatement,{replacements: [now, user_id, id]})
				.then(function (result) { 
				}, function(rejectedPromiseError){
					console.log('rpt_messages rejectedPromiseError')
					console.log(rejectedPromiseError)
				})

			},

			getPreviousMessages : function(cid, limit, callback){
				this.hasMany(models.TblOutboundMessageAttachments,
			    {foreignKey: 'message_id',
			      as: 'attachments'})

				this.findAll({
					attributes: ['id', 'message', 'bound_time', 'direction', 'additional_info', 'status', 'user_id'],
					where: {conversation_id: cid},
					include: [
						{model: TblOutboundMessageAttachments, as: 'attachments'},
						{model: TblUsers, as: 'tbl_users'},],
					limit: limit,
					order: [['id', 'DESC']]
				})
				.then(function(rows){
					callback(rows)
				})
			},

			updateMessage : function(id, user_id, status, now, fullname){
				statement = 'UPDATE tbl_messages '+
							'SET assigned_time = IF(assigned_time IS NULL, ?, assigned_time), '+
							   'status = ?, '+
							   'user_id = ?, '+
							   'fetched = 1 '+
							'WHERE id IN (?)'

				values = [now, status, user_id, id]

				sequelize.query(statement,{replacements: values})
				.then(function (result) { 
				}, function(rejectedPromiseError){
					console.log('tbl_messages:updateMessage() rejectedPromiseError')
					console.log(rejectedPromiseError)
				})

				if(status == 'discard'){
					rptStatement = 'UPDATE rpt_messages SET status = ?, ' +
													'duration = TIMESTAMPDIFF(SECOND, assigned_time, ?), ' +
													'fullname = ? WHERE message_id = ?';

					sequelize.query(rptStatement,{replacements: [status, now, fullname, id]})
					.then(function (result) {
						console.log('Message ['+ id +'] has been discarded by ['+ user_id +']')
					}, function(rejectedPromiseError){
						console.log('rpt_messages:updateMessage() rejectedPromiseError')
						console.log(rejectedPromiseError)
					})
				}
			},

			handleMessages : function(ids, user_id, fullname, date){
				statement = 'UPDATE tbl_messages '+
					'SET status = ?, '+
					   'user_id = ?, '+
					   'duration = TIMESTAMPDIFF(SECOND, assigned_time, ?) ' +
					'WHERE id IN (?)'

				values = ['handled', user_id, date, ids]
				sequelize.query(statement,{replacements: values})
				.then(function (result) {
				}, function(rejectedPromiseError){
					console.log('tbl_messages:handleMessages() rejectedPromiseError')
					console.log(rejectedPromiseError)
				})

				//UPDATE RPT_MESSAGES RECORD
				rptstatement = 'UPDATE rpt_messages '+
					'SET user_id = ?, '+
					   'fullname = ?, '+
					   'duration = TIMESTAMPDIFF(SECOND, assigned_time, ?) ' +
					'WHERE message_id IN (?)'

				rptvalues = [user_id, fullname, date, ids]
				sequelize.query(rptstatement,{replacements: rptvalues})
				.then(function (result) { 
					console.log('Blacklisted subscriber messages has been handled')
				}, function(rejectedPromiseError){
					console.log('tbl_messages:handleMssages() rejectedPromiseError')
					console.log(rejectedPromiseError)
				})
			}

		}
	});
	
	// TblMessages.belongsTo(sequelize.import('tbl_conversations'), {foreign_key : 'conversation_id'})
	return TblMessages
};
