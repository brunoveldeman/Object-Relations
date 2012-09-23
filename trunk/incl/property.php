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

/**************************************************************************************/
/* Properties                                                                              */
/**************************************************************************************/
//
// Functions to handle type:
// listproperties : List properties, sorted by $_GET['sort'] comumn
// viewproperty : Show property detail
// editproperty : Edit property detail
// getpropertytype : Get property type name from type id
// selectedtrue : return selected="selected" if values match
//
/**************************************************************************************/

/* Scrapbook code

..

*/


/*******************************************************************/
/* function                                                        */
/*   getptopertytype                                               */
/*******************************************************************/
//
// Description:
//   ?
// Inputs: 
//   function:
//	   ():
//       ?
//   POST:
//	   ?
//   GET:
//     ?
//
// Output:
//   return:
//     ?
//   HTML:
//     ?
//   MYSQL:
//     ?
//   GET:
//     ?
//   POST:
//     ?
//
// Security checks:
//   ?
//
// Security risk:
//   ?
/********************************************************************/
function getpropertytype($type)
{
	switch ($type) {
		case 0:
			$returnstring = "None | No extra data";
			break;
		case 1:
			$returnstring = "Text | Text field";
			break;
		case 2:
			$returnstring = "Numeric | Numeric field";
			break;
		case 3:
			$returnstring = "URL | Link to website";
			break;
		case 4:
			$returnstring = "Bool | Boolean field";
			break;
		case 11:
			$returnstring = "File | File stored in database (1Mb Max.)";
			break;
		case 12:
			$returnstring = "Image | Image stored in database (1Mb Max.)";
			break;
		case 13:
			$returnstring = "File | File stored on filesystem";
			break;
		case 14:
			$returnstring = "Image | Image stored on filesystem";
			break;
		default:
			$returnstring = "Other | Some other undefined type";
	}
	return($returnstring);
}


/*******************************************************************/
/* function                                                        */
/*   selectedtrue                                                  */
/*******************************************************************/
//
// Description:
//   ?
// Inputs: 
//   function:
//	   ():
//       ?
//   POST:
//	   ?
//   GET:
//     ?
//
// Output:
//   return:
//     ?
//   HTML:
//     ?
//   MYSQL:
//     ?
//   GET:
//     ?
//   POST:
//     ?
//
// Security checks:
//   ?
//
// Security risk:
//   ?
/********************************************************************/
function selectedtrue($value1, $value2)
{
	if ( $value1 == $value2 ) {
		$returnstring = "selected=\"selected\"";
	} else {
		$returnstring = "";
	}
	return $returnstring;
}


/*******************************************************************/
/* function                                                        */
/*   listproperties                                                */
/*******************************************************************/
//
// Description:
//   ?
// Inputs: 
//   function:
//	   ():
//       ?
//   POST:
//	   ?
//   GET:
//     ?
//
// Output:
//   return:
//     ?
//   HTML:
//     ?
//   MYSQL:
//     ?
//   GET:
//     ?
//   POST:
//     ?
//
// Security checks:
//   ?
//
// Security risk:
//   ?
/********************************************************************/
function listproperties($dbc)
{
	//Sort order
	if ( isset($_GET['sort']) ) { 
		switch ( $_GET['sort'] ) {
			case "property.id":
			case "property.name":
			case "property.description":
			case "property.type":
			case "property_class.name":
			case "property.timestamp":
				$sort = $_GET['sort'];
				break;
			default:
				$sort = "property.id";
		}
	} else {
		$sort = "property.id";
	}
	
	$search = "";
/*	//Search order
	if ( isset($_POST['search']) ) {
		$search	= "AND ( name LIKE \"%" . $_POST['search']. "%\" OR description LIKE \"%" . $_POST['search'] . "%\" ) ";
	} else {
		$search = "";
	}
*/
		// Define the query...
	$query = "SELECT property.id AS propertyid, property.name AS propertyname, property.description AS propertydescription, property.type AS propertytype, property.timestamp AS propertytimestamp, property_class.name as propertyclassname FROM property JOIN property_class ON (property_class.id = property.class_id) WHERE property.deleted = 0 $search ORDER BY $sort";

	// Run the query:
	if ($result = mysqli_query($dbc, $query)) {
		$rows = mysqli_num_rows($result);
		print "<table class=\"properties\">\n";
		print '<col width="40px" /><col width="150px" /><col /><col width="250px" /><col width="90px"> <col width="120px" /><col width="43px" />';
		print "\n<tr>\n";
		print "<th><a href=\"index.php?command=listproperties&sort=property.id\">Id</a></th>\n";
		print "<th><a href=\"index.php?command=listproperties&sort=property.name\">Name</a></th>\n";
		print "<th><a href=\"index.php?command=listproperties&sort=property.description\">Description</a></th>\n";
		print "<th><a href=\"index.php?command=listproperties&sort=property.type\">Type</a></th>\n";
		print "<th><a href=\"index.php?command=listproperties&sort=property_class.name\">Class</a></th>\n";
		print "<th><a href=\"index.php?command=listproperties&sort=property.timestamp\">Create Date</a></th>\n";
		print "<th></th>\n";
		print "</tr>\n";

		// Retrieve the returned records:
		while ($row = mysqli_fetch_assoc($result)) {
		
			// Print the record:
			print "<tr onClick=\"document.location.href='index.php?command=viewproperty&propertyid={$row['propertyid']}';\" style=\"cursor:pointer;\">\n";
			print "<td>" . htmlspecialchars($row['propertyid']) . "</td>\n";
			print "<td>" . htmlspecialchars($row['propertyname']) . "</td>\n";
			print "<td>" . htmlspecialchars($row['propertydescription']) . "\n";
			print "<td>" . getpropertytype(htmlspecialchars($row['propertytype'])) . "</td>\n";
			print "<td>" . htmlspecialchars($row['propertyclassname']) . "\n";
			print "<td>" . dateformat(htmlspecialchars($row['propertytimestamp'])) . "\n";
			print "</td>\n";			
			print "<td>\n";
			if (getperm('editproperty'))
			{
				print "<form action=\"index.php?command=editproperty&propertyid=" . htmlspecialchars($row['propertyid']) . "\" method=\"post\">\n";
				print "<input class=\"edit\" type=\"submit\" name=\"submit\" value=\"Edit\" />\n";
				print "</form>\n";
			}
			print "</td>\n";			
			print "</tr>\n";
		} // End of while loop.
		print "</table>\n";
		print "<div class=\"result\">$rows rows returned</div>\n";
	} else 
	{ // Query didn't run.
		debugprint( '<p id="clickme" class="error">Could not retrieve the data because:<br />' . mysqli_error($dbc) . '. The query being run was: ' . $query . '</p>');
	} // End of query IF.
}


/*******************************************************************/
/* function                                                        */
/*   viewproperty                                                  */
/*******************************************************************/
//
// Description:
//   ?
// Inputs: 
//   function:
//	   ():
//       ?
//   POST:
//	   ?
//   GET:
//     ?
//
// Output:
//   return:
//     ?
//   HTML:
//     ?
//   MYSQL:
//     ?
//   GET:
//     ?
//   POST:
//     ?
//
// Security checks:
//   ?
//
// Security risk:
//   ?
/********************************************************************/
function viewproperty($dbc)
{
	if (isset($_GET['propertyid']) && is_numeric($_GET['propertyid']) && ($_GET['propertyid'] > 0) ) { // Display the entry in a form:

		$query = "SELECT property.id as propertyid, property.name as propertyname, property.description as propertydescription, property.type as propertytype, property_class.name as propertyclassname FROM property JOIN property_class ON ( property_class.id = property.class_id) WHERE property.id={$_GET['propertyid']}";
		if ($result = mysqli_query($dbc, $query)) { // Run the query.
		
			$row = mysqli_fetch_assoc($result); // Retrieve the information.
			// Show Details:
			print "<table class=\"properties\">\n";
			print '<col width="150px" /><col />';
			print "<th colspan=\"2\">Type</th>\n";
			print "<tr>\n";
			print "<td>Name : </td>\n";
			print "<td>" . htmlspecialchars($row['propertyname']) . "</td>\n";
			print "</tr>\n";
			print "<tr>\n";
			print "<td>Description : </td>\n";
			print "<td>" . nl2br(htmlspecialchars($row['propertydescription'])) . "</td>\n";
			print "</tr>\n";
			print "<tr>\n";
			print "<td>Type :  </td>\n";
			print "<td>" . getpropertytype(htmlspecialchars($row['propertytype'])) . "</td>\n";
			print "</tr>\n";
			print "<tr>\n";
			print "<td>Class :  </td>\n";
			print "<td>" . htmlspecialchars($row['propertyclassname']) . "</td>\n";
			print "</tr>\n";
			print "</table>\n";
			
		} else { // Couldn't get the information.
			debugprint( '<p id="clickme" class="error">Could not retrieve the relation because:<br />' . mysqli_error($dbc) . '. The query being run was: ' . $query . '</p>');
		}
	
	} else { // No ID set.
		print '<p id="clickme" class="error">This page has been accessed in error.</p>';
	} // End of main IF.
}


/*******************************************************************/
/* function                                                        */
/*   editproperty                                                  */
/*******************************************************************/
//
// Description:
//   ?
// Inputs: 
//   function:
//	   ():
//       ?
//   POST:
//	   ?
//   GET:
//     ?
//
// Output:
//   return:
//     ?
//   HTML:
//     ?
//   MYSQL:
//     ?
//   GET:
//     ?
//   POST:
//     ?
//
// Security checks:
//   ?
//
// Security risk:
//   ?
/********************************************************************/
function editproperty($dbc)
{
	$error = false;
	if (isset($_POST['propertyid']) && is_numeric($_POST['propertyid']) && ($_POST['propertyid'] > 0)) { // Handle the form.

		// Validate and secure the form data:
		if ( !empty($_POST['name']) && !empty($_POST['description']) && !empty($_POST['type']) ) {
	
			// Prepare the values for storing:
			$name = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['name'])));
			$description = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['description'])));
			$type = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['type'])));
			$classid = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['class'])));
			// Define the query.
			$query = "UPDATE property SET name='$name', description='$description', type='$type', class_id='$classid'  WHERE id={$_POST['propertyid']}";
			if ($result = mysqli_query($dbc, $query)) {
				print '<p id="fade" class="info">The property has been updated.</p>';
			} else {
				debugprint( '<p class="error">Could not update the property because:<br />' . mysqli_error($dbc) . '. The query being run was: ' . $query . '</p>');
				$error = true;
			}	
		} // No problem!
	}
	
	if ( isset($_GET['propertyid']) && is_numeric($_GET['propertyid']) && ($_GET['propertyid'] > 0) ) { // Display the entry in a form:

		$query = "SELECT  property.id as propertyid, property.name as propertyname, property.description as propertydescription, property.type as propertytype, property.class_id as propertyclassid  FROM property JOIN property_class ON ( property_class.id = property.class_id) WHERE property.id={$_GET['propertyid']}";
		if ($result = mysqli_query($dbc, $query)) { // Run the query.
		
			$row = mysqli_fetch_assoc($result); // Retrieve the information.
			$propertyid = htmlspecialchars($row['propertyid']);
			$propertyclassid = htmlspecialchars($row['propertyclassid']);
			// Make the form:
			print "<form action=\"index.php?command=editproperty&propertyid=" . $_GET['propertyid'] . "\" method=\"post\">";
			print "<table class=\"properties\">\n";
			print '<col width="150px" /><col />';
			print "<th colspan=\"2\">";
			print "Property";
			print "<input class=\"save\" type=\"submit\" name=\"submit\" value=\"Save Property\"/>\n";
			print "</th>\n";
			print "<tr>\n";
			print "<td>Name :</td>\n";
			print "<td><input class=\"field\" type=\"text\" name=\"name\" value=\"" . htmlspecialchars($row['propertyname']) . "\" /></td>";
			print "</tr>\n";
			print "<tr>\n";
			print "<td>Description :</td>\n";
			print "<td><textarea class=\"field\" name=\"description\">" . htmlspecialchars($row['propertydescription']) . "</textarea></td>\n";
			print "</tr>\n";
			print "<tr>\n";
			print "<td>Type :</td>\n";
			// Make a select with types
			print "<td>\n";
			print "<select class=\"field\" name=\"type\" width=\"100px\">\n";
			print "<option value=\"0\"" . selectedtrue(0,htmlspecialchars($row['propertytype'])) . ">" . getpropertytype(0) . "</option>\n";
			print "<option value=\"1\"" . selectedtrue(1,htmlspecialchars($row['propertytype'])) . ">" . getpropertytype(1) . "</option>\n";
			print "<option value=\"2\"" . selectedtrue(2,htmlspecialchars($row['propertytype'])) . ">" . getpropertytype(2) . "</option>\n";
			print "<option value=\"3\"" . selectedtrue(3,htmlspecialchars($row['propertytype'])) . ">" . getpropertytype(3) . "</option>\n";
			print "<option value=\"4\"" . selectedtrue(4,htmlspecialchars($row['propertytype'])) . ">" . getpropertytype(4) . "</option>\n";
			print "<option value=\"11\"" . selectedtrue(11,htmlspecialchars($row['propertytype'])) . ">" . getpropertytype(11) . "</option>\n";
			print "<option value=\"12\"" . selectedtrue(12,htmlspecialchars($row['propertytype'])) . ">" . getpropertytype(12) . "</option>\n";
			print "<option value=\"13\"" . selectedtrue(13,htmlspecialchars($row['propertytype'])) . ">" . getpropertytype(13) . "</option>\n";
			print "<option value=\"14\"" . selectedtrue(14,htmlspecialchars($row['propertytype'])) . ">" . getpropertytype(14) . "</option>\n";
			print "</select>\n";
			print "</td>\n";
			print "</tr>\n";
			// Define query for class
			$query = "SELECT  id, name FROM property_class ORDER BY id ASC";
			if ($result = mysqli_query($dbc, $query)) { // Run the query.
				print "<tr>\n";
				print "<td>Class :</td>\n";
				print "<td><select width=\"100px\" class=\"field\" name=\"class\">\n";
				while ($row = mysqli_fetch_assoc($result)) {
				print '<option';
				if ( htmlspecialchars($row['id']) == $propertyclassid ) {
					print ' selected="selected"';
			}
				print ' value ="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['name']) . '</option>' ."\n";
				} 
			} else { // Couldn't get the information.
				debugprint( '<p id="clickme" class="error">Could not retrieve the object because:<br />' . mysqli_error($dbc) . '. The query being run was: ' . $query . '</p>');
			}
			print "</select>\n";
			print "</td>\n";
			print "</tr>\n";

			print "</table>\n";
			print '<input type="hidden" name="propertyid" value="' . $_GET['propertyid'] . '" />';
			print '</form>';
	
		
		} else { // Couldn't get the information.
			debugprint( '<p id="clickme" class="error">Could not retrieve the property because:<br />' . mysqli_error($dbc) . '. The query being run was: ' . $query . '</p>');
		}
	} else { // No ID set.
		print '<p id="clickme" class="error">This page has been accessed in error.</p>';
	} // End of main IF.
}


/*******************************************************************/
/* function                                                        */
/*   addproperty                                                   */
/*******************************************************************/
//
// Description:
//   ?
// Inputs: 
//   function:
//	   ():
//       ?
//   POST:
//	   ?
//   GET:
//     ?
//
// Output:
//   return:
//     ?
//   HTML:
//     ?
//   MYSQL:
//     ?
//   GET:
//     ?
//   POST:
//     ?
//
// Security checks:
//   ?
//
// Security risk:
//   ?
/********************************************************************/
function addproperty($dbc)
{
	$error = false;
	if ($_SERVER['REQUEST_METHOD'] == 'POST') 
	{ // Handle the form
		if ( !empty($_POST['name']) && !empty($_POST['description']) && !empty($_POST['type']) ) {

						// Prepare the values for storing:
			$name = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['name'])));
			$description = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['description'])));
			$type = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['type'])));
			$classid = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['class_id'])));
			$query = "INSERT INTO property (name, description, type, class_id) VALUES ('$name', '$description', '$type', '$classid')";
			$result = mysqli_query($dbc, $query);
			
			if (mysqli_affected_rows($dbc) == 1){
				// Print a message:
				$usedid = mysqli_insert_id($dbc);
				print '<p id="fade" class="info">Property has been saved with id=' . $usedid . '.</p>';
			} else {
				debugprint( '<p id="clickme" class="error">Could not store the property because:<br />' . mysqli_error($dbc) . '. The query being run was: ' . $query . '</p>');
			}
			
		} else { // Failed to enter data.
			print '<p id="clickme" class="error">Please enter a name and a description and select a type!</p>';
			$error=true;
		}
		listproperties($dbc);
		//header( 'Location: index.php?command=listproperties' );
	} elseif (!$error)
	{	// Show the form
		print "<form action=\"index.php?command=addproperty\" method=\"post\">\n";
		print "<table class=\"properties\">\n";
		print '<col width="150px" /><col />';
		print "<th colspan=\"2\">Property \n";
		print "<input class=\"save\" type=\"submit\" name=\"submit\" value=\"Save\" />\n";
		print "</th>\n";
		print "<tr><td>Name</td><td><input class=\"field\" type=\"text\" name=\"name\" /></td></tr>";
		print "<tr><td>Description</td><td><textarea class=\"field\" name=\"description\" cols=\"70\"></textarea></td></tr>";
		print "<tr><td>Property type</td><td><select class=\"field\" name=\"type\" width=\"100px\">\n";
		print "<option value=\"0\">" . getpropertytype(0) . "</option>\n";
		print "<option value=\"1\">" . getpropertytype(1) . "</option>\n";
		print "<option value=\"2\">" . getpropertytype(2) . "</option>\n";
		print "<option value=\"3\">" . getpropertytype(3) . "</option>\n";
		print "<option value=\"4\">" . getpropertytype(4) . "</option>\n";
		print "<option value=\"11\">" . getpropertytype(11) . "</option>\n";
		print "<option value=\"12\">" . getpropertytype(12) . "</option>\n";
		print "<option value=\"13\">" . getpropertytype(13) . "</option>\n";
		print "<option value=\"14\">" . getpropertytype(14) . "</option>\n";
		print "</select></td></tr>\n";
				// Define query for class
		$query = "SELECT  id, name FROM property_class ORDER BY id ASC";
		if ($result = mysqli_query($dbc, $query)) { // Run the query.
			print "<tr><td>Property class</td><td><select class=\"field\" name=\"class_id\">\n";
			while ($row = mysqli_fetch_assoc($result)) {
			print '<option';
			print ' value ="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['name']) . '</option>' ."\n";
			} 
		} else { // Couldn't get the information.
			debugprint( '<p id="clickme" class="error">Could not retrieve the object because:<br />' . mysqli_error($dbc) . '. The query being run was: ' . $query . '</p>');
		}
		print "</select>\n";
		print "</td></tr>\n";
		print "</table>\n";
		print "</form>\n";
	}
}

?>