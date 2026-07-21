// Mobile nav toggle
document.addEventListener("DOMContentLoaded", function () {
  var toggle = document.querySelector(".nav-toggle");
  var links = document.querySelector(".nav-links");
  if (toggle && links) {
    toggle.addEventListener("click", function () {
      var isOpen = links.style.display === "flex";
      links.style.display = isOpen ? "none" : "flex";
      links.style.flexDirection = "column";
      links.style.position = "absolute";
      links.style.top = "76px";
      links.style.left = "0";
      links.style.right = "0";
      links.style.background = "#0f0f16";
      links.style.padding = "16px 24px";
      links.style.borderBottom = "1px solid rgba(255,255,255,0.08)";
    });
  }

  var form = document.getElementById("anmeldeform");
  if (form) {
    form.addEventListener("submit", function (e) {
      e.preventDefault();
      var status = document.getElementById("form-status");
      var data = new FormData(form);

      fetch("contact-handler.php", {
        method: "POST",
        body: data
      })
        .then(function (res) {
          if (!res.ok) throw new Error("Serverfehler");
          return res.text();
        })
        .then(function () {
          status.textContent = "Danke! Deine Anfrage wurde verschickt. Wir melden uns zeitnah bei dir.";
          status.className = "form-status success";
          form.reset();
        })
        .catch(function () {
          status.textContent = "Es gab ein Problem beim Versand. Bitte ruf uns direkt an oder schreib uns per E-Mail.";
          status.className = "form-status error";
        });
    });
  }
});
