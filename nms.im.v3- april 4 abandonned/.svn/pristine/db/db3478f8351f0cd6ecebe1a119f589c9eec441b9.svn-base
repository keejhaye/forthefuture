/* jshint indent: 1 */

module.exports = function(sequelize, DataTypes) {
	return sequelize.define('tbl_services', {
		id: {
			type: DataTypes.BIGINT,
			allowNull: false,
			primaryKey: true,
			autoIncrement: true
		},
		code: {
			type: DataTypes.STRING,
			allowNull: true,
			defaultValue: '0000000000000000'
		},
		status: {
			type: DataTypes.STRING,
			allowNull: true,
			defaultValue: 'active'
		},
		name: {
			type: DataTypes.STRING,
			allowNull: false
		},
		description: {
			type: DataTypes.TEXT,
			allowNull: true
		},
		date_created: {
			type: DataTypes.DATE,
			allowNull: false,
			defaultValue: '0000-00-00 00:00:00'
		},
		nickname: {
			type: DataTypes.STRING,
			allowNull: true,
			defaultValue: 'a service'
		},
		color_theme: {
			type: DataTypes.STRING,
			allowNull: true,
			defaultValue: 'cccccc'
		},
		alert_sound_item: {
			type: DataTypes.INTEGER(5),
			allowNull: true,
			defaultValue: '2'
		},
		alert_short_delay: {
			type: DataTypes.INTEGER(5),
			allowNull: true,
			defaultValue: '100'
		},
		alert_long_delay: {
			type: DataTypes.INTEGER(5),
			allowNull: true,
			defaultValue: '3000'
		},
		alert_short_iterations: {
			type: DataTypes.INTEGER(5),
			allowNull: true,
			defaultValue: '10'
		},
		reply_timer: {
			type: DataTypes.INTEGER(5),
			allowNull: true,
			defaultValue: '0'
		},
		create_new_message: {
			type: DataTypes.BOOLEAN,
			allowNull: true,
			defaultValue: '1'
		},
		aggregator_username: {
			type: DataTypes.STRING,
			allowNull: true
		},
		aggregator_password: {
			type: DataTypes.STRING,
			allowNull: true
		},
		aggregator_url: {
			type: DataTypes.STRING,
			allowNull: true
		},
		allow_multiple_reply: {
			type: DataTypes.BOOLEAN,
			allowNull: true,
			defaultValue: '0'
		},
		min_char: {
			type: DataTypes.INTEGER(5),
			allowNull: true,
			defaultValue: '3'
		},
		max_char: {
			type: DataTypes.INTEGER(5),
			allowNull: true,
			defaultValue: '255'
		},
		auto_end_conversation: {
			type: DataTypes.BOOLEAN,
			allowNull: true,
			defaultValue: '0'
		},
		multiplier: {
			type: DataTypes.STRING,
			allowNull: true,
			defaultValue: '1.00'
		},
		enable_whitelist: {
			type: DataTypes.BOOLEAN,
			allowNull: true,
			defaultValue: '1'
		},
		timezone: {
			type: DataTypes.STRING,
			allowNull: true,
			defaultValue: '0.0'
		},
		message_limit: {
			type: DataTypes.INTEGER(11),
			allowNull: true,
			defaultValue: '0'
		},
		message_limit_action: {
			type: DataTypes.STRING,
			allowNull: true,
			defaultValue: 'discard'
		},
		message_limit_delay_period: {
			type: DataTypes.STRING,
			allowNull: true,
			defaultValue: '0'
		},
		message_limit_reset_period: {
			type: DataTypes.STRING,
			allowNull: true,
			defaultValue: '1 day'
		},
		persona_limit: {
			type: DataTypes.INTEGER(11),
			allowNull: true,
			defaultValue: '0'
		},
		persona_limit_action: {
			type: DataTypes.STRING,
			allowNull: true,
			defaultValue: 'discard'
		},
		persona_limit_reset_period: {
			type: DataTypes.STRING,
			allowNull: true,
			defaultValue: '1 day'
		},
		route: {
			type: DataTypes.STRING,
			allowNull: true,
			defaultValue: 'longestwaiting'
		},
		mapping: {
			type: DataTypes.STRING,
			allowNull: true,
			defaultValue: 'default'
		},
		listener_username: {
			type: DataTypes.STRING,
			allowNull: true,
			defaultValue: '0'
		},
		listener_password: {
			type: DataTypes.STRING,
			allowNull: true,
			defaultValue: '0'
		},
		is_dev: {
			type: DataTypes.INTEGER(1),
			allowNull: true,
			defaultValue: '0'
		},
		allow_anonymous_subscriber: {
			type: DataTypes.INTEGER(1),
			allowNull: true,
			defaultValue: '0'
		},
		has_membership: {
			type: DataTypes.BOOLEAN,
			allowNull: true,
			defaultValue: '0'
		},
		attach_image: {
			type: DataTypes.BOOLEAN,
			allowNull: true,
			defaultValue: '0'
		},
		email_inactivity: {
			type: DataTypes.STRING,
			allowNull: true,
			defaultValue: 'disabled'
		},
		last_inbound_time: {
			type: DataTypes.DATE,
			allowNull: true,
			defaultValue: '0000-00-00 00:00:00'
		},
		last_notification: {
			type: DataTypes.DATE,
			allowNull: true,
			defaultValue: '0000-00-00 00:00:00'
		},
		aggregator_ssl: {
			type: DataTypes.BOOLEAN,
			allowNull: true,
			defaultValue: '0'
		},
		subscriber_billing_idle_time: {
			type: DataTypes.INTEGER(11),
			allowNull: true,
			defaultValue: '120'
		},
		enable_subscriber_billing:{
			type: DataTypes.BOOLEAN,
			allowNull: true,
			defaultValue: '0'
		},
		enable_message_discard: {
			type: DataTypes.BOOLEAN,
			allowNull: true,
			defaultValue: '0'
		},
		platform:{
			type: DataTypes.STRING,
			allowNull: true,
			defaultValue: 'im'
		}
	}, {
		tableName: 'tbl_services',
		timestamps: false
	});
};
