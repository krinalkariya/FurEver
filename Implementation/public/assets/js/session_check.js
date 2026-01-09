// session_check.js
(function () {
  async function checkSession() {
    try {
      const res = await fetch("${window.location.origin}/furever/public/session_check.php", {
        method: "GET",
        cache: "no-store",
        headers: {
          "Accept": "application/json",
        },
      });

      if (!res.ok) return;
      const data = await res.json();

      // If session missing (user logged out) → redirect instantly
      if (!data.logged_in) {
        window.location.href = "${window.location.origin}/furever/public/";
      }
    } catch (err) {
      console.error("Session check failed:", err);
    }
  }

  // Run immediately on page load
  checkSession();

  // Also recheck periodically every 10 seconds (in case session expires mid-use)
  setInterval(checkSession, 10000);
})();