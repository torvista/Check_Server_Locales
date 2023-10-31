<?php

declare(strict_types=1);

/**
 * @link https://github.com/torvista/Check_Server_Locales
 * @author torvista
 * @updated 31 10 2023
 */

/** This utility checks what locales are installed on this server and so what locale you may put in the main language constants file (the two equivalents to english.php)
 *
 * USAGE INSTRUCTIONS:
 * 1. Open your browser
 * 2. Enter the URL for your store, followed by /extras/show_locales.php
 * 3. ... and press Enter
 * 4. Review the locales that are found that correspond to the test names in the following arrays. Results will be different between servers especially Windows/Unix
 */

// These lists are not exhaustive...add more as required.
// Todo: put them all in one multidimensional array for parsing

//The entries commented out all result in a fatal error for php8.3.0RC3+
// Uncaught IntlException: datefmt_create: invalid locale: U_ILLEGAL_ARGUMENT_ERROR

//English
$english = [
    'en',
    'en.UTF-8',
    //'english_america',
    //'english_britain',
    //'english_england',//fail
    //'english_gbr',
    //'english_great britain',
    //'english_uk',
    //'english_united kingdom',
    //'english_united states',
    //'english_united-kingdom',
    //'english_united-states',
    //'english_us',
    //'english_usa',
    'en-GB', //Windows
    'en-US', //Windows
    'en_GB.utf8',
    'en_US',
    'en_US.utf8',
    'en_us_utf8'
];

//Dutch
$dutch = [
    //'Dutch_Netherlands.1252',
    'nl',
    'nl-NL',
    'nld_nld',
    'nl_NL.UTF-8',
    'nl_NL.utf8'
];

//German
$german = [
    'de',
    'deu_deu',
    'de_DE',
    'de_DE.UTF-8',
    'de_DE@euro',
    'de_utf8',
    //'German_Germany.1252'
];

//Spanish
$spanish = [
    'es',
    'es-es',
    'es-ES', //Windows
    //'esp_esp',
    //'esp_spain',
    'es_ES.utf8',
    'es_ES@euro',
    'es_utf-8',
    'es_utf8',
    'es_utf8',
    //'spanish_esp',
    'Spanish_Modern_Sort',
    //'spanish_spain'
];
//----------------------------------------------------------------------------------------------

/**
 * @param $code
 * @param $language
 */
function list_nix_locales($code, $language): void
{
    echo "<h3>$language: using <em>system('locale -a | grep -i $code')</em></h3>";
    echo "<p>The available 'locale' strings for '$code' on this server are:</p>";
    echo "<pre>";
    system("locale -a | grep -i $code");
    echo "</pre>";
}

/**
 * @param $test_names
 * @param $language
 */
function check_locales($test_names, $language): void
{
    echo '<hr>';
    echo "<h3>$language</h3>";
    foreach ($test_names as $value) {
        echo '<hr style="margin-left:0;width:30%">';
        echo "<p>test locale: '<em>$value</em>'</p>";
        $locale_found = setlocale(LC_TIME, $value);

        if ($locale_found !== false) {
            echo "<p>locale '<em><strong>$locale_found</strong></em>' found for '<em>$value</em>'.</p>";
            $formatter = new IntlDateFormatter($value, IntlDateFormatter::FULL, IntlDateFormatter::FULL);
            echo '<p>eg: ' . $formatter->format(time()) . '</p>';
        } else {
            echo "<p>no locale found for '<em>$value</em>'</p>";
        }
    }
}
$isWindows = stripos(PHP_OS_FAMILY, "win") !== false;
$currentLocale = setlocale(LC_ALL, 0);
$os = php_uname();
$serverInfo = "<h3>$os<br>php: " . PHP_VERSION . '<br>current locale="' . $currentLocale . '"</h3>';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Test Locales - <?php
        echo ($isWindows ? ' WINDOWS' : ' UNIX'); ?></title>
    <style>
        body {
            padding: 1em;
            font-family: Verdana, Geneva, sans-serif;
            font-size: .8em
        }
        code, pre {
            font-size: 1.4em
        }
        h1 {
            font-size: 1.2em;
            text-decoration: underline;
        }
        h2 {
            font-size: 1.1em
        }
        h3 {
            font-size: 1em
        }
    </style>
</head>
<body>

<h1>Test Server Locales</h1>
<p>Embedded in this script are lists/arrays of possible locales for both Windows and Unix-based servers for some languages.<br>
    This script tries each one to see if the operating system (Unix/Windows) accepts it/it is installed on this server and so confirm what locale you may use for LC_TIME in the two main Zen Cart
    language files (english.php and their additional language equivalents).<br>
    The embedded lists are not comprehensive, please research and add to the arrays as per your required language.</p>
<hr>
<?php
if ($isWindows) { ?>
    <h2>This is a WINDOWS server</h2>
    <h3><?= $serverInfo; ?></h3>
    <p>It is possible to get a listing of all the installed locales in Windows with the Windows Powershell (requires
        .net).</p>
    <div style="margin-left: 50px">
        <p>Open Windows Powershell console, eg: <code>PS C:\Users\YOU></code></p>
        <p>Enter the command as shown to get the listing:</p>
        <p><code>[System.Globalization.Cultureinfo]::GetCultures('AllCultures')</code></p>
        <p>To be clever and get a csv file of this full listing (change the destination as required), use this set of
            commands on one line (the semicolons concatenate the commands):</p>
        <code>Function global:GET-CULTURE {
            [System.Globalization.Cultureinfo]::GetCultures('AllCultures') }; $locales=GET-CULTURE; $locales | EXPORT-CSV
            D:locales.csv</code>
    </div>
    <p>See the references at the foot of this page to convince you to dump your Windows-based
        hosting, and it's lack of support for utf-8:</p>
    <p>Quote from Microsoft:</p>
    <blockquote>"The locale argument can take a locale name, a language string, a language string and country/region
        code, a code
        page, or a language string, country/region code, and code page. The set of available locale names, languages,
        country/region codes, and code pages includes all those supported by the Windows NLS API except code pages that
        require more than two bytes per character, such as UTF-7 and UTF-8.<br>
        <strong>If you provide a code page value of UTF-7 or UTF-8, setlocale will fail, returning NULL</strong>."
    </blockquote>
<?php } else { ?>
    <h2>This is a UNIX server</h2>
    <h3><?= $serverInfo; ?></h3>
    <?php
    //English en
    list_nix_locales('en', 'English');

    //Dutch en
    list_nix_locales('nl', 'Dutch');

    //German de
    list_nix_locales('de', 'German');

    //Spanish es
    list_nix_locales('es', 'Spanish');
}

//English
check_locales($english, 'English');

//Dutch
check_locales($dutch, 'Dutch');

//German
check_locales($german, 'German');

//Spanish
check_locales($spanish, 'Spanish');

?>
<hr>
<h1>Resources - "Read 'em and Weep"</h1>
<p>Guide to getting PHP, utf-8 and mysql to play together: <a
            href="https://www.toptal.com/php/a-utf-8-primer-for-php-and-mysql" target="_blank">www.toptal.com/php/a-utf-8-primer-for-php-and-mysql</a>
</p>
<p>PHP setlocale: <a href="https://www.php.net/manual/en/function.setlocale.php" target="_blank">php.net/manual/en/function.setlocale.php</a>
</p>
<p>Table of locales/codepages: <a href="https://docs.moodle.org/dev/Table_of_locales" target="_blank">docs.moodle.org/dev/Table_of_locales</a>
</p>
<h2>The Wacky World of Windows</h2>
<p><a href="https://stackoverflow.com/questions/10995953/php-setlocale-in-windows-7" target="_blank">stackoverflow.com/questions/10995953/php-setlocale-in-windows-7</a>
</p>
<p>Table of Locales: <a href="https://www.science.co.il/language/Locale-codes.php#definitions" target="_blank">www.science.co.il/Language/Locale-codes.asp#definitions</a>
</p>
<p>Globalization: <a href="https://docs.microsoft.com/en-us/dotnet/standard/globalization-localization/globalization" target="_blank">docs.microsoft.com/en-us/dotnet/standard/globalization-localization/globalization</a>
</p>
<p>Windows Country/Region Strings: <a href="https://docs.microsoft.com/en-us/cpp/c-runtime-library/country-region-strings?view=vs-2019"
                                      target="_blank">docs.microsoft.com/en-us/cpp/c-runtime-library/country-region-strings?view=vs-2019</a>
</p>
<p>Windows Language Strings: <a href="https://docs.microsoft.com/en-us/cpp/c-runtime-library/language-strings?view=vs-2019" target="_blank">docs.microsoft.com/en-us/cpp/c-runtime-library/language-strings?view=vs-2019</a>
</p>
<p>Windows Language Code Identifiers (LCID): <a href="https://docs.microsoft.com/en-us/openspecs/windows_protocols/ms-lcid/70feba9f-294e-491e-b6eb-56532684c37f"
                                                target="_blank">https://docs.microsoft.com/en-us/openspecs/windows_protocols/ms-lcid/70feba9f-294e-491e-b6eb-56532684c37f</a>
</p>
<p>Locales and Languages (Windows): <a
            href="https://docs.microsoft.com/en-us/windows/desktop/intl/locales-and-languages" target="_blank">docs.microsoft.com/en-us/windows/desktop/intl/locales-and-languages</a>x
</p>
</body>
</html>
