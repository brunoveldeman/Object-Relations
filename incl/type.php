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
/* Types                                                                              */
/**************************************************************************************/
//
// Functions to handle type:
// listtypes : List types, sorted by $_GET['sort'] comumn
// viewtype : Show type detail
// edittype : Edit type detail
//
/**************************************************************************************/

/* Scrapbook code

..

*/


/*******************************************************************/
/* function                                                        */
/*   listtypes                                                      */
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
function listtypes($dbc)
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
	$query = "SELECT id, name, description, timestamp FROM type WHERE deleted = 0 $search ORDER BY $sort";

	// Run the query:
	if ($result = mysqli_query($dbc, $query)) {
		$rows = mysqli_num_rows($result);
		print "<table class=\"object\">\n";
		print "<col width=\"40px\" /><col width=\"150px\" /><col /><col width=\"120px\" /><col width=\"43px\" />\n";
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
			print "<tr onClick=\"document.location.href='index.php?command=viewtype&typeid={$row['id']}';\" style=\"cursor:pointer;\">\n";
			print "<td>" . htmlspecialchars($row['id']) . "</td>\n";
			print "<td>" . htmlspecialchars($row['name']) . "</td>\n";
			print "<td>" . htmlspecialchars($row['description']) . "\n";
			print "<td>" . dateformat(htmlspecialchars($row['timestamp'])) . "\n";
			print "</td>\n";			
			print "<td>\n";
			if (getperm('edittype'))
			{
				print "<form action=\"index.php?command=edittype&typeid=" . htmlspecialchars($row['id']) . "\" method=\"post\">\n";
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
/*   typesdefined                                                  */
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
function typesdefined($dbc)
{
	$defined = false;
			// Define the query...
	$query = "SELECT id FROM type WHERE deleted = 0";

	// Run the query:
	if ($result = mysqli_query($dbc, $query)) {
		$rows = mysqli_num_rows($result);
		if ($rows > 0)
		{
			$defined = true;
		}
	} else 
	{ // Query didn't run.
		debugprint( '<p id="clickme" class="error">Could not retrieve the data because:<br />' . mysqli_error($dbc) . '. The query being run was: ' . $query . '</p>');
	} // End of query IF.
	return($defined);
}


/*******************************************************************/
/* function                                                        */
/*   viewtype                                                      */
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
function viewtype($dbc)
{
	if (isset($_GET['typeid']) && is_numeric($_GET['typeid']) && ($_GET['typeid'] > 0) ) { // Display the entry in a form:

		$query = "SELECT  type.id as typeid, type.name as typename, type.description as typedescription FROM type WHERE type.id={$_GET['typeid']}";
		if ($result = mysqli_query($dbc, $query)) { // Run the query.
		
			$row = mysqli_fetch_assoc($result); // Retrieve the information.
			// Show Details:
			print "<table class=\"object\">\n";
			print '<col width="150px" /><col />';
			print "<th colspan=\"2\">Type</th>\n";
			print "<tr>\n";
			print "<td>Name : </td>\n";
			print "<td>" . htmlspecialchars($row['typename']) . "</td>\n";
			print "</tr>\n";
			print "<tr>\n";
			print "<td>Description : </td>\n";
			print "<td>" . nl2br(htmlspecialchars($row['typedescription'])) . "</td>\n";
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
/*   edittype                                                      */
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
function edittype($dbc)
{
	$error = false;
	if (isset($_POST['typeid']) && is_numeric($_POST['typeid']) && ($_POST['typeid'] > 0)) { // Handle the form.

		// Validate and secure the form data:
		if ( !empty($_POST['name']) && !empty($_POST['description']) ) {
	
			// Prepare the values for storing:
			$name = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['name'])));
			$description = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['description'])));
			// Define the query.
			$query = "UPDATE type SET name='$name', description='$description'  WHERE id={$_POST['typeid']}";
			if ($result = mysqli_query($dbc, $query)) {
				print "<p id=\"fade\" class=\"info\">The type has been updated.</p>\n";
			} else {
				debugprint( '<p class="error">Could not update the type because:<br />' . mysqli_error($dbc) . '. The query being run was: ' . $query . '</p>');
				$error = true;
			}	
		} // No problem!
	}
	
	if ( isset($_GET['typeid']) && is_numeric($_GET['typeid']) && ($_GET['typeid'] > 0) ) { // Display the entry in a form:

		$query = "SELECT  type.id as typeid, type.name as typename, type.description as typedescription  FROM type WHERE type.id={$_GET['typeid']}";
		if ($result = mysqli_query($dbc, $query)) { // Run the query.
		
			$row = mysqli_fetch_assoc($result); // Retrieve the information.
			$typeid = $row['typeid'];
			// Make the form:
			print "<form action=\"index.php?command=edittype&typeid=" . $_GET['typeid'] . "\" method=\"post\">";
			print "<table class=\"object\">";
			print '<col width="150px" /><col />';
			print "<th colspan=\"2\" class=\"object\">";
			print "Type";
			print "<input class=\"save\" type=\"submit\" name=\"submit\" value=\"Save Type\"/>\n";
			print "</th>\n";
			print "<tr>\n";
			print "<td>Name :</td>\n";
			print "<td><input class=\"field\" type=\"text\" name=\"name\" value=\"" . htmlspecialchars($row['typename']) . "\" /></td>";
			print "</tr>\n";
			print "<tr>\n";
			print "<td>Description :</td>\n";
			print "<td><textarea class=\"field\" name=\"description\">" . htmlentities($row['typedescription']) . "</textarea></td>\n";
			print "</tr>\n";
			print "</table>\n";
			print '<input type="hidden" name="typeid" value="' . $_GET['typeid'] . '" />';
			print '</form>';
	
		
		} else { // Couldn't get the information.
			debugprint( '<p id="clickme" class="error">Could not retrieve the type because:<br />' . mysqli_error($dbc) . '. The query being run was: ' . $query . '</p>');
		}
	} else { // No ID set.
		print '<p id="clickme" class="error">This page has been accessed in error.</p>';
	} // End of main IF.
}


/*******************************************************************/
/* function                                                        */
/*   addtype                                                       */
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
function addtype($dbc)
{
	$error = false;
	if ($_SERVER['REQUEST_METHOD'] == 'POST') 
	{ // Handle the form
		if ( !empty($_POST['name']) && !empty($_POST['description']) ) {

						// Prepare the values for storing:
			$name = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['name'])));
			$description = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['description'])));
		
			$query = "INSERT INTO type (name, description) VALUES ('$name', '$description')";
			$result = mysqli_query($dbc, $query);
			
			if (mysqli_affected_rows($dbc) == 1){
				// Print a message:
				$usedid = mysqli_insert_id($dbc);
				print '<p id="fade" class="info">Type has been saved with id=' . $usedid . '.</p>';
			} else {
				print '<p id="clickme" class="error">Could not store the type because:<br />' . mysqli_error($dbc) . '. The query being run was: ' . $query . '</p>';
			}
			
		} else { // Failed to enter data.
			print '<p id="clickme" class="error">Please enter a name and a description!</p>';
			$error=true;
		}
		listtypes($dbc);
		//header( 'Location: index.php?command=listtypes' );
	} elseif (!$error)
	{	// Show the form
		print "<form action=\"index.php?command=addtype\" method=\"post\">\n";
		print "<table class=\"object\">\n";
		print '<col width="150px" /><col />';
		print "<th colspan=\"2\">Type \n";
		print "<input class=\"save\" type=\"submit\" name=\"submit\" value=\"Save\" />\n";
		print "</th>\n";
		print "<tr><td>Name</td><td><input class=\"field\" type=\"text\" name=\"name\" /></td></tr>";
		print "<tr><td>Description</td><td><textarea class=\"field\" name=\"description\" cols=\"70\"></textarea></td></tr>";
		print "</table>\n";
		print "</form>\n";
	}
}

?>