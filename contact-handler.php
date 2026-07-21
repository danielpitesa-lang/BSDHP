<?php
/**
 * Einfache Mail-Weiterleitung für das Anmeldeformular.
 * Laeuft auf Hostinger-Shared-Hosting (PHP mail()-Funktion).
 *
 * WICHTIG vor dem Einsatz:
 * 1. $to_email auf die echte Empfaenger-Adresse der Fahrschule setzen.
 * 2. $from_domain an die eigene Domain anpassen (SPF/DKIM bei Hostinger
 *    fuer diese Domain aktivieren, sonst landen Mails im Spam).
 * 3. Optional: reCAPTCHA / Honeypot ergaenzen, um Spam zu reduzieren.
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

$vorname    = clean($_POST["vorname"] ?? "");
$nachname   = clean($_POST["nachname"] ?? "");
$email      = clean($_POST["email"] ?? "");
$telefon    = clean($_POST["telefon"] ?? "");
$klasse     = clean($_POST["klasse"] ?? "");
$nachricht  = clean($_POST["nachricht"] ?? "");
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

$headers   = [];
$headers[] = "From: Website Anmeldeformular <no-reply@$from_domain>";
$headers[] = "Reply-To: $email";
$headers[] = "Content-Type: text/plain; charset=utf-8";

$sent = mail($to_email, $subject, $body, implode("\r\n", $headers));

if ($sent) {
    http_response_code(200);
    echo "OK";
} else {
    http_response_code(500);
    echo "Mail konnte nicht gesendet werden.";
}
