<?php if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

// loads shows
$shows = Show::find_all_in_future();
$wp_shows = get_wp_shows_from_spektrix_shows($shows);
$shows = filter_published($shows,$wp_shows);

$fake_shows = load_fake_shows();
$fake_performances = load_fake_performances($fake_shows);
$shows = $shows + $fake_shows;