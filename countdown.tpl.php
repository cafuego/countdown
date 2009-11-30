<?php
// $Id: countdown.tpl.php,v 1.1.2.3 2009/05/08 21:52:47 deekayen Exp $

/**
 * @file countdown.tpl.php
 *
 * Theme implementation to display a list of related content.
 *
 * Available variables:
 * - $items: the list.
 */
$time = time();
$difference = variable_get('countdown_timestamp', $time) - $time;
if ($difference < 0) {
  $passed = 1;
  $difference = abs($difference);
}
else {
  $passed = 0;
}

$accuracy  = variable_get('countdown_accuracy', 'd');
$days_left = floor($difference/60/60/24);
$hrs_left  = floor(($difference - $days_left*60*60*24)/60/60);
$min_left  = floor(($difference - $days_left*60*60*24 - $hrs_left*60*60)/60);
$secs_left = floor(($difference - $days_left*60*60*24 - $hrs_left*60*60 - $min_left*60));

print format_plural($days_left, '1 day', '@count days');
if ($accuracy == 'h' || $accuracy == 'm' || $accuracy == 's') {
  print format_plural($hrs_left, ', 1 hour', ', @count hours');
}
if ($accuracy == 'm' || $accuracy == 's') {
  print format_plural($min_left, ', 1 minute', ', @count minutes');
}
if ($accuracy == 's') {
  print format_plural($secs_left, ', 1 second', ', @count seconds');
}

$event_name = variable_get('countdown_event_name', '');
$url = variable_get('countdown_url', '');
$event_name = empty($url) || $url == 'http://' ? $event_name : l($event_name, $url, array('absolute' => TRUE));

print t(($passed) ? ' since !s.' : ' until !s.', array('!s' => $event_name));

if ($accuracy != 'd') {
  $path = drupal_get_path('module', 'countdown');
  drupal_add_js($path .'/countdown.js');

  print <<<___EOS___
<script type="text/javascript"><!--
  init_countdown('$accuracy');
// --></script>
___EOS___;
}
