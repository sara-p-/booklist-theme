<?php

require get_theme_file_path( '/util/util.php' );

// Callback that supplies a custom data array for the custom API endpoint
function booklist_all_books_api_endpoint_callback( $request ) {
  $mainQuery = new WP_Query(array(
    'post_type' => array('book'),
    'posts_per_page' => -1
  ));

  $bookResults = array();

  while($mainQuery->have_posts()) {
    $mainQuery->the_post();

    $authorArray = createTermArray(get_the_ID(), 'author');
    $seriesArray = createTermArray(get_the_ID(), 'series');
    $genreArray = createTermArray(get_the_ID(), 'genre');
    $tropeArray = createTermArray(get_the_ID(), 'trope');
    $creatureArray = createTermArray(get_the_ID(), 'creature');
    $booktagArray = createTermArray(get_the_ID(), 'booktag');
    

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

function booklist_single_book_api_endpoint_callback($request) {
  $slug = $request['slug'];
  $books = [];
  $results = [];

  if($slug) {
    $books = get_posts(array(
      'name' => $slug,
      'post_type'      => 'book',
      'post_status'    => 'publish',
      'posts_per_page' => 1
    ));
  } 


  if ( empty( $books ) ) {
    return new WP_Error( 'no_book', 'Invalid book', array( 'status' => 404 ) );
  }
  else {
    $bookId = $books[0]->ID;
    $authorArray = createTermArray($bookId, 'author');
    $seriesArray = createTermArray($bookId, 'series');
    $genreArray = createTermArray($bookId, 'genre');
    $tropeArray = createTermArray($bookId, 'trope');
    $creatureArray = createTermArray($bookId, 'creature');
    $booktagArray = createTermArray($bookId, 'booktag');

    $results = array(
      'bookId' => $bookId,
        'title' => get_the_title($bookId),
        'slug' => get_post_field( 'post_name', $bookId ),
        'author' => $authorArray,
        'series' => $seriesArray,
        'image' => get_field('image', $bookId),
        'genres' => $genreArray,
        'tropes' => $tropeArray,
        'creatures' => $creatureArray,
        'booktags' => $booktagArray,
        'bookNumber' => get_field('book_number', $bookId),
        'publishDate' => get_field('publish_date', $bookId),
        'length' => get_field('length', $bookId),
        'rating' => get_field('rating', $bookId),
        'spice' => get_field('spice', $bookId),
        'finished' => get_field('finished', $bookId),
        'amountCompleted' => get_field('amount_completed', $bookId),
        'display' => get_field('display', $bookId),
        'description' => get_field('description', $bookId),
        'notes' => get_field('notes', $bookId),
        'smell' => get_field('smell', $bookId),
        'startDate' => get_field('start_date', $bookId),
        'finishDate' => get_field('finish_date', $bookId),
        'goodreadsLink' => get_field('goodreads_link', $bookId),
        'amazonLink' => get_field('amazon_link', $bookId),
    );
  }

  return $results;

}


// Adds theme support for custom books API endpoint
function register_booklist_api_endpoint() {
  // Register the route to fetch all of the books
    register_rest_route( 'booklist/v1', '/books/', array(
        'methods'             => WP_REST_SERVER::READABLE,
        'callback'            => 'booklist_all_books_api_endpoint_callback',
        'permission_callback' => '__return_true', // For public access, or define a custom permission check
    ));

  // Register the route to fetch one of the books
    register_rest_route( 'booklist/v1', '/book/', array(
        'methods'             => WP_REST_SERVER::READABLE,
        'callback'            => 'booklist_single_book_api_endpoint_callback',
        'permission_callback' => '__return_true', // For public access, or define a custom permission check
    ));
}
add_action( 'rest_api_init', 'register_booklist_api_endpoint' );