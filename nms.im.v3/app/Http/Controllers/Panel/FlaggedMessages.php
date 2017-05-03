<?php
namespace App\Http\Controllers\Panel;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class FlaggedMessages extends Controller {
    public function index(){
    return view('panel/FlaggedContent');    
    }

     public function flagged($id = null) {
            if ($id == null) {
               $query = \App\Models\TblFlaggedMessages::select(\DB::raw('tbl_flagged_messages.id AS flagged_id,m.id AS message_id,m.message,m.bound_time, u.username, m.conversation_id, tbl_flagged_messages.status'))
                      ->leftJoin('tbl_messages AS m', 'm.id', '=', 'tbl_flagged_messages.message_id')
                      ->leftJoin('tbl_users AS u', 'u.id', '=', 'tbl_flagged_messages.operator_id');     
        return $query->get();
        } else {
            return $this->show($id);
        }
    }

     public function show($id) {
        $query = \App\Models\TblFlaggedMessages::where('tbl_flagged_messages.id', $id)
                      ->select(\DB::raw('tbl_flagged_messages.id AS flagged_id,m.id AS message_id,m.message,m.bound_time, u.username AS operator, u1.username AS outbound_operator, tbl_flagged_messages.date_flagged, tbl_flagged_messages.date_moderated, tbl_flagged_messages.comments,u2.username AS moderated_by, tbl_flagged_messages.status'))
                      ->leftJoin('tbl_messages AS m', 'm.id', '=', 'tbl_flagged_messages.message_id')
                      ->leftJoin('tbl_users AS u', 'u.id', '=', 'tbl_flagged_messages.operator_id')
                      ->leftJoin('tbl_users AS u1', 'u1.id', '=', 'm.user_id')
                      ->leftJoin('tbl_users AS u2', 'u2.id', '=', 'tbl_flagged_messages.user_id');  
        return $query->first();
    }

    public function get_messages_id($conversation_id ,$message_id){
              $first = \TblMessages::where('id', '<=', $message_id)
              ->select('id')
              ->where('conversation_id', $conversation_id)
              ->orderby('id', 'DESC')
              ->limit(10);

               $query = \TblMessages::where('id', '>', $message_id)
              ->select('id')
              ->where('conversation_id', $conversation_id)
              ->orderBy('id', 'ASC')
              ->limit(10)
              ->union($first)
              ->orderBy('id');

      $ids = $query->get();
     
      // foreach($ids as $index => $value){
      //   $result[] = $value["id"];                  
      // }
    return $ids;
    }

    public function get_messages_history($conversation_id ,$message_id){
      $message_ids = self::get_messages_id($conversation_id ,$message_id)->toArray();

         $messages = \TblMessages::whereIn('tbl_messages.id',$message_ids)
              ->select(\DB::raw('tbl_messages.id as id, tbl_messages.direction as direction,tbl_messages.message as message, s.name as subscriber, p.name as persona, u.username as operator'))
              ->leftJoin('tbl_conversations AS cs','cs.id', '=', 'tbl_messages.conversation_id')
              ->leftJoin('tbl_conversation_duration AS cd', 'cd.conversation_id', '=', 'cs.id')
              ->leftJoin('tbl_subscribers AS s', 's.id', '=', 'cs.subscriber_id')
              ->leftJoin('tbl_personas AS p', 'p.id', '=', 'cs.persona_id')
              ->leftJoin('tbl_users AS u', 'u.id', '=', 'tbl_messages.user_id')
              ->orderBy('tbl_messages.id','ASC')
              ->groupBy('tbl_messages.id')
              ->get();

      return $messages;
    }

    public function update(Request $request, $id, $action){
      $record = \TblFlaggedMessages::find($id);
      $record->comments = $request->input('comments');
      $record->status = $action;
      $record->user_id = session('user.id');
      $record->date_moderated = gmdate('Y-m-d H:i:s');
      $record->save();

      return 0;

    }
      

}
