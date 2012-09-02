<?php

// Include the functions script:
include('../incl/functions.php');

// Open the database connection:
include('../data/mysql_connect.php');

// Check authentication:
include('../auth/auth.php');

// Check permissions:
include('../perm/perm.php');

	$query = "SELECT object_property_data_binary.data as data, object_property_data_binary.filetype as filetype, object_property_data_binary.filename as filename, object_property_data_binary.filesize as filesize FROM object_property_data_binary WHERE object_property_data_binary.object_property_id = {$_GET['id']}";
	if ($result = mysql_query($query, $dbc)) { // Run the query.
		if ( mysql_num_rows($result) > 0 ) {
			while ( $row = mysql_fetch_array($result) ) {
				header("Content-Type: {$row['filetype']}");
				header("Content-length: {$row['filesize']}");
  				header("Content-Disposition: attachment; filename={$row['filename']}");
  				header("Content-Description: PHP Generated Data");
				print $row['data'];
			}
		} else {
			print "<p class=\"error\">Property data not found</p>\n";
		}
	}
?>