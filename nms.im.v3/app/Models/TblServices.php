<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class TblServices extends Model {

    public $timestamps = false;

    protected $table = 'tbl_services';
    protected $fillable = ['code', 'status', 'name', 'description', 'date_created', 'nickname', 'color_theme', 'alert_sound_item', 'alert_short_delay', 'alert_long_delay', 'alert_short_iterations', 'reply_timer', 'create_new_message', 'aggregator_username', 'aggregator_password', 'aggregator_url', 'allow_multiple_reply', 'min_char', 'max_char', 'auto_end_conversation', 'multiplier', 'enable_whitelist', 'timezone', 'message_limit', 'message_limit_action', 'message_limit_delay_period', 'message_limit_reset_period', 'persona_limit', 'persona_limit_action', 'persona_limit_reset_period', 'subscriber_billing_idle_time', 'enable_subscriber_billing', 'route', 'mapping', 'listener_username', 'listener_password', 'is_dev', 'allow_anonymous_subscriber', 'has_membership', 'attach_image', 'email_inactivity', 'last_inbound_time', 'last_notification', 'aggregator_ssl','enable_subscriber_billing','subscriber_billing_idle_time','enable_message_discard','platform'];


    public function tblMessages() {
        return $this->belongsToMany(\App\Models\TblMessages::class, 'tbl_aggregator_responses', 'service_id', 'message_id');
    }

    public function tblMessagesMods() {
        return $this->belongsToMany(\App\Models\TblMessagesMod::class, 'tbl_aggregator_responses_mod', 'service_id', 'message_id');
    }

    public function tblBulletins() {
        return $this->belongsToMany(\App\Models\TblBulletins::class, 'tbl_bulletin_service', 'service_id', 'bulletin_id');
    }

    public function tblLibraries() {
        return $this->belongsToMany(\App\Models\TblLibraries::class, 'tbl_libraries_services', 'service_id', 'library_id');
    }

    public function tblGroups() {
        return $this->belongsToMany(\App\Models\TblGroups::class, 'tbl_service_group', 'service_id', 'group_id');
    }

    public function tblUsers() {
        return $this->belongsToMany(\App\Models\TblUsers::class, 'tbl_user_service', 'service_id', 'user_id');
    }

    public function tblAggregatorResponses() {
        return $this->hasMany(\App\Models\TblAggregatorResponses::class, 'service_id', 'id');
    }

    public function tblAggregatorResponsesMod() {
        return $this->hasMany(\App\Models\TblAggregatorResponsesMod::class, 'service_id', 'id');
    }

    public function tblAutoDiscard() {
        return $this->hasMany(\App\Models\TblAutoDiscard::class, 'service_id', 'id');
    }

    public function tblAutoreminders() {
        return $this->hasMany(\App\Models\TblAutoreminders::class, 'service_id', 'id');
    }

    public function tblAutoresponses() {
        return $this->hasMany(\App\Models\TblAutoresponses::class, 'service_id', 'id');
    }

    public function tblBulletinService() {
        return $this->hasMany(\App\Models\TblBulletinService::class, 'service_id', 'id');
    }

    public function tblCannedMessages() {
        return $this->hasMany(\App\Models\TblCannedMessages::class, 'service_id', 'id');
    }

    public function tblConversations() {
        return $this->hasMany(\App\Models\TblConversations::class, 'service_id', 'id');
    }

    public function tblConversationsMod() {
        return $this->hasMany(\App\Models\TblConversationsMod::class, 'service_id', 'id');
    }

    public function tblLibrariesServices() {
        return $this->hasMany(\App\Models\TblLibrariesServices::class, 'service_id', 'id');
    }

    public function tblPersonas() {
        return $this->hasMany(\App\Models\TblPersonas::class, 'service_id', 'id');
    }

    public function tblRules() {
        return $this->hasMany(\App\Models\TblRules::class, 'service_id', 'id');
    }

    public function tblServiceGroup() {
        return $this->hasMany(\App\Models\TblServiceGroup::class, 'service_id', 'id');
    }

    public function tblServiceSubscriberLimit() {
        return $this->hasMany(\App\Models\TblServiceSubscriberLimit::class, 'service_id', 'id');
    }

    public function tblSubscribers() {
        return $this->hasMany(\App\Models\TblSubscribers::class, 'service_id', 'id');
    }

    public function tblUserServices() {
        return $this->hasMany(\App\Models\TblUserService::class, 'service_id', 'id');
    }

    public function tblWhitelist() {
        return $this->hasMany(\App\Models\TblWhitelist::class, 'service_id', 'id');
    }

    public static function get_services(){
        $query = \TblServices::
                    select('tbl_services.*', DB::raw('COUNT(tbl_logged_in_users.id) as assigned'))
                    ->leftJoin('tbl_user_service','tbl_user_service.service_id','=','tbl_services.id')
                    ->leftJoin('tbl_users','tbl_users.id','=','tbl_user_service.user_id')
                    ->leftJoin('tbl_logged_in_users', 'tbl_users.id','=','tbl_logged_in_users.user_id')
                    ->where('tbl_logged_in_users.status', 'chat')
                    ->groupBy('tbl_services.id')
                    ->get();
        return $query;
    }

    public static function search($params){
        $query = \DB::table('tbl_services AS s');

        if(isset($params['keyword']) && ($params['keyword'] != ""))
            $query->where(function ($query) use ($params){
                $query->where('s.name', 'LIKE', "%".$params['keyword']."%")
                      ->orWhere('s.nickname', 'LIKE',  "%".$params['keyword']."%");
            });

        if(isset($params['limit']) && ($params['limit'] != ""))
            $query->limit($params['limit']);

        return $query;
    }

}
