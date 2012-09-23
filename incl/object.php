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
/* Object                                                                             */
/**************************************************************************************/
//
// Functions to handle objects:
//
// listobjects: list all objects, optional sort order and optional search string
/**************************************************************************************/

/* Scrapbook code

..

*/

/*******************************************************************/
/* function                                                        */
/*   listobjects                                                   */
/*******************************************************************/
//
// Description:
//   Output a table with objects, optional search and sort order
// Inputs: 
//   function:
//	   ():
//       $dbc: database connection
//   POST:
//	   $_POST['sort']: sort column
//   GET:
//     $_GET['sort']: sort column
//     $_GET['order']: sort order
//	   $_GET['search']: prefill the search field with last search
//
// Output:
//   return:
//     none
//   HTML:
//     generated html based on database tables
//   MYSQL:
//     SELECT object.id as objectid, object.name as objectname, object.description as objectdescription, object.timestamp as objecttimestamp, type.name as typename FROM object JOIN type ON (type.id = object.type_id) WHERE object.deleted = 0 $search ORDER BY $sort $order
//   GET:
//     several used...
//   POST:
//     several used...
//
// Security checks:
//   $_POST['search'] is checked for invalid characters, only alfanum and space allowed
//
// Security risk:
//   $_GET and $POST should not be direclty used in code but passed to a variable and checked for valid data
/********************************************************************/
function listobjects($dbc)
{
	//Sort column
	if ( isset($_GET['sort']) ) { 
		switch ( $_GET['sort'] ) {
			case "objectid":
			case "objectname":
			case "objectdescription":
			case "objecttimestamp":
			case "typename":
				$sort = $_GET['sort'];
				break;
			default:
				$sort = "objectname";
		}
	} else {
		$sort = "objectname";
	}
	//Sort order
	if ( isset($_GET['order']) ) { 
		switch ( $_GET['order'] ) {
			case "ASC":
				$order = "ASC";
				break;
			case "DESC":
				$order = "DESC";
				break;
			default:
				$order = "ASC";
		}
	} else {
		$order = "ASC";
	}

	//Sort column & order
	if ( isset($_POST['search']) OR isset($_GET['search']) ) {
		
		$searchstr = (isset($_POST['search']) ? $_POST['search'] : $_GET['search'] );
		$searchstr = preg_replace("/[^a-zA-Z0-9\s]/", "", $searchstr);
		$search	= "AND ( object.name LIKE \"%" . $searchstr . "%\" OR object.description LIKE \"%" . $searchstr . "%\" ) ";
	} else {
		$search = "";
		$searchstr = "";
	}

		// Define the query...
	$query = "SELECT object.id as objectid, object.name as objectname, object.description as objectdescription, object.timestamp as objecttimestamp, type.name as typename FROM object JOIN type ON (type.id = object.type_id) WHERE object.deleted = 0 $search ORDER BY $sort $order";

	// Run the query:
	if ($result = mysqli_query( $dbc, $query )) {
		$rows = mysqli_num_rows($result);
		print "<table class=\"object\">\n";
		print '<col width="40px" /><col width="150px" /><col /><col width="150px" /><col width="120px" /><col width="43px" />';
		print "\n<tr>\n";
		print "<th><a href=\"index.php?command=listobjects&sort=objectid&order=" . getorder("objectid", $sort, $order) . getsearch($searchstr) . "\">Id" . getorderarrow("objectid", $sort, $order) . "</a></th>\n";
		print "<th><a href=\"index.php?command=listobjects&sort=objectname&order=" . getorder("objectname", $sort, $order) . getsearch($searchstr) . "\">Name" . getorderarrow("objectname", $sort, $order) . "</a></th>\n";
		print "<th><a href=\"index.php?command=listobjects&sort=objectdescription&order=" . getorder("objectdescription", $sort, $order). getsearch($searchstr)  . "\">Description" . getorderarrow("objectdescription", $sort, $order) . "</a></th>\n";	
		print "<th><a href=\"index.php?command=listobjects&sort=typename&order=" . getorder("typename", $sort, $order) . getsearch($searchstr) . "\">Type" . getorderarrow("typename", $sort, $order) . "</a></th>\n";	
		print "<th><a href=\"index.php?command=listobjects&sort=objecttimestamp&order=" . getorder("objecttimestamp", $sort, $order) . getsearch($searchstr) . "\">Create Date" . getorderarrow("objecttimestamp", $sort, $order) . "</a></th>\n";
		print "<th></th>\n";
		print "</tr>\n";

		// Retrieve the returned records:
		while ($row = mysqli_fetch_assoc($result)) {
		
			// Print the record:
			print "<tr onClick=\"document.location.href='index.php?command=viewobject&objectid={$row['objectid']}';\" style=\"cursor:pointer;\">\n";
			print "<td>" . htmlspecialchars($row['objectid']) . "</td>\n";
			print "<td><a href=\"index.php?command=viewobject&objectid={$row['objectid']}\">" . htmlspecialchars($row['objectname']) . "</a></td>\n";
			print "<td>" . htmlspecialchars($row['objectdescription']) . "</td>\n";
			print "<td>" . htmlspecialchars($row['typename']) . "</td>\n";
			print "<td>" . dateformat(htmlspecialchars($row['objecttimestamp'])) . "\n";
			print "</td>\n";			
			print "<td>\n";
			if (getperm('editobject'))
			{
				print "<form action=\"index.php?command=editobject&objectid=" . htmlspecialchars($row['objectid']) . "\" method=\"post\">\n";
				print "<input class=\"edit\" type=\"submit\" name=\"submit\" value=\"Edit\" />\n";
				print "</form>\n";
			}
			print "</td>\n";			
			print "</tr>\n";
		} // End of while loop.
	print "</table>\n";
	print "<div class=\"result\">$rows rows returned</div>\n";
	if ( isset($_POST['search']) ) {
		print "<p id =\"fade\" class=\"info\">Found " . mysqli_num_rows($result) . " result(s) matching \"" . $searchstr . "\".</p>\n";
	}
	} else 
	{ // Query didn't run.
		debugprint( '<p id="clickme" class="error">Could not retrieve the data because:<br />' . mysqli_error($dbc) . '. The query being run was: ' . $query . '</p>');
	} // End of query IF.
}

/*******************************************************************/
/* function                                                        */
/*   getobjectid                                                   */
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
function getobjectid($dbc)
{
	$objectid = "1";
	foreach ($_GET as $varname => $varvalue) {
		if ($varname == 'objectid') {
	 		$objectid = $varvalue;
	 		$query = "SELECT object.id FROM object WHERE object.id=$objectid";
	 		$result = mysqli_query($bdc, $query);
	 		if ( !mysqli_num_rows($result) ) 
	 		{
		 		$objectid = "1";
	 		}
 		}
	}
	return($objectid);
}


/*******************************************************************/
/* function                                                        */
/*   viewobject                                                    */
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
function viewobject($dbc)
{
	if (isset($_GET['objectid']) && is_numeric($_GET['objectid']) && ($_GET['objectid'] > 0) ) { // Display the entry in a form:

		$query = "SELECT  object.id as objectid, object.name as objectname, object.description as objectdescription, object.type_id as typeid, type.name as typename FROM object JOIN type ON object.type_id = type.id WHERE object.id={$_GET['objectid']}";
		if ($result = mysqli_query($dbc, $query)) { // Run the query.
		
			$row = mysqli_fetch_assoc($result); // Retrieve the information.
			// Show Details:
			print "<table class=\"object\">\n";
			print '<col width="150px" /><col />';
			print "<th colspan=\"2\">Object</th>\n";
			print "<tr>\n";
			print "<td>Name : </td>\n";
			print "<td>" . htmlspecialchars($row['objectname']) . "</td>\n";
			print "</tr>\n";
			print "<tr>\n";
			print "<td>Description : </td>\n";
			print "<td>" . nl2br(htmlspecialchars($row['objectdescription'])) . "</td>\n";
						print "</tr>\n";
			print "<tr>\n";
			print "<td>Type :  </td>\n";
			print "<td>" . htmlspecialchars($row['typename']) . "</td>\n";
			print "</tr>\n";
			print "</table>\n";
			// Show Properties:
			$query = "SELECT property.name as propertyname, property.type as propertytype, object_property.id as objectpropertyid, object_property.shared as objectpropertyshared FROM object_property JOIN property ON object_property.property_id = property.id WHERE object_property.object_id={$_GET['objectid']} ORDER BY property.name ASC";
			if ($result = mysqli_query($dbc, $query)) { // Run the query.
				if ( mysqli_num_rows($result) > 0 ) {
					print "<table class=\"properties\">\n";
					print '<col width="150px" /><col />';
					print "<th colspan=\"2\">Properties</th>";
				
					while ( $row = mysqli_fetch_assoc($result) ) {
						print "<tr>\n";
						print "<td>" . htmlspecialchars($row['propertyname']);
						if ($row['objectpropertyshared']) {
							print " &#42;";
						}
						print "</td>\n";
						print "<td>\n";
						getpropertydata($dbc, $row['propertytype'], $row['objectpropertyid']);
						print "</td>\n";								
						print "</tr>\n";
					}
					print "</table>\n";
					print "<div class=\"result\">&#42; shared property</div>\n";
				}
			} else {
				debugprint( '<p id="clickme" class="error">Could not retrieve the property because:<br />' . mysqli_error($dbc) . '. The query being run was: ' . $query . '</p>');
			}
			
			// Show relations:
			$query = "SELECT object1.name AS objectname1, object2.name AS objectname2, object1.id AS objectid1, object2.id AS objectid2, " .
					  "relation.name AS relationname, object_relation.quantity AS objectrelationquantity, " .
					  "object_relation.unit AS objectrelationunit, object_relation.comment AS objectrelationcomment, " .
					  "relation.description AS relationdescription , relation.unidirectional AS unidirectional " .
					  "FROM object_relation " .
					  "JOIN relation ON object_relation.relation_id = relation.id " .
					  "JOIN object object2 ON object2.id = object_relation.object2_id " .
					  "JOIN object object1 ON object1.id = object_relation.object1_id " .
					  "WHERE object_relation.object1_id = {$_GET['objectid']} OR object_relation.object2_id = {$_GET['objectid']} ORDER BY relation.name";
			if ($result = mysqli_query($dbc, $query)) { // Run the query.
				if ( mysqli_num_rows($result) > 0 ) {
					print "<table class=\"relations\">\n";
					print '<col width="150px" /><col /><col width="35px" /><col width="45px" /><col />';
					print "<th>Relation</th>\n";
					print "<th colspan=\"4\">Related object</th>\n";

					while ( $row = mysqli_fetch_assoc($result) ) {
						print "<tr>\n";
						print "<td> ". htmlspecialchars($row['relationname']) . "</td>\n";
						print "<td> <a href=\"index.php?command=viewobject&objectid=" . htmlspecialchars($row['objectid1']) . "\">" . htmlspecialchars($row['objectname1']) . "</a> " . htmlspecialchars($row['relationdescription']) . " <a href=\"index.php?command=viewobject&objectid=" . htmlspecialchars($row['objectid2']) . "\">" . htmlspecialchars($row['objectname2']) . "</a></td>\n";
						print "<td>\n";
						print htmlspecialchars($row['objectrelationquantity']);
						print "</td>\n";
						print "<td>\n";
						print htmlspecialchars($row['objectrelationunit']);
						print "</td>\n";
						print "<td>\n";
						print htmlspecialchars($row['objectrelationcomment']);
						print "</td>\n";
						print "</tr>\n";
					}
					print "</table>\n";
				}
			} else {
				debugprint( '<p id="clickme" class="error">Could not retrieve the object because:<br />' . mysqli_error($dbc) . '. The query being run was: ' . $query . '</p>');
			}
						// Define the query...
				$query = "SELECT issue.id as issueid, issue.subject as issuesubject, issue.timestamp as issuetimestamp, issuetype.name as issuetypename, " .
							 "issuestatus.name as issuestatusname, user.name as username, issue.reference as issuereference " .
							 "FROM issue JOIN issuetype ON (issuetype.id = issue.issuetype_id) " .
							 "JOIN issuestatus ON (issuestatus.id = issue.issuestatus_id) " .
							 "JOIN user ON (user.id = issue.user_id) " .
							 "JOIN object_issues on (object_issues.issue_id = issue.id) " .
							 "WHERE issue.deleted = 0 AND object_issues.object_id = {$_GET['objectid']} ORDER BY issue.id";
				// Run the query:
				if ($result = mysqli_query($dbc, $query))
				{
					$rows = mysqli_num_rows($result);
					if ($rows > 0)
					{
						print "<table class=\"issues\"><th>Related issues</th></table>\n";
						print "<table class=\"issues\">\n";
						print '<col width="40px" /><col width="150px" /><col /><col width="150px" /><col width="120" /><col width="80" /><col width="120px" /><col width="99px" />';
						print "\n<tr>\n";
						print "<th>Id</th>\n";
						print "<th>Reference</th>\n";
						print "<th>Subject</th>\n";
						print "<th>Issue owner</th>\n";
						print "<th>Issue type</th>\n";	
						print "<th>Status</th>\n";	
						print "<th>Create Date</th>\n";
						print "<th></th>\n";
						print "</tr>\n";
				
						// Retrieve the returned records:
						while ($row = mysqli_fetch_assoc($result))
						{
							// Print the record:
							print "<tr onClick=\"document.location.href='index.php?command=viewissue&issueid={$row['issueid']}';\" style=\"cursor:pointer;\">\n";
							print "<td>" . htmlspecialchars($row['issueid']) . "</td>\n";
							print "<td><a href=\"index.php?command=viewissue&issueid={$row['issueid']}\">" . htmlspecialchars($row['issuereference'] ) . "</a></td>\n";
							print "<td><a href=\"index.php?command=viewissue&issueid={$row['issueid']}\">" . htmlspecialchars($row['issuesubject'] ) . "</a></td>\n";
							print "<td>" . htmlspecialchars($row['username']) . "</td>\n";
							print "<td>" . htmlspecialchars($row['issuetypename']) . "</td>\n";
							print "<td>" . htmlspecialchars($row['issuestatusname']) . "</td>\n";
							print "<td>" . dateformat(htmlspecialchars($row['issuetimestamp'])) . "\n";
							print "</td>\n";			
							print "<td>\n";
							if (getperm('editissue'))
							{
								print "<form style=\"float:right;\" action=\"index.php?command=editissue&issueid=" . htmlspecialchars($row['issueid']) . "\" method=\"post\">\n";
								print "<input class=\"edit\" type=\"submit\" name=\"submit\" value=\"Edit\" />\n";
								print "</form>\n";
							}
							if (getperm('addissuemsg'))
							{
								print "<form style=\"float:right;margin-right: 2px;\" action=\"index.php?command=addissuemsg&issueid=" . htmlspecialchars($row['issueid']) . "\" method=\"post\">\n";
								print "<input class=\"edit\" type=\"submit\" name=\"submit\" value=\"Update\" />\n";
								print "</form>\n";
							}
							print "</td>\n";			
							print "</tr>\n";
						} // End of while loop.
					print "</table>\n";
					print "<div class=\"result\">$rows rows returned</div>\n";
				}
			}
	
		} else { // Couldn't get the information.
			debugprint( '<p id="clickme" class="error">Could not retrieve the relation because:<br />' . mysqli_error($dbc) . '. The query being run was: ' . $query . '</p>');
		}
	
	} else { // No ID set.
		print '<p id="clickme" class="error">This page has been accessed in error.</p>';
	} // End of main IF.
}


/*******************************************************************/
/* function                                                        */
/*   addobject                                                     */
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
function addobject($dbc)
{
	$error = false;
	if ($_SERVER['REQUEST_METHOD'] == 'POST') 
	{ // Handle the form
		if ( !empty($_POST['name']) && !empty($_POST['description']) ) {

						// Prepare the values for storing:
			$name = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['name'])));
			$description = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['description'])));
			$type = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['type'])));
		
			$query = "INSERT INTO object (name, description, type_id ) VALUES ('$name', '$description', '$type')";
			$result = mysqli_query($dbc, $query);
			
			if (mysqli_affected_rows($dbc) == 1){
				// Print a message:
				$usedid = mysqli_insert_id($dbc);
				print '<p id="fade" class="info">Object has been saved.</p>';
			} else {
				debugprint( '<p id="clickme" class="error">Could not store the object because:<br />' . mysqli_error($dbc) . '. The query being run was: ' . $query . '</p>');
			}
			
		} else { // Failed to enter data.
			print '<p id="clickme" class="error">Please enter a name and a description!</p>';
			$error=true;
		}
		listobjects($dbc);
	} elseif (!$error)
	{	
		if(typesdefined($dbc))
		{
			// Show the form
			print "<form action=\"index.php?command=addobject\" method=\"post\">\n";
			print "<table>\n";
			print '<col width="150px" /><col />';
			print "<th class=\"object\" colspan=\"2\">Object \n";
			print "<input class=\"save\" type=\"submit\" name=\"submit\" value=\"Save\" />\n";
			print "</th>\n";
			print "<tr class=\"object\" >\n";
			print "<td>Name</td>\n";
			print "<td><input class=\"field\" type=\"text\" name=\"name\" /></label></td>";
			print "</tr>\n";
			print "<tr class=\"object\" >\n";
			print "<td>Description</td>\n";
			print "<td><textarea class=\"field\" name=\"description\" rows=\"5\" cols=\"70\"></textarea></td>";
			print "</tr>\n";
			// Define query for type
			$query = "SELECT  id, name FROM type ORDER BY id ASC";
			if ($result = mysqli_query($dbc, $query)) { // Run the query.
				print "<tr class=\"object\" >\n";
				print "<td>Type</td>\n";
				print  "<td><select class=\"field\" name=\"type\">\n";
				while ($row = mysqli_fetch_assoc($result)) {
					print '<option';
					print ' value ="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['name']) . '</option>' ."\n";
				} 			
			print "</select></td>\n";
			print "</tr>\n";

			} else { // Couldn't get the information.
				debugprint( '<p id="clickme" class="error">Could not retrieve the object because:<br />' . mysqli_error($dbc) . '. The query being run was: ' . $query . '</p>');
			}
			print "</table>\n";
			print "</form>\n";
		} else
		{
			print '<p id="clickme" class="error">No objects can be created until at least one type is created.<br />Click Type and then Add to create a type.</p>';
			listobjects($dbc);
		}
		
	}
}


/*******************************************************************/
/* function                                                        */
/*   addmobject                                                    */
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
function addmobject($dbc)
{
	$error = false;
	if ($_SERVER['REQUEST_METHOD'] == 'POST') 
	{ // Handle the form
		if ( !empty($_POST['name']) && !empty($_POST['description']) ) {

						// Prepare the values for storing:
			$name = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['name'])));
			$description = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['description'])));
			$type = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['type'])));
		
			$query = "INSERT INTO object (name, description, type_id ) VALUES ('$name', '$description', '$type')";
			$result = mysqli_query($dbc, $query);
			
			if (mysqli_affected_rows($dbc) == 1){
				// Print a message:
				$usedid = mysqli_insert_id($dbc);
				print '<p id="fade" class="info">Object has been saved.</p>';
			} else {
				debugprint( '<p id="clickme" class="error">Could not store the object because:<br />' . mysqli_error($dbc) . '. The query being run was: ' . $query . '</p>');
			}
			
		} else { // Failed to enter data.
			print '<p id="clickme" class="error">Please enter a name and a description!</p>';
			$error=true;
		}
	}
	if (!$error)
	{	
		if(typesdefined($dbc))
		{
			// Show the form
			print "<form action=\"index.php?command=addmobject\" method=\"post\">\n";
			print "<table>\n";
			print '<col width="150px" /><col />';
			print "<th class=\"object\" colspan=\"2\">Object \n";
			print "<input class=\"save\" type=\"submit\" name=\"submit\" value=\"Save & Next\" />\n";
			print "</th>\n";
			print "<tr class=\"object\" >\n";
			print "<td>Name</td>\n";
			print "<td><input class=\"field\" type=\"text\" name=\"name\" /></label></td>";
			print "</tr>\n";
			print "<tr class=\"object\" >\n";
			print "<td>Description</td>\n";
			print "<td><textarea class=\"field\" name=\"description\" rows=\"5\" cols=\"70\"></textarea></td>";
			print "</tr>\n";
			// Define query for type
			$query = "SELECT  id, name FROM type ORDER BY id ASC";
			if ($result = mysqli_query($dbc, $query)) { // Run the query.
				print "<tr class=\"object\" >\n";
				print "<td>Type</td>\n";
				print  "<td><select class=\"field\" name=\"type\">\n";
				while ($row = mysqli_fetch_assoc($result)) {
					print '<option';
					print ' value ="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['name']) . '</option>' ."\n";
				} 			
			print "</select></td>\n";
			print "</tr>\n";

			} else { // Couldn't get the information.
				debugprint( '<p id="clickme" class="error">Could not retrieve the object because:<br />' . mysqli_error($dbc) . '. The query being run was: ' . $query . '</p>');
			}
			print "</table>\n";
			print "</form>\n";
		} else
		{
			print '<p id="clickme" class="error">No objects can be created until at least one type is created.<br />Click Type and then Add to create a type.</p>';
			listobjects($dbc);
		}
	}
}


/*******************************************************************/
/* function                                                        */
/*   editobject                                                    */
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
function editobject($dbc)
{
	$error = false;
	if (isset($_POST['objectid']) && is_numeric($_POST['objectid']) && ($_POST['objectid'] > 0)) { // Handle the form.

		// Validate and secure the form data:
		if ( !empty($_POST['name']) && !empty($_POST['description']) && !empty($_POST['type']) ) {
	
			// Prepare the values for storing:
			$name = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['name'])));
			$description = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['description'])));
			$type = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['type'])));
			// Define the query.
			$query = "UPDATE object SET name='$name', description='$description', type_id='$type'  WHERE id={$_POST['objectid']}";
			if ($result = mysqli_query($dbc, $query)) {
				print '<p id="fade" class="info">The object has been updated.</p>';
			} else {
				debugprint( '<p id="clickme" class="error">Could not update the object because:<br />' . mysqli_error($dbc) . '. The query being run was: ' . $query . '</p>');
				$error = true;
			}	
		} // No problem!
	}
	
	if ( isset($_GET['objectid']) && is_numeric($_GET['objectid']) && ($_GET['objectid'] > 0) ) { // Display the entry in a form:

		$query = "SELECT  object.id as objectid, object.name as objectname, object.description as objectdescription, object.type_id as typeid, type.name as typename FROM object JOIN type ON object.type_id = type.id WHERE object.id={$_GET['objectid']}";
		if ($result = mysqli_query($dbc, $query)) { // Run the query.
		
			$row = mysqli_fetch_assoc($result); // Retrieve the information.
			$typeid = $row['typeid'];
			// Make the form:
			print "<form action=\"index.php?command=editobject&objectid=" . $_GET['objectid'] . "\" method=\"post\">";
			print "<table class=\"object\">";
			print '<col width="150px" /><col />';
			print "<th colspan=\"2\">";
			print "Object";
			print "<input class=\"save\" type=\"submit\" name=\"submit\" value=\"Save Object\"/>\n";
			print "</th>\n";
			print "<tr>\n";
			print "<td>Name :</td>\n";
			print "<td><input class=\"field\" type=\"text\" name=\"name\" value=\"" . htmlspecialchars($row['objectname']) . "\" /></td>";
			print "</tr>\n";
			print "<tr>\n";
			print "<td>Description :</td>\n";
			print "<td><textarea class=\"field\" name=\"description\">" . htmlspecialchars($row['objectdescription']) . "</textarea></td>\n";
			print "</tr>\n";
			// Define query for type
			$query = "SELECT  id, name FROM type ORDER BY id ASC";
			if ($result = mysqli_query($dbc, $query)) { // Run the query.
				print "<tr>\n";
				print "<td>Type :</td>\n";
				print "<td><select width=\"100px\" class=\"field\" name=\"type\">\n";
				while ($row = mysqli_fetch_assoc($result)) {
				print '<option';
				if ( $row['id'] == $typeid ) {
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
			print '<input type="hidden" name="objectid" value="' . $_GET['objectid'] . '" />';
			print '</form>';
	
			// Show Properties:
			print "<table class=\"properties\">";
			print '<col width="150px" /><col />';
			print "<th>Properties</th>\n";
			print "<th>\n";
			print "<form action=\"index.php?command=addobjectproperty&objectid=" . $_GET['objectid'] . "\" method=\"post\">\n";
			print '<input type="hidden" name="objectid" value="' . $_GET['objectid'] . '" />' . "\n";
			// Define query for property
			$query = "SELECT  id, name FROM property ORDER BY name ASC";
			if ($result = mysqli_query($dbc, $query)) { // Run the query.
				print '<select class="edit" name="propertyid">' . "\n";
				while ($row = mysqli_fetch_assoc($result)) {
				print '<option';
				print ' value ="' . $row['id'] . '">' . $row['name'] . '</option>' ."\n";
				} 
			} else { // Couldn't get the information.
				debugprint( '<p id="clickme" class="error">Could not retrieve the object because:<br />' . mysqli_error($dbc) . '. The query being run was: ' . $query . '</p>');
				$error = true;
			}
			print "</select>\n";
			print '<input class="edit" type="submit" name="submit" value="Add" />';
			print '</form>';
			print "</th>";
			$query = "SELECT object_property.object_id, property.name as propertyname, object_property.id as objectpropertyid, property.type as propertytype, object_property.shared as objectpropertyshared FROM object_property JOIN property ON object_property.property_id = property.id WHERE object_property.object_id={$_GET['objectid']} ORDER BY property.name ASC";
			if ($result = mysqli_query($dbc, $query)) { // Run the query.
			while ( $row = mysqli_fetch_assoc($result) ) {
				print "<tr>\n";
				print "<td>\n";
				print htmlentities($row['propertyname']);
				if ($row['objectpropertyshared']) {
					print " &#42;";
				}
				print "</td>\n";
				print "<td>\n";
				print "<div style=\"display:inline-block;float:left;\">\n";		
				getpropertydata($dbc, $row['propertytype'], $row['objectpropertyid']);
				print "</div>\n";
				print "<form style=\"display:inline-block;float:right;\" action=\"index.php?command=deleteobjectproperty&objectpropertyid=" . $row['objectpropertyid'] . "&objectid=" . $_GET['objectid'] . "\" method=\"post\">";
				print '<input type="hidden" name="objectid" value="' . $_GET['objectid'] . '" />';
				print '<input type="hidden" name="propertytype" value="' . htmlspecialchars($row['propertytype']) . '" />';
				print '<input type="hidden" name="objectpropertyid" value="' . htmlspecialchars($row['objectpropertyid']) . '" />';
				print '<input type="hidden" name="objectpropertyshared" value="' . htmlspecialchars($row['objectpropertyshared']) . '" />';
				if (!$row['objectpropertyshared'])
				{
					print "<input style=\"float:left;\" class=\"delete\" type=\"submit\" name=\"submit\" value=\"Delete\" />";
				} else
				{
					print "<input style=\"float:left;\" class=\"delete\" type=\"submit\" name=\"submit\" value=\"Delete &#42;\" />";
				}
				print '</form>';
				print "<form style=\"display:inline-block;float:right;\" action=\"index.php?command=editobjectproperty&objectpropertyid=" . htmlspecialchars($row['objectpropertyid']) . "&objectid=" . $_GET['objectid'] . "\" method=\"post\">";
				print '<input type="hidden" name="objectid" value="' . $_GET['objectid'] . '" />';
				print '<input type="hidden" name="objectpropertyid" value="' . htmlspecialchars($row['objectpropertyid']) . '" />';
				if (!$row['objectpropertyshared'])
				{
					print '<input class="edit" type="submit" name="submit" value="Edit" />';
				} else
				{
					print '<input class="edit" type="submit" name="submit" value="Edit &#42;" />';
				}
				print '</form>';
				print "</td>\n";
				print "</tr>\n";
				}
			} else {
				debugprint( '<p id="clickme" class="error">Could not retrieve the property because:<br />' . mysqli_error($dbc) . '. The query being run was: ' . $query . '</p>');
				$error = true;
			}
			print "</table>\n";
			print "<table class=\"relations\">\n";
			print '<col width="150px" /><col /><col width="35px" /><col width="45px" /><col /><col width="45px" />';
			print "<th>Relation</th>\n";
			print "<th colspan=\"5\">\n";
			// Listbox relations
			print "<form action=\"index.php?command=addobjectrelation&objectid=" . $_GET['objectid'] . "\" method=\"post\">\n";
			print '<input type="hidden" name="objectid" value="' . $_GET['objectid'] . '" />' . "\n";
			// Define query for relation
			$query = "SELECT  id, name FROM relation ORDER BY name ASC";
			if ($result = mysqli_query($dbc, $query)) { // Run the query.
				print '<select class="edit" name="relationid">' . "\n";
				while ($row = mysqli_fetch_assoc($result)) {
				print '<option';
				print ' value ="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['name']) . '</option>' ."\n";
				} 
			} else { // Couldn't get the information.
				debugprint( '<p id="clickme" class="error">Could not retrieve the relation because:<br />' . mysqli_error($dbc) . '. The query being run was: ' . $query . '</p>');
				$error = true;
			}
			print "</select>\n";
			// Define query for objects
			$query = "SELECT  id, name FROM object WHERE id <> {$_GET['objectid']} ORDER BY name ASC";
			if ($result = mysqli_query($dbc, $query)) { // Run the query.
				print '<select class="edit" name="robjectid">' . "\n";
				while ($row = mysqli_fetch_assoc($result)) {
				print '<option';
				print ' value ="' . $row['id'] . '">' . $row['name'] . '</option>' ."\n";
				} 
			} else { // Couldn't get the information.
				debugprint( '<p id="clickme" class="error">Could not retrieve the object because:<br />' . mysqli_error($dbc) . '. The query being run was: ' . $query . '</p>');
				$error = true;
			}
			print "</select>\n";
			print "<span class=\"relations\">Qty: <input class=\"sfield\" type=\"text\" size=\"5\" name=\"objectrelationquantity\" value=\"0\"/></span>\n";
			print "<span>Unit: <input class=\"sfield\" type=\"text\" size=\"5\" name=\"objectrelationunit\" value=\"pcs\"/></span>\n";
			print "<span>Remark: <input class=\"sfield\" type=\"text\" size=\"50\" name=\"objectrelationcomment\" value=\"-\"/></span>\n";
			print '<input class="edit" type="submit" name="submit" value="Add" />';
			print '</form>';
			print "</th>\n";
			// Show relations:
			$query = "SELECT object1.name as objectname1, object2.name as objectname2, object1.id as objectid1, object2.id as objectid2, " .
					  "relation.name as relationname, object_relation.id as objectrelationid, relation.description as relationdescription, " .
					  "relation.unidirectional as unidirectional, object_relation.id as objectrelationid, object_relation.quantity AS objectrelationquantity, " .
					  "object_relation.unit AS objectrelationunit, object_relation.comment AS objectrelationcomment " .
					  "FROM object_relation " .
					  "JOIN relation ON object_relation.relation_id = relation.id " .
					  "JOIN object object2 ON object2.id = object_relation.object2_id " .
					  "JOIN object object1 ON object1.id = object_relation.object1_id " .
					  "WHERE object_relation.object1_id = {$_GET['objectid']} OR object_relation.object2_id = {$_GET['objectid']} ORDER BY relation.name";
			if ($result = mysqli_query($dbc, $query)) { // Run the query.
				while ( $row = mysqli_fetch_assoc($result) ) {
					print "<tr>\n";
					print "<td>";
					print htmlspecialchars($row['relationname']);
					print "</td>\n";
					print "<td>\n";
					print "<div style=\"display:inline-block;float:left;\">\n";		
					print "<a href=\"index.php?command=viewobject&objectid=" . htmlspecialchars($row['objectid1']) . "\">" . htmlspecialchars($row['objectname1']) . "</a> " . htmlspecialchars($row['relationdescription']) . " <a href=\"index.php?command=viewobject&objectid=" . htmlspecialchars($row['objectid2']) . "\">" . htmlspecialchars($row['objectname2']) . "</a>";
					print "</div>\n";
					print "</td>\n";
					print "<td>\n";
					print htmlspecialchars($row['objectrelationquantity']);
					print "</td>\n";
					print "<td>\n";
					print htmlspecialchars($row['objectrelationunit']);
					print "</td>\n";
					print "<td>\n";
					print htmlspecialchars($row['objectrelationcomment']);
					print "</td>\n";
					print "<td>\n";
					print "<form  style=\"display:inline-block;float:right;\" action=\"index.php?command=deleteobjectrelation&objectid=" . $_GET['objectid'] . "\" method=\"post\">";
					print '<input type="hidden" name="objectid" value="' . $_GET['objectid'] . '" />';
					print '<input type="hidden" name="objectrelationid" value="' . htmlspecialchars($row['objectrelationid']) . '" /> ';
					print '<input class="delete" type="submit" name="submit" value="Delete" />';
					print '</form>';
					print "</td>\n";
					print "</tr>\n";
				}
			print "</table>\n";
			} else {
				debugprint( '<p id="clickme" class="error">Could not retrieve the object because:<br />' . mysqli_error($dbc) . '. The query being run was: ' . $query . '</p>');
			}
		print "<div class=\"result\">&#42; shared property</div>\n";	
		} else { // Couldn't get the information.
			debugprint( '<p class="error">Could not retrieve the object because:<br />' . mysqli_error($dbc) . '. The query being run was: ' . $query . '</p>');
		}
	} else { // No ID set.
		print '<p class="error">This page has been accessed in error.</p>';
	} // End of main IF.
}

/**************************************************************************************/
/* Object property                                                                    */
/**************************************************************************************/
//
// Functions to handle object properties:
// getpropertydata: return the object property for display
// geteditpropertydata: return the object property for editing
// addobjectproperty: Show a form to add a object property based on the property type
// editobjectproperty: Show a form to edit an object property
// storeobjectproperty : Store new object property in the database
// updateobjectproperty : Update an object property in the database
// deleteobjectproperty : Delete an object property
//
// Property types:
//   0: none -> no extra data
//   1: text -> text field
//   2: numeric -> numbers only field
//   3: URL -> show link
//  11: file -> file stored in BLOB
//  12: image -> image stored in BLOB
//  13: file -> file stored on filesystem
//  14: image -> image stored on filesystem
/**************************************************************************************/

/* Scrapbook code

..

*/


/*******************************************************************/
/* function                                                        */
/*   addobjectproperty                                             */
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
function addobjectproperty($dbc)
{
	$error = false;
	if ( (!$error) && ($_SERVER['REQUEST_METHOD'] == 'POST') )
	{	// Show the form
		if ( isset($_GET['objectid']) && is_numeric($_GET['objectid']) && ($_GET['objectid'] > 0) && isset($_POST['propertyid']) )
		{ 
			// Define query for property
			$query = "SELECT  id, name, type FROM property WHERE id={$_POST['propertyid']} LIMIT 1";
			// Run the query
			if ($result = mysqli_query($dbc, $query)) { 
				$row = mysqli_fetch_assoc($result);
				}
			$propertytype = htmlspecialchars($row['type']);	
			// New property
			print "<form action=\"index.php?command=storeobjectproperty&objectid=" . $_GET['objectid'] . "\" method=\"post\"";
			switch ($propertytype)
			{
				case 11:
				case 12:
				case 13:
				case 14:
				print " ENCTYPE=\"multipart/form-data\"";
				break;
			}
			print ">\n";
			print "<table class=\"properties\">\n";
			print '<col width="150px" /><col />';
			print "<th colspan=\"2\">Associate property\n";
			print "<input class=\"save\" type=\"submit\" name=\"submit\" value=\"Save\" />\n";
			print "</th>\n"; 
			print "<tr><td>" . $row['name'] . ":</td><td>";
			geteditpropertydata($dbc, $propertytype, 0);
			$max_upload = (int)(ini_get('upload_max_filesize'));
			$max_post = (int)(ini_get('post_max_size'));
			$memory_limit = (int)(ini_get('memory_limit'));
			$maxfilesize = min($max_upload, $max_post, $memory_limit);
			print "(Maximum $maxfilesize Mb)\n";
			print "</td></tr>\n";
			print "<tr><td colspan=\"2\">\n";
			print '<input type="hidden" name="objectid" value="' . $_GET['objectid'] . '" />';
			print '<input type="hidden" name="propertyid" value="' . $_POST['propertyid'] . '" />';
			print '<input type="hidden" name="propertytype" value="' . $propertytype . '" />';
			print '<input type="checkbox" name="newshared">Shared property';
			print '<input type="hidden" name="shared" value="0" />';
			print "</td></tr>\n";
			print "</table>\n";
			print "</form>\n";
			print "<div class=\"note\">(Shared properties can be used by several objects and editing them will change the value for every object)</div>\n";
			// Use existing property
			// Run the query
			$query = "SELECT object_property.id as objectpropertyid, object_property.property_id as objectpropertypropertyid, object_property.object_id as objectpropertyobjectid, object.name as objectname, property.name as propertyname FROM object_property JOIN object ON object.id = object_property.object_id JOIN property ON property.id = object_property.property_id WHERE object_property.property_id={$_POST['propertyid']} AND object_property.shared = 1 AND object_property.object_id <> {$_POST['objectid']} ORDER BY object.name";
			if ($result = mysqli_query($dbc, $query))
			{
				if ( mysqli_num_rows($result) < 1 )
				{
					print "<div class=\"note\">No shared properties of this type</div>\n";
				} else
				{
					print "<form action=\"index.php?command=storeobjectproperty&objectid=" . $_GET['objectid'] . "\" method=\"post\">\n";
					print "<table class=\"properties\">\n";
					print '<col width="150px" /><col />';
					print "<th colspan=\"2\">Associate existing shared property\n";
					print "<input class=\"save\" type=\"submit\" name=\"submit\" value=\"Save\" />\n";
					print "</th>\n";
					print "<tr><td>" . $row['name'] . ":</td><td>\n";
					print "<select class=\"field\" name=\"propertydata\">\n";
					while ($row = mysqli_fetch_assoc($result))
					{
						print "<option value=\"" . htmlspecialchars($row['objectpropertyid']) . "\">" . htmlspecialchars($row['propertyname']) . " from \"" . htmlspecialchars($row['objectname']) . "\"</option>\n";
					};
					print "</select>\n";
					print "</td></tr>\n";
					print '<input type="hidden" name="objectid" value="' . $_GET['objectid'] . '" />';
					print '<input type="hidden" name="propertyid" value="' . $_POST['propertyid'] . '" />';
					print '<input type="hidden" name="propertytype" value="' . $propertytype . '" />';
					print '<input type="hidden" name="shared" value="1" />';
				}
			}
			print "</table>\n";
			print "</form>\n";
		}
	}
}


/*******************************************************************/
/* function                                                        */
/*   editobjectproperty                                            */
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
function editobjectproperty($dbc)
{
	$error = false;
	if ( (!$error) && ($_SERVER['REQUEST_METHOD'] == 'POST') )
	{	// Show the form
		if ( isset($_GET['objectpropertyid']) && is_numeric($_GET['objectpropertyid']) && ($_GET['objectpropertyid'] > 0) && isset($_GET['objectid'])   ) { 
			// Define query for property
			$query = "SELECT object_property.object_id, property.name as propertyname, object_property.id as objectpropertyid, object_property.shared as objectpropertyshared, property.type as propertytype FROM object_property JOIN property ON object_property.property_id = property.id WHERE object_property.id={$_GET['objectpropertyid']}";
			// Run the query
			if ($result = mysqli_query($dbc, $query)) { 
				$row = mysqli_fetch_assoc($result);
				}
			$propertytype = htmlspecialchars($row['propertytype']);
			print "<form action=\"index.php?command=updateobjectproperty&objectpropertyid=" . $_POST['objectpropertyid'] . "&objectid=" . $_POST['objectid'] . "\" method=\"post\"";
			// Needed to upload files
			switch ($row['propertytype'])
			{
				case 11:
				case 12:
				case 13:
				case 14:
				print " ENCTYPE=\"multipart/form-data\"";
				break;
			}
			print ">\n";
			print "<table class=\"properties\">\n";
			print '<col width="150px" /><col />';
			print "<th colspan=\"2\">Object Property\n";
			print "<input class=\"save\"type=\"submit\" name=\"submit\" value=\"Save\" />\n";
			print "</th>\n";
			print "<tr><td>" . $row['propertyname'] . ":";
			if ($row['objectpropertyshared']) {
				print " &#42; (Shared property)";
			}
			print "</td><td>\n";
			geteditpropertydata($dbc, $row['propertytype'], $_POST['objectpropertyid']);
			print "</td></tr>\n";
			print '<input type="hidden" name="objectid" value="' . $_GET['objectid'] . '" />';
			print '<input type="hidden" name="objectpropertyid" value="' . $_POST['objectpropertyid'] . '" />';
			print '<input type="hidden" name="propertytype" value="' . $propertytype . '" />';
			
			print "</form>\n";
			print "</table>\n";
			// Used by these objects if shared
			if ($row['objectpropertyshared']) {
				print "<div class=\"note\">&#42; Changing it will change the property for all objects that share it</div>\n";
				print "<table class=\"properties\">\n";
				print "<th>Shared by these objects</th>";
				$query = "SELECT object.name as objectname, object.id as objectid FROM object_property JOIN object ON object.id = object_property.object_id JOIN property ON property.id = object_property.property_id WHERE object_property.id={$_GET['objectpropertyid']} AND object_property.shared = 1";
				// Run the query
				if ($result = mysqli_query($dbc, $query)) { 
					while ($row = mysqli_fetch_assoc($result)) {
						print "<tr><td><a href=\"index.php?command=viewobject&id=" . htmlspecialchars($row['objectid']) . "\">" . htmlspecialchars($row['objectname']) . "</a></td></tr>\n";
					};
				}
				print "</table>\n";
				if ( mysqli_num_rows($result) == 1 ) {
					print "<form style=\"float:right;\" action=\"index.php?command=deleteobjectproperty&objectpropertyid=" . $_POST['objectpropertyid'] . "&objectid=" . $_GET['objectid'] . "\" method=\"post\">";
					print '<input type="hidden" name="objectid" value="' . $_GET['objectid'] . '" />';
					print '<input type="hidden" name="propertytype" value="' . $propertytype . '" />';
					print '<input type="hidden" name="objectpropertyshared" value="' . htmlspecialchars($row['objectpropertyshared']) . '" />';
					print "<input style=\"float:left;\" class=\"delete\" type=\"submit\" name=\"submit\" value=\"Delete\" />";
					print '</form>';

				}
			}
			
		}
	}
}


/*******************************************************************/
/* function                                                        */
/*   storeobjectproperty                                           */
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
function storeobjectproperty($dbc)
{
	$error = false;
	if ( ($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_GET['objectid']) && isset($_POST['propertyid'])) 
	{ // Handle the form
		if ( !empty($_POST['propertyid']) && !empty($_POST['objectid']) && !empty($_POST['propertydata']) && !empty($_POST['propertytype']) && isset($_POST['shared'])  ) {

			// Prepare the values for storing:
			$data = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['propertydata'])));
			$objectid = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['objectid'])));
			$propertyid = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['propertyid'])));
			$propertytype = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['propertytype'])));
			if (!$error && isset($_POST['shared'])) {
				$shared = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['shared'])));
			} else {
				$shared = 0;
			}
			if (!$error && isset($_POST['newshared']) && $_POST['newshared'] == 'on') {
				$shared = 1;
				$query = "INSERT INTO object_property (object_id, property_id, shared) VALUES ('$objectid', '$propertyid', '$shared')";
			} else {
				if ( $shared == 1) {
					$query = "INSERT INTO object_property (id, object_id, property_id, shared) VALUES ('$data', '$objectid', '$propertyid', '$shared')";
				} else {
					$query = "INSERT INTO object_property (object_id, property_id, shared) VALUES ('$objectid', '$propertyid', '$shared')";
				}
			}
			// Insert the property in the object_property table
			
			if (!$error) 
			{
				$result = mysqli_query($dbc, $query);
				// Get the ID used in object_property table
				$usedid = mysqli_insert_id($dbc);
			}
			if ( !mysqli_affected_rows($dbc) == 1){
				$error = true;
			}
			// Insert the data into object_property_data_text table if not shared
			if (!$error && $_POST['shared'] == 0) {
				switch ( $propertytype )
				{
					case 1:
					case 2:
					case 3:
						$query = "INSERT INTO object_property_data_text (object_property_id, data) VALUES ('$usedid', '$data')";
						$result = mysqli_query($dbc, $query);
						if(!mysqli_affected_rows($dbc) == 1){
							$error=true;;
						} 
						break;
					case 11:
					case 12:
						$filename = $_FILES["uploadfile"]["name"];
						$filetype = $_FILES["uploadfile"]["type"];
						$filesize = $_FILES["uploadfile"]["size"];
						$file = $_FILES["uploadfile"]["tmp_name"];
						$filedata = addslashes(fread(fopen($file, "r"), filesize($file)));
						fclose($file);
						$query = "INSERT INTO object_property_data_binary (object_property_id, data, filename, filesize, filetype) " .
								"VALUES ('$usedid', '$filedata', '$filename', '$filesize', '$filetype')";
						$result = mysqli_query($dbc, $query);
						if(!mysqli_affected_rows($dbc) == 1){
							$error=true;;
						}
						break;
					case 13:
					case 14:
						$filename = $_FILES["uploadfile"]["name"];
						$filetype = $_FILES["uploadfile"]["type"];
						$filesize = $_FILES["uploadfile"]["size"];
						$file = $_FILES["uploadfile"]["tmp_name"];
						$fsfilename = "file" . $usedid . "." . pathinfo($filename, PATHINFO_EXTENSION);
						// Store the file on the filesystem
						$filepath = getfilepath() . $fsfilename;
						if(!(move_uploaded_file($_FILES['uploadfile']['tmp_name'], $filepath))) {
	 						$error = true;
						}
						// Insert data into database
						if($error == false) {
							$query = "INSERT INTO object_property_data_binary (object_property_id, data, filename, filesize, filetype) " .
								"VALUES ('$usedid', '$filepath', '$filename', '$filesize', '$filetype')";
							$result = mysqli_query($dbc, $query);
							if(!mysqli_affected_rows($dbc) == 1){
								$error=true;;					
							}
						}
						// Generate a thumbnail
						// Only tested with jpg, I want a generic solution
						//createthumbnail($fsfilename);
						break;
				}
			}
			
			if ( !$error ) {
				print '<p id="fade" class="info">Object property has been saved.</p>';
			} else {
				debugprint( '<p id="clickme" class="error">Could not store the object property because:<br />' . mysqli_error($dbc) . '. The query being run was: ' . $query . '</p>');
			}
			
		} else { // Failed to enter data.
			print '<p class="error">Please enter some data!</p>';
			$error=true;
		}
		editobject($dbc);
	} 
}


/*******************************************************************/
/* function                                                        */
/*   updateobjectproperty                                          */
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
function updateobjectproperty($dbc)
{
	$error = false;
	if ( ($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_GET['objectpropertyid']) && isset($_POST['objectpropertyid'])) 
	{ // Handle the form
		if ( !empty($_POST['objectpropertyid']) && !empty($_POST['objectid']) && !empty($_POST['propertydata']) && !empty($_POST['propertytype']) ) {

			// Prepare the values for storing:
			$data = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['propertydata'])));
			$objectid = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['objectid'])));
			//$propertyid = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['propertyid'])));
			$propertytype = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['propertytype'])));
			$objectpropertyid = ($_GET['objectpropertyid']);
			
			switch ( $propertytype )
			{
				case 1:
				case 2:
				case 3:
					$query = "UPDATE object_property_data_text SET data='$data' WHERE object_property_id={$_GET['objectpropertyid']}";
					$result = mysqli_query($dbc, $query);
					if(!mysqli_affected_rows($dbc) == 1){
						$error=true;;
					}
					break;
				case 11:
				case 12:
					$filename = $_FILES["uploadfile"]["name"];
					$filetype = $_FILES["uploadfile"]["type"];
					$filesize = $_FILES["uploadfile"]["size"];
					$file = $_FILES["uploadfile"]["tmp_name"];
					$filedata = addslashes(fread(fopen($file, "r"), filesize($file)));
					$query = "UPDATE object_property_data_binary SET data='$filedata', filename='$filename', filesize='$filesize', filetype='$filetype' WHERE object_property_id={$_GET['objectpropertyid']}";
					$result = mysqli_query($dbc, $query);
					if(!mysqli_affected_rows($dbc) == 1){
						$error=true;
					}
					break;
				case 13:
				case 14:
					$filename = $_FILES["uploadfile"]["name"];
					$filetype = $_FILES["uploadfile"]["type"];
					$filesize = $_FILES["uploadfile"]["size"];
					$file = $_FILES["uploadfile"]["tmp_name"];
					// Get filename from database
					$query = "SELECT  object_property_data_binary.filename as filename FROM object_property_data_binary WHERE object_property_data_binary.object_property_id = $objectpropertyid";
					if ($result = mysqli_query($dbc, $query)) { // Run the query.
						if ( mysqli_num_rows($result) > 0 ) {
							$row = mysqli_fetch_assoc($result);
							$oldfilepath = getfilepath() . "file" . $_GET['objectpropertyid'] . "." . pathinfo($row['filename'], PATHINFO_EXTENSION);
						} else {
							$error = true;
						}
					}

					$newfilepath = getfilepath() . "file" . $_GET['objectpropertyid'] . "." . pathinfo($filename, PATHINFO_EXTENSION); 
					$tempfile = tempnam(getfilepath(), "tmp");
					// Try to rename to temp file
					if (!rename($oldfilepath, $tempfile)) {
						$error = true;
					}
					// Try to upload the new file
					if (!$error == true) {
						if(!move_uploaded_file($_FILES['uploadfile']['tmp_name'], $newfilepath)) {
 							$error = true;
						}				
					}
					// Try to update the database
					if (!$error == true) {
						$query = "UPDATE object_property_data_binary SET data='$newfilepath', filename='$filename', filesize='$filesize', filetype='$filetype' WHERE object_property_id={$_GET['objectpropertyid']}";
						$result = mysqli_query($dbc, $query);
						if ( !mysqli_affected_rows($dbc) == 1){
							$error = true;
						}
					}
					// Delete the temp file if no $error
					if (!$error) {
						unlink($tempfile);
					} else { // Or else put the file back so we don't loose it :)
						rename($tempfile, $oldfilepath);
					}
					break;		
			}
			
			if ( !$error ) {
				print '<p id="fade" class="info">Object property has been updated</p>';
			} else {
				debugprint( '<p id="clickme" class="error">Could not store the object property because:<br />' . mysqli_error($dbc) . '. The query being run was: ' . $query . '</p>');
			}
			
		} else { // Failed to enter data.
			print '<p id="clickme" class="error">Please enter some data!</p>';
			$error=true;
		}
		editobject($dbc);
		//header( 'Location: index.php?command=editobject&id=' . $_POST['objectid'] );
	} 
}


/*******************************************************************/
/* function                                                        */
/*   getpropertydata                                               */
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
function getpropertydata($dbc, $propertytype, $objectpropertyid)
{
	switch ($propertytype)
	{
		case 0:
			// None
			print " ";
			break;
		case 1:
			// Text
		case 2:
			// Numeric
			$query = "SELECT object_property_data_text.data as data FROM object_property_data_text WHERE object_property_data_text.object_property_id = {$objectpropertyid}";
			if ($result = mysqli_query($dbc, $query)) { // Run the query.
				if ( mysqli_num_rows($result) > 0 ) {
					while ( $row = mysqli_fetch_assoc($result) ) {
						print htmlspecialchars($row['data']) . "\n";
					}
				} else {
					print "<p id=\"clickme\" class=\"error\">Property data not found</p>\n";
				}
			}
		break;
		case 3:
			// URL
			$query = "SELECT object_property_data_text.data as data FROM object_property_data_text WHERE object_property_data_text.object_property_id = {$objectpropertyid}";
			if ($result = mysqli_query($dbc, $query)) { // Run the query.
				if ( mysqli_num_rows($result) > 0 ) {
					while ( $row = mysqli_fetch_assoc($result) ) {
						print "<a href=\"" . htmlspecialchars($row['data']) . "\" target=\"_blank\">" . htmlspecialchars($row['data']) . "</a>\n";
					}
				} else {
					print "<p id=\"clickme\" class=\"error\">Property data not found</p>\n";
				}
			}
		break;
		case 11:
			// File in database
			print "<a href=\"rend/filedownload.php?command=show&id=$objectpropertyid\" target=\"_blank\">Download</a>\n";
			$query = "SELECT object_property_data_binary.filetype as filetype, object_property_data_binary.filename as filename, object_property_data_binary.filesize as filesize FROM object_property_data_binary WHERE object_property_data_binary.object_property_id = $objectpropertyid";
			if ($result = mysqli_query($dbc, $query)) { // Run the query.
				if ( mysqli_num_rows($result) > 0 ) {
					while ( $row = mysqli_fetch_assoc($result) ) {
  						print "[Filename: " . htmlspecialchars($row['filename']) . "]";
					}
				} else {
					print "<p id=\"clickme\" class=\"error\">Property data not found</p>\n";
				}
			}
		break;
		case 12:
			// Image in database
			$query = "SELECT object_property_data_binary.filetype as filetype, object_property_data_binary.filename as filename, object_property_data_binary.filesize as filesize FROM object_property_data_binary WHERE object_property_data_binary.object_property_id = $objectpropertyid";
			if ($result = mysqli_query($dbc, $query)) { // Run the query.
				if ( mysqli_num_rows($result) > 0 ) {
					$row = mysqli_fetch_assoc($result);
					print "<a href=\"rend/showpropimg.php?command=show&id=$objectpropertyid\" rel=\"lightbox[propertygalery]\" title=\"{$row['filename']}\"><img alt=\"Image\" src=\"rend/showpropimg.php?command=show&id=$objectpropertyid\" height=\"100\" /></a>\n";
  					print "[Filename: " . htmlspecialchars($row['filename']) . "]";
				} else {
					print "<p id=\"clickme\" class=\"error\">Property data not found</p>\n";
				}
			}
			break;
		case 13:
			// File on filesystem
			print "<a href=\"rend/filedownloadfs.php?command=show&id=$objectpropertyid\" target=\"_blank\">Download</a>\n";
			$query = "SELECT object_property_data_binary.data as filedata, object_property_data_binary.filetype as filetype, object_property_data_binary.filename as filename, object_property_data_binary.filesize as filesize FROM object_property_data_binary WHERE object_property_data_binary.object_property_id = $objectpropertyid";
			if ($result = mysqli_query($dbc, $query)) { // Run the query.
				if ( mysqli_num_rows($result) > 0 ) {
					$row = mysqli_fetch_assoc($result);
  					print "[Filename: " . htmlspecialchars($row['filename']) . "]";
				} else {
					print "<p id=\"clickme\" class=\"error\">Property data not found</p>\n";
				}
			}
		break;
		case 14:
			// Image on filesystem
			$query = "SELECT object_property_data_binary.data as filedata, object_property_data_binary.filetype as filetype, object_property_data_binary.filename as filename, object_property_data_binary.filesize as filesize FROM object_property_data_binary WHERE object_property_data_binary.object_property_id = $objectpropertyid";
			if ($result = mysqli_query($dbc, $query)) { // Run the query.
				if ( mysqli_num_rows($result) > 0 ) {
					$row = mysqli_fetch_assoc($result);	
					print "<a href=\"rend/showpropimgfs.php?command=show&id=$objectpropertyid\" rel=\"lightbox[propertygalery]\" title=\"{$row['filename']}\"><img alt=\"Image\" src=\"rend/showpropimgfs.php?command=show&id=$objectpropertyid\" height=\"100\" /></a>\n";
  					print "[Filename: " . $row['filename'] . "]";
				} else {
					print "<p id=\"clickme\" class=\"error\">Property data not found</p>\n";
				}
			}
		break;

		default:
			print "-\n";
	}
		
}


/*******************************************************************/
/* function                                                        */
/*   geteditpropertydata                                           */
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
function geteditpropertydata($dbc, $propertytype, $objectpropertyid)
{
	// If objectpropertyid = 0, show empty field
	switch ($propertytype)
	{
		case 0:
			// None
			print " ";
			break;
		case 1:
			// Text
		case 2:
			// Number
		case 3:
			// URL
			$data = "";
			if ( $objectpropertyid <> 0 ){
				$query = "SELECT object_property_data_text.data as data FROM object_property_data_text WHERE object_property_data_text.object_property_id = {$objectpropertyid}";
				if ($result = mysqli_query($dbc, $query)) { // Run the query.
					if ( mysqli_num_rows($result) > 0 ) {
						$row = mysqli_fetch_assoc($result);
						$data = $row['data'];
					} else {
						print "<p id=\"clickme\" class=\"error\">Property data not found</p>\n";
					}
				}
			}
			print "<input class=\"field\" type=\"text\" value=\"";
			print $data;
			print "\" name=\"propertydata\">\n";
		break;
		case 11:
			// File in database
			$data = "";
			if ( $objectpropertyid <> 0 ){
				$query = "SELECT object_property_data_binary.filetype as filetype, object_property_data_binary.filename as filename, object_property_data_binary.filesize as filesize FROM object_property_data_binary WHERE object_property_data_binary.object_property_id = $objectpropertyid";
				if ($result = mysqli_query($dbc, $query)) { // Run the query.
					if ( mysqli_num_rows($result) > 0 ) {
						$row = mysqli_fetch_assoc($result);
  						print "[Filename: " . htmlspecialchars($row['filename']) . "] ";
						print "[Filetype: " . htmlspecialchars($row['filetype']) . "] ";
						print "[Filesize: " . htmlspecialchars($row['filesize']) . "]\n";
					} else {
						print "<p id=\"clickme\" class=\"error\">Property data not found</p>\n";
					}
				}
			}
			print "<input class=\"field\" type=\"hidden\" value=\"null\" name=\"propertydata\">\n";
			print "<input class=\"field\" type=\"file\" name=\"uploadfile\">\n";
		break;
		case 12:
			// Image in database
			$data = "";
			if ( $objectpropertyid <> 0 ){
				print "<a href=\"rend/showpropimg.php?command=show&id=$objectpropertyid\" target=\"_blank\"><img alt=\"Image\" src=\"rend/showpropimg.php?command=show&id=$objectpropertyid\" height=\"100\"/></a>\n";
				$query = "SELECT object_property_data_binary.filetype as filetype, object_property_data_binary.filename as filename, object_property_data_binary.filesize as filesize FROM object_property_data_binary WHERE object_property_data_binary.object_property_id = $objectpropertyid";
				if ($result = mysqli_query($dbc, $query)) { // Run the query.
					if ( mysqli_num_rows($result) > 0 ) {
						$row = mysqli_fetch_assoc($result);
  						print "[Filename: " . htmlspecialchars($row['filename']) . "] ";
						print "[Filetype: " . htmlspecialchars($row['filetype']) . "] ";
						print "[Filesize: " . htmlspecialchars($row['filesize']) . "]\n";
					} else {
						print "<p id=\"clickme\" class=\"error\">Property data not found</p>\n";
					}
				}
			}
			print "<input class=\"field\" type=\"hidden\" value=\"null\" name=\"propertydata\">\n";
			print "<input class=\"field\" type=\"file\" name=\"uploadfile\">\n";
			break;
		case 13:
			// File on filesystem
			$data = "";
			if ( $objectpropertyid <> 0 ){
				$query = "SELECT object_property_data_binary.data as filedata, object_property_data_binary.filetype as filetype, object_property_data_binary.filename as filename, object_property_data_binary.filesize as filesize FROM object_property_data_binary WHERE object_property_data_binary.object_property_id = $objectpropertyid";
				if ($result = mysqli_query($dbc, $query)) { // Run the query.
					if ( mysqli_num_rows($result) > 0 ) {
						$row = mysqli_fetch_assoc($result);
  						print "[Filename: " . htmlspecialchars($row['filename']) . "] ";
						print "[Filetype: " . htmlspecialchars($row['filetype']) . "] ";
						print "[Filesize: " . htmlspecialchars($row['filesize']) . "]\n";
					} else {
						print "<p id=\"clickme\" class=\"error\">Property data not found</p>\n";
					}
				}
			}
			print "<input class=\"field\" type=\"hidden\" value=\"null\" name=\"propertydata\">\n";
			print "<input type=\"file\" name=\"uploadfile\">\n";
			break;
		case 14:
			// Image on filesystem
			$data = "";
			if ( $objectpropertyid <> 0 ){
				$query = "SELECT object_property_data_binary.data as filedata, object_property_data_binary.filetype as filetype, object_property_data_binary.filename as filename, object_property_data_binary.filesize as filesize FROM object_property_data_binary WHERE object_property_data_binary.object_property_id = $objectpropertyid";
				if ($result = mysqli_query($dbc, $query)) { // Run the query.
					if ( mysqli_num_rows($result) > 0 ) {
						$row = mysqli_fetch_assoc($result);
						print "<a href=\"{$row['filedata']}\" target=\"_blank\"><img alt=\"Image\" src=\"" . htmlspecialchars($row['filedata']) . "\" height=\"100\"/></a>\n";
  						print "[Filename: " . htmlspecialchars($row['filename']) . "] ";
						print "[Filetype: " . htmlspecialchars($row['filetype']) . "] ";
						print "[Filesize: " . htmlspecialchars($row['filesize']) . "]\n";
					} else {
						print "<p id=\"clickme\" class=\"error\">Property data not found</p>\n";
					}
				}
			}
			print "<input class=\"field\" type=\"hidden\" value=\"null\" name=\"propertydata\">\n";
			print "<input type=\"file\" name=\"uploadfile\">\n";
			break;

		default:
			print "-\n";
	}
		
}


/*******************************************************************/
/* function                                                        */
/*   deleteobjectproperty                                          */
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
function deleteobjectproperty($dbc)
{
	$error = false;
	if ( ($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_GET['objectpropertyid']) && isset($_POST['objectpropertyid']) && isset($_POST['objectid'])) 
	{ // Handle the form
		if ( !empty($_POST['objectpropertyid']) && !empty($_POST['propertytype']) ) {

			$propertytype = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['propertytype'])));
			
			// Check if shared propery and if used
			$query = "SELECT shared FROM object_property WHERE id={$_GET['objectpropertyid']} AND shared = 1";
			$result = mysqli_query($dbc, $query);
			$rows = mysqli_num_rows($result);
			if ($rows > 1)
			{
				// This is a shared property associated with more then one object
				$query = "DELETE FROM object_property WHERE id={$_GET['objectpropertyid']} AND object_id={$_GET['objectid']}";
				$result = mysqli_query($dbc, $query);
				if ( !mysqli_affected_rows($dbc) == 1){
					$error = true;
				}

			} else
			{
				// This is not a shared property or is only associated to the current object
				// delete the data from object_property_data_text or binary table					
				switch ( $propertytype )
				{
					case 1:
					case 2:
					case 3:
						$query = "DELETE FROM object_property_data_text WHERE object_property_id={$_GET['objectpropertyid']}";
						$result = mysqli_query($dbc, $query);
						if ( !mysqli_affected_rows($dbc) == 1){
							$error = true;
						}
						break;
					case 11:
					case 12:
						$query = "DELETE FROM object_property_data_binary WHERE object_property_id={$_GET['objectpropertyid']}";
						$result = mysqli_query($dbc, $query);
						if ( !mysqli_affected_rows($dbc) == 1){
							$error = true;
						}
						break;
					case 13:
					case 14:
						// File is not deleted from filesystem.
						$query = "DELETE FROM object_property_data_binary WHERE object_property_id={$_GET['objectpropertyid']}";
						$result = mysqli_query($dbc, $query);
						if ( !mysqli_affected_rows($dbc) == 1){
							$error = true;
						}
						break;
				}
				$query = "DELETE FROM object_property WHERE id={$_GET['objectpropertyid']}";
				$result = mysqli_query($dbc, $query);
				if ( !mysqli_affected_rows($dbc) == 1){
					$error = true;
				}
			}
			if ( !$error ) {
				print '<p id="fade" class="info">Object property has been deleted</p>';
			} else {
				debugprint( '<p id="clickme" class="error">Could not delete the object property because:</ br>' . mysqli_error($dbc) . '.The query being run was: ' . $query . '</p>');
			}
			
		} else { // Failed to enter data.
			print '<p class="error">Something went wrong!</p>';
			$error=true;
		}
		editobject($dbc);
		//header( 'Location: index.php?command=editobject&id=' . $_GET['objectid'] );
	} 
}

?>