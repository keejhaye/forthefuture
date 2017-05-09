<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class Context extends Controller {

    public function index() {
        return view('panel/ContextContent')->with('page_title', 'Services');
    }

    //  -- Kris' changes 5/8/2017
    public function getServicesByPage($offset, $limit){
        $personas = \TblServices::orderBy('id', 'asc')->skip($offset)->take($limit); 
        return $personas->get(); 
    }

    public function countAllServices(){
        $cnt = \TblServices::orderBy('id', 'asc');
        return $cnt->count();
    }
    //  -- Kris' changes 5/8/2017

    public function search(Request $request){
        return \TblServices::orderBy('id', 'asc')->get();
    }

    public function services($id = null) {
        if ($id == null) {
            return \TblServices::orderBy('id', 'asc')->get();
        } else {
           //  $result->whitelist = \App\Models\TblWhitelist::where('service_id', $request->id)->lists('ip_address')->toArray();
            return $this->show($id);
        }
    }

    public function show($id) {
        return \TblServices::find($id);
    }

    public function add(Request $request) {

        if(!$request->input('name')){
            return 1;
        }

        $pound = chr(163);
        $color = str_replace('#','',$request->input('color_theme'));
        
        $service = new \TblServices;
        $service->code = $request->input('code');
        $service->name = $request->input('name');
        $service->status = $request->input('status');
        $service->description = $request->input('description');
        $service->nickname = $request->input('nickname');
        $service->color_theme = $color;
        $service->alert_sound_item = $request->input('alert_sound_item');
        $service->alert_short_delay = $request->input('alert_short_delay');
        $service->alert_long_delay = $request->input('alert_long_delay');
        $service->alert_short_iterations = $request->input('alert_short_iterations');
        $service->reply_timer = $request->input('reply_timer');
        $service->create_new_message = $request->input('create_new_message');
        $service->aggregator_username = $request->input('aggregator_username');
        $service->aggregator_password = $request->input('aggregator_password');
        $service->aggregator_url = $request->input('aggregator_url');
        $service->allow_multiple_reply = $request->input('allow_multiple_reply');
        $service->allow_blacklist = $request->input('allow_blacklist');
        $service->min_char = $request->input('min_char');
        $service->max_char = $request->input('max_char');
        $service->auto_end_conversation = $request->input('auto_end_conversation');
        $service->multiplier = $request->input('multiplier');
        $service->enable_whitelist = $request->input('enable_whitelist');
        $service->timezone = $request->input('timezone');
        $service->message_limit = $request->input('message_limit');
        $service->message_limit_action = $request->input('message_limit_action');
        $service->message_limit_delay_period = $request->input('message_limit_delay_period');
        $service->message_limit_reset_period = $request->input('message_limit_reset_period');
        $service->persona_limit = $request->input('persona_limit');
        $service->persona_limit_action = $request->input('persona_limit_action');
        $service->persona_limit_reset_period = $request->input('subscriber_billing_idle_time');
        $service->route = $request->input('route');
        $service->listener_username = $request->input('listener_username');
        $service->mapping = $request->input('mapping');
        $service->listener_password = $request->input('listener_password');
        $service->is_dev = $request->input('is_dev');
        $service->allow_anonymous_subscriber = $request->input('allow_anonymous');
        $service->has_membership = $request->input('has_membership');
        $service->attach_image =$request->input('attach_image');
        $service->email_inactivity =$request->input('email_inactivity');
        $service->last_inbound_time = $request->input('last_inbound_times');
        $service->last_notification =$request->input('last_notification');
        $service->aggregator_ssl = $request->input('aggregator_ssl');
        $service->outbound_link_filter = $request->input('outbound_link_filter');
        $service->allow_url = $request->input('allow_url');
        $service->date_created = gmdate('Y-m-d H:i:s');
        $service->save();


        $old_data = "-";
        $new_data = $service->toArray();    
        \LogHelper::user_activity("services", $service->name, 'add', $old_data, $new_data);

        $this->update_node_data($service, 'new_service');
        return 0;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id) {
        $service = \TblServices::find($id);
        $old_data = $service->toArray();
        $color = str_replace('#','',$request->input('color_theme'));
        $service->status = $request->input('status');
        $service->description = $request->input('description');
        $service->nickname = $request->input('nickname');
        $service->color_theme = $color;
        $service->alert_sound_item = $request->input('alert_sound_item');
        $service->alert_short_delay = $request->input('alert_short_delay');
        $service->alert_long_delay = $request->input('alert_long_delay');
        $service->alert_short_iterations = $request->input('alert_short_iterations');
        $service->reply_timer = $request->input('reply_timer');
        $service->create_new_message = $request->input('create_new_message');
        $service->aggregator_username = $request->input('aggregator_username');
        $service->aggregator_password = $request->input('aggregator_password');
        $service->aggregator_url = $request->input('aggregator_url');
        $service->allow_multiple_reply = $request->input('allow_multiple_reply');
        $service->allow_blacklist = $request->input('allow_blacklist');
        $service->min_char = $request->input('min_char');
        $service->max_char = $request->input('max_char');
        $service->auto_end_conversation = $request->input('auto_end_conversation');
        $service->multiplier = $request->input('multiplier');
        $service->enable_whitelist = $request->input('enable_whitelist');
        $service->timezone = $request->input('timezone');
        $service->message_limit = $request->input('message_limit');
        $service->message_limit_action = $request->input('message_limit_action');
        $service->message_limit_delay_period = $request->input('message_limit_delay_period');
        $service->message_limit_reset_period = $request->input('message_limit_reset_period');
        $service->persona_limit = $request->input('persona_limit');
        $service->persona_limit_action = $request->input('persona_limit_action');
        $service->persona_limit_reset_period = $request->input('subscriber_billing_idle_time');
        $service->route = $request->input('route');
        $service->listener_username = $request->input('listener_username');
        $service->mapping = $request->input('mapping');
        $service->listener_password = $request->input('listener_password');
        $service->is_dev = $request->input('is_dev');
        $service->allow_anonymous_subscriber = $request->input('allow_anonymous');
        $service->has_membership = $request->input('has_membership');
        $service->attach_image =$request->input('attach_image');
        $service->email_inactivity =$request->input('email_inactivity');
        $service->last_inbound_time = $request->input('last_inbound_times');
        $service->last_notification =$request->input('last_notification');
        $service->aggregator_ssl = $request->input('aggregator_ssl');
        $service->outbound_link_filter = $request->input('outbound_link_filter');
        $service->allow_url = $request->input('allow_url');

        $service->save();
        $new_data = $request->input();    
        \LogHelper::user_activity("services", $service->name, 'update', $old_data, $new_data);  

        if($service->status == 1){
         \TblUserService::where('service_id', $service->id)->delete();
        }

        $this->update_node_data($service, 'update_service');
        return 0;
    }

    protected function update_node_data($service, $action){
        $service_update = [
            'action' => $action,
            'service' => [
                'id' => $service->id,
                'name' => $service->name,
                'status' => $service->status,
                'allow_multiple_reply' => $service->allow_multiple_reply,
                'allow_blacklist' => $service->allow_blacklist,
                'message_limit' => $service->message_limit,
                'min_char' => $service->min_char,
                'max_char' => $service->max_char,
                'color_theme' => $service->color_theme,
                'timezone' => $service->timezone,
                ]
        ];
        \Redis::publish('update-data', json_encode($service_update));
    }
}
