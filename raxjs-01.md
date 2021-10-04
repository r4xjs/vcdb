---

title: raxjs-01
author: raxjs
tags: [php,bash,real]

---

Customer support feature, based on a vulnerability found in the wild.

<!--more-->

# Code

## support.php

{{< code language="php"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
<?php
require_once 'config.inc';

// make sure that we have a valid session
require_once 'private/admin.inc';

function my_escapeshellarg($a){ 
	return $a=='' ? '""' : escapeshellarg($a);
}

/************************************
 * display support template and open ssh support tunnel to our f00.bar.ru server
 ***********************************/
$scode = $_REQUEST['f00_support_support_code'];
$spass = $_REQUEST['f00_support_access_code'];
$ocode = $_REQUEST['f00_support_override_code'];

if (($_REQUEST['f00_support_submit'] == 1) && checkCSRFToken('support:start') && $spass != '' &&
	(strpos($scode, ':') !== FALSE || (intval($scode) >= 1 && intval($scode) <= 65535))) {

	f00seclog($db,
		'starting_support_tunnel',
		array(
			'support_code' => $scode,
			'access_code' => $spass,
			'override_code' => $ocode
		),
		f00seclog_ALERT
	);

	// received request to start ssh support tunnel
	$p = popen('/f00/bin/start_ssh_support_tunnel ' . escapeshellarg(trim($scode)) .
			' ' . escapeshellarg(trim($ocode)) . ' >/dev/null 2>&1', 'w');
	fwrite($p, $spass);
	$retVal = pclose($p);

	if ($retVal == 2) {
		badMessage('ssh', TR('DNS error'));
	} else if ($retVal == 1) {
		badMessage('ssh', TR('Network error, could not make outbound connection'));
	} else {
		badMessage('ssh', TR(/*d=F00Bar's customers can request support directly from F00Bar by using the /F00Bar web interface.  The session is initiated by the customer.*/'Support Session Initiated to F00Bar'));
	}
} else if (/*disable on FIPS build*/!file_exists('/f00s/config/fips') && $_REQUEST['gen_pack'] == '1') {
	$filename = 'info-base-'.VERSION.'-'.trim(`hostname`).'.pack';

	if (intval($_REQUEST['bwl'])>0) {
		$limiter = ' | '.BIN_DIR.'cstream -t '.intval($_REQUEST['bwl']);
	} else {
		$limiter = '';
	}

	$p = popen('sudo '.BIN_DIR.'generate_log_pack'.$limiter,'r');
	if($p) {
		ob_end_clean();
		header('Content-Type: application/x-data');
		header("Content-Disposition: attachment; filename=$filename");
		fpassthru($p);
		pclose($p);
		exit(0);
	}
}
$tmpl->display('support.tpl');

{{< /code >}}

## /f00/bin/start_ssh_support_tunnel

{{< code language="shell"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
if [[ ! -z "$ssh_password" ]]; then
	# we use this script to output the exported $ssh_password variable for the SSH_ASKPASS env var to ssh
	echo "$ssh_password" 
	exit 0
fi

if [[ -z "$1" ]]; then
	echo "usage: "`basename $0`" <port_to_listen_on> [port_to_connect_to]"
	exit 1
fi

port=$1
if [[ ! -z "$2" ]]; then
	port_to_connect_to=$2
else
	port_to_connect_to=22
fi

# read the password (and don't mess with whitespace)
IFS=$'\x01'
read p
unset IFS

export ssh_password="$p"

if echo "$port" | grep "..*:[0-9][0-9]*" >/dev/null 2>/dev/null ; then

	# hidden feature where host:port was given
	host=`echo "$port" | cut -f1 -d':'`
	port=`echo "$port" | cut -f2 -d':'`
else

	# use default host
	host="f00.bar.ru" 
fi


# check for DNS (doesn't apply if $port contains IP:port)
ping -c1 "$host"
ret=$?
if [[ $ret = 2 ]]; then
	# dns failure
	echo "DNS Resolution Failure"
	exit 2
fi



# display must be set for ssh to think about using SSH_ASKPASS
export DISPLAY="localhost:0.0 "
# recur on this script with no arguments to get the password
export SSH_ASKPASS="$0"
ssh -f "support@$host" -p $port_to_connect_to -R "$port:localhost:22" -q -o "UserKnownHostsFile /dev/null" -o "NumberOfPasswordPrompts 1" -o "StrictHostKeyChecking no" sleep 600

exit 0

{{< /code >}}


# Solution

## support.php

{{< code language="php" highlight="17,34,35" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}
<?php
require_once 'config.inc';

// make sure that we have a valid session
require_once 'private/admin.inc';

function my_escapeshellarg($a){ 
	return $a=='' ? '""' : escapeshellarg($a);
}

/************************************
 * display support template and open ssh support tunnel to our f00.bar.ru server
 ***********************************/
$scode = $_REQUEST['f00_support_support_code'];
$spass = $_REQUEST['f00_support_access_code'];
// 1) $ocode as well as $scode and $spass is fully under user control
$ocode = $_REQUEST['f00_support_override_code'];

if (($_REQUEST['f00_support_submit'] == 1) && checkCSRFToken('support:start') && $spass != '' &&
	(strpos($scode, ':') !== FALSE || (intval($scode) >= 1 && intval($scode) <= 65535))) {

	f00seclog($db,
		'starting_support_tunnel',
		array(
			'support_code' => $scode,
			'access_code' => $spass,
			'override_code' => $ocode
		),
		f00seclog_ALERT
	);

	// received request to start ssh support tunnel
	// 2) $ocode is passed as second argument to as shell script, the value is sanitized by
	//    the php function escapeshellarg and trim.
	$p = popen('/f00/bin/start_ssh_support_tunnel ' . escapeshellarg(trim($scode)) .
			' ' . escapeshellarg(trim($ocode)) . ' >/dev/null 2>&1', 'w');
	fwrite($p, $spass);
	$retVal = pclose($p);
	// use $retVal for error handling ....

} else if (/*disable on FIPS build*/!file_exists('/f00/config/fips') && $_REQUEST['gen_pack'] == '1') {
	$filename = 'info-base-'.VERSION.'-'.trim(`hostname`).'.pack';

	if (intval($_REQUEST['bwl'])>0) {
		$limiter = ' | '.BIN_DIR.'cstream -t '.intval($_REQUEST['bwl']);
	} else {
		$limiter = '';
	}

	$p = popen('sudo '.BIN_DIR.'generate_log_pack'.$limiter,'r');
	if($p) {
		ob_end_clean();
		header('Content-Type: application/x-data');
		header("Content-Disposition: attachment; filename=$filename");
		fpassthru($p);
		pclose($p);
		exit(0);
	}
}
$tmpl->display('support.tpl');
{{< /code >}}

## /f00/bin/start_ssh_support_tunnel:

{{< code language="shell" highlight="15,55-60" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}
if [[ ! -z "$ssh_password" ]]; then
	# we use this script to output the exported $ssh_password variable for the SSH_ASKPASS env var to ssh
	echo "$ssh_password" 
	exit 0
fi

if [[ -z "$1" ]]; then
	echo "usage: "`basename $0`" <port_to_listen_on> [port_to_connect_to]"
	exit 1
fi

port=$1
if [[ ! -z "$2" ]]; then
	# 3) the second argument is $ocode wich is now defined as $port_to_connect_to
	port_to_connect_to=$2
else
	port_to_connect_to=22
fi

# read the password (and don't mess with whitespace)
IFS=$'\x01'
read p
unset IFS

export ssh_password="$p"

if echo "$port" | grep "..*:[0-9][0-9]*" >/dev/null 2>/dev/null ; then

	# hidden feature where host:port was given
	host=`echo "$port" | cut -f1 -d':'`
	port=`echo "$port" | cut -f2 -d':'`
else

	# use default host
	host="f00.bar.ru" 
fi


# check for DNS (doesn't apply if $port contains IP:port)
ping -c1 "$host"
ret=$?
if [[ $ret = 2 ]]; then
	# dns failure
	echo "DNS Resolution Failure"
	exit 2
fi



# display must be set for ssh to think about using SSH_ASKPASS
export DISPLAY="localhost:0.0 "
# recur on this script with no arguments to get the password
export SSH_ASKPASS="$0"
# 4) $port_to_connect_to is user controlled and not quoted, this leads
#    to argument injection for the ssh client binary.
#    The vulnerability could be exploited via "22 -o ProxyCommand=...." to
#    gain RCE.
#    Additionally in combination with the "hidden" feature an attacker could bind

ssh -f "support@$host" -p $port_to_connect_to -R "$port:localhost:22" -q -o "UserKnownHostsFile /dev/null" -o "NumberOfPasswordPrompts 1" -o "StrictHostKeyChecking no" sleep 600

exit 0
{{< /code >}}
