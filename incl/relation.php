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
/* Relations                                                                          */
/**************************************************************************************/
//
// Functions to handle type:
// listrelations : List relations, sorted by $_GET['sort'] comumn
// viewrelation : Show relation detail
// editrelation : Edit relation detail
//
/**************************************************************************************/

/* Scrapbook code

..

*/


/*******************************************************************/
/* function                                                        */
/*   listrelations                                                 */
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
function listrelations($dbc)
{
	//Sort order
	if ( isset($_GET['sort']) ) { 
		switch ( $_GET['sort'] ) {
			case "id":
			case "name":
			case "description":
			case "timestamp":
				$sort = $_GET['sort'];
				break;
			default:
				$sort = "id";
		}
	} else {
		$sort = "id";
	}
	//Search order
	if ( isset($_POST['search']) ) {
		$search	= "AND ( name LIKE \"%" . $_POST['search']. "%\" OR description LIKE \"%" . $_POST['search'] . "%\" ) ";
	} else {
		$search = "";
	}

		// Define the query...
	$query = "SELECT id, name, description, timestamp FROM relation WHERE deleted = 0 $search ORDER BY $sort";

	// Run the query:
	if ($result = mysqli_query($dbc, $query)) {
		$rows = mysqli_num_rows($result);
		print "<table class=\"relations\">\n";
		print '<col width="40px" /><col width="150px" /><col /><col width="120px" /><col width="43px" />';
		print "\n<tr>\n";
		print "<th><a href=\"index.php?command=listtypes&sort=id\">Id</a></th>\n";
		print "<th><a href=\"index.php?command=listtypes&sort=name\">Name</a></th>\n";
		print "<th><a href=\"index.php?command=listtypes&sort=description\">Description</a></th>\n";	
		print "<th><a href=\"index.php?command=listtypes&sort=timestamp\">Create Date</a></th>\n";
		print "<th></th>\n";
		print "</tr>\n";

		// Retrieve the returned records:
		while ($row = mysqli_fetch_assoc($result)) {
		
			// Print the record:
			print "<tr onClick=\"document.location.href='index.php?command=viewrelation&relationid={$row['id']}';\" style=\"cursor:pointer;\">\n";
			print "<td>" . htmlspecialchars($row['id']) . "</td>\n";
			print "<td>" . htmlspecialchars($row['name']) . "</td>\n";
			print "<td>" . htmlspecialchars($row['description']) . "\n";
			print "<td>" . dateformat(htmlspecialchars($row['timestamp'])) . "\n";
			print "</td>\n";			
			print "<td>\n";
			if (getperm('editrelation'))
			{
				print "<form action=\"index.php?command=editrelation&relationid=" . htmlspecialchars($row['id']) . "\" method=\"post\">\n";
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
/*   viewrelation                                                  */
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
function viewrelation($dbc)
{
	if (isset($_GET['relationid']) && is_numeric($_GET['relationid']) && ($_GET['relationid'] > 0) ) { // Display the entry in a form:

		$query = "SELECT  relation.id as relationid, relation.name as relationname, relation.description as relationdescription FROM relation WHERE relation.id={$_GET['relationid']}";
		if ($result = mysqli_query($dbc, $query)) { // Run the query.
			$row = mysqli_fetch_assoc($result); // Retrieve the information.
			// Show Details:
			print "<table class=\"relations\">\n";
			print '<col width="150px" /><col />';
			print "<th colspan=\"2\">Relation</th>\n";
			print "<tr>\n";
			print "<td>Name : </td>\n";
			print "<td>" . htmlspecialchars($row['relationname']) . "</td>\n";
			print "</tr>\n";
			print "<tr>\n";
			print "<td>Description : </td>\n";
			print "<td>" . nl2br(htmlspecialchars($row['relationdescription'])) . "</td>\n";
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
/*   editrelation                                                  */
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
function editrelation($dbc)
{
	$error = false;
	if (isset($_POST['relationid']) && is_numeric($_POST['relationid']) && ($_POST['relationid'] > 0)) { // Handle the form.

		// Validate and secure the form data:
		if ( !empty($_POST['name']) && !empty($_POST['description']) ) {
	
			// Prepare the values for storing:
			$name = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['name'])));
			$description = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['description'])));
			// Define the query.
			$query = "UPDATE relation SET name='$name', description='$description'  WHERE id={$_POST['relationid']}";
			if ($result = mysqli_query($dbc, $query)) {
				print "<p id=\"fade\" class=\"info\">The relation has been updated.</p>\n";
			} else {
				debugprint( '<p class="error">Could not update the relation because:<br />' . mysqli_error($dbc) . '. The query being run was: ' . $query . '</p>');
				$error = true;
			}	
		} // No problem!
	}
	
	if ( isset($_GET['relationid']) && is_numeric($_GET['relationid']) && ($_GET['relationid'] > 0) ) { // Display the entry in a form:

		$query = "SELECT  relation.id as relationid, relation.name as relationname, relation.description as relationdescription  FROM relation WHERE relation.id={$_GET['relationid']}";
		if ($result = mysqli_query($dbc, $query)) { // Run the query.
		
			$row = mysqli_fetch_assoc($result); // Retrieve the information.
			$relationid = $row['relationid'];
			// Make the form:
			print "<form action=\"index.php?command=editrelation&relationid=" . $_GET['relationid'] . "\" method=\"post\">";
			print "<table class=\"relations\">";
			print '<col width="150px" /><col />';
			print "<th colspan=\"2\">";
			print "Relation";
			print "<input class=\"save\" type=\"submit\" name=\"submit\" value=\"Save Relation\"/>\n";
			print "</th>\n";
			print "<tr>\n";
			print "<td>Name :</td>\n";
			print "<td><input class=\"field\" type=\"text\" name=\"name\" value=\"" . htmlspecialchars($row['relationname']) . "\" /></td>";
			print "</tr>\n";
			print "<tr>\n";
			print "<td>Description :</td>\n";
			
			print "<td><textarea class=\"field\" name=\"description\">" . htmlspecialchars($row['relationdescription']) . "</textarea></td>\n";
			print "</tr>\n";
			print "</table>\n";
			print '<input type="hidden" name="relationid" value="' . $_GET['relationid'] . '" />';
			print '</form>';
	
		
		} else { // Couldn't get the information.
			debugprint( '<p id="clickme" class="error">Could not retrieve the relation because:<br />' . mysqli_error($dbc) . '. The query being run was: ' . $query . '</p>');
		}
	} else { // No ID set.
		print '<p id="clickme" class="error">This page has been accessed in error.</p>';
	} // End of main IF.
}


/*******************************************************************/
/* function                                                        */
/*   addrelation                                                   */
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
function addrelation($dbc)
{
	$error = false;
	if ($_SERVER['REQUEST_METHOD'] == 'POST') 
	{ // Handle the form
		if ( !empty($_POST['name']) && !empty($_POST['description']) ) {

						// Prepare the values for storing:
			$name = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['name'])));
			$description = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['description'])));
		
			$query = "INSERT INTO relation (name, description) VALUES ('$name', '$description')";
			$result = mysqli_query($dbc, $query);
			
			if (mysqli_affected_rows($dbc) == 1){
				// Print a message:
				$usedid = mysqli_insert_id($dbc);
				print '<p id="fade" class="info">Relation has been saved.</p>';
			} else {
				debugprint( '<p id="clickme" class="error">Could not store the relation because:<br />' . mysqli_error($dbc) . '. The query being run was: ' . $query . '</p>');
			}
			
		} else { // Failed to enter data.
			print '<p id="clickme" class="error">Please enter a name and a description!</p>';
			$error=true;
		}
		listrelations($dbc);
		//header( 'Location: index.php?command=listrelations' );
	} elseif (!$error)
	{	// Show the form
		print "<form action=\"index.php?command=addrelation\" method=\"post\">\n";
		print "<table class=\"relations\">\n";
		print '<col width="150px" /><col />';
		print "<th colspan=\"2\">Property \n";
		print "<input class=\"save\" type=\"submit\" name=\"submit\" value=\"Save\" />\n";
		print "</th>\n";
		print "<tr><td>Name</td><td><input class=\"field\" type=\"text\" name=\"name\" /></td></tr>";
		print "<tr><td>Description</td><td><textarea class=\"field\" name=\"description\" cols=\"70\"></textarea></td></tr>";
		print "</table>\n";
		print "</form>\n";
	}
}

/**************************************************************************************/
/* Object relation                                                                    */
/**************************************************************************************/
//
// Functions to handle object relations:
// addobjectrelation: Add a relation
// deleteobjectrelation: Delete a relation
//
/**************************************************************************************/

/* Scrapbook code

..

*/


/*******************************************************************/
/* function                                                        */
/*   addobjectrelation                                             */
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
function addobjectrelation($dbc)
{
	$error = false;
	if ( $_SERVER['REQUEST_METHOD'] == 'POST' )
	{	// Add
		if ( isset($_GET['objectid']) && is_numeric($_GET['objectid']) && ($_GET['objectid'] > 0) && isset($_POST['relationid']) && isset($_POST['robjectid']) ) { 
			
			// Prepare the values for storing:
			$objectid = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['objectid'])));
			$relationid = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['relationid'])));
			$robjectid = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['robjectid'])));
			$objectrelationquantity = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['objectrelationquantity'])));
			$objectrelationunit = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['objectrelationunit'])));
			$objectrelationcomment = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['objectrelationcomment'])));
			// Define the query.
			$query = "INSERT INTO object_relation (relation_id, object1_id, object2_id, quantity, unit, comment) VALUES ('$relationid', '$objectid', '$robjectid', '$objectrelationquantity', '$objectrelationunit', '$objectrelationcomment' )";
			if ($result = mysqli_query($dbc, $query)) {
				print "<p id=\"fade\" class=\"info\">The object relation has been updated.</p>\n";
			} else {
				debugprint( '<p id="clickme" class="error">Could not save the object relation because:<br />' . mysqli_error($dbc) . '. The query being run was: ' . $query . '</p>');
				$error = true;
			}	
		} // No problem!
	}
	editobject($dbc);
}


/*******************************************************************/
/* function                                                        */
/*   deleteobjectrelation                                          */
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
function deleteobjectrelation($dbc)
{
	$error = false;
	if ( isset($_GET['objectid']) && is_numeric($_GET['objectid']) && ($_GET['objectid'] > 0) && ($_SERVER['REQUEST_METHOD'] == 'POST') )
	{	// Delete
		if ( isset($_POST['objectrelationid']) &&  is_numeric($_POST['objectrelationid']) && ($_POST['objectrelationid']) ) { 
			// Prepare the values for storing:
			$objectrelationid = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['objectrelationid'])));
			// Define the query.
			$query = "DELETE FROM object_relation WHERE id=$objectrelationid";
			if ($result = mysqli_query($dbc, $query)) {
				print "<p id=\"fade\" class=\"info\">The object relation has been deleted.</p>\n";
			} else {
				debugprint( '<p id="clickme" class="error">Could not delete the object relation because:<br />' . mysqli_error($dbc) . '. The query being run was: ' . $query . '</p>');
				$error = true;
			}	
		} // No problem!
	}
	editobject($dbc);
}

?>