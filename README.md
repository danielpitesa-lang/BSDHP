# die BSD UG – Website (Entwurf)

Statische Website (HTML/CSS/JS + optionaler PHP-Formular-Handler) im dunklen
"Cinema"-Look (Blau/Schwarz, passend zum Logo) für die Fahrschule die BSD UG
in Gerlingen.

## Struktur

```
BSDHP-website/
  index.html               Startseite
  kurse.html                Alle Führerscheinklassen & Weiterbildungen im Detail
  preise-anmeldung.html     Preistabellen (B, BE, A, B196, ASF) + Anmeldeformular
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
Klasse A, B196, Klasse BE und ASF. Die Werte wurden auf Basis der öffentlichen
Preisliste eines Wettbewerbers in Gerlingen (Stand 01.04.2026) errechnet,
jeweils 10 % darunter. Diese Kalkulation berücksichtigt keine eigenen Kosten
oder Margen – bitte vor Veröffentlichung selbst gegenprüfen, ob die Preise
wirtschaftlich tragfähig sind. Für Klassen ohne Vergleichsbasis
(AM, A1, C1/C1E, C/CE, D/DE, BKF, Weiterbildung, Erste-Hilfe, ADR) verweist
die Seite auf eine individuelle Anfrage.

## Wichtig vor dem Go-Live

1. **contact-handler.php**: `$to_email` (z. B. info@die-bsd.de) und
   `$from_domain` auf die echte Domain setzen.
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
