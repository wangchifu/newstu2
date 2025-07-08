<?php
function chk_id_number($cardid) {
    $err ='';
    //先將字母數字存成陣列
    $alphabet =['A'=>'10','B'=>'11','C'=>'12','D'=>'13','E'=>'14','F'=>'15','G'=>'16','H'=>'17','I'=>'34',
                'J'=>'18','K'=>'19','L'=>'20','M'=>'21','N'=>'22','O'=>'35','P'=>'23','Q'=>'24','R'=>'25',
                'S'=>'26','T'=>'27','U'=>'28','V'=>'29','W'=>'32','X'=>'30','Y'=>'31','Z'=>'33'];
    //檢查字元長度
    if(strlen($cardid) !=10){//長度不對
        $err = '1';
        return false;
    }

    //驗證英文字母正確性
    $alpha = substr($cardid,0,1);//英文字母
    $alpha = strtoupper($alpha);//若輸入英文字母為小寫則轉大寫
    if(!preg_match("/[A-Za-z]/",$alpha)){
        $err = '2';
        return false;
    }else{
        //計算字母總和
        $nx = $alphabet[$alpha];        
        $ns = intval($nx[0]) * 1 + intval($nx[1]) * 9;
        $ns = intval($nx[0]) * 1 + intval($nx[1]) * 9;
    }

    //驗證男女性別
    $gender = substr($cardid,1,1);//取性別位置
    //驗證性別,89為新式居留證號8為男，9為女
    if($gender !='1' && $gender !='2' && $gender !='8' && $gender !='9'){
        $err = '3';
        return false;
    }

    //N2x8+N3x7+N4x6+N5x5+N6x4+N7x3+N8x2+N9+N10
    if($err ==''){
        $i = 8;
        $j = 1;
        $ms =0;
        //先算 N2x8 + N3x7 + N4x6 + N5x5 + N6x4 + N7x3 + N8x2
        while($i >= 2){
            $mx = substr($cardid,$j,1);//由第j筆每次取一個數字
            $my = $mx * $i;//N*$i
            $ms = $ms + $my;//ms為加總
            $j+=1;
            $i--;
        }
        //最後再加上 N9 及 N10
        $ms = $ms + substr($cardid,8,1) + substr($cardid,9,1);
        //最後驗證除10
        $total = $ns + $ms;//上方的英文數字總和 + N2~N10總和
        if( ($total%10) !=0){
            $err = '4';
            return false;
        }
    }
    //錯誤訊息返回
    // switch($err){
    //     case '1':$msg = '字元數錯誤';break;
    //     case '2':$msg = '英文字母錯誤';break;
    //     case '3':$msg = '性別錯誤';break;
    //     case '4':$msg = '驗證失敗';break;
    //     default:$msg = '驗證通過';break;
    // }
    // \App\Library\CommonTools::writeErrorLogByMessage('身份字號：'.$cardid);
    // \App\Library\CommonTools::writeErrorLogByMessage($msg);
    return true;
}

//檢查檔名
function chk_file_format($file,$jh_school) {
	$chk='NO';
	//if(ereg("^[0-9]{3}_[0-9]{6}_[0-9]{8}\.csv",$file))   $chk='OK';
	//if(ereg("^[0-9]{3}_[0-9]{6}_[0-9]{8}\.csv",$file))   $chk='OK';
	//if (preg_match("/^[0-9]{3}_[0-9]{6}_[0-9]{8}.csv/",$file))  $chk='OK';
    if($jh_school==1){
        //建和分校
        $regex="/^[0-9]{3}_074603-1_[0-9]{8}.xlsx/";
    }else{
        $regex="/^[0-9]{3}_[0-9]{6}_[0-9]{8}.xlsx/";
    }	
	if (preg_match($regex,$file))  $chk='OK';
	Return $chk;
}

function shuffleAssoc($array) {
    // 取得陣列的鍵值並打亂順序
    $keys = array_keys($array);
    shuffle($keys);

    // 建立新的陣列，保留原鍵值的打亂順序
    $shuffledArray = [];
    foreach ($keys as $key) {
        $shuffledArray[$key] = $array[$key];
    }

    return $shuffledArray;
}

function get_ip()
{
    $ipAddress = '';
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        // to get shared ISP IP address
        $ipAddress = $_SERVER['HTTP_CLIENT_IP'];
    } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        // check for IPs passing through proxy servers
        // check if multiple IP addresses are set and take the first one
        $ipAddressList = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        foreach ($ipAddressList as $ip) {
            if (!empty($ip)) {
                // if you prefer, you can check for valid IP address here
                $ipAddress = $ip;
                break;
            }
        }
    } else if (!empty($_SERVER['HTTP_X_FORWARDED'])) {
        $ipAddress = $_SERVER['HTTP_X_FORWARDED'];
    } else if (!empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP'])) {
        $ipAddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
    } else if (!empty($_SERVER['HTTP_FORWARDED_FOR'])) {
        $ipAddress = $_SERVER['HTTP_FORWARDED_FOR'];
    } else if (!empty($_SERVER['HTTP_FORWARDED'])) {
        $ipAddress = $_SERVER['HTTP_FORWARDED'];
    } else if (!empty($_SERVER['REMOTE_ADDR'])) {
        $ipAddress = $_SERVER['REMOTE_ADDR'];
    }
    return $ipAddress;
}

function logging($event,$for_code, $ip)
{   
    $message = auth()->user()->school->name." ".auth()->user()->name."(".auth()->user()->id.") ".$event; 
    $att['message'] = $message;
    $att['user_id'] = auth()->user()->id;
    $att['for_code'] = $for_code;
    $att['ip'] = $ip;
    $att['group_id'] = auth()->user()->group_id;
    \App\Models\Log::create($att);
    return true;    
}

function substr_cut_name($user_name){
	//获取字符串长度
	$strlen = mb_strlen($user_name, 'utf-8');
	//如果字符创长度小于2，不做任何处理
	if($strlen<2){
		return $user_name;
	}else{
		//mb_substr — 获取字符串的部分
		$firstStr = mb_substr($user_name, 0, 1, 'utf-8');
		$lastStr = mb_substr($user_name, -1, 1, 'utf-8');
		//str_repeat — 重复一个字符串
		return $strlen == 2 ? $firstStr . str_repeat('〇', mb_strlen($user_name, 'utf-8') - 1) : $firstStr . str_repeat("〇", $strlen - 2) . $lastStr;
	}
}
