<?php
/**
 * Mail-Weiterleitung fuer das Anmeldeformular.
 *
 * Versendet per authentifiziertem SMTP (nicht PHP mail()), damit die Mails
 * nicht im Spam landen - SPF/DKIM/DMARC bei Hostinger sind fuer die Domain
 * bereits korrekt konfiguriert, aber nur ein Versand ueber das echte,
 * authentifizierte Postfach nutzt das auch tatsaechlich aus.
 *
 * SETUP (einmalig, direkt auf dem Server):
 * 1. "smtp-config.example.php" als Vorlage nehmen.
 * 2. Eine Kopie als "smtp-config.php" EINE EBENE OBERHALB von public_html
 *    anlegen (im Hostinger-Dateimanager auf "Home" -> nicht in
 *    public_html hinein, sondern daneben) - WICHTIG, sonst wird die Datei
 *    beim naechsten GitHub-Push wieder geloescht (Hostinger checkt
 *    public_html bei jedem Deploy frisch aus Git aus).
 * 3. Darin das echte Passwort von info@die-bsd.com eintragen.
 *
 * Ohne smtp-config.php faellt der Handler auf PHP mail() zurueck
 * (funktioniert, aber Spam-Risiko) und meldet das im Log.
 */

header("Content-Type: text/plain; charset=utf-8");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo "Methode nicht erlaubt.";
    exit;
}

$to_email    = "info@die-bsd.com";
$from_domain = "die-bsd.com";

function clean($value) {
    return htmlspecialchars(trim($value ?? ""), ENT_QUOTES, "UTF-8");
}

$vorname     = clean($_POST["vorname"] ?? "");
$nachname    = clean($_POST["nachname"] ?? "");
$email       = clean($_POST["email"] ?? "");
$telefon     = clean($_POST["telefon"] ?? "");
$klasse      = clean($_POST["klasse"] ?? "");
$nachricht   = clean($_POST["nachricht"] ?? "");
$datenschutz = isset($_POST["datenschutz"]);

// Grundlegende Validierung
if (!$vorname || !$nachname || !filter_var($email, FILTER_VALIDATE_EMAIL) || !$datenschutz) {
    http_response_code(400);
    echo "Bitte alle Pflichtfelder korrekt ausfuellen.";
    exit;
}

$subject = "Neue Anmeldung: $vorname $nachname ($klasse)";

$body = "Neue Anfrage über das Anmeldeformular\n\n"
      . "Name: $vorname $nachname\n"
      . "E-Mail: $email\n"
      . "Telefon: $telefon\n"
      . "Gewuenschte Klasse: $klasse\n"
      . "Nachricht:\n$nachricht\n";

/**
 * Minimaler SMTP-Client ueber ein TLS-Socket (kein externes Package noetig).
 * Wirft eine Exception bei jedem Fehler, damit der Aufrufer sauber auf
 * mail() zurueckfallen kann.
 */
function send_via_smtp($host, $port, $user, $pass, $fromEmail, $fromName, $toEmail, $replyTo, $subject, $body) {
    $timeout = 15;
    $socket = @stream_socket_client(
        "ssl://$host:$port",
        $errno,
        $errstr,
        $timeout,
        STREAM_CLIENT_CONNECT
    );
    if (!$socket) {
        throw new Exception("SMTP-Verbindung fehlgeschlagen: $errstr ($errno)");
    }

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
            throw new Exception("Unerwartete SMTP-Antwort (erwartet $code): $resp");
        }
        return $resp;
    };

    $expect(220);
    $write("EHLO " . ($_SERVER["SERVER_NAME"] ?? "localhost"));
    $expect(250);
    $write("AUTH LOGIN");
    $expect(334);
    $write(base64_encode($user));
    $expect(334);
    $write(base64_encode($pass));
    $expect(235);

    $write("MAIL FROM:<$fromEmail>");
    $expect(250);
    $write("RCPT TO:<$toEmail>");
    $expect(250);
    $write("DATA");
    $expect(354);

    $headers = [];
    $headers[] = "From: $fromName <$fromEmail>";
    $headers[] = "To: <$toEmail>";
    $headers[] = "Reply-To: <$replyTo>";
    $headers[] = "Subject: =?UTF-8?B?" . base64_encode($subject) . "?=";
    $headers[] = "MIME-Version: 1.0";
    $headers[] = "Content-Type: text/plain; charset=UTF-8";
    $headers[] = "Content-Transfer-Encoding: base64";
    $headers[] = "Date: " . date("r");

    // Dot-stuffing gemaess SMTP-Protokoll
    $encodedBody = chunk_split(base64_encode($body));
    $message = implode("\r\n", $headers) . "\r\n\r\n" . $encodedBody . "\r\n.";

    $write($message);
    $expect(250);
    $write("QUIT");
    fclose($socket);

    return true;
}

$sent = false;
// Liegt bewusst EINE Ebene oberhalb von public_html (also ausserhalb des
// Web-Verzeichnisses und ausserhalb des GitHub-Deployments) - so bleibt
// die Datei erhalten, auch wenn Hostinger bei jedem Push public_html neu
// auscheckt, und sie ist nie direkt per URL aufrufbar.
$configFile = dirname(__DIR__) . "/smtp-config.php";

if (file_exists($configFile)) {
    try {
        $cfg = require $configFile;
        send_via_smtp(
            $cfg["host"],
            $cfg["port"],
            $cfg["username"],
            $cfg["password"],
            $cfg["username"],
            "Website Anmeldeformular",
            $to_email,
            $email,
            $subject,
            $body
        );
        $sent = true;
    } catch (Throwable $e) {
        error_log("SMTP-Versand fehlgeschlagen, falle auf mail() zurueck: " . $e->getMessage());
    }
}

if (!$sent) {
    // Fallback: einfache PHP mail() (funktioniert, aber hoeheres Spam-Risiko
    // ohne authentifizierten SMTP-Login).
    $headers   = [];
    $headers[] = "From: Website Anmeldeformular <no-reply@$from_domain>";
    $headers[] = "Reply-To: $email";
    $headers[] = "Content-Type: text/plain; charset=utf-8";

    $sent = mail($to_email, $subject, $body, implode("\r\n", $headers));
}

if ($sent) {
    http_response_code(200);
    echo "OK";
} else {
    http_response_code(500);
    echo "Mail konnte nicht gesendet werden.";
}
