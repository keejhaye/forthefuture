<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class Subscribers extends Controller {

    public function index() {
        return view('panel/SubscribersContent')->with('page_title', 'Subscribers');
    }

    //  -- Kris' changes 5/8/2017
    public function getSubscribersByPage($offset, $limit){
        $personas = \TblSubscribers::orderBy('id', 'asc')->skip($offset)->take($limit); 
        
        if(session()->get('user.role_id') == 6){
            $personas->whereIn('service_id', session()->get('user.services'));
        }
        return $personas->get(); 
    }

    public function countAllSubscribers(){
        $cnt = \TblSubscribers::orderBy('id', 'asc');

        if(session()->get('user.role_id') == 6){
            $cnt->whereIn('service_id', session()->get('user.services'));
        }
        return $cnt->count();
    }
    //  -- Kris' changes 5/8/2017

    public function subscribers($id = null) {
        if ($id == null) {
            $subscribers = \TblSubscribers::orderBy('id', 'asc')->limit('2000');

            if(session()->get('user.role_id') == 6){
                $subscribers->whereIn('service_id', session()->get('user.services'));
            }
            return $subscribers->get();
        } else {
            return $this->show($id);
        }
    }

     public function show($id) {
        return \TblSubscribers::find($id);
    }
      public function add(Request $request) {
        $persona = new \TblSubscribers;
     
        $persona->name = $request->input('name');
        $persona->service_id = $request->input('service_id');
        $persona->status = $request->input('status'); 
        $persona->profile = json_encode('profile');
        $persona->additional_info = json_encode('additional_info');
        $persona->date_created = gmdate('Y-m-d H:i:s');
        
        $persona->save();

        return 'Success';
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id) {
        $persona = \TblPersonas::find($id);
     
        $profile = $this->profile_to_array( $request->input('profile'));
        $persona->name = $request->input('name');
        $persona->service_id = $request->input('service_id');
        $persona->status = $request->input('status'); 
        $persona->profile = json_encode($profile);
        $persona->additional_info = json_encode('additional_info');
        $persona->date_created = gmdate('Y-m-d H:i:s');
        
        $persona->save();

        return "Update Success";
    }
    
    public function profile_to_array($profile) {
    $data = array();
    $profile = str_replace("<p>", "", $profile);
    $profile = str_replace("</p>", "<br />", $profile);
    $array = explode("<br />", $profile);
    $cnt = count($array);
    $i = 0;
    foreach ($array as $key => $item) {
      $i++;
      if ($i < $cnt) {
        $temp = explode(":", $item);
        $index = trim(strtolower($temp[0]));
        $data[$index] = trim($temp[1]);
      }
    }
    return $data;
  }

  public function add_note(Request $request){
    $data = $request->input();

    $note = new \TblSubscriberNotes;
    $note->user_id = \Session::get('user.id');
    $note->subscriber_id = $data['subscriber_id'];
    $note->persona_id = $data['persona_id'];
    $note->comment = $data['comment'];
    $note->date_created = gmdate('Y-m-d H:i:s');
    $note->save();

    $result = json_encode([
        'id' => $note->id,
        'comment' => $data['comment'],
        'date' => $note->date_created,
        'action' => "update_susbcriber_note",
        'cid' => $data['cid']
        ]);

    \Redis::publish('update-data',$result);
    echo $result;
  }

  public function delete_note(Request $request){
    $note = \TblSubscriberNotes::find($request->input('note_id'));
    $status = "error";
    
    if($note){
        $note->delete();
        $status = "ok";
    }

    $result = json_encode([
        'action' => "delete_subscriber_note",
        'status' => $status,
        'cid' => $request->input('cid'),
        'note_id' => $request->input('note_id')
    ]);

    \Redis::publish('update-data',$result);
    echo $result;
  }

  public function get_conversation_history($id){
    $conversations = \TblConversations::where('tbl_conversations.subscriber_id', $id)
                  ->select(\DB::raw('s.name as subscriber, p.name as persona, m.message as message, m.direction as direction'))
                  ->leftJoin('tbl_subscribers AS s', 's.id', '=', 'tbl_conversations.subscriber_id')
                  ->leftJoin('tbl_personas AS p', 'p.id', '=', 'tbl_conversations.persona_id')
                  ->leftJoin('tbl_messages AS m', 'm.conversation_id', '=', 'tbl_conversations.id')
                  ->groupBy('tbl_conversations.id')
                  ->get();
    return $conversations;
  }

  public function get_messages($id){
    $messages = \TblMessages::where('tbl_messages.conversation_id', $id)
              ->select(\DB::raw('tbl_messages.id as id, tbl_messages.direction as direction,tbl_messages.message as message, s.name as subscriber, p.name as persona'))
              ->leftJoin('tbl_conversations AS cs','cs.id', '=', 'tbl_messages.conversation_id')
              ->leftJoin('tbl_subscribers AS s', 's.id', '=', 'cs.subscriber_id')
              ->leftJoin('tbl_personas AS p', 'p.id', '=', 'cs.persona_id')
              ->get();
    return $messages;

  }

    public function add_to_blacklist($id){
     $subscriber = \TblSubscribers::find($id);
     $subscriber->status = "blocked";
     $subscriber->save();

      $blacklist = new \TblBlacklist;

      $blacklist->user_id = session('user.id');
      $blacklist->subscriber_id = $id;
      $blacklist->date_created = gmdate('Y-m-d H:i:s');
      $blacklist->save();

      return 0;
    }

}
