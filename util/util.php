<?php

/**
 * Creates an array of WP term objects
 *
 * When given the term name as a string, this function will return an array of WP term objects for the specified term.
 *
 * @param string $postId ID of the post object.
 * @param string $termName Name of the term.
 * @return array An array of term objects.
 */
function createTermArray($postId, $termName) {
    $terms = get_the_terms( $postId, $termName );
    $termArray = [];
    if(is_array($terms) || is_object($terms)) {
      foreach($terms as $term) {
        array_push($termArray, array(
          'id' => $term->term_id,
          'name' => $term->name,
          'slug' => $term->slug
        ));
      }
    }

    return $termArray;
}