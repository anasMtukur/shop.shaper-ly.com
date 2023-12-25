<?php
session_start();
setlocale( LC_ALL, "en_NG" );

/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */

require_once "src/db/class-db-connection.php";
require_once "src/db/class-db-entity.php";
require_once "src/db/class-paged-db-entity.php";
require_once "src/helpers/class-code-generator.php";
require_once "src/helpers/class-address-helper.php";
require_once "src/helpers/class-logged-account.php";
require_once "src/services/ui/class-ui-fragments.php";
require_once "src/class-registered-user.php";
require_once "src/class-products.php";
require_once "src/class-clients.php";
require_once "src/class-product-sizes.php";
require_once "src/class-orders.php";
require_once "src/class-order-items.php";
require_once "src/class-payments.php";
require_once "src/class-expenses.php";
require_once "src/class-client-accounts.php";
require_once "src/class-client-account-transactions.php";
require_once "src/class-dashboard.php";
require_once "src/services/reports/class-reports.php";
require_once "src/services/account/class-account-helper.php";
require_once "src/auth/class-auth.php";

$CUR_UNIT = '&#8358;';
$LOGGED   = false;
$ORI_FROM = "index.php";
$INFO     = "";
$ERROR    = "";
$SUCCESS  = "";
$WARN     = "";

if(isset($_SESSION["sps_logged"])){
    $LOGGED = true;
    $logged_account = $_SESSION["sps_logged_user_account"];
}

if(isset($_REQUEST["user_from"])){
    $ORI_FROM = $_REQUEST["user_from"];
}

if(isset($_REQUEST["info"])){
    $INFO = $_REQUEST["info"];
}

if(isset($_REQUEST["error"])){
    $ERROR = $_REQUEST["error"];
}

if(isset($_REQUEST["success"])){
    $SUCCESS = $_REQUEST["success"];
}

if(isset($_REQUEST["warn"])){
    $WARN = $_REQUEST["warn"];
}

if ( ! function_exists( 'money_format' ) ) {

    function money_format($format, $number) {
        $regex  = '/%((?:[\^!\-]|\+|\(|\=.)*)([0-9]+)?'.
            '(?:#([0-9]+))?(?:\.([0-9]+))?([in%])/';
        if (setlocale(LC_MONETARY, 0) == 'C') {
            setlocale(LC_MONETARY, '');
        }
        $locale = localeconv();
        preg_match_all($regex, $format, $matches, PREG_SET_ORDER);
        foreach ($matches as $fmatch) {
            $value = floatval($number);
            $flags = array(
                'fillchar'  => preg_match('/\=(.)/', $fmatch[1], $match) ? $match[1] : ' ',
                'nogroup'   => preg_match('/\^/', $fmatch[1]) > 0,
                'usesignal' => preg_match('/\+|\(/', $fmatch[1], $match) ? $match[0] : '+',
                'nosimbol'  => preg_match('/\!/', $fmatch[1]) > 0,
                'isleft'    => preg_match('/\-/', $fmatch[1]) > 0
            );
            $width      = trim($fmatch[2]) ? (int)$fmatch[2] : 0;
            $left       = trim($fmatch[3]) ? (int)$fmatch[3] : 0;
            $right      = trim($fmatch[4]) ? (int)$fmatch[4] : $locale['int_frac_digits'];
            $conversion = $fmatch[5];
    
            $positive = true;
            if ($value < 0) {
                $positive = false;
                $value  *= -1;
            }
            $letter = $positive ? 'p' : 'n';
    
            $prefix = $suffix = $cprefix = $csuffix = $signal = '';
    
            $signal = $positive ? $locale['positive_sign'] : $locale['negative_sign'];
            switch (true) {
                case $locale["{$letter}_sign_posn"] == 1 && $flags['usesignal'] == '+':
                    $prefix = $signal;
                    break;
                case $locale["{$letter}_sign_posn"] == 2 && $flags['usesignal'] == '+':
                    $suffix = $signal;
                    break;
                case $locale["{$letter}_sign_posn"] == 3 && $flags['usesignal'] == '+':
                    $cprefix = $signal;
                    break;
                case $locale["{$letter}_sign_posn"] == 4 && $flags['usesignal'] == '+':
                    $csuffix = $signal;
                    break;
                case $flags['usesignal'] == '(':
                case $locale["{$letter}_sign_posn"] == 0:
                    $prefix = '(';
                    $suffix = ')';
                    break;
            }
            if (!$flags['nosimbol']) {
                $currency = $cprefix .
                            ($conversion == 'i' ? $locale['int_curr_symbol'] : $locale['currency_symbol']) .
                            $csuffix;
            } else {
                $currency = '';
            }
            $space  = $locale["{$letter}_sep_by_space"] ? ' ' : '';
    
            $value = number_format($value, $right, $locale['mon_decimal_point'],
                    $flags['nogroup'] ? '' : $locale['mon_thousands_sep']);
            $value = @explode($locale['mon_decimal_point'], $value);
    
            $n = strlen($prefix) + strlen($currency) + strlen($value[0]);
            if ($left > 0 && $left > $n) {
                $value[0] = str_repeat($flags['fillchar'], $left - $n) . $value[0];
            }
            $value = implode($locale['mon_decimal_point'], $value);
            if ($locale["{$letter}_cs_precedes"]) {
                $value = $prefix . $currency . $space . $value . $suffix;
            } else {
                $value = $prefix . $value . $space . $currency . $suffix;
            }
            if ($width > 0) {
                $value = str_pad($value, $width, $flags['fillchar'], $flags['isleft'] ?
                        STR_PAD_RIGHT : STR_PAD_LEFT);
            }
    
            $format = str_replace($fmatch[0], $value, $format);
        }
        return $format;
    }
}

function format_percentage($percentage, $precision = 2) {
    return round($percentage, $precision) . '%';
}

function calculate_percentage($number, $total) {

    // Can't divide by zero so let's catch that early.
    if ($total == 0) {
        return 0;
    }

    return ($number / $total) * 100;
}

function calculate_percentage_for_display($number, $total) {
    return format_percentage(calculate_percentage($number, $total));
}