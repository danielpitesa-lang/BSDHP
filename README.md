# Fahrschule BSD – Website (Entwurf)

Statische Website (HTML/CSS/JS + optionaler PHP-Formular-Handler) im dunklen
"Cinema"-Look für die Fahrschule die BSD UG in Leonberg.

## Struktur

```
BSDHP-website/
  index.html               Startseite
  kurse.html                Führerscheinklassen im Detail
  preise-anmeldung.html     Preise + Anmeldeformular
  impressum.html            Impressum (Platzhalter, siehe unten)
  datenschutz.html          Datenschutzerklärung (Platzhalter, siehe unten)
  contact-handler.php       PHP-Mail-Handler fürs Anmeldeformular (Hostinger)
  assets/css/style.css      Gesamtes Styling
  assets/js/main.js         Mobile Nav + Formular-Versand
```

## Wichtig vor dem Go-Live

1. **Platzhalter ersetzen**: In `impressum.html`, `datenschutz.html` und
   `preise-anmeldung.html` sind alle mit `[bitte ergänzen]` markierten Stellen
   auszufüllen (Adresse, Kontakt, Registernummer, USt-ID, Preise, Fahrlehrererlaubnis-Nr.).
   Das sind rechtlich vorgeschriebene Angaben – Vorlage ist keine Rechtsberatung,
   bitte vor Veröffentlichung prüfen lassen.
2. **contact-handler.php**: `$to_email` und `$from_domain` auf eure echten
   Werte setzen.
3. **Bilder**: Aktuell rein CSS-basiertes Design ohne echte Fotos. Sobald Fotos
   vom Fahrzeug/Team/Standort Leonberg vorliegen, in `assets/img/` ablegen und
   im Hero-Bereich (`index.html`) ergänzen.

## Ins GitHub-Repo bringen

Das Repo `https://github.com/danielpitesa-lang/BSDHP.git` ist aktuell leer.
Lokal (z. B. in deinem Terminal):

```bash
git clone https://github.com/danielpitesa-lang/BSDHP.git
# Inhalte dieses Ordners (BSDHP-website/*) in den geklonten Ordner kopieren
cd BSDHP
git add .
git commit -m "Erste Version der Website (Cinema Style)"
git push origin main
```

## Deployment auf Hostinger

- **Ohne Git-Integration**: Dateien per Datei-Manager oder FTP in den
  `public_html`-Ordner (bzw. Unterordner der Domain) hochladen.
- **Mit Git-Integration**: Falls im Hostinger-Dashboard "Git" verfügbar ist,
  kann das GitHub-Repo direkt verbunden und bei jedem Push automatisch
  deployed werden.
- PHP wird von Hostinger-Shared-Hosting unterstützt, `contact-handler.php`
  sollte damit ohne Zusatzkonfiguration funktionieren.
