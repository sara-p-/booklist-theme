<?php
// Callback that supplies a custom data array for the custom API endpoint
function booklist_api_endpoint_callback( $request ) {
  $mainQuery = new WP_Query(array(
    'post_type' => array('book'),
    'posts_per_page' => -1
  ));

  $bookResults = array();

  while($mainQuery->have_posts()) {
    $mainQuery->the_post();

    // Author array 
    $authorTerms = get_the_terms( get_the_ID(), 'author' );
    $authorArray = array();
    if(is_array($authorTerms) || is_object($authorTerms)) {
      foreach($authorTerms as $author) {
        array_push($authorArray, array(
          'authorId' => $author->term_id,
          'name' => $author->name,
          'slug' => $author->slug
        ));
      }
    }

    // Series Array
    $seriesTerms = get_the_terms( get_the_ID(), 'series' );
    $seriesArray = array();
    if(is_array($seriesTerms) || is_object($seriesTerms)) {
      foreach($seriesTerms as $series) {
        array_push($seriesArray, array(
          'seriesId' => $series->term_id,
          'name' => $series->name,
          'slug' => $series->slug
        ));
      }
    }
    
    // Genre Array
    $genreTerms = get_the_terms( get_the_ID(), 'genre' );
    $genreArray = array();
    if(is_array($genreTerms) || is_object($genreTerms)) {
      foreach($genreTerms as $genre) {
        array_push($genreArray, array(
          'genreId' => $genre->term_id,
          'name' => $genre->name,
          'slug' => $genre->slug
        ));
      }
    }
    
    // Trope Array
    $tropeTerms = get_the_terms( get_the_ID(), 'trope' );
    $tropeArray = array();
    if(is_array($tropeTerms) || is_object($tropeTerms)) {
      foreach($tropeTerms as $trope) {
        array_push($tropeArray, array(
          'tropeId' => $trope->term_id,
          'name' => $trope->name,
          'slug' => $trope->slug
        ));
      }
    }
    
    // Creature Array
    $creatureTerms = get_the_terms( get_the_ID(), 'creature' );
    $creatureArray = array();
    if(is_array($creatureTerms) || is_object($creatureTerms)) {
      foreach($creatureTerms as $creature) {
        array_push($creatureArray, array(
          'creatureId' => $creature->term_id,
          'name' => $creature->name,
          'slug' => $creature->slug
        ));
      }
    }

    // BookTag Array
    $booktagTerms = get_the_terms( get_the_ID(), 'booktag' );
    $booktagArray = array();
    if(is_array($booktagTerms) || is_object($booktagTerms)) {
      foreach($booktagTerms as $booktag) {
        array_push($booktagArray, array(
          'booktagId' => $booktag->term_id,
          'name' => $booktag->name,
          'slug' => $booktag->slug
        ));
      }
    }



    // Push everything into the bookResults array
    array_push($bookResults, array(
      'bookId' => get_the_ID(),
      'title' => get_the_title(),
      'slug' => get_post_field('post_name'),
      'author' => $authorArray,
      'series' => $seriesArray,
      'image' => get_field('image'),
      'genres' => $genreArray,
      'tropes' => $tropeArray,
      'creatures' => $creatureArray,
      'booktags' => $booktagArray,
      'bookNumber' => get_field('book_number'),
      'publishDate' => get_field('publish_date'),
      'length' => get_field('length'),
      'rating' => get_field('rating'),
      'spice' => get_field('spice'),
      'finished' => get_field('finished'),
      'amountCompleted' => get_field('amount_completed'),
      'display' => get_field('display'),
      'description' => get_field('description'),
      'notes' => get_field('notes'),
      'smell' => get_field('smell'),
      'startDate' => get_field('start_date'),
      'finishDate' => get_field('finish_date'),
      'goodreadsLink' => get_field('goodreads_link'),
      'amazonLink' => get_field('amazon_link'),
    ));
  }
	


    return rest_ensure_response( $bookResults );
}

// Adds theme support for custom books API endpoint
function register_booklist_api_endpoint() {
    register_rest_route( 'booklist/v1', '/books/', array(
        'methods'             => WP_REST_SERVER::READABLE,
        'callback'            => 'booklist_api_endpoint_callback',
        'permission_callback' => '__return_true', // For public access, or define a custom permission check
    ));
}
add_action( 'rest_api_init', 'register_booklist_api_endpoint' );