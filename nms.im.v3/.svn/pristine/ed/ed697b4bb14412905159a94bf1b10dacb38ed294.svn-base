/* jshint indent: 1 */

module.exports = function(sequelize, DataTypes) {
  return sequelize.define('tbl_conversation_notes', {
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
      allowNull: true,
      references: {
        model: 'tbl_conversations',
        key: 'id'
      }
    },
    comment: {
      type: DataTypes.STRING,
      allowNull: true
    },
    date_created: {
      type: DataTypes.DATE,
      allowNull: true
    },
    type: {
      type: DataTypes.STRING,
      allowNull: true
    },
  }, {
    tableName: 'tbl_conversation_notes',
    timestamps : false,
    classMethods : {
      updateNoteType : function(note){
        this.update({
            type : note.new_type
          },
          { where: { id : note.id } }
          )
        .then(function (result) {
        },
        function(rejectedPromiseError){
          console.log('updateNoteType rejectedPromiseError')
        })
      },
      getConversationsNotes : function(cids, callback){
        cids = cids.toString()
        getConversationsNotesQuery = 'SELECT id as note_id, conversation_id, comment, date_created, type ' +
                  'FROM tbl_conversation_notes WHERE conversation_id IN('+ cids +') ORDER BY id DESC LIMIT 20'

        sequelize.query(getConversationsNotesQuery,{ replacements: [], type: sequelize.QueryTypes.SELECT })
          .then(function(rows){
            callback(rows)
          }, function(rejectedPromiseError){
            console.log('tbl_conversations rejectedPromiseError')
            console.log(rejectedPromiseError)
          })
      }
    },
  });
};