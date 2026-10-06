<?php
/**
 * Navbar condivisa (home + pagine legali).
 *
 * Link ASSOLUTI (/#sezione): dalla home scorrono alla sezione,
 * dalle pagine legali riportano alla home sulla sezione giusta.
 * Solo markup, nessuna logica.
 */
?>
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top" aria-label="Navigazione principale">
    <div class="container-fluid mx-lg-5">
        <a class="navbar-brand fw-bold pb-0" href="/#hero">
            <img alt="Citytel Sistem Srl - Vai alla home" class="img-fluid navbar-logo"
                src="/assets/img/loghi/logo-citytel.svg">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Apri o chiudi il menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="/#hero">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="/#chi-siamo">Chi siamo</a></li>
                <li class="nav-item"><a class="nav-link" href="/#servizi">Servizi</a></li>
                <li class="nav-item"><a class="nav-link" href="/#lavori">Lavori</a></li>
                <li class="nav-item"><a class="nav-link" href="/#perche-sceglierci">Perchè sceglierci</a></li>
                <li class="nav-item"><a class="nav-link" href="/#clienti">Clienti</a></li>
                <li class="nav-item"><a class="nav-link" href="/#lavora-con-noi">Lavora con noi</a></li>
                <li class="nav-item"><a class="nav-link" href="/#footer">Contatti</a></li>
            </ul>
        </div>
    </div>
</nav>