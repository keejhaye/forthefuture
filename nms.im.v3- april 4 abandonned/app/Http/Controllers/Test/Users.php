<?php

namespace App\Http\Controllers\Test;

use App\Libraries;
use Illuminate\Cache\RedisStore as RedisStore;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Cache;
use Session;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Contracts\Auth\Authenticatable;
// use Illuminate\Auth\Authenticatable as AuthenticableTrait;

class Users extends Controller {

    public function index() {
        
    }
      public function add() {
        $users = new \TblUsers;
     
        $users->username = "v3op5";
        $users->firstname = "v3op5";
        $users->lastname = "v3op5"; 
        $users->passwordencrypt = "v3op5";
        $users->password = "v3op5";
        $users->email = "";
        $users->role_id = 5;
        $users->status = "active";
        $users->date_created = gmdate('Y-m-d H:i:s');
        
        $users->save();

        $model = new \TblUserService;
        $model->user_id = $users->id;
        $model->service_id = 1;
        $model->save();
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
        $user = \TblUsers::find($id);
        $user->username = $request->input('username');
        $user->firstname = $request->input('firstname');
        $user->lastname = $request->input('lastname');
        $user->password = $request->input('password');
        $user->email = $request->input('email');
        $user->role_id = $request->input('role_id');
        $user->status = $request->input('status');
        $user->date_created = gmdate('Y-m-d H:i:s');
        return (string)$user->save();
    }

    public function session_test(Request $request){
        die("<pre>".print_r($request->session()->all(),1));
        // $outbound_count = session('outbound_count');
        // session()->set('outbound_count', $outbound_count + 1);
        // session()->save();

        $data = array(
            'AUTH_CHECK' => (string) Auth::check(),
            'TOKEN' => session()->getToken(),
            'SESSION' => session()->all(),
            );

        // Auth::logout();
        // session()->flush();
        // session()->save();
        // die("<pre>" . print_r($data,1));
        // $counter = \RptMessages::count_outbound(session()->get('user.id'));
        // $assigned_conversations = \Redis::keys('assigned_conversations_*');
        // die("<pre>" . print_r($counter,1));
        // die("<pre>" . print_r(\Redis::decr('online_count'),1));
        // die("<pre>" . print_r(\Redis::connection() ,1));
        // die("<pre>" . print_r(\Cache::getPrefix() ,1));
        // session()->flush();
        
        // GET SESSIONS IN REDIS
        $keys = \Redis::keys('laravel*');

        foreach($keys as $session){
            $session_str = \Redis::get($session);
            $unserialized = unserialize(\Redis::get($session));

            // echo "<pre>" . print_r(\Redis::get($session),1) . "</pre>";
            // echo "<pre>" . print_r(unserialize(\Redis::get($session)),1) . "</pre>";
            echo "<pre>" . print_r(unserialize($unserialized),1) . "</pre>";
        }
        // die("<pre>" . print_r(\Redis::keys('laravel*'),1));
        
    }

    public function redis_stats(){
        $keys = \Redis::keys('total_outbound_*');
        // echo "<pre>" . print_r($keys,1) . "</pre>";

        foreach($keys as $key){
            \Redis::del($key);
        }

        // $user_stat = \Redis::get('user_stat:48');
        // \Redis::del('user_stat:48');
        // echo "<pre>" . print_r(\Redis::get('user_stat:48'),1) . "</pre>";

        // $members = \Redis::get($session)

        // $inbound = \Redis::get('total_inbound');
        // \Redis::incr('total_inbound');
        // $new = \Redis::get('total_inbound');
        // echo "<pre>" . print_r($inbound,1) . "</pre>";
        // echo "<pre>" . print_r($new,1) . "</pre>";
    }

    public function piczelle(Request $request){
        // $image_url = "http://content.screencast.com/users/porongki/folders/Jing/media/950d4ea5-e378-426f-934a-ab225959121b/2016-01-26_1125.png";
        $image_url = $request->input('url');
        $pic = new \Piczelle();

        $pic->manipulate(
            $request->input("type", "thumb")
            , $request->input("url")
            , $request->input("fill", "000000")
            , $request->input("width", "200")
            , $request->input("height", "200"));
    }

    public function test_image(){
        header('Pragma: cache');
        header('Content-Type: image/jpeg');
        // $image_url = "http://content.screencast.com/users/porongki/folders/Jing/media/950d4ea5-e378-426f-934a-ab225959121b/2016-01-26_1125.png";
        $image_url = $request->input('url');
        $percent = 0.5;

        list($width, $height) = getimagesize($image_url);
        $newwidth = $width * $percent;
        $newheight = $height * $percent;

        $thumb = imagecreatetruecolor($newwidth, $newheight);
        $source = imagecreatefromjpeg($image_url);
        imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
        
        ob_clean();
        imagepng($thumb);
        imagedestroy($thumb);
    }
}
