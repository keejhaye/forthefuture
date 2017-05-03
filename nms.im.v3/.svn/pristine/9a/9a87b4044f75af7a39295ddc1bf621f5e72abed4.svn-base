<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
header('Cache-Control: no-cache');
header('Pragma: no-cache');

use App\Libraries\Spamfilter;
use App\Libraries\Mailsender;
use Route;
use App\Models\TblOutboundMessageSpamLogs;

class Chat extends Controller {
    public function index(Request $request) {
        //clock()->startEvent('event_name', 'Event description.');
        echo view('panel.chat.main', [
            'page_title' => "<% (pendingCount > 0) ? '('+pendingCount+')' : ''  %>"
        ]);
        $user_id = session('user.id');
        //clock()->endEvent('event_name');
        $this->check_if_logged_in($user_id);
        $logged_in = \TblLoggedInUsers::whereUserId($user_id)->first();
        if($logged_in){
        $logged_in->chat_time = gmdate('Y-m-d H:i:s');
        $logged_in->time_parked = (NULL);
        $logged_in->status = 'chat';
        $logged_in->save();
    
        }
    }

    public function status() {
        return view('panel.chat.status');
    }

    public function status2() {
        return view('panel.chat.status2');
    }

    public function admin() {
        return view('panel.chat.admin');
    }


    protected function validate_message($post, $servId){

        $serviceInfo = \TblServices::where('id', $servId)->first();

        if ($serviceInfo->allow_url) {
            $spamLib = new Spamfilter();
            $filtered = $spamLib::filter($post);

            if(!empty($filtered)){

                $userId = session('user.id');
                $outboundSPamLogs = new TblOutboundMessageSpamLogs;
                $outboundSPamLogs->user_id = $userId;
                $outboundSPamLogs->ip_address = \Request::ip();
                $outboundSPamLogs->message = $post;
                $outboundSPamLogs->date_created = gmdate("Y-m-d H:i:s");
                $outboundSPamLogs->save();

                $users = \TblUsers::where('id', $userId)->get();
                $viewData = array(
                        "username" => $users[0]['username'],
                        "post" => $post,
                        "filtered" => $filtered,
                        "ipAddress" => \Request::ip(),
                        "incidentDate" => gmdate("Y-m-d H:i:s")
                    );
                $sendData = array(
                        "emailFrom" => "kbd@newmediastaff.com",
                        "fromDesc" => "Outbound msg spam alert",
                        "emailTo" => "dellakristofferjohn@gmail.com",
                        "toDesc" => "TEST TO",
                        "mailSubj" => "Outbound msg spam alert - ".gmdate("Y-m-d H:i:s"),
                        "viewForm" => "emails.outboundMessageSpam"
                    );

                if (!empty(env('MAIL_USERNAME')) && !empty(env('MAIL_PASSWORD'))) {
                    $emailSender = new Mailsender();
                    $emailSender::send_mail($viewData, $sendData);
                }
                \TblUsers::where('id', $userId)->update(['status' => 'inactive']);
                $request = Request::create('auth/logout', 'GET', array());
                return Route::dispatch($request)->logout();
                die();
            }
        }
    }

    public function reply(Request $request) {
        // die(print_r($_FILES, 1));
        clock()->startEvent('event_name', 'Event description.');
        $post = $request->input();


        $post['details'] = json_decode($post['details'], true);

        if (!isset($_FILES)) {
            $image = array();
        } else {
            $image = $request->file('images');
            $post['path'] = config('application.upload_dir');
        }

        $user = \TblUsers::find(\Session::get('user.id'));
        $inbound = \TblMessages::get_relational_inbound_object($post["details"]["subscriber_last_message_id"]);
        $outbound_time = gmdate("Y-m-d H:i:s");

        //validate outbound message if spam
        $this->validate_message($post["message"], $inbound->service_id);

        $outboundLib = new \Outbound();
        $outbound = $outboundLib->send($post["details"], $post["message"], $user, $outbound_time, $inbound, $image, $post['path'], $post['expiry']);
        //$this->add_to_outbound_queue($inbound, $outbound, $post["details"], $outbound_time);

        $attachments = array();
        if(sizeof($outbound->TblOutboundMessageAttachments) > 0)
        foreach ($outbound->TblOutboundMessageAttachments as $row) {
            $attachments[] = array(
                'id' => $row->id,
                'path' => $row->path,
                'file' => $row->file,
                'date_created' => $row->date_created,
            );
        }

        $response = array(
            'id' => $outbound->id,
            'date' => date("Y-m-d H:i:s", strtotime('+8 hours')),
            'conversation_id' => $post["details"]['conversation_id'],
            'content' => $post["message"],
            'attachments' => $attachments,
        );
        echo json_encode($response, JSON_PRETTY_PRINT);

        $avg_response = \RptMessages::operator_avg_response($user->id);
        $avg = gmdate("H:i:s", $avg_response[0]['total_duration']);

        $response['fullname'] = $user->full_name(2);
        $response['username'] = $user->username;
        $response['persona_id'] = $outbound->Inbound->persona_id;
        $response['persona_name'] = $outbound->Inbound->persona_name;
        $response['subscriber_id'] = $outbound->Inbound->subscriber_id;
        $response['subscriber_name'] = $outbound->Inbound->subscriber_name;
        $response['service_id'] = $outbound->Inbound->service_id;
        $response['service_name'] = $outbound->Inbound->service_name;
        $response['user_id'] = $user->id;
        $response['content'] = $post['message'];
        $response['attachments'] = $attachments;
        $response['chat_time'] = $avg;

        $outbound_count = session('outbound_count');
        session()->set('chat_time',$avg);
        session()->set('outbound_count', $outbound_count + 1);
        session()->save();
        
        $user_stat = json_decode(\Redis::get('user_stat:'.$user->id));
        $user_stat->outbound_count = $outbound_count + 1;
        $user_stat->chat_time = $avg;
        \Redis::set('user_stat:'.$user->id, json_encode($user_stat));
        \Redis::publish('send-outbound', json_encode($response, JSON_PRETTY_PRINT));

        //\TblSubscriberBilling::end_subscriber_billing($post["details"]['conversation_id'], $user);
        \TblMessageInterval::log_message_interval($inbound, $outbound, $user);
        clock()->endEvent('event_name');
    }

    public function flag_message(Request $request) {
        $data = $request->input();
        $flagged_message = new \TblFlaggedMessages();
        $flagged_message->message_id = $data['message_id'];
        $flagged_message->operator_id = \Session::get('user.id');
        $flagged_message->date_flagged = gmdate('Y-m-d H:i:s');
        $flagged_message->save();
        $result = json_encode([
            'action' => 'flag_message',
            'message_id' => $data['message_id'],
            'conversation_id' => $data['conversation_id'],
            'user_id' => \Session::get('user.id')
        ]);
        \Redis::publish('update-data', $result);
        echo $result;
    }

    protected function add_to_outbound_queue($inbound, $outbound, $outbound_details, $outbound_time) {
        $inbound = $inbound;
        $params = array("outbound_id" => $outbound->id,
            "outbound_details" => $outbound_details,
            "conversation_id" => $outbound->conversation_id,
            //"conversation_duration_id" => $outbound_details['conversation_duration_id'],
            "inbound_id" => $inbound->id,
            "user_id" => \Session::get('user_id'));

        $record = new \TblOutboundQueue();
        $record->details = json_encode($params);
        $record->outbound_time = $outbound_time;
        $record->date_created = gmdate("Y-m-d H:i:s");
        $record->save();
    }

    public function blacklist(Request $request) {
        $user_id = session('user.id');
        $post = $request->input();
        $post['details'] = json_decode($post['details'], true);
        $post['details']['action'] ='blacklist';
        $post['details']['user_id'] = $user_id;
       

        $record = \TblBlacklist::whereSubscriberId($post['details']['subscriber'])->first();
        if (!$record) {
            $record = new \TblBlacklist();
            $record->subscriber_id = $post['details']['subscriber'];
            $record->user_id = $user_id;
            $record->date_created = gmdate("Y-m-d H:i:s");
            $record->save();
            
            \Redis::publish('update-data', json_encode($post['details']));
            return 0;
        } else {
            return 1;
        }
    }

     public function discard(Request $request) {
        $user_id = session('user.id');
        $user = \TblUsers::find($user_id);

        $post = $request->input();
        $post['details'] = json_decode($post['details'], true);
        $post['details']['action'] ='discard';
        $post['details']['user_id'] = $user_id;
        $post['details']['date'] = gmdate('Y-m-d H:i:s');
        $post['details']['fullname'] = $user->full_name(2);
            
        \Redis::publish('update-data', json_encode($post['details']));
    }

    public function add_note(Request $request){
        $data = $request->input();

        $note = new \TblConversationNotes();
        $note->user_id = \Session::get('user.id');
        $note->conversation_id = $data['cid'];
        $note->comment = $data['comment'];
        $note->date_created = gmdate('Y-m-d H:i:s');
        $note->type = $data['type'];
        $note->save();

        $result = json_encode([
            'cid' => $data['cid'],
            'note' => $note->toArray(),
            'action' => "update_conversation_notes"
        ]);

        \Redis::publish('update-data',$result);
        echo $result;
    }

    public function delete_note(Request $request){
        $note = \TblConversationNotes::find($request->input('note_id'));
        $status = "error";
        
        if($note){
            $note->delete();
            $status = "ok";
        }

        $result = json_encode([
            'action' => "delete_conversation_note",
            'status' => $status,
            'user_id' => \Session::get('user.id'),
            'cid' => $request->input('cid'),
            'note_id' => $request->input('note_id')
        ]);

        \Redis::publish('update-data',$result);
        echo $result;
    }
     public function check_if_logged_in($user_id){
         $logged_in = \TblLoggedInUsers::whereUserId($user_id)->first();

         if(!$logged_in){
            session()->flush();
       //Auth::logout();
        return Redirect::to('login');
         }

    }

}
