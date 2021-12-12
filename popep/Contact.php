<?php

include 'header.php';
?>

<section class="u-align-center u-clearfix u-section-1" id="sec-b84d">
    <div class="u-clearfix u-sheet u-valign-middle u-sheet-1">
        <h2 class="u-text u-text-default u-text-1">Contáctanos</h2>
        <div class="u-form u-form-1">
            <form action="scripts/form-b84d.php" method="POST" class="u-clearfix u-form-spacing-10 u-form-vertical u-inner-form" style="padding: 10px" source="customphp" name="form">
                <div class="u-form-group u-form-name">
                    <label for="name-3b9a" class="u-form-control-hidden u-label">Name</label>
                    <input type="text" placeholder="Introduce tu nombre" id="name-3b9a" name="name" class="u-border-1 u-border-grey-30 u-input u-input-rectangle u-white" required="">
                </div>
                <div class="u-form-email u-form-group">
                    <label for="email-3b9a" class="u-form-control-hidden u-label">Email</label>
                    <input type="email" placeholder="Email" id="email-3b9a" name="email" class="u-border-1 u-border-grey-30 u-input u-input-rectangle u-white" required="">
                </div>
                <div class="u-form-group u-form-message">
                    <label for="message-3b9a" class="u-form-control-hidden u-label">Message</label>
                    <textarea placeholder="Escribe aquí tu mensaje" rows="4" cols="50" id="message-3b9a" name="message" class="u-border-1 u-border-grey-30 u-input u-input-rectangle u-white" required=""></textarea>
                </div>
                <div class="u-align-left u-form-group u-form-submit">
                    <a href="#" class="u-active-custom-color-2 u-border-none u-btn u-btn-submit u-button-style u-custom-color-1 u-hover-custom-color-2 u-btn-1">Enviar<br>
                    </a>
                    <input type="submit" value="submit" class="u-form-control-hidden">
                </div>
                <div class="u-form-send-message u-form-send-success"> Thank you! Your message has been sent. </div>
                <div class="u-form-send-error u-form-send-message"> Unable to send your message. Please fix errors then try again. </div>
                <input type="hidden" value="" name="recaptchaResponse">
            </form>
        </div>
    </div>
</section>


<?php

include 'footer.php';
?>