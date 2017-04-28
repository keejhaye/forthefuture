/* jshint indent: 1 */

module.exports = function(sequelize, DataTypes) {
  return sequelize.define('tbl_canned_messages', {
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
    label: {
      type: DataTypes.STRING,
      allowNull: true
    },
    message: {
      type: DataTypes.STRING,
      allowNull: true
    },
    date_created: {
      type: DataTypes.DATE,
      allowNull: false,
      defaultValue: '0000-00-00'
    }
  }, {
    tableName: 'tbl_canned_messages',
    timestamps: false
  });
};
