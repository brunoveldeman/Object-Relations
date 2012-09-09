<?php
/* Legal Stuff

	This file is part of Object relations.

    Object Relations is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Object relations is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Object Relations.  If not, see <http://www.gnu.org/licenses/>.
*/

// Include the functions script:
include('../incl/functions.php');

// Open the database connection:
include('../data/mysql_connect.php');

// Check authentication:
include('../auth/auth.php');

// Check permissions:
include('../perm/perm.php');

	$query = "SELECT object_property_data_binary.data as data, object_property_data_binary.filetype as filetype FROM object_property_data_binary WHERE object_property_data_binary.object_property_id = {$_GET['id']}";
	if ($result = mysql_query($query, $dbc)) { // Run the query.
		if ( mysql_num_rows($result) > 0 ) {
			while ( $row = mysql_fetch_array($result) ) {
				header("Content-Type: {$row['filetype']}");
				print $row['data'];
			}
		} else {
			print "<p class=\"error\">Property data not found</p>\n";
		}
	}
?>