# die BSD UG – Website (Entwurf)

Statische Website (HTML/CSS/JS + optionaler PHP-Formular-Handler) im dunklen
"Cinema"-Look (Blau/Schwarz, passend zum Logo) für die Fahrschule die BSD UG
in Gerlingen.

## Struktur

```
BSDHP-website/
  index.html               Startseite
  kurse.html                Alle Führerscheinklassen & Weiterbildungen im Detail
  preise-anmeldung.html     Preistabellen (B, BE, A, B196) + Anmeldeformular
  impressum.html            Impressum (mit echten Registerdaten, ein offenes Feld)
  datenschutz.html          Datenschutzerklärung (Vorlage, wenige offene Felder)
  contact-handler.php       PHP-Mail-Handler fürs Anmeldeformular (Hostinger)
  assets/css/style.css      Gesamtes Styling
  assets/js/main.js         Mobile Nav + Formular-Versand
```

## Datenbasis

Adresse, Telefon, E-Mail, Handelsregister (HRB 758055, Amtsgericht Stuttgart),
USt-IdNr. (DE308412711) und Aufsichtsbehörde (Landratsamt Ludwigsburg) wurden
von der bestehenden Website https://www.die-bsd.de/ übernommen.

Der Leistungsumfang (Kurse-Seite) deckt sowohl den privaten Führerschein
(AM, A1, A, B, BE) als auch den beruflichen Bereich ab (C1/C1E/C/CE/D/DE,
BKF-Grundqualifizierung, Modul-Weiterbildung/Schlüsselzahl 95, Erste-Hilfe,
ADR, Ladungssicherung, Feuerwehrschulungen).

Die Preise-Seite enthält eigene Preistabellen für Klasse B (Basic/Premium),
Klasse A, B196 und Klasse BE. Die Werte wurden auf Basis der öffentlichen
Preisliste eines Wettbewerbers in Gerlingen (Stand 01.04.2026) errechnet,
jeweils 10 % darunter. Diese Kalkulation berücksichtigt keine eigenen Kosten
oder Margen – bitte vor Veröffentlichung selbst gegenprüfen, ob die Preise
wirtschaftlich tragfähig sind. Für Klassen ohne Vergleichsbasis
(AM, A1, C1/C1E, C/CE, D/DE, BKF, Weiterbildung, Erste-Hilfe, ADR) verweist
die Seite auf eine individuelle Anfrage.

## Wichtig vor dem Go-Live

1. ~~**contact-handler.php**: `$to_email`/`$from_domain` auf die echte
   Domain setzen.~~ Erledigt – info@die-bsd.com / die-bsd.com.
   SPF/DKIM/DMARC sind bei Hostinger für die Domain bereits korrekt gesetzt.
   **Noch offen:** Damit Mails nicht im Spam landen, muss der Handler über
   das echte, authentifizierte Postfach senden (nicht nur PHP `mail()`):
   - `smtp-config.example.php` kopieren zu `smtp-config.php` (**nur direkt
     im Hostinger-Dateimanager**, nicht über GitHub committen).
   - **Wichtig:** Die Datei muss EINE EBENE OBERHALB von `public_html`
     liegen (im Dateimanager: "Home" → dort anlegen, nicht in
     `public_html` hinein). Hostinger checkt `public_html` bei jedem
     GitHub-Push frisch aus – Dateien direkt darin, die nicht in Git
     stehen, werden dabei wieder gelöscht. Eine Ebene höher übersteht die
     Datei jeden Deploy und ist zusätzlich nie per URL erreichbar.
   - Darin das echte Passwort von info@die-bsd.com eintragen.
   - Ohne diese Datei funktioniert das Formular trotzdem (Fallback auf
     PHP `mail()`), landet aber ggf. im Spam-Ordner.
2. **Bilder**: Aktuell rein CSS-basiertes Design ohne echte Fotos. Sobald
   Fotos vom Fahrzeug/Team/Standort Gerlingen vorliegen, in `assets/img/`
   ablegen und im Hero-Bereich (`index.html`) ergänzen.
3. Rechtstexte (Impressum/Datenschutz) sind sorgfältig erstellte Vorlagen,
   aber keine Rechtsberatung – vor Veröffentlichung final prüfen lassen.

## Ins GitHub-Repo bringen

Das Repo `https://github.com/danielpitesa-lang/BSDHP.git` wurde bereits mit
diesem Stand aktualisiert (Root-Dateien, `assets/css/style.css`,
`assets/js/main.js`, alte `styles.css` entfernt).

## Deployment auf Hostinger

- **Ohne Git-Integration**: Dateien per Datei-Manager oder FTP in den
  `public_html`-Ordner (bzw. Unterordner der Domain) hochladen.
- **Mit Git-Integration**: Falls im Hostinger-Dashboard "Git" verfügbar ist,
  kann das GitHub-Repo direkt verbunden und bei jedem Push automatisch
  deployed werden.
- PHP wird von Hostinger-Shared-Hosting unterstützt, `contact-handler.php`
  sollte damit ohne Zusatzkonfiguration funktionieren.
