<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\School;
use App\Models\Group;

class HomeController extends Controller
{
    public function index(){

        $groups = Group::all();
        $data = [
            'groups'=>$groups,
        ];
        return view('index',$data);
    }

    public function about(){

        return view('about');
    }

    public function teach(){

        return view('teach');
    }

    public function pic()
    {
        $key = rand(10000, 99999);
        $back = rand(0, 9);
        /*
        $r = rand(0,255);
        $g = rand(0,255);
        $b = rand(0,255);
        */
        $r = 0;
        $g = 0;
        $b = 0;

        session(['chaptcha' => $key]);

        //$cht = array(0=>"零",1=>"壹",2=>"貳",3=>"參",4=>"肆",5=>"伍",6=>"陸",7=>"柒",8=>"捌",9=>"玖");
        $cht = array(0 => "0", 1 => "1", 2 => "2", 3 => "3", 4 => "4", 5 => "5", 6 => "6", 7 => "7", 8 => "8", 9 => "9");
        $cht_key = "";
        for ($i = 0; $i < 5; $i++) $cht_key .= $cht[substr($key, $i, 1)];

        header("Content-type: image/gif");
        $im = imagecreatefromgif(asset('img/back.gif')) or die("無法建立GD圖片");
        $text_color = imagecolorallocate($im, $r, $g, $b);

        imagettftext($im, 25, 0, 5, 32, $text_color, public_path('font/AdobeGothicStd-Bold.otf'), $cht_key);
        imagegif($im);
        imagedestroy($im);
    }

    public function superuser(){
        return view('auth.superuser');
    }

    public function sauth(Request $request){
        if ($request->input('chaptcha') != session('chaptcha')) {
            return back()->withErrors(['error' => '驗證碼錯誤']);
        }

        if ($request->input('username') <> env('ADMIN_ACC')) {
            return back()->withErrors(['error' => '無此帳號']);
        }

        $password = $request->input('password');
        if (Hash::check($password, env('ADMIN_PWD'))) {            
            $user = User::where('username', env('ADMIN_ACC'))->first();            
            Auth::login($user);
            session(['system_admin' => true]);
            return redirect()->route('suser');
        } else {
            return back()->withErrors(['error' => '密碼錯誤']);
        }
    }

    public function slogout()
    {
        session(['system_admin' => null]);
        Auth::logout();
        return redirect()->route('index');
    }

    public function suser()
    {
        if (session('system_admin') <> true) {
            return redirect()->back();
        }
        $users = User::where('login_type','<>','local')->orderBy('id', 'desc')->paginate('20');
        $data = [
            'users' => $users,
        ];
        return view('suser', $data);
    }

    public function suser_search(Request $request)
    {        
        if (session('system_admin') <> true) {
            return redirect()->back();
        }        
        $schools = School::where('name','like','%'.$request->input('want').'%')->get();
        $school_ids = [];
        foreach($schools as $school){
            $school_ids[] = $school->id;
        }        
        $users = User::where('title','like','%'.$request->input('want').'%')->orWhere('name','like','%'.$request->input('want').'%')->orWhere(function($query) use($school_ids){ $query->whereIn('school_id', $school_ids);})->orderBy('id', 'desc')->get();
        $data = [
            'users' => $users,
            'want' => $request->input('want'),
        ];
        return view('suser_search', $data);
    }

    public function impersonate(User $user)
    {
        if (session('system_admin') <> true) {
            return redirect()->back();
        }
        Auth::user()->impersonate($user);                
                
        return redirect()->route('index');
    }

    public function impersonate_leave()
    {        
        Auth::user()->leaveImpersonation();

        return redirect()->route('suser');
    }

    public function glogin(){
        return view('auth.glogin');
    }

    public function logins(){
        return view('auth.logins');
    }

    public function logout(){
        Auth::logout();
        $url = "https://chc.sso.edu.tw/oidc/v1/logout-to-go";
        $post_logout_redirect_uri = env('APP_URL');
        $id_token_hint = session('id_token');
        $link = $url . "?post_logout_redirect_uri=".$post_logout_redirect_uri."&id_token_hint=" . $id_token_hint;
        return redirect($link);        
    }

    public function gauth(Request $request){
        if ($request->input('chaptcha') != session('chaptcha')) {
            return back()->withErrors(['errors' => ['驗證碼錯誤！']]);
        }

        $username = explode('@', $request->input('username'));

        $data = array("email" => $username[0], "password" => $request->input('password'));
        $data_string = json_encode($data);
        $ch = curl_init('https://school.chc.edu.tw/api/auth');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string)
            )
        );
        $result = curl_exec($ch);
        $obj = json_decode($result, true);

        //學生禁止訪問
        if ($obj['success']) {

            if ($obj['kind'] == "學生") {
                return back()->withErrors(['errors' => ['學生禁止進入']]);
            }
            if(!str_contains($obj['title'],'教務') & !str_contains($obj['title'],'教導') & !str_contains($obj['title'],'教學') & !str_contains($obj['title'],'註冊') & !str_contains($obj['title'],'資訊')){
                return back()->withErrors(['errors' => ['職稱必須含「教務,教導,教學,註冊,資訊」等字眼方能進入。']]);
            }

            //是否已有此帳號
            $user = User::where('personid', $obj['edu_key'])                
                ->first();

            if (empty($user)) {
                $school = School::where('code',$obj['code'])->first();
                if(empty($school)){
                    return back()->withErrors(['errors' => ['貴校不在系統內']]);
                }
                $att['name'] = $obj['name'];
                $att['title'] = $obj['title']; 
                $att['personid'] = $obj['edu_key'];
                $att['username'] = $username[0];
                $att['password'] = bcrypt($request->input('password'));
                $att['login_type'] = "gsuite"; 
                $att['school_id'] = $school->id;                                               
                $att['group_id'] = $school->group->id;     
                $user = User::create($att);            
            } else {                
                //有此使用者，即更新使用者資料
                $school = School::where('code',$obj['code'])->first();

                $att['name'] = $obj['name'];
                $att['title'] = $obj['title']; 
                $att['username'] = $username[0];
                $att['password'] = bcrypt($request->input('password'));  
                $att['personid'] = $obj['edu_key'];                  
                $att['login_type'] = "gsuite"; 
                $att['school_id'] = $school->id;                                               
                $att['group_id'] = $school->group->id; 
                $user->update($att);                                               
            }
            Auth::login($user);
            return redirect()->route('index');
            
        };

        return back()->withErrors(['errors' => ['帳號密碼錯誤']]);;
    }
}
