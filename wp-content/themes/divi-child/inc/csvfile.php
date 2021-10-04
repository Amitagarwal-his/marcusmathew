<?php

function csv_pull_wpse_212972() {
  global $wpdb;
  $file = 'email_csv'; // ?? not defined in original code
  $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}wp_signup_details;",ARRAY_A);

  if (empty($results)) {
    return;
  }

  $csv_output = '"'.implode('";"',array_keys($results[0])).'";'."\n";;

  foreach ($results as $row) {
    $csv_output .= '"'.implode('";"',$row).'";'."\n";
  }
  $csv_output .= "\n";

  $filename = $file."_".date("Y-m-d_H-i",time());
  header("Content-type: application/vnd.ms-excel");
  header("Content-disposition: csv" . date("Y-m-d") . ".csv");
  header( "Content-disposition: filename=".$filename.".csv");
  print $csv_output;
  exit;
}
add_action('wp_ajax_csv_pull','csv_pull_wpse_212972');