<?php
/**
 * TEMPORAERES Diagnose-Skript - testet nur den SMTP-Login, verschickt
 * keine Mail. Nach dem Test wieder loeschen (auch aus GitHub)!
 */

header("Content-Type: text/plain; charset=utf-8");

$configFile = dirname(__DIR__) . "/smtp-config.php";

if (!file_exists($configFile)) {
    echo "FEHLER: smtp-config.php existiert nicht in " . __DIR__;
    exit;
}

echo "Roh-Dateiinhalt (raw):\n---\n" . file_get_contents($configFile) . "\n---\n\n";

$cfg = require $configFile;
echo "var_export von \$cfg:\n";
var_export($cfg);
echo "\n\n";
echo "Typ von \$cfg: " . gettype($cfg) . "\n\n";

try {
    $socket = @stream_socket_client(
        "ssl://" . $cfg["host"] . ":" . $cfg["port"],
        $errno,
        $errstr,
        15,
        STREAM_CLIENT_CONNECT
    );
    if (!$socket) {
        throw new Exception("Verbindung fehlgeschlagen: $errstr ($errno)");
    }
    echo "Verbindung zum SMTP-Server hergestellt.\n";

    $read = function () use ($socket) {
        $data = "";
        while ($line = fgets($socket, 515)) {
            $data .= $line;
            if (isset($line[3]) && $line[3] === " ") break;
        }
        return $data;
    };
    $write = function ($cmd) use ($socket) {
        fwrite($socket, $cmd . "\r\n");
    };
    $expect = function ($code) use ($read) {
        $resp = $read();
        if (substr($resp, 0, 3) !== (string) $code) {
            throw new Exception("Erwartet $code, bekommen: $resp");
        }
        return $resp;
    };

    echo $expect(220);
    $write("EHLO " . ($_SERVER["SERVER_NAME"] ?? "localhost"));
    echo $expect(250);
    $write("AUTH LOGIN");
    echo $expect(334);
    $write(base64_encode($cfg["username"]));
    echo $expect(334);
    $write(base64_encode($cfg["password"]));
    echo $expect(235);

    echo "\nSMTP-LOGIN ERFOLGREICH.\n";
    $write("QUIT");
    fclose($socket);
} catch (Throwable $e) {
    echo "\nSMTP-LOGIN FEHLGESCHLAGEN: " . $e->getMessage() . "\n";
}
