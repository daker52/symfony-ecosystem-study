<?php

$pass = getenv('JWT_PASSPHRASE') ?: 'a4d6dc834a9f9d0433208429148866fa2326c924ca37975f2211ae064e46e505';
$dir = __DIR__.'/config/jwt';
if (!is_dir($dir)) {
    mkdir($dir, 0777, true);
}

$config = [
    'private_key_bits' => 4096,
    'private_key_type' => OPENSSL_KEYTYPE_RSA,
];

$res = openssl_pkey_new($config);
if ($res === false) {
    fwrite(STDERR, openssl_error_string().PHP_EOL);
    exit(1);
}

openssl_pkey_export($res, $priv, $pass);
$details = openssl_pkey_get_details($res);
file_put_contents($dir.'/private.pem', $priv);
file_put_contents($dir.'/public.pem', $details['key']);
echo "JWT keys written to config/jwt/\n";
