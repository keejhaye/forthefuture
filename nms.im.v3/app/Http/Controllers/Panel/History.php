<?php
namespace App\Http\Controllers\Panel;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\TblConversationLogs;
use App\Models\TblConversationDuration;
use App\Models\TblConversationComments;
header('Cache-Control: no-cache');
header('Pragma: no-cache');

class History extends Controller {
    public function index(){
    return view('panel/HistoryContent');    
    }

    public function get_traces($id){
    $query = TblConversationLogs::where('conversation_duration_id', $id)->select('logs');
        
        return $query->first();     
    }

    public function get_logs($id){
     $query = \App\Models\TblConversationDuration::where('tbl_conversation_duration.id', $id)
                      ->select(\DB::raw('p.name as persona, s.name as subscriber,m.direction, m.message, m.bound_time, u.username as operator, m.id as message_id'))
                      ->leftJoin('tbl_conversations AS cs', 'cs.id', '=', 'tbl_conversation_duration.conversation_id')
                      ->leftJoin('tbl_messages AS m', 'm.conversation_id', '=', 'cs.id')
                      ->leftJoin('tbl_subscribers AS s', 's.id', '=', 'cs.subscriber_id')
                      ->leftJoin('tbl_personas AS p','p.id','=', 'cs.persona_id')
                      ->leftJoin('tbl_users AS u','u.id', '=', 'tbl_conversation_duration.user_id');     
        return $query->get();
    }
    
    public function get_conversation_comments($id){
      $query = \App\Models\TblConversationComments::where('conversation_id', $id)
                  ->select(\DB::raw('message_id, comment, date_created'));
        return $query->get();

    }

}
