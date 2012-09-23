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

// Include the settings
include ('../incl/settings.php');

// Include the functions script:
include('../incl/functions.php');

// Open the database connection:
include('../data/mysql_connect.php');

// Check authentication:
include('../auth/auth.php');

// Check permissions:
include('../perm/perm.php');

	$objectpropertyid = mysqli_real_escape_string($dbc, $_GET['id']);
	$query = "SELECT object_property_data_binary.data as data, object_property_data_binary.filetype as filetype, object_property_data_binary.filename as filename, object_property_data_binary.filesize as filesize FROM object_property_data_binary WHERE object_property_data_binary.object_property_id = $objectpropertyid";
	if ($result = mysqli_query($dbc, $query)) { // Run the query.
		if ( mysqli_num_rows($result) > 0 ) {
			while ( $row = mysqli_fetch_assoc($result) ) {
				
				$filelocation = dirname(__FILE__) . "/../" . $row['data'];
				if ($file = fopen($filelocation, "r"))
				{
					header("Content-Type: {$row['filetype']}");
					header("Content-length: {$row['filesize']}");
  					header("Content-Disposition: attachment; filename={$row['filename']}");
  					header("Content-Description: PHP Generated Data");
					while(!feof($file))
					{
						$buff = fread($file, 2048);
						print $buff;
					}
				}
				fclose($file);
			}
		} else {
			print "<p class=\"error\">Property data not found</p>\n";
		}
	}
	
mysqli_close($dbc); // Close the connection.

?>