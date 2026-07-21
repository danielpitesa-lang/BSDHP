# die BSD UG – Website (Entwurf)

Statische Website (HTML/CSS/JS + optionaler PHP-Formular-Handler) im dunklen
"Cinema"-Look (Blau/Schwarz, passend zum Logo) für die Fahrschule die BSD UG
in Gerlingen.

## Struktur

```
BSDHP-website/
  index.html               Startseite
  kurse.html                Alle Führerscheinklassen & Weiterbildungen im Detail
  preise-anmeldung.html     Preise (Link zur bestehenden Preisliste) + Anmeldeformular
  impressum.html            Impressum (mit echten Registerdaten, ein offenes Feld)
  datenschutz.html          Datenschutzerklärung (Vorlage, wenige offene Felder)
  contact-handler.php       PHP-Mail-Handler fürs Anmeldeformular (Hostinger)
  assets/css/style.css      Gesamtes Styling
  assets/js/main.js         Mobile Nav + Formular-Versand
```

## Datenbasis

Adresse, Telefon, E-Mail, Handelsregister (HRB 758055, Amtsgericht Stuttgart),
USt-IdNr. (DE308412711) und Aufsichtsbehörde (Landratsamt Ludwigsburg) wurden
von der bestehenden Website https://www.die-bsd.de/ übernommen. Offen ist nur
noch die Fahrlehrerlaubnis-Nummer in `impressum.html`.

Der Leistungsumfang (Kurse-Seite) deckt sowohl den privaten Führerschein
(AM, A1, A, B, BE) als auch den beruflichen Bereich ab (C1/C1E/C/CE/D/DE,
BKF-Grundqualifizierung, Modul-Weiterbildung/Schlüsselzahl 95, Erste-Hilfe,
ADR, Ladungssicherung, Feuerwehrschulungen).

Die Preise-Seite verlinkt bewusst auf die bestehende Preisliste unter
https://www.die-bsd.de/preise statt eine eigene Tabelle zu pflegen – so bleibt
sie automatisch aktuell.

## Wichtig vor dem Go-Live

1. **Fahrlehrerlaubnis-Nummer** in `impressum.html` ergänzen (auf der alten
   Seite nicht angegeben).
2. **contact-handler.php**: `$to_email` (z. B. info@die-bsd.de) und
   `$from_domain` auf die echte Domain setzen.
3. **Bilder**: Aktuell rein CSS-basiertes Design ohne echte Fotos. Sobald
   Fotos vom Fahrzeug/Team/Standort Gerlingen vorliegen, in `assets/img/`
   ablegen und im Hero-Bereich (`index.html`) ergänzen.
4. Rechtstexte (Impressum/Datenschutz) sind sorgfältig erstellte Vorlagen,
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
