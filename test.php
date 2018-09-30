<?php
ob_start();
$dn = array(
    "countryName" => "MU",
    "stateOrProvinceName" => "Mauritius",
    "localityName" => "Mauritius",
    "organizationName" => "Test",
    "organizationalUnitName" => "TEST",
    "commonName" => "TEST",
    "emailAddress" => "test@example.com"
);
$pkeyout = "test.key";
$certout = "test.cert";
$privkey = openssl_pkey_new();
$csr = openssl_csr_new($dn, $privkey);
$sscert = openssl_csr_sign($csr, null, $privkey, 365);

openssl_csr_export($csr, $csrout);
openssl_x509_export($sscert, $certout);
openssl_pkey_export($privkey, $pkeyout, "mypassword");

// Show any errors that occurred here
while (($e = openssl_error_string()) !== false) {
   echo $e . "\n";
}

$opts = array(
  'ssl' => array(
      'local_cert' => 'test.cert',
      'local_pk' => 'test.key',
      'verify_peer' => false,
      'verify_peer_name' => false
      )
  );
$timeout = 160;
$host = "ssl://www.google.com:443";
$context = stream_context_create($opts);
$socket = stream_socket_client (
  $host, $errno, $errstr, $timeout,
  STREAM_CLIENT_CONNECT, $context);
if (!$socket) {
    echo "<br>";
    echo "Failure $errno errstr $errstr.\n";
} else {
    echo "Success.";
    if (! isset($_SERVER['HTTPS']) or $_SERVER['HTTPS'] == 'off' ) {
    $redirect_url = "https://www.google.com";
    header("Location: $redirect_url");
}
}
?>