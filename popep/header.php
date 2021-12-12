<?php
include "autoload.php";

use \Clases\BaseDeDatos as bd;
use \Clases\Usuario;
use \Clases\Publicacion;
use \Clases\Mensaje;

session_start();
if (isset($_SESSION['rol'])) {
    $bd = new bd($_SESSION['rol']);
} else {
    $bd = new bd();
}
?>

<!DOCTYPE html>
<html style="font-size: 16px;">

    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="utf-8">
        <meta name="keywords"
              content="Sólo irás más rápido, acompañado llegarás más lejos, ¿Por qué Popep es la casa de los emprendedores?, ¿Tienes un pryecto?, SÍGUENOS EN">
        <meta name="description" content="">
        <meta name="page_type" content="np-template-header-footer-from-plugin">
        <title>Casa</title>
        <link rel="stylesheet" href="nicepage.css" media="screen">
        <link rel="stylesheet" href="Casa.css" media="screen">

        <script class="u-script" type="text/javascript" src="jquery.js" defer=""></script>
        <script class="u-script" type="text/javascript" src="nicepage.js" defer=""></script>
        <meta name="generator" content="Nicepage 4.0.3, nicepage.com">
        <link id="u-theme-google-font" rel="stylesheet"
              href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i|Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i">
        <link id="u-page-google-font" rel="stylesheet"
              href="https://fonts.googleapis.com/css?family=Raleway:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i|Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i">






        <script type="application/ld+json">{
            "@context": "http://schema.org",
            "@type": "Organization",
            "name": ""
            }</script>
        <meta name="theme-color" content="#478ac9">
        <meta property="og:title" content="Casa">
        <meta property="og:type" content="website">
    </head>

    <body data-home-page="Casa.html" data-home-page-title="Casa" class="u-body">
        <header class="u-align-center-xs u-border-1 u-border-grey-25 u-clearfix u-header u-header" id="sec-992b">
            <div class="u-clearfix u-sheet u-sheet-1">
                <img class="u-image u-image-default u-preserve-proportions u-image-1" src="images/logo_size_invert.jpg" alt=""
                     data-image-width="165" data-image-height="165">
                <form action="#" method="get" class="u-border-1 u-border-grey-15 u-search u-search-right u-search-1">
                    <button class="u-search-button" type="submit">
                        <span class="u-search-icon u-spacing-10 u-text-grey-40">
                            <svg class="u-svg-link" preserveAspectRatio="xMidYMin slice" viewBox="0 0 56.966 56.966" style="">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-b04b"></use>
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1"
                                 id="svg-b04b" x="0px" y="0px" viewBox="0 0 56.966 56.966" style="enable-background:new 0 0 56.966 56.966;"
                                 xml:space="preserve" class="u-svg-content">
                            <path
                                d="M55.146,51.887L41.588,37.786c3.486-4.144,5.396-9.358,5.396-14.786c0-12.682-10.318-23-23-23s-23,10.318-23,23  s10.318,23,23,23c4.761,0,9.298-1.436,13.177-4.162l13.661,14.208c0.571,0.593,1.339,0.92,2.162,0.92  c0.779,0,1.518-0.297,2.079-0.837C56.255,54.982,56.293,53.08,55.146,51.887z M23.984,6c9.374,0,17,7.626,17,17s-7.626,17-17,17  s-17-7.626-17-17S14.61,6,23.984,6z">
                            </path>
                            <g></g>
                            <g></g>
                            <g></g>
                            <g></g>
                            <g></g>
                            <g></g>
                            <g></g>
                            <g></g>
                            <g></g>
                            <g></g>
                            <g></g>
                            <g></g>
                            <g></g>
                            <g></g>
                            <g></g>
                            </svg>
                        </span>
                    </button>
                    <input class="u-search-input" type="search" name="search" value="" placeholder="Search">
                </form>
                <nav class="u-align-left u-font-size-14 u-menu u-menu-dropdown u-nav-spacing-25 u-offcanvas u-menu-1">
                    <div class="menu-collapse">
                        <a class="u-button-style u-nav-link" href="#" style="padding: 4px 0px; font-size: calc(1em + 8px);">
                            <svg class="u-svg-link" preserveAspectRatio="xMidYMin slice" viewBox="0 0 302 302" style="">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-7b92"></use>
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1"
                                 id="svg-7b92" x="0px" y="0px" viewBox="0 0 302 302" style="enable-background:new 0 0 302 302;"
                                 xml:space="preserve" class="u-svg-content">
                            <g>
                            <rect y="36" width="302" height="30"></rect>
                            <rect y="236" width="302" height="30"></rect>
                            <rect y="136" width="302" height="30"></rect>
                            </g>
                            <g></g>
                            <g></g>
                            <g></g>
                            <g></g>
                            <g></g>
                            <g></g>
                            <g></g>
                            <g></g>
                            <g></g>
                            <g></g>
                            <g></g>
                            <g></g>
                            <g></g>
                            <g></g>
                            <g></g>
                            </svg>
                        </a>
                    </div>
                    <div class="u-custom-menu u-nav-container">
                        <ul class="u-nav u-unstyled u-nav-1">
                            <li class="u-nav-item"><a
                                    class="u-button-style u-nav-link u-text-active-palette-1-base u-text-hover-palette-2-base"
                                    href="index.php" style="padding: 10px 20px;">Home</a>
                            </li>
                            <li class="u-nav-item"><a
                                    class="u-button-style u-nav-link u-text-active-palette-1-base u-text-hover-palette-2-base"
                                    href="About.php" style="padding: 10px 20px;">Sobre nosotros</a>
                            </li>
                            <li class="u-nav-item"><a
                                    class="u-button-style u-nav-link u-text-active-palette-1-base u-text-hover-palette-2-base"
                                    href="Contact.php" style="padding: 10px 20px;">Contacto</a>
                            </li>
                            <?php
                            if (isset($_SESSION)) {
//                                if ($_SESSION['rol'] == 2) {
                                    ?>
                                    <li class="u-nav-item"><a
                                            class="u-button-style u-nav-link u-text-active-palette-1-base u-text-hover-palette-2-base"
                                            href="panel.php" style="padding: 10px 20px;">Panel admin</a>
                                    </li>
                                    <?php
                                }
//                            }
                            ?>
                        </ul>
                    </div>
                    <div class="u-custom-menu u-nav-container-collapse">
                        <div
                            class="u-align-center u-black u-container-style u-inner-container-layout u-opacity u-opacity-95 u-sidenav">
                            <div class="u-inner-container-layout u-sidenav-overflow">
                                <div class="u-menu-close"></div>
                                <ul class="u-align-center u-nav u-popupmenu-items u-unstyled u-nav-2">
                                    <li class="u-nav-item"><a class="u-button-style u-nav-link" href="Casa.php">Casa</a>
                                    </li>
                                    <li class="u-nav-item"><a class="u-button-style u-nav-link" href="About.php">About</a>
                                    </li>
                                    <li class="u-nav-item"><a class="u-button-style u-nav-link" href="Contact.php">Contact</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="u-black u-menu-overlay u-opacity u-opacity-70"></div>
                    </div>
                </nav>
            </div>
        </header>