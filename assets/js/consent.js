// Einfacher Cookie-Consent fuer Google Analytics (GA4).
// Analytics wird NUR nach aktiver Zustimmung geladen (DSGVO/TTDSG-konform).
// Entscheidung wird lokal im Browser gespeichert (localStorage), nicht
// personenbezogen und nicht serverseitig erfasst.

(function () {
  var GA_MEASUREMENT_ID = "G-20TDVK5R9E";
  var STORAGE_KEY = "bsd-cookie-consent"; // "granted" | "denied"

  function loadGoogleAnalytics() {
    if (window.__gaLoaded) return;
    window.__gaLoaded = true;

    var script = document.createElement("script");
    script.async = true;
    script.src = "https://www.googletagmanager.com/gtag/js?id=" + GA_MEASUREMENT_ID;
    document.head.appendChild(script);

    window.dataLayer = window.dataLayer || [];
    function gtag() { window.dataLayer.push(arguments); }
    window.gtag = gtag;
    gtag("js", new Date());
    // anonymize_ip ist bei GA4 Standard, explizit trotzdem gesetzt.
    gtag("config", GA_MEASUREMENT_ID, { anonymize_ip: true });
  }

  function hideBanner(banner) {
    if (banner) banner.remove();
  }

  function initConsentBanner() {
    var stored = null;
    try {
      stored = window.localStorage.getItem(STORAGE_KEY);
    } catch (e) {
      // localStorage evtl. blockiert - dann kein Tracking, aber auch kein Banner-Fehler.
    }

    if (stored === "granted") {
      loadGoogleAnalytics();
      return;
    }
    if (stored === "denied") {
      return;
    }

    // Noch keine Entscheidung getroffen -> Banner anzeigen.
    var banner = document.createElement("div");
    banner.className = "cookie-banner";
    banner.innerHTML =
      '<div class="cookie-banner-text">' +
      "Wir nutzen Google Analytics, um zu verstehen, wie Besucher unsere Website nutzen, " +
      'und so unser Angebot zu verbessern. Mehr dazu in unserer <a href="datenschutz.html">Datenschutzerklärung</a>.' +
      "</div>" +
      '<div class="cookie-banner-actions">' +
      '<button type="button" class="btn btn-secondary" data-consent="denied">Ablehnen</button>' +
      '<button type="button" class="btn btn-primary" data-consent="granted">Akzeptieren</button>' +
      "</div>";
    document.body.appendChild(banner);

    banner.addEventListener("click", function (e) {
      var choice = e.target && e.target.getAttribute("data-consent");
      if (!choice) return;
      try {
        window.localStorage.setItem(STORAGE_KEY, choice);
      } catch (e2) {
        // ignore
      }
      if (choice === "granted") loadGoogleAnalytics();
      hideBanner(banner);
    });
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initConsentBanner);
  } else {
    initConsentBanner();
  }
})();
