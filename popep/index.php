
<?php
include 'header.php';

$register = false;
$termAcept = true;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['nombre'])) {
        if (isset($_POST['acept'])) {
            $pwd = password_hash($_POST['pwd'], PASSWORD_DEFAULT);
            $registro = $bd->registrar_usuario($_POST['nombre'], $_POST['email'], $_POST['telf'], $_POST['direccion'], $pwd);
            if (!$registro) {
                $register = true;
            } else {
                $_SESSION['usuario'] = $_POST['email'];
                $_SESSION['rol'] = 1;
                $estado = true;
            }
        } else {
            $termAcept = false;
        }
    } else if (isset($_POST['sesionStart'])) {
        $user = $bd->comprobar_usuario($_POST['email'], $_POST['pwd']); //devuelve false si no es un usuario correcto
        if ($user) {
            //Guardamos en variable de sesiÃ³n el rol y el usuario
            $_SESSION['id'] = $user[0];
            $_SESSION['usuario'] = $user[1];
            $_SESSION['rol'] = $user[2];
        } else {
            //Variable de tipo bandera para volver al formulario a pedir los datos pq el login no es correcto
            $error = true;
        }
    }

    $cambio = false;

    if (isset($_POST['valoracion'])) {
        if ($bd->introducirValoracion($_SESSION['id'], $_SESSION['id_publicacionValorada'], $_POST['mensaje'], $_POST['puntuacion'])) {
            $cambio = true;
        }
    }
}
?>

<section class="u-clearfix u-section-1" id="sec-2c7a">
    <div class="u-clearfix u-sheet u-sheet-1">
        <div class="u-clearfix u-expanded-width u-gutter-0 u-layout-wrap u-layout-wrap-1">
            <div class="u-layout">
                <div class="u-layout-row">
                    <?php
                    if (!isset($_SESSION['usuario'])) {
                        ?>
                        <div class="localizacion">
                            <?php
                            if (isset($error) && ($error == true)) {
                                echo "<p>Revise usuario y contraseÃ±a</p>";
                            }
                            ?>
                            <div class="form form_login">
                                <h2>¡Te echábamos de menos!</h2>
                                <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                                    <input type="email" name="email" placeholder="Email">
                                    <input type="password" name="pwd" placeholder="Contraseña">
                                    <br>
                                    <input name="sesionStart" type="submit" id="botonSesion" value="Iniciar sesión">
                                </form>
                            </div>

                            <div class="form">
                                <h2>¡Aún no te conocemos!, regístrate y empieza a apoyar proyectos</h2>
                                <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                                    <input type="text" name="nombre" placeholder="Nombre">
                                    <input type="email" name="email" placeholder="Email">
                                    <input type="text" name="telf" placeholder="Teléfono">
                                    <input type="text" name="direccion" placeholder="Dirección">
                                    <input type="password" name="pwd" placeholder="Contraseña">
                                    <div id="checkbox"><input type="checkbox" value="1" name="acept"><b>He leído y acepto la política de privacidad</b></div>
                                    <input type="submit" id="botonRegistro" value="Registrarme">
                                    <?php
                                    if ($register) {
                                        ?>
                                        <p>El usuario ya está registrado</p>
                                        <?php
                                    } else if (!$termAcept) {
                                        ?>
                                        <p>Debe aceptar los términos</p>
                                        <?php
                                    }
                                    ?>
                                </form>
                            </div>
                        </div>
                        <?php
                    } else {
                        ?>
                        <div class="u-container-style u-grey-5 u-layout-cell u-size-14 u-layout-cell-1">
                            <div class="u-container-layout u-container-layout-1">
                                <?php
                                if ($publicacion = $bd->getDataPublicacion()) {
                                    $data_publicacion = $publicacion->getData();
                                    $_SESSION['id_publicacionValorada'] = $data_publicacion['id'];
                                }else{
                                    $publicacion = null;
                                }

                                if (!empty($publicacion)) {
                                    ?>
                                    <h2>Envíale un mensaje a <?php echo $data_publicacion['usuario'] ?></h2>
                                    <form action="index.php" method="POST">
                                        <textarea name="mensaje" rows="10" cols="22" placeholder="Escribe tu mensaje"></textarea>
                                        <select name="puntuacion">
                                            <option value="1">No apta</option>
                                            <option value="2">Bien</option>
                                            <option value="3">Muy bien</option>
                                            <option value="4">Excelente</option>
                                        </select>
                                        <input type="submit" value="Enviar" name="valoracion"/>
                                    </form>
                                    <?php
                                }else{
                                    echo 'Sin resultados';
                                }
                                ?>
                            </div>
                        </div>
                        <div class="u-container-style u-layout-cell u-size-46 u-layout-cell-2">
                            <div class="u-container-layout u-container-layout-2">
                                <?php
                                if (!empty($publicacion)) {
                                    ?>
                                    <div id="img_publicacion"><img src="<?php echo $data_publicacion['img'] ?>" alt="alt"/></div>
                                    <div id="text_publicacion">
                                        <h2><?php echo $data_publicacion['titulo'] ?></h2>
                                        <p><?php echo $data_publicacion['descripcion'] ?></p>
                                    </div>
                                    <?php
                                } else {
                                    echo '<p>No se han encontrado más publicaciones</p>';
                                }
                                ?>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="u-clearfix u-valign-middle u-white u-section-2" id="carousel_ffea">
    <div class="u-clearfix u-layout-wrap u-layout-wrap-1">
        <div class="u-layout">
            <div class="u-layout-row">
                <div class="u-align-left u-container-style u-layout-cell u-left-cell u-size-23 u-layout-cell-1">
                    <div class="u-container-layout u-valign-top u-container-layout-1">
                        <h1 class="u-custom-font u-font-raleway u-text u-text-custom-color-1 u-title u-text-1">Sólo irás más rápido, acompañado llegarás más lejos</h1>
                        <div class="u-border-6 u-border-black u-line u-line-horizontal u-line-1"></div>
                        <p class="u-text u-text-2"> Construye tu equipo recibiendo ofertas de las personas que están interesadas en tu proyecto</p>
                        <a href="https://nicepage.cloud" class="u-active-white u-border-2 u-border-active-grey-30 u-border-custom-color-2 u-border-hover-grey-30 u-btn u-btn-round u-button-style u-custom-color-1 u-custom-font u-font-raleway u-hover-white u-radius-5 u-btn-1">read more</a>
                    </div>
                </div>
                <div class="u-container-style u-image u-layout-cell u-right-cell u-size-37 u-image-1" data-image-width="1318" data-image-height="1080">
                    <div class="u-container-layout u-container-layout-2"></div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="u-align-center u-clearfix u-custom-color-1 u-section-3" id="carousel_ffba">
    <div class="u-clearfix u-sheet u-sheet-1">
        <h1 class="u-text u-text-1">¿Por qué Popep es la casa de los emprendedores?</h1>
        <p class="u-text u-text-default u-text-2">Un único lugar donde todos nos reunimos</p>
        <div class="u-expanded-width u-list u-list-1">
            <div class="u-repeater u-repeater-1">
                <div class="u-align-center u-container-style u-list-item u-radius-15 u-repeater-item u-shape-round u-white u-list-item-1">
                    <div class="u-container-layout u-similar-container u-container-layout-1"><span class="u-custom-color-3 u-icon u-icon-circle u-spacing-27 u-text-custom-color-1 u-icon-1"><svg class="u-svg-link" preserveAspectRatio="xMidYMin slice" viewBox="0 0 55 55" style=""><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-5725"></use></svg><svg class="u-svg-content" viewBox="0 0 55 55" x="0px" y="0px" id="svg-5725" style="enable-background:new 0 0 55 55;"><path d="M49,0c-3.309,0-6,2.691-6,6c0,1.035,0.263,2.009,0.726,2.86l-9.829,9.829C32.542,17.634,30.846,17,29,17
                                                                                                                                                                                                        s-3.542,0.634-4.898,1.688l-7.669-7.669C16.785,10.424,17,9.74,17,9c0-2.206-1.794-4-4-4S9,6.794,9,9s1.794,4,4,4
                                                                                                                                                                                                        c0.74,0,1.424-0.215,2.019-0.567l7.669,7.669C21.634,21.458,21,23.154,21,25s0.634,3.542,1.688,4.897L10.024,42.562
                                                                                                                                                                                                        C8.958,41.595,7.549,41,6,41c-3.309,0-6,2.691-6,6s2.691,6,6,6s6-2.691,6-6c0-1.035-0.263-2.009-0.726-2.86l12.829-12.829
                                                                                                                                                                                                        c1.106,0.86,2.44,1.436,3.898,1.619v10.16c-2.833,0.478-5,2.942-5,5.91c0,3.309,2.691,6,6,6s6-2.691,6-6c0-2.967-2.167-5.431-5-5.91
                                                                                                                                                                                                        v-10.16c1.458-0.183,2.792-0.759,3.898-1.619l7.669,7.669C41.215,39.576,41,40.26,41,41c0,2.206,1.794,4,4,4s4-1.794,4-4
                                                                                                                                                                                                        s-1.794-4-4-4c-0.74,0-1.424,0.215-2.019,0.567l-7.669-7.669C36.366,28.542,37,26.846,37,25s-0.634-3.542-1.688-4.897l9.665-9.665
                                                                                                                                                                                                        C46.042,11.405,47.451,12,49,12c3.309,0,6-2.691,6-6S52.309,0,49,0z"></path></svg></span>
                        <h5 class="u-custom-font u-font-raleway u-text u-text-custom-color-1 u-text-default u-text-3">PUBLICA TU PROPUESTA</h5>
                        <ul class="u-align-left u-text u-text-4">
                            <li>Entra en tu perfil y pulsa en "Crear nueva publicación".</li>
                            <li>Añade la información y prepárate para recibir a los emprendedores interesados</li>
                        </ul>
                    </div>
                </div>
                <div class="u-align-center u-container-style u-list-item u-radius-15 u-repeater-item u-shape-round u-white u-list-item-2">
                    <div class="u-container-layout u-similar-container u-container-layout-2"><span class="u-custom-color-3 u-icon u-icon-circle u-spacing-27 u-text-custom-color-1 u-icon-2"><svg class="u-svg-link" preserveAspectRatio="xMidYMin slice" viewBox="0 0 60 60" style=""><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-0225"></use></svg><svg class="u-svg-content" viewBox="0 0 60 60" x="0px" y="0px" id="svg-0225" style="enable-background:new 0 0 60 60;"><path d="M57.49,27H54v-7.268C54,18.226,52.774,17,51.268,17H50v-2.414V0H10v2H9H8H7v2H6H4v2v4H2.732C1.226,10,0,11.226,0,12.732
                                                                                                                                                                                                        v45.23l0.058,0.002c0.078,0.367,0.234,0.719,0.471,1.029C1.018,59.634,1.76,60,2.565,60h44.759c1.156,0,2.174-0.779,2.45-1.813
                                                                                                                                                                                                        L60,30.149v-0.177C60,28.25,58.944,27,57.49,27z M18,5h10c0.552,0,1,0.447,1,1s-0.448,1-1,1H18c-0.552,0-1-0.447-1-1S17.448,5,18,5z
                                                                                                                                                                                                        M18,12h24c0.552,0,1,0.447,1,1s-0.448,1-1,1H18c-0.552,0-1-0.447-1-1S17.448,12,18,12z M18,19h24c0.552,0,1,0.447,1,1s-0.448,1-1,1
                                                                                                                                                                                                        H18c-0.552,0-1-0.447-1-1S17.448,19,18,19z M2,51.526V12.732C2,12.328,2.329,12,2.732,12H4v34.041L2,51.526z M6,6h1v31.813l-1,2.743
                                                                                                                                                                                                        V6z M52,27h-5H12.731c-1.156,0-2.174,0.779-2.45,1.813L9,32.328V4h1v6v2v13v1h40v-1v-6h1.268C51.671,19,52,19.328,52,19.732V27z"></path></svg></span>
                        <h5 class="u-custom-font u-font-raleway u-text u-text-custom-color-1 u-text-default u-text-5">ENCUENTRA PROYECTOS EN LOS QUE PARTICIPAR</h5>
                        <ul class="u-align-left u-text u-text-6">
                            <li>Navega por el carrusel de publicaciones para informarte de los nuevos proyectos que buscan aliados</li>
                        </ul>
                    </div>
                </div>
                <div class="u-align-center u-container-style u-list-item u-radius-15 u-repeater-item u-shape-round u-white u-list-item-3">
                    <div class="u-container-layout u-similar-container u-container-layout-3"><span class="u-custom-color-3 u-icon u-icon-circle u-spacing-27 u-text-custom-color-1 u-icon-3"><svg class="u-svg-link" preserveAspectRatio="xMidYMin slice" viewBox="0 0 60 60" style=""><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-81a3"></use></svg><svg class="u-svg-content" viewBox="0 0 60 60" x="0px" y="0px" id="svg-81a3" style="enable-background:new 0 0 60 60;"><g><path d="M55.014,45.389l-9.553-4.776C44.56,40.162,44,39.256,44,38.248v-3.381c0.229-0.28,0.47-0.599,0.719-0.951
                                                                                                                                                                                                        c1.239-1.75,2.232-3.698,2.954-5.799C49.084,27.47,50,26.075,50,24.5v-4c0-0.963-0.36-1.896-1-2.625v-5.319
                                                                                                                                                                                                        c0.056-0.55,0.276-3.824-2.092-6.525C44.854,3.688,41.521,2.5,37,2.5s-7.854,1.188-9.908,3.53
                                                                                                                                                                                                        c-2.368,2.701-2.148,5.976-2.092,6.525v5.319c-0.64,0.729-1,1.662-1,2.625v4c0,1.217,0.553,2.352,1.497,3.109
                                                                                                                                                                                                        c0.916,3.627,2.833,6.36,3.503,7.237v3.309c0,0.968-0.528,1.856-1.377,2.32l-8.921,4.866c-2.9,1.582-4.701,4.616-4.701,7.92V57.5
                                                                                                                                                                                                        h46v-4.043C60,50.019,58.089,46.927,55.014,45.389z"></path><path d="M17.983,44.025l8.921-4.866c0.367-0.201,0.596-0.586,0.596-1.004v-2.814c-0.885-1.232-2.446-3.712-3.337-6.91
                                                                                                                                                                                                        C23.1,27.399,22.5,25.994,22.5,24.5v-4c0-1.124,0.352-2.219,1-3.141v-4.733c-0.034-0.383-0.074-1.269,0.096-2.395
                                                                                                                                                                                                        C22.219,9.751,20.687,9.5,19,9.5c-10.389,0-10.994,8.855-11,9v4.579c-0.648,0.706-1,1.521-1,2.33v3.454
                                                                                                                                                                                                        c0,1.079,0.483,2.085,1.311,2.765c0.825,3.11,2.854,5.46,3.644,6.285v2.743c0,0.787-0.428,1.509-1.171,1.915l-6.653,4.173
                                                                                                                                                                                                        C1.583,48.134,0,50.801,0,53.703V57.5h12.5v-4.238C12.5,49.409,14.601,45.87,17.983,44.025z"></path>
                            </g></svg></span>
                        <h5 class="u-custom-font u-font-raleway u-text u-text-custom-color-1 u-text-default u-text-7">CREA NUEVAS ALIANZAS</h5>
                        <ul class="u-align-left u-text u-text-8">
                            <li>Contacta con los responsables de los proyectos para obtener más información y establecer sinergias</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="u-align-right u-clearfix u-image u-shading u-section-4" id="carousel_28f5" data-image-width="1920" data-image-height="910">
    <div class="u-clearfix u-sheet u-valign-bottom-xs u-valign-middle-lg u-valign-middle-md u-valign-middle-xl u-sheet-1">
        <div class="u-align-left u-border-6 u-border-white u-container-style u-group u-white u-group-1">
            <div class="u-container-layout u-valign-middle u-container-layout-1">
                <h1 class="u-text u-text-custom-color-1 u-text-1">¿Tienes un pryecto?<br>
                </h1>
                <h6 class="u-custom-font u-text u-text-custom-color-5 u-text-default u-text-font u-text-2">Crea tu primera publicación</h6>
                <a href="https://nicepage.com/c/full-width-slider-html-templates" class="u-border-2 u-border-active-grey-30 u-border-custom-color-2 u-border-hover-grey-30 u-btn u-btn-round u-button-style u-custom-font u-none u-radius-3 u-text-active-black u-text-body-color u-text-hover-black u-btn-1">Crear publicación</a>
            </div>
        </div>
    </div>
</section>
<section class="u-align-center u-clearfix u-custom-color-1 u-section-5" id="carousel_6272">
    <div class="u-clearfix u-sheet u-valign-middle-xs u-sheet-1">
        <h2 class="u-text u-text-default u-text-1">SÍGUENOS EN</h2>
        <div class="u-social-icons u-spacing-20 u-social-icons-1">
            <a class="u-social-url" target="_blank" href=""><span class="u-icon u-icon-circle u-social-facebook u-social-icon u-icon-1">
                    <svg class="u-svg-link" preserveAspectRatio="xMidYMin slice" viewBox="0 0 112 112" style=""><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-f107"></use></svg>
                    <svg x="0px" y="0px" viewBox="0 0 112 112" id="svg-f107" class="u-svg-content"><path d="M75.5,28.8H65.4c-1.5,0-4,0.9-4,4.3v9.4h13.9l-1.5,15.8H61.4v45.1H42.8V58.3h-8.8V42.4h8.8V32.2 c0-7.4,3.4-18.8,18.8-18.8h13.8v15.4H75.5z"></path></svg>
                </span>
            </a>
            <a class="u-social-url" target="_blank" href=""><span class="u-icon u-icon-circle u-social-icon u-social-twitter u-icon-2">
                    <svg class="u-svg-link" preserveAspectRatio="xMidYMin slice" viewBox="0 0 112 112" style=""><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-e140"></use></svg>
                    <svg x="0px" y="0px" viewBox="0 0 112 112" id="svg-e140" class="u-svg-content"><path d="M92.2,38.2c0,0.8,0,1.6,0,2.3c0,24.3-18.6,52.4-52.6,52.4c-10.6,0.1-20.2-2.9-28.5-8.2 c1.4,0.2,2.9,0.2,4.4,0.2c8.7,0,16.7-2.9,23-7.9c-8.1-0.2-14.9-5.5-17.3-12.8c1.1,0.2,2.4,0.2,3.4,0.2c1.6,0,3.3-0.2,4.8-0.7 c-8.4-1.6-14.9-9.2-14.9-18c0-0.2,0-0.2,0-0.2c2.5,1.4,5.4,2.2,8.4,2.3c-5-3.3-8.3-8.9-8.3-15.4c0-3.4,1-6.5,2.5-9.2 c9.1,11.1,22.7,18.5,38,19.2c-0.2-1.4-0.4-2.8-0.4-4.3c0.1-10,8.3-18.2,18.5-18.2c5.4,0,10.1,2.2,13.5,5.7c4.3-0.8,8.1-2.3,11.7-4.5 c-1.4,4.3-4.3,7.9-8.1,10.1c3.7-0.4,7.3-1.4,10.6-2.9C98.9,32.3,95.7,35.5,92.2,38.2z"></path></svg>
                </span>
            </a>
            <a class="u-social-url" target="_blank" href=""><span class="u-icon u-icon-circle u-social-icon u-social-instagram u-icon-3">
                    <svg class="u-svg-link" preserveAspectRatio="xMidYMin slice" viewBox="0 0 112 112" style=""><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-4718"></use></svg>
                    <svg x="0px" y="0px" viewBox="0 0 112 112" id="svg-4718" class="u-svg-content"><path d="M55.9,32.9c-12.8,0-23.2,10.4-23.2,23.2s10.4,23.2,23.2,23.2s23.2-10.4,23.2-23.2S68.7,32.9,55.9,32.9z M55.9,69.4c-7.4,0-13.3-6-13.3-13.3c-0.1-7.4,6-13.3,13.3-13.3s13.3,6,13.3,13.3C69.3,63.5,63.3,69.4,55.9,69.4z"></path><path d="M79.7,26.8c-3,0-5.4,2.5-5.4,5.4s2.5,5.4,5.4,5.4c3,0,5.4-2.5,5.4-5.4S82.7,26.8,79.7,26.8z"></path><path d="M78.2,11H33.5C21,11,10.8,21.3,10.8,33.7v44.7c0,12.6,10.2,22.8,22.7,22.8h44.7c12.6,0,22.7-10.2,22.7-22.7 V33.7C100.8,21.1,90.6,11,78.2,11z M91,78.4c0,7.1-5.8,12.8-12.8,12.8H33.5c-7.1,0-12.8-5.8-12.8-12.8V33.7 c0-7.1,5.8-12.8,12.8-12.8h44.7c7.1,0,12.8,5.8,12.8,12.8V78.4z"></path></svg>
                </span>
            </a>
            <a class="u-social-url" target="_blank" href="#"><span class="u-icon u-icon-circle u-social-icon u-social-linkedin u-icon-4">
                    <svg class="u-svg-link" preserveAspectRatio="xMidYMin slice" viewBox="0 0 112 112" style=""><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-438b"></use></svg>
                    <svg x="0px" y="0px" viewBox="0 0 112 112" id="svg-438b" class="u-svg-content"><path d="M33.8,96.8H14.5v-58h19.3V96.8z M24.1,30.9L24.1,30.9c-6.6,0-10.8-4.5-10.8-10.1c0-5.8,4.3-10.1,10.9-10.1 S34.9,15,35.1,20.8C35.1,26.4,30.8,30.9,24.1,30.9z M103.3,96.8H84.1v-31c0-7.8-2.7-13.1-9.8-13.1c-5.3,0-8.5,3.6-9.9,7.1 c-0.6,1.3-0.6,3-0.6,4.8V97H44.5c0,0,0.3-52.6,0-58h19.3v8.2c2.6-3.9,7.2-9.6,17.4-9.6c12.7,0,22.2,8.4,22.2,26.1V96.8z"></path></svg>
                </span>
            </a>
            <a class="u-social-url" target="_blank" href="#"><span class="u-icon u-icon-circle u-social-icon u-social-pinterest u-icon-5">
                    <svg class="u-svg-link" preserveAspectRatio="xMidYMin slice" viewBox="0 0 112 112" style=""><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-3428"></use></svg>
                    <svg x="0px" y="0px" viewBox="0 0 112 112" id="svg-3428" class="u-svg-content"><path d="M61.9,79.8c-5.5-0.3-7.8-3.1-12-5.8c-2.3,12.4-5.4,24.3-13.8,30.5c-2.6-18.7,3.8-32.8,6.9-47.7 c-5.1-8.7,0.7-26.2,11.5-21.9c13.5,5.4-11.6,32.3,5.1,35.7c17.6,3.5,24.7-30.5,13.8-41.4c-15.7-16.1-45.7-0.5-42,22.3 c0.9,5.6,6.7,7.2,2.3,15c-10.1-2.2-13-10.2-12.7-20.7c0.6-17.3,15.5-29.3,30.5-31.1c19-2.2,36.8,6.9,39.2,24.7 C93.4,59.5,82.3,81.3,61.9,79.8z"></path></svg>
                </span>
            </a>
        </div>
        <div class="u-border-10 u-border-white u-line u-line-horizontal u-line-1"></div>
    </div>
</section>

<?php
include 'footer.php';
?>
        