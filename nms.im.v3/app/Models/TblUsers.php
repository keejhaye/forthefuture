<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class TblUsers extends Authenticatable {

    public $timestamps = false;

    protected $table = 'tbl_users';
    protected $fillable = ['username', 'password', 'passwordencrypt', 'email', 'role_id', 'firstname', 'lastname', 'status', 'logins', 'last_login', 'date_created', 'remember_token','platform'];
    protected $hidden = ['passwordencrypt', 'remember_token'];

    public function tblRoles() {
        return $this->belongsTo(\App\Models\TblRoles::class, 'role_id', 'id');
    }

    public function tblSubscribers() {
        return $this->belongsToMany(\App\Models\TblSubscribers::class, 'tbl_blacklist', 'user_id', 'subscriber_id');
    }

    public function tblBulletins() {
        return $this->belongsToMany(\App\Models\TblBulletins::class, 'tbl_bulletin_user', 'user_id', 'bulletin_id');
    }

//    public function tblConversationComments() {
//        return $this->belongsToMany(\App\Models\TblConversationComments::class, 'tbl_conversation_comment_users', 'user_id', 'conversation_comment_id');
//    }
//
//    public function tblConversations() {
//        return $this->belongsToMany(\App\Models\TblConversations::class, 'tbl_conversation_logs', 'user_id', 'conversation_id');
//    }
//
//    public function tblConversations() {
//        return $this->belongsToMany(\App\Models\TblConversations::class, 'tbl_conversation_messages_logs', 'user_id', 'conversation_id');
//    }

    public function tblConversations() {
        return $this->belongsToMany(\App\Models\TblConversations::class, 'tbl_discarded_conversations', 'user_id', 'conversation_id');
    }

    public function tblConversationsMod_tblPendingConversationsMod() {
        return $this->belongsToMany(\App\Models\TblConversationsMod::class, 'tbl_pending_conversations_mod', 'user_id', 'conversation_id');
    }

//    public function tblConversations() {
//        return $this->belongsToMany(\App\Models\TblConversations::class, 'tbl_timed_out_conversations', 'user_id', 'conversation_id');
//    }

    public function tblServices() {
        return $this->belongsToMany(\App\Models\TblServices::class, 'tbl_user_service', 'user_id', 'service_id');
    }

    public function tblActions() {
        return $this->hasMany(\App\Models\TblActions::class, 'operator_id', 'id');
    }

    public function tblBlacklist() {
        return $this->hasMany(\App\Models\TblBlacklist::class, 'user_id', 'id');
    }

    public function tblBulletinUser() {
        return $this->hasMany(\App\Models\TblBulletinUser::class, 'user_id', 'id');
    }

    public function tblConversationCommentUser() {
        return $this->hasMany(\App\Models\TblConversationCommentUser::class, 'user_id', 'id');
    }

    public function tblConversationComments() {
        return $this->hasMany(\App\Models\TblConversationComments::class, 'user_id', 'id');
    }

    public function tblConversationDuration() {
        return $this->hasMany(\App\Models\TblConversationDuration::class, 'user_id', 'id');
    }

    public function tblConversationDurationMod() {
        return $this->hasMany(\App\Models\TblConversationDurationMod::class, 'user_id', 'id');
    }

    public function tblConversationLogs() {
        return $this->hasMany(\App\Models\TblConversationLogs::class, 'user_id', 'id');
    }

    public function tblConversationMessagesLogs() {
        return $this->hasMany(\App\Models\TblConversationMessagesLogs::class, 'user_id', 'id');
    }

    public function tblConversationsMod() {
        return $this->hasMany(\App\Models\TblConversationsMod::class, 'user_id', 'id');
    }

    public function tblDiscardedConversations() {
        return $this->hasMany(\App\Models\TblDiscardedConversations::class, 'user_id', 'id');
    }

//    public function tblFlaggedMessagesOperatorId() {
//        return $this->hasMany(\App\Models\TblFlaggedMessages::class, 'operator_id', 'id');
//    }

    public function tblFlaggedMessagesUserId() {
        return $this->hasMany(\App\Models\TblFlaggedMessages::class, 'user_id', 'id');
    }

    public function tblFlaggedMessagesModUserId() {
        return $this->hasMany(\App\Models\TblFlaggedMessagesMod::class, 'user_id', 'id');
    }

    public function tblFlaggedMessagesModOperatorId() {
        return $this->hasMany(\App\Models\TblFlaggedMessagesMod::class, 'operator_id', 'id');
    }

    public function tblIgnoredConversationLogs() {
        return $this->hasMany(\App\Models\TblIgnoredConversationLogs::class, 'user_id', 'id');
    }

    public function tblLoggedInUsers() {
        return $this->hasMany(\App\Models\TblLoggedInUsers::class, 'user_id', 'id');
    }

    public function tblMessages() {
        return $this->hasMany(\App\Models\TblMessages::class, 'user_id', 'id');
    }

    public function tblMessagesMod() {
        return $this->hasMany(\App\Models\TblMessagesMod::class, 'user_id', 'id');
    }

    public function tblPendingConversations() {
        return $this->hasMany(\App\Models\TblPendingConversations::class, 'user_id', 'id');
    }

    public function tblPendingConversationsMod() {
        return $this->hasMany(\App\Models\TblPendingConversationsMod::class, 'user_id', 'id');
    }

    public function tblSubscriberBilling() {
        return $this->hasMany(\App\Models\TblSubscriberBilling::class, 'user_id', 'id');
    }

    public function tblSubscriberNotes() {
        return $this->hasMany(\App\Models\TblSubscriberNotes::class, 'user_id', 'id');
    }

    public function tblTimedOutConversations() {
        return $this->hasMany(\App\Models\TblTimedOutConversations::class, 'user_id', 'id');
    }

    public function tblUserActivities() {
        return $this->hasMany(\App\Models\TblUserActivities::class, 'user_id', 'id');
    }

    public function tblUserConversationLogs() {
        return $this->hasMany(\App\Models\TblUserConversationLogs::class, 'user_id', 'id');
    }

    public function tblUserLogs() {
        return $this->hasMany(\App\Models\TblUserLogs::class, 'user_id', 'id');
    }

    public function tblUserServices() {
        return $this->hasMany(\App\Models\TblUserService::class, 'user_id', 'id');
    }


    public function getAuthPassword() {
        return $this->passwordencrypt;
    }

    /**
    *
    * @param int $format 1 - first last<br/>
    *                    2 - last, first<br/>
    *                    3 - last, first (username - role)<br/>
    * @return string
    */
    public function full_name($format = 0) {
        switch ($format) {
            case 1:
                return $this->firstname . $this->lastname . sprintf("%04d", ($this->id));
            case 2:
                return $this->firstname . " " . $this->lastname;
            case 3:
                return $this->lastname . ", " . $this->firstname;
            case 4:
                return $this->username;
            case 5:
                return $this->firstname . " (" . $this->TblRoles->name . ")";
            case 6:
                return $this->firstname . " " . $this->lastname . " (" . $this->username . ")";
            case 7:
                return $this->username .  " (" . $this->TblRoles->name . ")";
            default:
                return $this->firstname . " " . $this->lastname . " (" . $this->TblRoles->name . ")";
        }
    }
    
    public function search(){
        $results = TblUsers::orderBy('username')->get();
        return $results;
    }

    public static function search_user($params){
        $query = \DB::table('tbl_users AS u');

        $user_level_operation = $params['user_level_operation'] ?? '<';

        if(isset($params['level']) && !empty($params['level']))
            $query->leftJoin('tbl_roles AS gr', 'gr.id', '=', 'u.role_id')
                  ->where('gr.level', $user_level_operation, $params['level']);

        if(isset($params['service_id']) && !empty($params['service_id']))
            $query->leftJoin('tbl_user_service AS us', 'us.user_id', '=', 'u.id')
                  ->where('us.service_id', $params['service_id']);

        if(isset($params['id']) && !empty($params['id']))
            $query->where('u.id', $params['id']);

        if(isset($params['group_id']) && !empty($params['group_id']))
            $query->where('u.role_id', $params['role_id']);

        if(isset($params['status']) && ($params['status'] != ""))
            $query->where('u.status', $params['status']);

        if(isset($params['keyword']) && ($params['keyword'] != ""))
            $query->where(function ($query) use ($params){
                $query->where('u.username', 'LIKE', "%".$params['keyword']."%")
                      ->orWhere('u.firstname', 'LIKE',  "%".$params['keyword']."%")
                      ->orWhere('u.lastname', 'LIKE',  "%".$params['keyword']."%");
            });

        if(isset($params['limit']) && ($params['limit'] != ""))
            $query->limit($params['limit']);

        return $query;
    }

    public static function get_bot(){
        $bot = TblUsers::where('username', '=', 'bot')->get()->first();
        return $bot;
    }
}


