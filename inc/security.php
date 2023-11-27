<?php
function crypto_rand_secure($min, $max)
{
  $range = $max - $min;
  if ($range < 1) return $min; // not so random...
  $log = ceil(log($range, 2));
  $bytes = (int) ($log / 8) + 1; // length in bytes
  $bits = (int) $log + 1; // length in bits
  $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
  do {
    $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
    $rnd = $rnd & $filter; // discard irrelevant bits
  } while ($rnd > $range);
  return $min + $rnd;
}

function GenerateRandomToken($length)
{
  $token = "";
  $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
  $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
  $codeAlphabet .= "0123456789";
  $max = strlen($codeAlphabet); // edited

  for ($i = 0; $i < $length; $i++) {
    $token .= $codeAlphabet[crypto_rand_secure(0, $max - 1)];
  }

  return $token;
}

function storeTokenForUser($user, $token)
{
  $sql = "UPDATE intranet_empleados SET token = '" . $token . "' WHERE id = " . $user;
  $res = fullQuery($sql);
}

function fetchTokenByUserName($user)
{
  $dev = '';
  $sql = "SELECT token FROM intranet_empleados WHERE id = " . $user;
  $res = fullQuery($sql);
  $con = mysqli_num_rows($res);
  if ($con == 1) {
    $row = mysqli_fetch_assoc($res);
    $dev = $row['token'];
  }
  return $dev;
}

function onLogin($user)
{
  $token = GenerateRandomToken(250); // generate a token, should be 128 - 256 bit
  storeTokenForUser($user, $token);
  $cookie = $user . ':' . $token;
  //$secret_key = GenerateRandomToken(242);
  $secret_key = md5('Intranet_Security-2021');
  $mac = hash_hmac('sha256', $cookie, $secret_key);
  $cookie .= ':' . $mac;
  setcookie('rememberme', $cookie, time() + (10 * 365 * 24 * 60 * 60));
}
function rememberMe()
{
  $debug = 0;
  $dev = 0;
  $cookie = isset($_COOKIE['rememberme']) ? $_COOKIE['rememberme'] : '';
  if ($debug == 1) {
    echo '<br>Cookie: ' . $cookie;
  }
  if ($cookie) {
    list($user, $token, $mac) = explode(':', $cookie);
    //$secret_key = GenerateRandomToken(242);
    $secret_key = md5('Intranet_Security-2021');
    if (!hash_equals(hash_hmac('sha256', $user . ':' . $token, $secret_key), $mac)) {
      if ($debug == 1) {
        echo '<br>falso';
      }
      return false;
    }
    $usertoken = fetchTokenByUserName($user);
    if ($usertoken != '') {
      if ($debug == 1) {
        echo '<br>User: ' . $usertoken . '<br>Token:<br>' . $token;
      }
      if (hash_equals($usertoken, $token)) {
        //logUserIn($user);
        $dev = $user;
      }
    }
  }
  //echo '<br>Devuelve: '.$dev;
  return $dev;
}
