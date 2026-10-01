/**
 * SmoothScroll / Auto-Reveal Library
 * Applicato esclusivamente ai .container-fluid dentro <section>
 */
document.addEventListener('DOMContentLoaded', () => {
  // Seleziona unicamente i .container-fluid che si trovano dentro un tag <section>
  const autoRevealSelectors = 'section .container-fluid';

  const targetElements = document.querySelectorAll(autoRevealSelectors);

  // Se non ci sono elementi corrispettivi, interrompe l'esecuzione
  if (targetElements.length === 0) return;

  // 1. Aggiunge la classe .reveal a tutti i target trovati
  targetElements.forEach(el => el.classList.add('reveal'));

  // 2. Configura l'Intersection Observer
  const observerOptions = {
    root: null,
    threshold: 0.15 // L'animazione parte quando il 15% del container è visibile
  };

  const observer = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
        // Rimuove l'osservazione per ottimizzare le prestazioni
        observer.unobserve(entry.target);
      }
    });
  }, observerOptions);

  // 3. Avvia l'osservazione
  targetElements.forEach(el => observer.observe(el));
});