<?php
// $Id: countdown.tpl.php,v 1.1.2.1 2008/05/12 15:29:47 deekayen Exp $

/**
 * @file countdown.tpl.php
 *
 * Theme implementation to display a list of related content.
 *
 * Available variables:
 * - $items: the list.
 */
define('DAY', 60*60*24);
define('HOUR', 60*60);

$path = drupal_get_path('module', 'countdown');
drupal_add_css($path .'/countdown.css');

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
$days_left = floor($difference/DAY);
$hrs_left  = floor(($difference - $days_left*DAY)/HOUR);
$min_left  = floor(($difference - $days_left*DAY - $hrs_left*HOUR)/60);
$secs_left = floor(($difference - $days_left*DAY - $hrs_left*HOUR - $min_left*60));

if (variable_get('countdown_labels', '') == 'inline') {
  print t('<div id="countdown-digit-days" class="countdown digit">!i</span>', array('!i' => format_plural($days_left, '1</div><div id="countdown-label-days" class="countdown label">day', '@count</div><span id="countdown-label-days" class="countdown label">days')));
  if ($accuracy == 'h' || $accuracy == 'm' || $accuracy == 's') {
    print  t('<div id="countdown-digit-hrs" class="countdown digit">!i</span>', array('!i' => format_plural($hrs_left, '1</div><div id="countdown-label-hrs" class="countdown label">hour', '@count</div><span id="countdown-label-hrs" class="countdown label">hours')));
  }
  if ($accuracy == 'm' || $accuracy == 's') {
    print  t('<div id="countdown-digit-mins" class="countdown digit">!i</span>', array('!i' => format_plural($min_left, '1</div><div id="countdown-label-mins" class="countdown label">minute', '@count</div><span id="countdown-label-mins" class="countdown label">minutes')));
  }
  if ($accuracy == 's') {
    print t('<div id="countdown-digit-secs" class="countdown digit">!i</span>', array('!i' => format_plural($secs_left, '1</div><div id="countdown-label-secs" class="countdown label">second', '@count</div><span id="countdown-label-secs" class="countdown label">seconds')));
  }
}
else {
  print t('<div id="countdown-digit-days" class="countdown digit">!i</div>', array('!i' => $days_left));
  if ($accuracy == 'h' || $accuracy == 'm' || $accuracy == 's') {
    print  t('<div id="countdown-digit-hrs" class="countdown digit">!i</div>', array('!i' => $hrs_left));
  }
  if ($accuracy == 'm' || $accuracy == 's') {
    print  t('<div id="countdown-digit-mins" class="countdown digit">!i</div>', array('!i' => $min_left));
  }
  if ($accuracy == 's') {
    print t('<div id="countdown-digit-secs" class="countdown digit">!i</div>', array('!i' => $secs_left));
  }
}

if ($accuracy != 'd') {
  drupal_add_js($path .'/countdown.js');

  print <<<___EOS___
<script type="text/javascript"><!--
  init_countdown('$accuracy');
// --></script>
___EOS___;
}

if (variable_get('countdown_labels', '') == 'below') {
  print '<div id="countdown-labels">';
  print t('<span id="countdown-label-days" class="countdown label">!s</span>', array('!s' => format_plural($days_left, 'day', 'days')));
  if ($accuracy == 'h' || $accuracy == 'm' || $accuracy == 's') {
    print  t('<span id="countdown-label-hrs" class="countdown label">!s</span>', array('!s' => format_plural($hrs_left, 'hr', 'hrs')));
  }
  if ($accuracy == 'm' || $accuracy == 's') {
    print  t('<span id="countdown-label-mins" class="countdown label">!s</span>', array('!s' => format_plural($hrs_left, 'min', 'mins')));
  }
  if ($accuracy == 's') {
    print t('<span id="countdown-label-secs" class="countdown label">!s</span>', array('!s' => format_plural($hrs_left, 'sec', 'secs')));
  }
  print '</div>';
}

if ($event_name = variable_get('countdown_event_name', '')) {
  $event_url = variable_get('countdown_url', '');
  print '<span id="countdown-event" class="countdown event">';
  print t(($passed) ? 'since !s.' : 'until !s.', array('!s' => empty($event_url) ? $event_name : l($event_name, $event_url, array('absolute' => TRUE))));
  print '</span>';
}

?>
