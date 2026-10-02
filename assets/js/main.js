/* ==========================================================================
   MAIN JS - Logiche della Landing Page
   ========================================================================== */

document.addEventListener('DOMContentLoaded', () => {
  
  // Inizializzazione dei Moduli
  initBackToTop();
  initServiziToggle();

});


/* ==========================================================================
   1. Pulsante Back To Top
   ========================================================================== */
function initBackToTop() {
  const backToTopBtn = document.getElementById("btn-back-to-top");
  
  // Se l'elemento non esiste nella pagina corrente, evita errori e interrompe
  if (!backToTopBtn) return;

  // Gestione visibilità allo scroll
  window.addEventListener("scroll", () => {
    const scrollTop = document.body.scrollTop || document.documentElement.scrollTop;
    backToTopBtn.style.display = scrollTop > 20 ? "flex" : "none";
  });

  // Scroll verso l'alto (con movimento fluido)
  backToTopBtn.addEventListener("click", () => {
    window.scrollTo({
      top: 0,
      behavior: "smooth"
    });
  });
}


/* ==========================================================================
   2. Toggle Testo Bottone "Altri Servizi" (Bootstrap Collapse)
   ========================================================================== */
function initServiziToggle() {
  const altriServiziCollapse = document.getElementById('altriServizi');
  const btnAltriServizi = document.getElementById('btnAltriServizi');

  // Controllo di sicurezza: esce se gli elementi non sono presenti nell'HTML
  if (!altriServiziCollapse || !btnAltriServizi) return;

  altriServiziCollapse.addEventListener('show.bs.collapse', () => {
    btnAltriServizi.textContent = 'Nascondi';
  });

  altriServiziCollapse.addEventListener('hide.bs.collapse', () => {
    btnAltriServizi.textContent = 'Altri servizi';
  });
}