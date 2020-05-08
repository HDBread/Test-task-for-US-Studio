<?php

function get_currency($currency_code, $date) {

    $file_currency_cache = __DIR__ . '/XML_daily.asp';

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://www.cbr.ru/scripts/XML_daily.asp?date_req=' . $date);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);

    $out = curl_exec($ch);

    curl_close($ch);

    file_put_contents($file_currency_cache, $out);


    $content_currency = simplexml_load_file($file_currency_cache);

    return number_format(str_replace(',', '.', $content_currency->xpath('Valute[CharCode="'
            . $currency_code . '"]')[0]->Value), 4);
}
?>

<form id="dateForm" action="index.php" method="POST">
    <input type="date" name="Date">
    <input type="submit" name="submit" value="Получить курс">   
</form>

<?php
if (isset($_POST['submit'])) {
    $previouseDate = date('d/m/Y', strtotime($_POST['Date'] . '-1 day'));
    $chouseDate = date("d.m.Y", strtotime($_POST['Date']));

    echo "Курс на " . $currentDate . " -> " . $previouseDate . "<br/>";
    echo "USD: " . get_currency('USD', $currentDate)
        . "->" . get_currency('USD', $previouseDate) . "<br/>";
    echo "EUR: " . get_currency('EUR', $currentDate)
        . "->" . get_currency('EUR', $previouseDate);
}
?>

