<?php

// If your PHP version is < 8
if ( ! function_exists( 'str_contains' ) ) {
    /**
     * Based on original work from the PHP Laravel framework.
     *
     * @author scm6079
     * @link https://www.php.net/manual/en/function.str-contains.php#125977 Original Source
     *
     * @param string $haystack The string to search in.
     * @param string $needle   The substring to search for in the haystack.
     *
     * @return boolean Returns true if needle is in haystack, false otherwise.
     */
    function str_contains( $haystack, $needle ) {
        return $needle !== '' && mb_strpos( $haystack, $needle ) !== false;
    }
}

if ( ! function_exists( 'ct_set_script_type' ) ) {
    /**
     * Allows you to indicate that a script should be treated as a module or
     * importmap by adding the correct type attribute to the script tag that
     * WordPress will output. When using wp_enqueue_script() you should add
     * a type parameter to the source indicating what type this file should
     * be treated as:
     *
     * Type module      =>  your-file.js?type=module
     * Type import map  =>  your-file.json?type=importmap
     *
     * @author Christopher Keers (Caboodle Tech)
     * @link https://gist.github.com/blizzardengle/3e4d5c789f1a13ff8ab86e83738a46c4 Original Source
     *
     * @param string $tag    The current HTML script tag WordPress is about to output.
     * @param string $handle The handle (id) used for this script tag.
     * @param string $src    The source of the JavaScript file.
     *
     * @return string The original HTML from $tag or a modified version with type added to the script tag.
     */
    function ct_set_script_type( $tag, $handle, $src ) {
        $url = wp_parse_url( $src, PHP_URL_QUERY );
        if ( $url === false ) {
            return $tag;
        }
        parse_str( $url, $query );
        if ( array_key_exists( 'type', $query ) ) {
            $type = 'module';
            if ( str_contains( $query['type'], 'map' ) ) {
                $type = 'importmap';
            }
            if ( str_contains( $tag, 'type="' ) ) {
                $tag = preg_replace( '/type=".*?"/', 'type="' . $type . '"', $tag );
            } else {
                $tag = str_replace( 'src=', 'type="' . $type . '" src=', $tag );
            }
            return $tag;
        }
        return $tag;
    }
    add_filter( 'script_loader_tag', 'ct_set_script_type', 10, 3 );
}