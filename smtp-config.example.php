<?php
/**
 * Vorlage fuer die SMTP-Zugangsdaten des Kontaktformulars.
 *
 * WICHTIG:
 * 1. Diese Datei in "smtp-config.php" umbenennen (bzw. eine Kopie anlegen).
 * 2. Das echte Passwort des Postfachs info@die-bsd.com eintragen.
 * 3. smtp-config.php NUR direkt im Hostinger-Dateimanager anlegen/bearbeiten
 *    - NIEMALS ins GitHub-Repository committen (steht bereits in .gitignore).
 *    So bleibt das Passwort erhalten, auch wenn ueber GitHub neue Versionen
 *    der Website ausgerollt werden.
 */

return [
    "host"     => "smtp.hostinger.com",
    "port"     => 465,
    "username" => "info@die-bsd.com",
    "password" => "HIER_DAS_ECHTE_POSTFACH_PASSWORT_EINTRAGEN",
];
