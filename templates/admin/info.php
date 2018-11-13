<style type="text/css">
.sits-info-list {
    list-style: circle;
    padding: 0 20px 0;
}
.sits-info-list li {
    margin: 10px auto;
}
.sits-info-list li .social-icons-to-share-light, .sits-info-list li .social-icons-to-share-dark, .sits-info-list li .social-icons-to-share-color {
    text-align: left;
}
.sits-shortcode {
    background-color: #000;
    color: #fff;
    padding: 4px 10px 6px;
    border-radius: 4px;
    font-weight: bolder;
    font-style: italic;
}
</style>

<h2>Ajustes de iconos sociales para compartir contenido</h2>

<p>Hay tres maneras de poner incluir los iconos sociales en un página, para ellos puedes usar los siguiente shortcodes:</p>

<ul class="sits-info-list">
    <li>
        La versión con light usando el shortcode <span class="sits-shortcode">[<?php echo $shortcode_light; ?>]</span> en tu contenido. <?php echo do_shortcode( '[' . $shortcode_light . ']' ); ?>
    </li>
    <li>
        La versión con dark usando el shortcode <span class="sits-shortcode">[<?php echo $shortcode_dark; ?>]</span> en tu contenido. <?php echo do_shortcode( '[' . $shortcode_dark . ']' ); ?>
    </li>
    <li>
        La versión con color usando el shortcode <span class="sits-shortcode">[<?php echo $shortcode_color; ?>]</span> en tu contenido. <?php echo do_shortcode( '[' . $shortcode_color . ']' ); ?>
    </li>
</ul>

<h2>Incluir automáticamente por contenido</h2>

<p>Elige, por tipo de contenido, si y dónde quieres que se muestre automáticamente los botones de compartir</p>
<form method="post">
<?php

foreach ( get_post_types( [ 'public' => true ] ) as $post_type_value) :
    if ( $post_type_value !== 'attachment' ) :

?>
<h3><?php echo ucfirst( $post_type_value ); ?>:</h3>
<?php
        $text_from_location = [
            'sits_top_' => 'Entre el título y el texto',
            'sits_bottom_' => 'Al final del texto',
            'sits_fixed_' => 'Fijo en la parte baja de la pantalla'
        ];

        foreach( $locations_name as $location ) :
            ?>
            <hr />
            <?php
            echo $text_from_location[ $location ];
            $key_index = $location . $post_type_value;

            if ( isset( $sits_options ) && isset( $sits_options[ $key_index ] ) ) :
?>

    <select name="<?php echo $key_index; ?>" value="<?php echo $sits_options[ $key_index ]; ?>">
        <option value="hidden"<?php if ( $sits_options[ $key_index ] === 'hidden' ): echo 'selected'; endif; ?>>No mostrar</option>
        <option value="light"<?php if ( $sits_options[ $key_index ] === 'light' ): echo 'selected'; endif; ?>>Versión Light</option>
        <option value="dark"<?php if ( $sits_options[ $key_index ] === 'dark' ): echo 'selected'; endif; ?>>Versión Dark</option>
        <option value="color"<?php if ( $sits_options[ $key_index ] === 'color' ): echo 'selected'; endif; ?>>Versión Color</option>
    </select>

            <?php else : ?>

    <select name="<?php echo $key_index; ?>">
        <option value="hidden">No mostrar</option>
        <option value="light">Versión Light</option>
        <option value="dark">Versión Dark</option>
        <option value="color">Versión Color</option>
    </select>

<?php
            endif ;
        endforeach ;
    endif ;
endforeach ;
?>
<br />
<p></p>
<input type="submit" class="button-primary" value="Guardar cambios">
</form>
