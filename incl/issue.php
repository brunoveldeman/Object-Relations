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
/*   listissues                                                    */
/*******************************************************************/
//
// Description:
//   Output a table with issues, optional sort order
// Inputs: 
//   function:
//	   ():
//       $dbc: database connection
//   POST:
//	   ?
//   GET:
//     ?
//
// Output:
//   return:
//     none
//   HTML:
//     generated html based on database tables
//   MYSQL:
//     ?
//   GET:
//     ?
//   POST:
//     ?
//
// Security checks:
//   $_POST['search'] is checked for invalid characters, only alfanum and space allowed
//
// Security risk:
//   $_GET and $POST should not be direclty used in code but passed to a variable and checked for valid data
/********************************************************************/
function listissues($dbc)
{
	//Sort column
	if ( isset($_GET['sort']) ) { 
		switch ( $_GET['sort'] ) {
			case "issueid":
			case "issuereference":
			case "issuesubject":
			case "username":
			case "issuetypename":
			case "issuestatusname":
			case "issuetimestamp":
				$sort = $_GET['sort'];
				break;
			default:
				$sort = "issuesubject";
		}
	} else {
		$sort = "issuesubject";
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
	$query = "SELECT issue.id as issueid, issue.subject as issuesubject, issue.timestamp as issuetimestamp, issuetype.name as issuetypename, " .
				 "issuestatus.name as issuestatusname, user.name as username, issue.reference as issuereference " .
				 "FROM issue JOIN issuetype ON (issuetype.id = issue.issuetype_id) " .
				 "JOIN issuestatus ON (issuestatus.id = issue.issuestatus_id) " .
				 "JOIN user ON (user.id = issue.user_id) " .
				 "WHERE issue.deleted = 0 $search ORDER BY $sort $order";

	// Run the query:
	if ($result = mysqli_query($dbc, $query)) {
		$rows = mysqli_num_rows($result);
		print "<table class=\"issues\">\n";
		print '<col width="40px" /><col width="150px" /><col /><col width="150px" /><col width="120" /><col width="80" /><col width="120px" /><col width="99px" />';
		print "\n<tr>\n";
		print "<th><a href=\"index.php?command=listissues&sort=issueid&order=" . getorder("issueid", $sort, $order) . getsearch($searchstr) . "\">Id" . getorderarrow("issueid", $sort, $order) . "</a></th>\n";
		print "<th><a href=\"index.php?command=listissues&sort=issuereference&order=" . getorder("issuereference", $sort, $order) . getsearch($searchstr) . "\">Reference" . getorderarrow("issuereference", $sort, $order) . "</a></th>\n";
		print "<th><a href=\"index.php?command=listissues&sort=issuesubject&order=" . getorder("issuesubject", $sort, $order) . getsearch($searchstr) . "\">Subject" . getorderarrow("issuesubject", $sort, $order) . "</a></th>\n";
		print "<th><a href=\"index.php?command=listissues&sort=username&order=" . getorder("username", $sort, $order) . getsearch($searchstr) . "\">Issue owner" . getorderarrow("username", $sort, $order) . "</a></th>\n";
		print "<th><a href=\"index.php?command=listissues&sort=issuetypename&order=" . getorder("issuetypename", $sort, $order). getsearch($searchstr)  . "\">Issue type" . getorderarrow("issuetypename", $sort, $order) . "</a></th>\n";	
		print "<th><a href=\"index.php?command=listissues&sort=issuestatusname&order=" . getorder("issuestatusname", $sort, $order) . getsearch($searchstr) . "\">Status" . getorderarrow("issuestatusname", $sort, $order) . "</a></th>\n";	
		print "<th><a href=\"index.php?command=listissues&sort=issuetimestamp&order=" . getorder("issuetimestamp", $sort, $order) . getsearch($searchstr) . "\">Create Date" . getorderarrow("issuetimestamp", $sort, $order) . "</a></th>\n";
		print "<th></th>\n";
		print "</tr>\n";

		// Retrieve the returned records:
		while ($row = mysqli_fetch_assoc($result)) {
		
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
/*   viewissue                                                     */
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
function viewissue($dbc)
{
	if (isset($_GET['issueid']) && is_numeric($_GET['issueid']) && ($_GET['issueid'] > 0) ) { // Display the entry in a form:

		$query = "SELECT  issue.id as issueid, issue.subject as issuesubject, issue.detail as issuedetail, " .
					"issue.timestamp as issuetimestamp, user.name AS username, issuestatus.name AS issuestatusname, " .
					"issuetype.name AS issuetypename, issue.reference AS issuereference " .
					"FROM issue JOIN user ON (user.id = issue.user_id) JOIN issuestatus ON (issuestatus.id = issue.issuestatus_id) " . 
					"JOIN issuetype ON (issuetype.id = issue.issuetype_id) " .
					"WHERE issue.id={$_GET['issueid']}";
		if ($result = mysqli_query($dbc, $query)) { // Run the query.
			$row = mysqli_fetch_assoc($result); // Retrieve the information.
			$issueid = htmlspecialchars($row['issueid']);
			// Show Details:
			print "<table class=\"issues\">\n";
			print '<col width="150px" /><col />';
			print "<th colspan=\"2\">Issue\n";
			if (getperm('addissuemsg'))
			{
				print "<form style=\"float:right;margin-right: 2px;\" action=\"index.php?command=addissuemsg&issueid=" . $issueid . "\" method=\"post\">\n";
				print "<input class=\"edit\" type=\"submit\" name=\"submit\" value=\"Update\" />\n";
				print "</form>\n";
			}
			print "</th>\n";
			print "<tr class=\"issueshead\" >\n";
			print "<td>Create date : </td>\n";
			print "<td>" . dateformat(htmlspecialchars($row['issuetimestamp'])) . "</td>\n";
			print "</tr>\n";
			print "<tr>\n";
			print "<td>Reference : </td>\n";
			print "<td>" . htmlspecialchars($row['issuereference']) . "</td>\n";
			print "</tr>\n";
			print "<tr>\n";
			print "<td>Subject : </td>\n";
			print "<td>" . htmlspecialchars($row['issuesubject']) . "</td>\n";
			print "</tr>\n";
			print "<tr>\n";
			print "<td>Issue Type : </td>\n";
			print "<td>" . htmlspecialchars($row['issuetypename']) . "</td>\n";
			print "</tr>\n";
			print "<tr>\n";
			print "<td>Status : </td>\n";
			print "<td>" . htmlspecialchars($row['issuestatusname']) . "</td>\n";
			print "</tr>\n";
			print "<tr>\n";
			print "<td>Detail : </td>\n";
			print "<td style=\"white-space: pre-line\">" . nl2br(htmlspecialchars($row['issuedetail'])) . "</td>\n";
			print "</tr>\n";
			print "<tr>\n";
			print "<td>Issue owner : </td>\n";
			print "<td>" . nl2br(htmlspecialchars($row['username'])) . "</td>\n";
			print "</tr>\n";
			print "</table>\n";
			// List related objects with link
			print "<table class=\"object\">\n";
			print "<th>\n";
			print "Related objects\n";
			// Listbox related objects
			if( getperm('addissue') OR getperm('editissue') )
			{
				print "<form style=\"display:inline\" action=\"index.php?command=addobjectissue&issueid=" . $_GET['issueid'] . "\" method=\"post\">\n";
				print '<input type="hidden" name="issueid" value="' . $_GET['issueid'] . '" />' . "\n";
				print '<input type="hidden" name="from" value="view" />' . "\n";
				// Define query for objects
				$query = "SELECT  id, name FROM object WHERE deleted = 0 ORDER BY name ASC";
				if ($result = mysqli_query($dbc, $query)) { // Run the query.
					print '<select class="edit" name="objectid">' . "\n";
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
			}
			print "</th>\n";
			print "<tr>\n";
			print "<td>\n";
			// List of related objects
			$query = "SELECT object.name AS objectname, object.id as objectid FROM object " .
						"JOIN object_issues on (object_issues.object_id = object.id) WHERE object_issues.issue_id={$_GET['issueid']}";
			if ($result = mysqli_query($dbc, $query)) { // Run the query.
				if(mysqli_num_rows($result) <> 0) {
					print "|";
				}
				while($row = mysqli_fetch_assoc($result)) {   // Retrieve the information.
					print " <a href=\"index.php?command=viewobject&objectid={$row['objectid']}\">" . htmlspecialchars($row['objectname']) . "</a>";
					if( getperm('addissue') OR getperm('editissue') )
					{
						print "<form style=\"display:inline;\" action=\"index.php?command=deleteobjectissue&issueid=" . $_GET['issueid'] ."\" method=\"post\">\n";
						print "<input type=\"hidden\" name=\"objectid\" value=\"" . $row['objectid'] . "\" />\n";
						print "<input type=\"hidden\" name=\"issueid\" value=\"" . $_GET['issueid'] . "\" />\n";
						print "<input type=\"hidden\" name=\"from\" value=\"view\" />\n";
						print "<input class=\"smallx\" type=\"submit\" name=\"submit\" value=\"X\" />\n";
						print "</form>\n";
					}
					print " | ";
				}
			}
			print "</td>\n";
			print "</tr>\n";
			print "</table>\n";
			// List issue update details
			print "<table class=\"issueupdates\">\n";
			print '<col width="150px" /><col />';
			print "<th colspan=\"2\">Issue updates\n";
			if (getperm('addissuemsg'))
			{
				print "<form style=\"float:right;margin-right: 2px;\" action=\"index.php?command=addissuemsg&issueid=" . $issueid . "\" method=\"post\">\n";
				print "<input class=\"edit\" type=\"submit\" name=\"submit\" value=\"Update\" />\n";
				print "</form>\n";
			}
			print "</th>\n";
			$query = "SELECT issue_msg.user_id as issuemsguserid, issue_msg.subject as issuemsgsubject, issue_msg.detail as issuemsgdetail, " .
						"issue_msg.timestamp as issuemsgtimestamp, user.name as username, issuestatus.name AS issuestatusname FROM issue_msg " .
						"JOIN user ON (user.id = issue_msg.user_id) JOIN issuestatus ON (issue_msg.status_id = issuestatus.id) " .
						"WHERE issue_msg.deleted = 0 AND issue_msg.issue_id = {$_GET['issueid']} ORDER BY issue_msg.timestamp";
			if ($result = mysqli_query($dbc, $query)) { // Run the query.
				while($row = mysqli_fetch_assoc($result) ) {   // Retrieve the information.
					print "<tr class=\"issuesupdateshead\" >\n";
					print "<td>Create date : </td>\n";
					print "<td>" . dateformat(htmlspecialchars($row['issuemsgtimestamp'])) . "</td>\n";
					print "</tr>\n";
					print "<tr>\n";
					print "<td>User Name : </td>\n";
					print "<td>" . htmlspecialchars($row['username']) . "</td>\n";
					print "</tr>\n";
					print "<tr>\n";
					print "<td>Action : </td>\n";
					print "<td>" . htmlspecialchars($row['issuemsgsubject']) . "</td>\n";
					print "</tr>\n";
					print "<tr>\n";
					print "<td>Status : </td>\n";
					print "<td>" . htmlspecialchars($row['issuestatusname']) . "</td>\n";
					print "</tr>\n";
					print "<tr>\n";
					print "<td>Detail : </td>\n";
					print "<td style=\"white-space: pre-line\">" . nl2br(htmlspecialchars($row['issuemsgdetail'])) . "</td>\n";
					print "</tr>\n";
				}		
			}
			print "</table>\n";
		} else { // Couldn't get the information.
			debufprint( '<p id="clickme" class="error">Could not retrieve the relation because:<br />' . mysqli_error($dbc) . '. The query being run was: ' . $query . '</p>');
		}
	
	} else { // No ID set.
		print '<p id="clickme" class="error">This page has been accessed in error.</p>';
	} // End of main IF.
}

/*******************************************************************/
/* function                                                        */
/*   editissue                                                     */
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
function editissue($dbc)
{
	$error = false;
	if (isset($_POST['issueid']) && is_numeric($_POST['issueid']) && ($_POST['issueid'] > 0)) { // Handle the form.

		// Validate and secure the form data:
		if ( !empty($_POST['issuereference']) && !empty($_POST['issuesubject']) && !empty($_POST['issuetypeid']) && !empty($_POST['issuestatusid']) && !empty($_POST['issuedetail']) && !empty($_POST['userid']) ) {
	
			// Prepare the values for storing:
			$issuereference = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['issuereference'])));
			$issuesubject = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['issuesubject'])));
			$issuetypeid = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['issuetypeid'])));
			$issuestatusid = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['issuestatusid'])));
			$issuedetail = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['issuedetail'])));
			$userid = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['userid'])));
			// Define the query.
			$query = "UPDATE issue SET reference='$issuereference', subject='$issuesubject', issuetype_id='$issuetypeid', issuestatus_id='$issuestatusid', detail='$issuedetail', user_id='$userid'  WHERE id={$_POST['issueid']}";
			if ($result = mysqli_query($dbc, $query)) {
				print '<p id="fade" class="info">The issue has been updated.</p>';
			} else {
				debugprint( '<p id="clickme" class="error">Could not update the issue because:<br />' . mysqli_error($dbc) . '. The query being run was: ' . $query . '</p>');
				$error = true;
			}	
		} // No problem!
	}
	
	if (isset($_GET['issueid']) && is_numeric($_GET['issueid']) && ($_GET['issueid'] > 0) ) { // Display the entry in a form:

		$query = "SELECT  issue.id as issueid, issue.subject as issuesubject, issue.detail as issuedetail, " .
					"issue.timestamp as issuetimestamp, issue.user_id AS userid,  issue.issuestatus_id AS issuestatusid, " .
					"issue.issuetype_id AS issuetypeid, issue.reference as issuereference " .
					"FROM issue WHERE issue.id={$_GET['issueid']}";
		if ($result = mysqli_query($dbc, $query)) { // Run the query.
			$row = mysqli_fetch_assoc($result); // Retrieve the information.
			$issuetimestamp = $row['issuetimestamp'];
			$issuesubject = $row['issuesubject'];
			$issuereference = $row['issuereference'];
			$issuestatusid = $row['issuestatusid'];
			$issuetypeid = $row['issuetypeid'];
			$userid = $row['userid'];
			$issuedetail = $row['issuedetail'];
			// Make the form:
			print "<form action=\"index.php?command=editissue&issueid=" . $_GET['issueid'] . "\" method=\"POST\">";
			print "<table class=\"issues\" >\n";
			print '<col width="150px" /><col />';
			print "<th colspan=\"2\">Issue\n";
			print "<input class=\"save\" type=\"submit\" name=\"submit\" value=\"Save Issue\" />\n";
			print "<input type=\"hidden\" name=\"issueid\" value=\"" . $_GET['issueid'] . "\" />\n";
			print "</th>\n";
			print "<tr>\n";
			print "<td>Create date : </td>\n";
			print "<td>" . dateformat(htmlspecialchars($issuetimestamp)) . "</td>\n";
			print "</tr>\n";
			print "<tr>\n";
			print "<td>Reference : </td>\n";
			print "<td><input class=\"field\" type=\"text\" name=\"issuereference\" value=\"" . htmlspecialchars($issuereference) . "\" /></td>\n";
			print "</tr>\n";
			print "<tr>\n";
			print "<td>Subject : </td>\n";
			print "<td><input class=\"field\" type=\"text\" name=\"issuesubject\" value=\"" . htmlspecialchars($issuesubject) . "\" /></td>\n";
			print "</tr>\n";
			$query = "SELECT id, name FROM issuetype WHERE deleted = 0 ORDER BY name";
			if ($result = mysqli_query($dbc, $query)) { // Run the query.
			print "<tr>\n";
			print "<td>Issue type :</td>\n";
			print "<td><select width=\"100px\" class=\"field\" name=\"issuetypeid\">\n";
				while ($row = mysqli_fetch_assoc($result)) {
				print '<option';
				if ( $row['id'] == $issuetypeid ) {
					print ' selected="selected"';
			}
				print ' value ="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['name']) . '</option>' ."\n";
				} 
			} else { // Couldn't get the information.
				debugprint( '<p id="clickme" class="error">Could not retrieve the object because:<br />' . mysqli_error($dbc) . '. The query being run was: ' . $query . '</p>');
			}
			print "</select>\n";
			print "</tr>\n";
			$query = "SELECT id, name FROM issuestatus WHERE deleted = 0 ORDER BY name";
			if ($result = mysqli_query($dbc, $query)) { // Run the query.
			print "<tr>\n";
			print "<td>Status :</td>\n";
			print "<td><select width=\"100px\" class=\"field\" name=\"issuestatusid\">\n";
				while ($row = mysqli_fetch_assoc($result)) {
				print '<option';
				if ( $row['id'] == $issuestatusid ) {
					print ' selected="selected"';
			}
				print ' value ="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['name']) . '</option>' ."\n";
				} 
			} else { // Couldn't get the information.
				debugprint( '<p id="clickme" class="error">Could not retrieve the object because:<br />' . mysqli_error($dbc) . '. The query being run was: ' . $query . '</p>');
			}
			print "</select>\n";
			print "</tr>\n";
			print "<tr>\n";
			print "<td>Detail : </td>\n";
			
			print "<td><textarea class=\"field\" name=\"issuedetail\">" . htmlspecialchars($issuedetail) . "</textarea></td>\n";
			print "</tr>\n";
			$query = "SELECT id, name FROM user WHERE deleted = 0 ORDER BY name";
			if ($result = mysqli_query($dbc, $query)) { // Run the query.
			print "<tr>\n";
			print "<td>User name :</td>\n";
			print "<td><select width=\"100px\" class=\"field\" name=\"userid\">\n";
				while ($row = mysqli_fetch_assoc($result)) {
				print '<option';
				if ( $row['id'] == $userid ) {
					print ' selected="selected"';
			}
				print ' value ="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['name']) . '</option>' ."\n";
				} 
			} else { // Couldn't get the information.
				debugprint( '<p id="clickme" class="error">Could not retrieve the user because:<br />' . mysqli_error($dbc) . '. The query being run was: ' . $query . '</p>');
			}
			print "</select>\n";
			print "</tr>\n";
			print "</table>\n";
			print "</form>\n";
			// List related products with link
			print "<table class=\"object\">\n";
			print "<th>\n";
			print "Related objects\n";
			// Listbox related objects
			print "<form style=\"display:inline\" action=\"index.php?command=addobjectissue&issueid=" . $_GET['issueid'] . "\" method=\"post\">\n";
			print '<input type="hidden" name="issueid" value="' . $_GET['issueid'] . '" />' . "\n";
			print '<input type="hidden" name="from" value="edit" />' . "\n";
			// Define query for objects
			$query = "SELECT  id, name FROM object WHERE deleted = 0 ORDER BY name ASC";
			if ($result = mysqli_query($dbc, $query)) { // Run the query.
				print '<select class="edit" name="objectid">' . "\n";
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
			
			print "</th>\n";
			print "<tr>\n";
			print "<td>\n";
			$query = "SELECT object.name AS objectname, object.id as objectid FROM object " .
						"JOIN object_issues on (object_issues.object_id = object.id) WHERE object_issues.issue_id={$_GET['issueid']}";
			if ($result = mysqli_query($dbc, $query)) { // Run the query.
				if(mysqli_num_rows($result) <> 0) {
					print "|";
				}
				while($row = mysqli_fetch_assoc($result)) {   // Retrieve the information.
					print " <a href=\"index.php?command=viewobject&objectid={$row['objectid']}\">" . htmlspecialchars($row['objectname']) . "</a>";
					print "<form style=\"display:inline;\" action=\"index.php?command=deleteobjectissue&issueid=" . $_GET['issueid'] ."\" method=\"post\">\n";
					print "<input type=\"hidden\" name=\"objectid\" value=\"" . $row['objectid'] . "\" />\n";
					print "<input type=\"hidden\" name=\"issueid\" value=\"" . $_GET['issueid'] . "\" />\n";
					print "<input type=\"hidden\" name=\"from\" value=\"edit\" />\n";
					print "<input class=\"smallx\" type=\"submit\" name=\"submit\" value=\"X\" />\n";
					print "</form>\n";
					print " | ";
				}
			}
			print "</td>\n";
			print "</tr>\n";
			print "</table>\n";
			// Edit update issue details forms
			print "<table class=\"issueupdates\">\n";
			print '<col width="150px" /><col />';
			print "<th colspan=\"2\">Issue updates</th>\n";
			print "</table>\n";
			$query = "SELECT issue_msg.id as issuemsgid, issue_msg.user_id as issuemsguserid, issue_msg.subject as issuemsgsubject, issue_msg.detail as issuemsgdetail, " .
						"issue_msg.timestamp as issuemsgtimestamp, user.id as userid, issue_msg.status_id as issuemsgstatusid FROM issue_msg " .
						"JOIN user ON (user.id = issue_msg.user_id) " .
						"WHERE issue_msg.deleted = 0 AND issue_msg.issue_id = {$_GET['issueid']} ORDER BY issue_msg.timestamp";
			if ($result = mysqli_query($dbc, $query)) { // Run the query.
				while($row = mysqli_fetch_assoc($result) ) {   // Retrieve the information.
					$userid = $row['userid'];
					$issuemsgstatusid = $row['issuemsgstatusid'];
					print "<form action=\"index.php?command=updateissuemsg&issueid=" . $_GET['issueid'] ."\" method=\"post\">\n";
					print "<table class=\"issueupdates\">\n";
					print "<col width=\"150px\" /><col />\n";
					print "<th colspan=\"2\">Update\n";
					print "<input class=\"save\" type=\"submit\" name=\"submit\" value=\"Save Action\" />\n";
					print "</th>\n";
					print "<tr>\n";
					print "<td>Create date : </td>\n";
					print "<td>" . dateformat(htmlspecialchars($row['issuemsgtimestamp'])) . "\n";
					
					print "</td>\n";
					print "</tr>\n";
					$uquery = "SELECT id, name FROM user WHERE deleted = 0 ORDER BY name";
					if ($uresult = mysqli_query($dbc, $uquery)) { // Run the query.
						print "<tr>\n";
						print "<td>User name :</td>\n";
						print "<td><select width=\"100px\" class=\"field\" name=\"userid\">\n";
						while ($urow = mysqli_fetch_assoc($uresult)) {
							print '<option';
							if ( $urow['id'] == $userid ) {
								print ' selected="selected"';
							}
							print ' value ="' . htmlspecialchars($urow['id']) . '">' . htmlspecialchars($urow['name']) . '</option>' ."\n";
						}
					} else { // Couldn't get the information.
						debugprint( '<p id="clickme" class="error">Could not retrieve the object because:<br />' . mysqli_error($dbc) . '. The query being run was: ' . $query . '</p>');
					}
					print "</select>\n";
					print "</tr>\n";
					print "<tr>\n";
					print "<td>Action : </td>\n";
					print "<td>\n";
					print "<input type=\"text\" class=\"field\" name=\"issuemsgsubject\" value=\"" . htmlspecialchars($row['issuemsgsubject']) . "\">\n";
					print "</td>\n";
					print "</tr>\n";
					$squery = "SELECT id, name FROM issuestatus WHERE deleted = 0 ORDER BY name";
					if ($sresult = mysqli_query($dbc, $squery)) { // Run the query.
					print "<tr>\n";
					print "<td>Status :</td>\n";
					print "<td><select width=\"100px\" class=\"field\" name=\"issuemsgstatusid\">\n";
						while ($srow = mysqli_fetch_assoc($sresult)) {
						print '<option';
						if ( $srow['id'] == $issuemsgstatusid ) {
							print ' selected="selected"';
					}
						print ' value ="' . htmlspecialchars($srow['id']) . '">' . htmlspecialchars($srow['name']) . '</option>' ."\n";
						} 
					} else { // Couldn't get the information.
						debugprint( '<p id="clickme" class="error">Could not retrieve the object because:<br />' . mysqli_error($dbc) . '. The query being run was: ' . $query . '</p>');
					}
					print "</select>\n";
					print "</tr>\n";
					print "<tr>\n";
					print "<td>Detail : </td>\n";
					print "<td><textarea class=\"field\" name=\"issuemsgdetail\" >" . htmlspecialchars($row['issuemsgdetail']) . "</textarea></td>\n";
					print "</tr>\n";
					print "<input type=\"hidden\" name=\"issuemsgid\" value=\"" . $row['issuemsgid'] . "\">\n";
					print "</table>\n";
					print "</form>\n";
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
/*   addissue                                                     */
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
function addissue($dbc)
{
	$error = false;
	if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Handle the form.
		// Validate and secure the form data: submit=Save&issuereference=gsdfg&issuesubject=sdfg&issuetypeid=3&issuestatusid=3&issuedetail=Pellentesque+habitant+morbi+tristique+senectus+et+netus+et+malesuada+fames+ac+turpis+egestas.+Pellentesque+egestas%2C+tortor+a+vestibulum+porttitor%2C+orci+risus+interdum+massa%2C+nec+feugiat+sem+quam+id+massa.+Curabitur+elit+dolor%2C+euismod+ut+mollis+lobortis%2C+dapibus+at+arcu.+Nulla+et+sagittis+justo.+Etiam+et+est+arcu.+Aenean+feugiat+pharetra+dolor%2C+at+sagittis+nunc+interdum+ac.+Donec+lorem+arcu%2C+mollis+vitae+rutrum+id%3B+suscipit+non+tortor.%0D%0A%0D%0AIn+ut+erat+sed+dolor+condimentum+tristique+eu+in+odio.+Class+aptent+taciti+sociosqu+ad+litora+torquent+per+conubia+nostra%2C+per+inceptos+himenaeos.+Praesent+id+augue+massa.+Vestibulum+et+magna+lacus.+Duis+adipiscing+vestibulum+libero+at+pretium.+Pellentesque+tristique+iaculis+laoreet.+Sed+pellentesque+diam+in+tellus+pretium+iaculis%21+Integer+augue+erat%2C+molestie+vitae+varius+sit+amet%3B+consequat+ac+dui.+Pellentesque+fringilla%3B+est+a+commodo+commodo%3B+felis+sem+pretium+felis%2C+at+gravida+leo+dolor+id+lectus.+Mauris+at+iaculis+risus.+Vivamus+varius+scelerisque+velit%2C+in.%0D%0A&userid=1&objectid=0
		if ( !empty($_POST['issuereference']) && !empty($_POST['issuesubject']) && !empty($_POST['issuetypeid']) && !empty($_POST['issuestatusid']) && !empty($_POST['issuedetail']) && !empty($_POST['userid']) ) {
	
			// Prepare the values for storing:
			$issuereference = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['issuereference'])));
			$issuesubject = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['issuesubject'])));
			$issuetypeid = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['issuetypeid'])));
			$issuestatusid = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['issuestatusid'])));
			$issuedetail = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['issuedetail'])));
			$userid = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['userid'])));
			$objectid = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['objectid'])));		
			// Define the query.
			$query = "INSERT INTO issue (reference, subject, issuetype_id, issuestatus_id, detail, user_id) VALUES ('$issuereference', '$issuesubject', '$issuetypeid', '$issuestatusid', '$issuedetail', '$userid')";
			if ($result = mysqli_query($dbc, $query)) {
				$usedid = mysqli_insert_id($dbc);
			} else {
				$error = true;
			}
			if($_POST['objectid'] <> "0" && !$error){
				$query = "INSERT INTO object_issues (issue_id, object_id) VALUES ('$usedid', '$objectid')";
				if (!$result = mysqli_query($dbc, $query)) {
					$error = true;
				}
			}
			if (!$error) {
				print '<p id="fade" class="info">The issue has been saved.</p>';
			} else {
				debugprint( '<p id="clickme" class="error">Could not save the issue because:<br />' . mysqli_error($dbc) . '. The query being run was: ' . $query . '</p>');
			}

		} // No problem!
		listissues($dbc);
	} elseif (!$error) {
		// Make the form:
		print "<form action=\"index.php?command=addissue\" method=\"POST\">";
		print "<table class=\"issues\">\n";
		print '<col width="150px" /><col />';
		print "<th colspan=\"2\">Issue \n";
		print "<input class=\"save\" type=\"submit\" name=\"submit\" value=\"Save\"/>\n";
		print "</th>\n";
		print "<tr>\n";
		print "<td>Reference : </td>\n";
		print "<td><input class=\"field\" type=\"text\" name=\"issuereference\" /></td>\n";
		print "</tr>\n";
		print "<tr>\n";
		print "<td>Subject : </td>\n";
		print "<td><input class=\"field\" type=\"text\" name=\"issuesubject\" /></td>\n";
		print "</tr>\n";
		$query = "SELECT id, name FROM issuetype WHERE deleted = 0 ORDER BY name";
		if ($result = mysqli_query($dbc, $query)) { // Run the query.
		print "<tr>\n";
		print "<td>Issue type :</td>\n";
		print "<td><select width=\"100px\" class=\"field\" name=\"issuetypeid\">\n";
			while ($row = mysqli_fetch_assoc($result)) {
				print '<option';
				print ' value ="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['name']) . '</option>' ."\n";
			} 
		} else { // Couldn't get the information.
			debugprint( '<p id="clickme" class="error">Could not retrieve the object because:<br />' . mysqli_error($dbc) . '. The query being run was: ' . $query . '</p>');
		}
		print "</select>\n";
		print "</tr>\n";
		$query = "SELECT id, name FROM issuestatus WHERE deleted = 0 ORDER BY name";
		if ($result = mysqli_query($dbc, $query)) { // Run the query.
		print "<tr>\n";
		print "<td>Status :</td>\n";
		print "<td><select width=\"100px\" class=\"field\" name=\"issuestatusid\">\n";
			while ($row = mysqli_fetch_assoc($result)) {
				print '<option';
				print ' value ="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['name']) . '</option>' ."\n";
			} 
		} else { // Couldn't get the information.
			debugprint( '<p id="clickme" class="error">Could not retrieve the object because:<br />' . mysqli_error($dbc) . '. The query being run was: ' . $query . '</p>');
		}
		print "</select>\n";
		print "</tr>\n";
		print "<tr>\n";
		print "<td>Detail : </td>\n";
		print "<td><textarea class=\"field\" name=\"issuedetail\" ></textarea></td>\n";
		print "</tr>\n";
		$query = "SELECT id, name FROM user WHERE deleted = 0 ORDER BY name";
		if ($result = mysqli_query($dbc, $query)) { // Run the query.
		print "<tr>\n";
		print "<td>Issue Owner :</td>\n";
		print "<td><select width=\"100px\" class=\"field\" name=\"userid\">\n";
			while ($row = mysqli_fetch_assoc($result)) {
			print '<option';
			if ( $row['id'] == getuserid() ) {
				print ' selected="selected"';
			}
			print ' value ="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['name']) . '</option>' ."\n";
			} 
		} else { // Couldn't get the information.
			debugprint( '<p id="clickme" class="error">Could not retrieve the object because:<br />' . mysqli_error($dbc) . '. The query being run was: ' . $query . '</p>');
		}
		print "</select>\n";
		print "</tr>\n";
		print "</table>\n";
		// List related products with link
		print "<table class=\"object\">\n";
		print "<th>\n";
		print "Related object\n";
		print "</th>\n";
		print "<tr>\n";
		print "<td>\n";
		// Define query for objects
		$query = "SELECT  id, name FROM object WHERE deleted = 0 ORDER BY name ASC";
		if ($result = mysqli_query($dbc, $query)) { // Run the query.
			print '<select class="edit" name="objectid">' . "\n";
			print '<option value ="0">-none-</option>' ."\n";
			while ($row = mysqli_fetch_assoc($result)) {
				print '<option';
				print ' value ="' . $row['id'] . '">' . $row['name'] . '</option>' ."\n";
			} 
		} else { // Couldn't get the information.
			debugprint( '<p id="clickme" class="error">Could not retrieve the object because:<br />' . mysqli_error($dbc) . '. The query being run was: ' . $query . '</p>');
		$error = true;
		}
	print "</select>\n";
	print "</td>\n";
	print "</tr>\n";
	print "</table>\n";
	print "</form>\n";
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
//     n
//     $_GET['order']: sort order
//	   $_GET['search']: prefill the search field with last search
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
function deleteobjectissue($dbc)
{
	$error = false;
	if ( ($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_POST['objectid']) && isset($_POST['issueid'])) 
	{ // Handle the form
		if ( !empty($_POST['objectid']) && !empty($_POST['issueid']) ) {

			$issueid = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['issueid'])));
			$objectid = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['objectid'])));
			
			// delete the data from object_issues table
			
			$query = "DELETE FROM object_issues WHERE object_id={$objectid} AND issue_id={$issueid}";
			$result = mysqli_query($dbc, $query);
			if ( !mysqli_affected_rows($dbc) == 1){
				$error = true;
			}
			if ( !$error ) {
				print '<p id="fade" class="info">Object has been removed from issue</p>';
			} else {
				debugprint( '<p id="clickme" class="error">Could not remove the object because:</ br>' . mysqli_error($dbc) . '.The query being run was: ' . $query . '</p>');
			}
			
		} else { // Failed to enter data.
			print '<p class="error">Something went wrong!</p>';
			$error=true;
		}
		if ($_POST['from'] == 'edit')
		{
			editissue($dbc);
		} else
		{
			viewissue($dbc);
		}
	} 
}

/*******************************************************************/
/* function                                                        */
/*   addobjectissue	                                               */
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
function addobjectissue($dbc)
{
	$error = false;
	if ( ($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_POST['objectid']) && isset($_POST['issueid'])) 
	{ // Handle the form
		if ( !empty($_POST['objectid']) && !empty($_POST['issueid']) ) {

			$issueid = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['issueid'])));
			$objectid = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['objectid'])));
			
			// add the data from object_issues table
			
			$query = "INSERT INTO object_issues (issue_id, object_id) VALUES ('$issueid', '$objectid')";
			$result = mysqli_query($dbc, $query);
			if ( !mysqli_affected_rows($dbc) == 1){
				$error = true;
			}
			if ( !$error ) {
				print '<p id="fade" class="info">Object has been added to the issue</p>';
			} else {
				debugprint( '<p id="clickme" class="error">Could not add the object because:</ br>' . mysqli_error($dbc) . '.The query being run was: ' . $query . '</p>');
			}
			
		} else { // Failed to enter data.
			print '<p class="error">Something went wrong!</p>';
			$error=true;
		}
		if ($_POST['from'] == 'edit' && getperm('editissue'))
		{
			editissue($dbc);
		} else
		{
			viewissue($dbc);
		}
	} 
}

/*******************************************************************/
/* function                                                        */
/*   updateissuemsg	                                               */
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
function updateissuemsg($dbc)
{
	$error = false;
	if ( ($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_POST['issuemsgid']) ) 
	{ // Handle the form
		if ( !empty($_POST['userid']) && !empty($_POST['issuemsgsubject']) && !empty($_POST['issuemsgdetail']) && !empty($_POST['issuemsgid']) && !empty($_POST['issuemsgstatusid']) ) {

			$userid = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['userid'])));
			$issuemsgsubject = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['issuemsgsubject'])));
			$issuemsgdetail = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['issuemsgdetail'])));
			$issuemsgid = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['issuemsgid'])));
			$issuemsgstatusid = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['issuemsgstatusid'])));
			
			// update the data from issues_msg table
			
			$query = "UPDATE issue_msg SET subject='$issuemsgsubject', detail='$issuemsgdetail', status_id='$issuemsgstatusid', user_id='$userid' WHERE id='$issuemsgid'";
			$result = mysqli_query($dbc, $query);
			if ( !mysqli_affected_rows($dbc) == 1){
				$error = true;
			}
			if ( !$error ) {
				print '<p id="fade" class="info">Action has been updated</p>';
			} else {
				debugprint( '<p id="clickme" class="error">Could not update the action because:</ br>' . mysqli_error($dbc) . '.The query being run was: ' . $query . '</p>');
			}
			
		} else { // Failed to enter data.
			print '<p class="error">Something went wrong!</p>';
			$error=true;
		}
		editissue($dbc);
	} 
}

/*******************************************************************/
/* function                                                        */
/*   addissuemsg	                                               */
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
function addissuemsg($dbc)
{
	$error = false;
	if ( ($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_POST['userid']) && isset($_POST['issuemsgsubject']) && isset($_POST['issuemsgdetail']) && isset($_POST['issuemsgstatusid']) && isset($_POST['issueid'])) 
	{ // Handle the form
		if ( !empty($_POST['userid']) && !empty($_POST['issuemsgsubject']) && !empty($_POST['issuemsgdetail']) && !empty($_POST['issuemsgstatusid']) && !empty($_POST['issueid']) ) 
		{
			$userid = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['userid'])));
			$issuemsgsubject = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['issuemsgsubject'])));
			$issuemsgdetail = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['issuemsgdetail'])));
			$issuemsgstatusid = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['issuemsgstatusid'])));
			$issueid = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['issueid'])));
			
			// insert the data into issues_msg
			
			$query = "INSERT INTO issue_msg (issue_id, user_id, subject, detail, status_id) VALUES ('$issueid', '$userid', '$issuemsgsubject', '$issuemsgdetail', '$issuemsgstatusid' )";
			$result = mysqli_query($dbc, $query);
			if ( !mysqli_affected_rows($dbc) == 1){
				$error = true;
			}
			$query = "UPDATE issue SET issuestatus_id = '$issuemsgstatusid' WHERE id='$issueid'";
			$result = mysqli_query($dbc, $query);
			if ( !$result ){
				$error = true;
			}
			if ( !$error ) {
				print '<p id="fade" class="info">Update saved</p>';
			} else {
				debugprint( '<p id="clickme" class="error">Could not save because:</ br>' . mysqli_error($dbc) . '.The query being run was: ' . $query . '</p>');
			}
			
		} else { // Failed to enter data.
			print '<p class="error">Something went wrong!</p>';
			$error=true;
		}
		viewissue($dbc);
	} else {
	
		// Show the form to edit issue_msg
		print "<form action=\"index.php?command=addissuemsg&issueid=" . $_GET['issueid'] ."\" method=\"post\">\n";
		print "<table class=\"issueupdates\">\n";
		print '<col width="150px" /><col />';
		print "<th colspan=\"2\">New issue update\n";
		print '<input class="save" type="submit" name="submit" value="Save Update" />';
		print "<input type=\"hidden\" name=\"issueid\" value=\"" . $_GET['issueid'] . "\">\n";
		print "</th>\n";
		print "</tr>\n";
		$uquery = "SELECT id, name FROM user WHERE deleted = 0 ORDER BY name";
		if ($uresult = mysqli_query($dbc, $uquery)) { // Run the query.
			print "<tr>\n";
			print "<td>User name :</td>\n";
			print "<td><select width=\"100px\" class=\"field\" name=\"userid\">\n";
			while ($urow = mysqli_fetch_assoc($uresult)) {
				print '<option';
				if ( $urow['id'] == getuserid() ) {
					print ' selected="selected"';
				}
				print ' value ="' . htmlspecialchars($urow['id']) . '">' . htmlspecialchars($urow['name']) . '</option>' ."\n";
			}
		} else { // Couldn't get the information.
			debugprint( '<p id="clickme" class="error">Could not retrieve the object because:<br />' . mysqli_error($dbc) . '. The query being run was: ' . $query . '</p>');
		}
		print "</select>\n";
		print "</tr>\n";
		$query = "SELECT id, name FROM issuestatus WHERE deleted = 0 ORDER BY name";
		if ($result = mysqli_query($dbc, $query)) { // Run the query.
		print "<tr>\n";
		print "<td>Status :</td>\n";
		print "<td><select width=\"100px\" class=\"field\" name=\"issuemsgstatusid\">\n";
		while ($row = mysqli_fetch_assoc($result)) {
			print '<option';
			print ' value ="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['name']) . '</option>' ."\n";
			} 
		} else { // Couldn't get the information.
			debugprint( '<p id="clickme" class="error">Could not retrieve the object because:<br />' . mysqli_error($dbc) . '. The query being run was: ' . $query . '</p>');
		}
		print "</select>\n";
		print "</tr>\n";
		print "<tr>\n";
		print "<td>Action : </td>\n";
		print "<td>\n";
		print "<input type=\"text\" class=\"field\" name=\"issuemsgsubject\">\n";
		print "</td>\n";
		print "</tr>\n";
		print "<tr>\n";
		print "<td>Detail : </td>\n";
		print "<td><textarea class=\"field\" name=\"issuemsgdetail\" ></textarea></td>\n";
		print "</tr>\n";
		print "</table>\n";
		print "</form>\n";
		viewissue($dbc);
	}
}

?>