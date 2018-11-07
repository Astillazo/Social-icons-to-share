<style type="text/css">
.sits-info-list {
    list-style: circle;
    padding: 0 20px 0;
}
.sits-info-list li {
    margin: 10px auto;
}
.sits-info-list li .social-icons-to-share-light, .sits-info-list li .social-icons-to-share-dark {
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

<p>Hay dos maneras de poner incluir los iconos sociales en un página, para ellos puedes usar los siguiente shortcodes:</p>

<ul class="sits-info-list">
    <li>
        La versión con light usando el shortcode <span class="sits-shortcode">[<?php echo $shortcode_light; ?>]</span> en tu contenido. <?php echo do_shortcode( '[' . $shortcode_light . ']' ); ?>
    </li>
    <li>
        La versión con dark usando el shortcode <span class="sits-shortcode">[<?php echo $shortcode_dark; ?>]</span> en tu contenido. <?php echo do_shortcode( '[' . $shortcode_dark . ']' ); ?>
    </li>
</ul>

<h2>Incluir automáticamente por contenido</h2>

<p>Elige, por tipo de contenido, si y dónde quieres que se muestre automáticamente los botones de compartir</p>
<form method="post">
<?php

$founds = [];

if ( isset( $sits_options ) ) :
    foreach( $sits_options as $varname => $value ):
        $post_type_found = preg_replace( '/(sits_top_|sits_bottom_)/i', '', $varname );
        $name = $post_type_found;
        $post_type_object = get_post_type_object( $post_type_found );

        if ( $post_type_object instanceof WP_Post_Type ) {
            $name = $post_type_object->label;
        }

        if ( ! in_array( $post_type_found, $founds ) ) :
            array_push( $founds, $post_type_found );

?>
<h3><?php echo ucfirst( $name ); ?>:</h3>
<?php
        endif;
?>
<hr />
<?php if ( strpos( $varname , '_top_' ) !== false ): ?>Entre el título y el texto<?php else: ?>Al final del texto<?php endif; ?>
<select name="<?php echo $varname; ?>" value="<?php echo $value; ?>">
    <option value="hidden"<?php if ( $value === 'hidden' ): echo 'selected'; endif; ?>>No mostrar</option>
    <option value="light"<?php if ( $value === 'light' ): echo 'selected'; endif; ?>>Versión Light</option>
    <option value="dark"<?php if ( $value === 'dark' ): echo 'selected'; endif; ?>>Versión Dark</option>
</select>

<?php

    endforeach ;
endif ;

foreach ( get_post_types( [ 'public' => true ] ) as $post_type_value) :
    if ( $post_type_value !== 'attachment' && ! in_array( $post_type_value, $founds ) ) :
    ?>
<h3><?php echo ucfirst( $post_type_value ); ?>:</h3>
<hr />
Entre el título y el texto
<select name="<?php echo 'sits_top_' . $post_type_value; ?>">
    <option value="hidden">No mostrar</option>
    <option value="light">Versión Light</option>
    <option value="dark">Versión Dark</option>
</select>
<hr />
Al final del texto
<select name="<?php echo 'sits_bottom_' . $post_type_value; ?>">
    <option value="hidden">No mostrar</option>
    <option value="light">Versión Light</option>
    <option value="dark">Versión Dark</option>
</select>

    <?php
    endif ;
endforeach ;
?>
<br />
<p></p>
<input type="submit" class="button-primary" value="Guardar cambios">
</form>
