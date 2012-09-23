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


/*******************************************************************/
/* function                                                        */
/*   listusers                                                      */
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
function listusers($dbc)
{
	//Sort order
	if ( isset($_GET['sort']) ) { 
		switch ( $_GET['sort'] ) {
			case "userid":
			case "userlogin":
			case "username":
			case "usergroupname":
			case "usertimestamp":
				$sort = $_GET['sort'];
				break;
			default:
				$sort = "userid";
		}
	} else {
		$sort = "userid";
	}
	//Search order
	if ( isset($_POST['search']) ) {
		$search	= "AND ( name LIKE \"%" . $_POST['search']. "%\" OR description LIKE \"%" . $_POST['search'] . "%\" ) ";
	} else {
		$search = "";
	}

		// Define the query...
	$query = "SELECT user.id as userid, user.login as userlogin, user.name as username, user.timestamp as usertimestamp, usergroup.name as usergroupname FROM user " .
				"JOIN usergroup ON (usergroup.id = user.group_id) WHERE user.deleted = 0 $search ORDER BY $sort";

	// Run the query:
	if ($result = mysqli_query($dbc, $query)) {
	
		print "<table class=\"users\">\n";
		print '<col width="40px" /><col width="150px" /><col /><col width="120" /><col width="120px" /><col width="43px" />';
		print "\n<tr>\n";
		print "<th><a href=\"index.php?command=listusers&sort=userid\">Id</a></th>\n";
		print "<th><a href=\"index.php?command=listusers&sort=userlogin\">Login</a></th>\n";
		print "<th><a href=\"index.php?command=listusers&sort=username\">Full Name</a></th>\n";	
		print "<th><a href=\"index.php?command=listusers&sort=usergroupname\">User Group</a></th>\n";	
		print "<th><a href=\"index.php?command=listusers&sort=usertimestamp\">Create Date</a></th>\n";
		print "<th></th>\n";
		print "</tr>\n";

		// Retrieve the returned records:
		while ($row = mysqli_fetch_assoc($result)) {
			$rows = mysqli_num_rows($result);
			// Print the record:
			print "<tr onClick=\"document.location.href='index.php?command=edituser&userid={$row['userid']}';\" style=\"cursor:pointer;\">\n";
			print "<td>" . htmlspecialchars($row['userid']) . "</td>\n";
			print "<td>" . htmlspecialchars($row['userlogin']) . "</td>\n";
			print "<td>" . htmlspecialchars($row['username']) . "</td>\n";
			print "<td>" . htmlspecialchars($row['usergroupname']) . "</td>\n";
			print "<td>" . dateformat(htmlspecialchars($row['usertimestamp'])) . "\n";
			print "</td>\n";			
			print "<td>\n";
			print "<form action=\"index.php?command=edituser&userid=" . htmlspecialchars($row['userid']) . "\" method=\"post\">\n";
			print "<input class=\"edit\" type=\"submit\" name=\"submit\" value=\"Edit\" />\n";
			print "</form>\n";
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
/*   adduser                                                       */
/*******************************************************************/
//
// Description:
//   Add user into database, ask password and group
// Inputs: 
//   function:
//	   ():
//       $dbc: database conection
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
function adduser($dbc)
{
	$error = false;
	if ($_SERVER['REQUEST_METHOD'] == 'POST') 
	{ // Handle the form
		if ( !empty($_POST['login']) && !empty($_POST['name']) && !empty($_POST['group_id']) && !empty($_POST['password']) ) {

						// Prepare the values for storing:
			$login = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['login'])));
			$name = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['name'])));
			$groupid = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['group_id'])));
			$password = md5(mysqli_real_escape_string($dbc, trim(strip_tags($_POST['password']))));
			$query = "INSERT INTO user (login, name, group_id, password) VALUES ('$login', '$name', '$groupid', '$password')";
			$result = mysqli_query($dbc, $query);
			if (mysqli_affected_rows($dbc) == 1){
				// Print a message:
				$usedid = mysqli_insert_id($dbc);
				print '<p id="fade" class="info">User has been saved with id=' . $usedid . '.</p>';
			} else {
				debugprint( '<p id="clickme" class="error">Could not store the user because:<br />' . mysqli_error($dbc) . '. The query being run was: ' . $query . '</p>');
			}
			
		} else { // Failed to enter data.
			print '<p id="clickme" class="error">Please enter a login and name and select a group!</p>';
			$error=true;
		}
		listusers($dbc);
	} elseif (!$error)
	{	// Show the form
		print "<form action=\"index.php?command=adduser\" method=\"post\">\n";
		print "<table class=\"users\">\n";
		print '<col width="150px" /><col />';
		print "<th colspan=\"2\">User\n";
		print "<input class=\"save\" type=\"submit\" name=\"submit\" value=\"Save\" />\n";
		print "</th>\n";
		print "<tr><td>Login</td><td><input class=\"field\" type=\"text\" name=\"login\" /></td></tr>";
		print "<tr><td>Full Name</td><td><textarea class=\"field\" name=\"name\" rows=\"2\" cols=\"70\"></textarea></td></tr>";
		print "<tr><td>Password</td><td><input class=\"field\" type=\"password\" name=\"password\" /></td></tr>";
				// Define query for group
		$query = "SELECT  id, name FROM usergroup ORDER BY name ASC";
		if ($result = mysqli_query($dbc, $query)) { // Run the query.
			print "<tr><td>User Group</td><td><select class=\"field\" class=\"field\" name=\"group_id\">\n";
			while ($row = mysqli_fetch_assoc($result)) {
			print '<option';
			print ' value ="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['name']) . '</option>' ."\n";
			} 
		} else { // Couldn't get the information.
			debugprint( '<p id="clickme" class="error">Could not retrieve the user because:<br />' . mysqli_error($dbc) . '. The query being run was: ' . $query . '</p>');
		}
		print "</select>\n";
		print "</td></tr>\n";
		print "</table>\n";
		print "</form>\n";
	}
}

/*******************************************************************/
/* function                                                        */
/*   edituser                                                      */
/*******************************************************************/
//
// Description:
//   Edit user name, login, user group and password
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
function edituser($dbc)
{
	$error = false;
	if (isset($_POST['userid']) && is_numeric($_POST['userid']) && ($_POST['userid'] > 0)) { // Handle the form.

		// Validate and secure the form data:
		if ( !empty($_POST['login']) && !empty($_POST['name']) && !empty($_POST['groupid']) ) {

						// Prepare the values for storing:
			$login = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['login'])));
			$name = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['name'])));
			$groupid = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['groupid'])));
			
			// Define the query.
			if(!empty($_POST['password'])) {
				$password = md5(mysqli_real_escape_string($dbc, trim(strip_tags($_POST['password']))));
				$query = "UPDATE user SET login='$login', name='$name', group_id='$groupid', password='$password'  WHERE id={$_POST['userid']}";
			} else {
				$query = "UPDATE user SET login='$login', name='$name', group_id='$groupid'  WHERE id={$_POST['userid']}";
			}
			if ($result = mysqli_query($dbc, $query)) {
				print '<p id="fade" class="info">The user has been updated.</p>';
			} else {
				debugprint( '<p class="error">Could not update the user because:<br />' . mysqli_error($dbc) . '. The query being run was: ' . $query . '</p>');
				$error = true;
			}	
		} // No problem!
	}

	if ( isset($_GET['userid']) && is_numeric($_GET['userid']) && ($_GET['userid'] > 0) ) { // Display the entry in a form:

		$query = "SELECT user.id as userid, user.login as userlogin, user.name as username, user.timestamp as usertimestamp, usergroup.name as usergroupname, usergroup.id as usergroupid FROM user " .
				"JOIN usergroup ON (usergroup.id = user.group_id) WHERE user.id = " . $_GET['userid'];
		if ($result = mysqli_query($dbc, $query)) { // Run the query.
		
			$row = mysqli_fetch_assoc($result); // Retrieve the information.
			$userid = htmlspecialchars($row['userid']);
			$usergroupid = htmlspecialchars($row['usergroupid']);
			// Make the form:
			print "<form action=\"index.php?command=edituser&userid=" . $_GET['userid'] . "\" method=\"post\">";
			print "<table class=\"users\">";
			print "<col width=\"150px\" /><col />\n";
			print "<th colspan=\"2\">";
			print "User";
			print "<input class=\"save\" type=\"submit\" name=\"submit\" value=\"Save User\"/>\n";
			print "</th>\n";
			print "<tr>\n";
			print "<td>Login :</td>\n";
			print "<td><input class=\"field\" type=\"text\" name=\"login\" value=\"" . htmlspecialchars($row['userlogin']) . "\" /></td>";
			print "</tr>\n";
			print "<tr>\n";
			print "<td>Full Name :</td>\n";
			print "<td><input class=\"field\" type=\"text\" name=\"name\" value=\"" . htmlspecialchars($row['username']) . "\" /></td>\n";
			print "</tr>\n";
			print "<tr>\n";
			print "<td>Password :</td>\n";
			print "<td><input class=\"field\" type=\"password\" name=\"password\" value=\"\" /></td>\n";
			print "</tr>\n";
			// Define query for group
			$query = "SELECT  id, name FROM usergroup ORDER BY name ASC";
			if ($result = mysqli_query($dbc, $query)) { // Run the query.
				print "<tr>\n";
				print "<td>User Group :</td>\n";
				print "<td><select width=\"100px\" class=\"field\" name=\"groupid\">\n";
				while ($row = mysqli_fetch_assoc($result)) {
				print '<option';
				if ( htmlspecialchars($row['id']) == $usergroupid ) {
					print ' selected="selected"';
			}
				print ' value ="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['name']) . '</option>' ."\n";
				} 
			} else { // Couldn't get the information.
				debugprint( '<p id="clickme" class="error">Could not retrieve the user group because:<br />' . mysqli_error($dbc) . '. The query being run was: ' . $query . '</p>');
			}
			print "</select>\n";
			print "</td>\n";
			print "</tr>\n";

			print "</table>\n";
			print '<input type="hidden" name="userid" value="' . $_GET['userid'] . '" />';
			print '</form>';
	
		
		} else { // Couldn't get the information.
			debugprint( '<p id="clickme" class="error">Could not retrieve the user because:<br />' . mysqli_error($dbc) . '. The query being run was: ' . $query . '</p>');
		}
	} else { // No ID set.
		print '<p id="clickme" class="error">This page has been accessed in error.</p>';
	} // End of main IF.
}


/*******************************************************************/
/* function                                                        */
/*   listgroups                                                    */
/*******************************************************************/
//
// Description:
//   List all user groups from database.
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
function listgroups($dbc)
{
	//Sort order
	if ( isset($_GET['sort']) ) { 
		switch ( $_GET['sort'] ) {
			case "groupid":
			case "groupname":
			case "groupdescription":
			case "grouptimestamp":
				$sort = $_GET['sort'];
				break;
			default:
				$sort = "groupid";
		}
	} else {
		$sort = "groupid";
	}
	//Search order
	if ( isset($_POST['search']) ) {
		$search	= "AND ( name LIKE \"%" . $_POST['search']. "%\" OR description LIKE \"%" . $_POST['search'] . "%\" ) ";
	} else {
		$search = "";
	}

		// Define the query...
	$query = "SELECT usergroup.id as groupid, usergroup.name as groupname,usergroup.description as groupdescription, usergroup.timestamp as grouptimestamp FROM usergroup WHERE usergroup.deleted = 0 $search ORDER BY $sort";

	// Run the query:
	if ($result = mysqli_query($dbc, $query)) {
	
		print "<table class=\"users\">\n";
		print '<col width="40px" /><col width="150px" /><col /><col width="120px" /><col width="43px" />';
		print "\n<tr>\n";
		print "<th><a href=\"index.php?command=listgroups&sort=groupid\">Id</a></th>\n";
		print "<th><a href=\"index.php?command=listgroups&sort=groupname\">Group Name</a></th>\n";	
		print "<th><a href=\"index.php?command=listgroups&sort=groupdescription\">Group Description</a></th>\n";	
		print "<th><a href=\"index.php?command=listgroups&sort=usertimestamp\">Create Date</a></th>\n";
		print "<th></th>\n";
		print "</tr>\n";

		// Retrieve the returned records:
		$rows = 0;
		while ($row = mysqli_fetch_assoc($result)) {
			$rows = mysqli_num_rows($result);
			// Print the record:
			print "<tr onClick=\"document.location.href='index.php?command=editgroup&groupid={$row['groupid']}';\" style=\"cursor:pointer;\">\n";
			print "<td>" . htmlspecialchars($row['groupid']) . "</td>\n";
			print "<td>" . htmlspecialchars($row['groupname']) . "</td>\n";
			print "<td>" . htmlspecialchars($row['groupdescription']) . "</td>\n";
			print "<td>" . dateformat(htmlspecialchars($row['grouptimestamp'])) . "\n";
			print "</td>\n";			
			print "<td>\n";
			print "<form action=\"index.php?command=editgroup&groupid=" . htmlspecialchars($row['groupid']) . "\" method=\"post\">\n";
			print "<input class=\"edit\" type=\"submit\" name=\"submit\" value=\"Edit\" />\n";
			print "</form>\n";
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
/*   addgroup                                                      */
/*******************************************************************/
//
// Description:
//   Add group into database with selected permissions
// Inputs: 
//   function:
//	   ():
//       $dbc: database conection
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
function addgroup($dbc)
{
	$error = false;
	if ($_SERVER['REQUEST_METHOD'] == 'POST') 
	{ // Handle the form
		if ( !empty($_POST['name']) && !empty($_POST['description']) ) {

			// Prepare the values for storing:
			$name = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['name'])));
			$description = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['description'])));
			$query = "INSERT INTO usergroup (name, description) VALUES ('$name', '$description')";
			$result = mysqli_query($dbc, $query);
			if (mysqli_affected_rows($dbc) == 1){
				// Print a message:
				$usedid = mysqli_insert_id($dbc);

			} else {
				$error = true;
			}
			
		} else { // Failed to enter data.
			print '<p id="clickme" class="error">Please enter a name and description!</p>';
			$error=true;
		}
		// Insert the permission into the permissions database
		if(!$error) {
			foreach($_POST as $key => $value) {
				if (is_numeric($key) and is_numeric($value)) {
					$permid = mysqli_real_escape_string($dbc, $key);
					$permset = mysqli_real_escape_string($dbc, $value);
					$query = "INSERT INTO usergroup_permissions(group_id, permission_id, value) VALUES ('$usedid', '$permid', '$permset')";
					$result = mysqli_query($dbc, $query);
					if (!mysqli_affected_rows($dbc) == 1){
						$error = true;
					}
				}
			}
		}
		if($error) {
			// Problem
		} else {
			// OK
			listgroups($dbc);
		}
	} elseif (!$error)
	{	// Show the form
		print "<form action=\"index.php?command=addgroup\" method=\"post\">\n";
		print "<table class=\"users\">\n";
		print "<col width=\"150px\" /><col />\n";
		print "<th colspan=\"2\">Group\n";
		print "<input class=\"save\" type=\"submit\" name=\"submit\" value=\"Save\" />\n";
		print "</th>\n";
		print "<tr><td>Group Name</td><td><input class=\"field\" type=\"text\" name=\"name\" /></td></tr>";
		print "<tr><td>Group Description</td><td><input class=\"field\" type=\"text\" size=\"50\" name=\"description\"></td></tr>";
		print "</table>\n";
				// Define query for permissions
		$query = "SELECT  id, name, description FROM permissions ORDER BY displayorder ASC";
		if ($result = mysqli_query($dbc, $query)) { // Run the query.
			print "<table class=\"object\">\n";
			print "<col /><col width=\"150px\" /><col width=\"150px\" />\n";
			print "<th colspan=\"3\">Group permissions:</th>\n";
			while ($row = mysqli_fetch_assoc($result)) {
				print "<tr><td>" . $row['description'] . "</td>\n";
				print "<td><input type=\"radio\" name=\"" . $row['id'] . "\" value=\"1\" />Allow</td>";
				print "<td><input type=\"radio\" name=\"" . $row['id'] . "\" value=\"0\" checked=\"checked\" />Deny</td>";
				print "</tr>\n";
			} 
			print "</table>\n";
		} else { // Couldn't get the information.
			print '<p id="clickme" class="error">Could not retrieve the user because:<br />' . mysqli_error($dbc) . '. The query being run was: ' . $query . '</p>';
		}
		print "</form>\n";
	}
}

/*******************************************************************/
/* function                                                        */
/*   editgroup                                                     */
/*******************************************************************/
//
// Description:
//   Edit group permissions
// Inputs: 
//   function:
//	   ():
//       $dbc: database conection
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
function editgroup($dbc)
{
	$error = false;
	if (isset($_POST['groupid']) && is_numeric($_POST['groupid']) && ($_POST['groupid'] > 0)) 
	{ // Handle the form
		if ( !empty($_POST['name']) && !empty($_POST['description']) &&  !empty($_POST['groupid'])) {

			// Prepare the values for updating:
			$name = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['name'])));
			$description = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['description'])));

			$query = "UPDATE usergroup SET name='$name', description='$description' WHERE id={$_POST['groupid']}";
			if (!$result = mysqli_query($dbc, $query)){
				$error = true;
			}
		} else { // Failed to enter data.
			print '<p id="clickme" class="error">Please enter a name and description!</p>';
			$error=true;
		}
		// Insert the permission into the permissions database
		if(!$error) {
			foreach($_POST as $key => $value) {
				if (is_numeric($key) and is_numeric($value)) {
					$permid = mysqli_real_escape_string($dbc, $key);
					$permset = mysqli_real_escape_string($dbc, $value);
					$query = "UPDATE usergroup_permissions SET value=$permset WHERE group_id = {$_POST['groupid']} AND permission_id = $permid";
					if (!$result = mysqli_query($dbc, $query)){
						$error = true;
					}
				}
			}
		}
		if($error) {
			// Problem
			debugprint( '<p id="clickme" class="error">Could not retrieve the permissions because:<br />' . mysqli_error($dbc) . '. The query being run was: ' . $query . '</p>');
		} else {
			// OK
			listgroups($dbc);
		}
	} elseif (!$error)
	{	
		if ( isset($_GET['groupid']) && is_numeric($_GET['groupid']) && ($_GET['groupid'] > 0) ) { // Display the entry in a form:
		$query = "SELECT name, description FROM usergroup WHERE id = {$_GET['groupid']}";
		if ($result = mysqli_query($dbc, $query)) {
			$row = mysqli_fetch_assoc($result);
			print "<form action=\"index.php?command=editgroup&groupid=" . $_GET['groupid'] . "\" method=\"post\">\n";
			print "<table class=\"users\">\n";
			print "<col width=\"150px\" /><col />\n";
			print "<th colspan=\"2\">Group\n";
			print "<input class=\"save\" type=\"submit\" name=\"submit\" value=\"Save\" />\n";
			print "</th>\n";
			print "<tr><td>Group Name</td><td><input class=\"field\" type=\"text\" name=\"name\" value=\"" . $row['name'] . "\" /></td></tr>";
			print "<tr><td>Group Description</td><td><input class=\"field\" type=\"text\" size=\"50\" name=\"description\" value=\"" . $row['description'] . "\"></td></tr>";
			print "<input type=\"hidden\" name=\"groupid\" value=\"" . $_GET['groupid'] . "\" /></p>";
			print "</table>\n";
				// Define query for permissions
			$query = "SELECT  permissions.id as permissionsid, permissions.name as permissionsname, permissions.description as permissionsdescription, " .
						"usergroup_permissions.value as usergrouppermissionsvalue FROM permissions " .
						"JOIN usergroup_permissions ON usergroup_permissions.permission_id = permissions.id " .
						"WHERE usergroup_permissions.group_id = {$_GET['groupid']} ORDER BY permissions.displayorder ASC";
			if ($result = mysqli_query($dbc, $query)) { // Run the query.
				print "<table class=\"users\">\n";
				print "<col /><col width=\"150px\" /><col width=\"150px\" />\n";
				print "<th colspan=\"3\">Group permissions:</th>\n";
				while ($row = mysqli_fetch_assoc($result)) {
					print "<tr><td>" . $row['permissionsdescription'] . "</td>\n";
					if ($row['usergrouppermissionsvalue'] == 1){
						$allowchecked = "checked=\"checked\"";
						$denychecked = "";
					} else {
						$allowchecked = "";
						$denychecked = "checked=\"checked\"";
					};
					print "<td><input type=\"radio\" name=\"" . $row['permissionsid'] . "\" value=\"1\" $allowchecked />Allow";
					if ($row['usergrouppermissionsvalue'] == 1){
						print " *";
					}
					print "</td>";
					print "<td><input type=\"radio\" name=\"" . $row['permissionsid'] . "\" value=\"0\" $denychecked />Deny";
					if ($row['usergrouppermissionsvalue'] == 0){
						print " *";
					}

					print "</td>";
					print "</tr>\n";
				} 
				print "</table>\n";
				print "<div class=\"result\">* current setting</div>\n";
			} else { // Couldn't get the information.
				debugprint( '<p id="clickme" class="error">Could not retrieve the permissions because:<br />' . mysqli_error($dbc) . '. The query being run was: ' . $query . '</p>');
			}
			print "</form>\n";
			}
		}
	}
}


?>