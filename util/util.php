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

/**
 * Translates the 1-10 rating range into a 1-5 rating range
 *
 * When given the rating value, this function will translate it into a new rating value.
 *
 * @param string rating value.
 * @return string New rating value.
 */
function newRatingValue($oldRating) {
  $number = (int) $oldRating;
  if($number <= 5) {
    return '1';
  } 
  if($number === 6) {
    return '2';
  }
  if($number === 7) {
    return '3';
  }
  if($number === 8) {
    return '4';
  }
  if($number >= 9) {
    return '5';
  }

}