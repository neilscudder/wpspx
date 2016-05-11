<?php

// loads shows
$shows = Show::this_week();
$wp_shows = get_wp_shows_from_spektrix_shows($shows);
$shows = filter_published($shows,$wp_shows);