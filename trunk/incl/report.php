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
/*   reports                                                       */
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
function reports($dbc) {
	$error = false;
	// Report by type
	print "<div class=\"group\">\n";
	print "Report by type: Show objects that have any of the selected types.<hr/>\n";
	print"<form name=\"input\" action=\"index.php?command=typereport\" method=\"post\">\n";
	$query = "SELECT type.name as typename, type.id as typeid FROM type ORDER BY type.name";
	if ($result = mysqli_query($dbc, $query)) {
		while ($row = mysqli_fetch_assoc($result)) {	
			print "<input type=\"checkbox\" name=\"typeid" . htmlspecialchars($row['typeid']) . "\" value=\"" . htmlspecialchars($row['typeid']) . "\"/>" . htmlspecialchars($row['typename']) . "<br/>\n";
		}
	}
	print "<p><input class=\"createreport\" type=\"submit\" value=\"Create Report\" /></p>\n";
	print "</form>\n";
	print "</div>\n";
	// Report by any property
	print "<div class=\"group\">\n";
	print "Report by property: Show objects that have <b>ANY</b> of the selected properties<hr/>\n";
	print"<form name=\"input\" action=\"index.php?command=propertyreport\" method=\"post\">\n";
	$query = "SELECT property.name as propertyname, property.id as propertyid FROM property ORDER BY property.name";
	if ($result = mysqli_query($dbc, $query)) {
		while ($row = mysqli_fetch_assoc($result)) {	
			print "<input type=\"checkbox\" name=\"propertyid" . htmlspecialchars($row['propertyid']) . "\" value=\"" . htmlspecialchars($row['propertyid']) . "\"/>" . htmlspecialchars($row['propertyname']) . "<br/>\n";
		}
	}
	print "<p><input class=\"createreport\" type=\"submit\" value=\"Create Report\" /></p>\n";
	print "</form>\n";
	print "</div>\n";
/*	// Report by all properties
	print "<div class=\"group\">\n";
	print "Report by property: Show objects that have <b>ALL</b> of the selected properties<hr/>\n";
	print"<form name=\"input\" action=\"index.php?command=propertyallreport\" method=\"post\">\n";
	$query = "SELECT property.name as propertyname, property.id as propertyid FROM property ORDER BY property.name";
	if ($result = mysqli_query($dbc, $query)) {
		while ($row = mysqli_fetch_assoc($result)) {	
			print "<input type=\"checkbox\" name=\"propertyid{$row['propertyid']}\" value=\"{$row['propertyid']}\"/>{$row['propertyname']}<br/>\n";
		}
	} 
	print "<p><input class=\"createreport\" type=\"submit\" value=\"Create Report\" /></p>\n";
	print "</form>\n";
	print "</div>\n"; */
	// Report by property class
	print "<div class=\"group\">\n";
	print "Report by property class: Show objects that have any properties in the selected classes<hr/>\n";
	print"<form name=\"input\" action=\"index.php?command=propertyclassreport\" method=\"post\">\n";
	$query = "SELECT property_class.name as propertyclassname, property_class.id as propertyclassid FROM property_class ORDER BY property_class.name";
	if ($result = mysqli_query($dbc, $query)) {
		while ($row = mysqli_fetch_assoc($result)) {	
			print "<input type=\"checkbox\" name=\"propertyclassid" . htmlspecialchars($row['propertyclassid']) . "\" value=\"" . htmlspecialchars($row['propertyclassid']) . "\"/>" . htmlspecialchars($row['propertyclassname']) . "<br/>\n";
		}
	}
	print "<p><input class=\"createreport\" type=\"submit\" value=\"Create Report\" /></p>\n";
	print "</form>\n";
	print "</div>\n";
	// Report by issue type
	print "<div class=\"group\">\n";
	print "Report by issue type: Show issues of selected type and status<hr/>\n";
	print"<form name=\"input\" action=\"index.php?command=issuetypereport\" method=\"post\">\n";
	print "Issue type :\n";
	print "<select width=\"150px\" name=\"issuetypeid\">\n";
	print "<option value=\"*\">-All-</option>\n";
	$query = "SELECT issuetype.name as issuetypename, issuetype.id as issuetypeid FROM issuetype ORDER BY issuetype.name";
	if ($result = mysqli_query($dbc, $query)) {
		while ($row = mysqli_fetch_assoc($result)) {	
			print "<option  value=\"" . htmlspecialchars($row['issuetypeid']) . "\" />" . htmlspecialchars($row['issuetypename']) . "</option>\n";
		}
	}
	print "</select>\n";
	$query = "SELECT id, name FROM issuestatus WHERE deleted = 0 ORDER BY name";
	if ($result = mysqli_query($dbc, $query)) { // Run the query.
	print "Status :\n";
	print "<select width=\"150px\" name=\"issuestatusid\">\n";
	print "<option value=\"*\">-All-</option>\n";
	while ($row = mysqli_fetch_assoc($result)) {
		print '<option';
		print ' value ="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['name']) . '</option>' ."\n";
		} 
	} else { // Couldn't get the information.
		debugprint( '<p id="clickme" class="error">Could not retrieve the object because:<br />' . mysqli_error($dbc) . '. The query being run was: ' . $query . '</p>' );
	}
	print "</select>\n";

	print "<p><input class=\"createreport\" type=\"submit\" value=\"Create Report\" /></p>\n";
	print "</form>\n";
	print "</div>\n";
}


/*******************************************************************/
/* function                                                        */
/*   typereport                                                    */
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
function typereport($dbc)
{
	$report = "0";
	foreach ($_POST as $key => $value) {
        $report = $report . "," . $value;
	}
	// Define the query...
	$query = "SELECT object.id as objectid, object.name as objectname, object.description as objectdescription, " .
				"object.timestamp as objecttimestamp, type.name as typename " .
				"FROM object JOIN type ON (type.id = object.type_id) " .
				"WHERE object.deleted = 0 AND type.id IN($report) " .
				"ORDER BY object.name";
	// Run the query:
	if ($result = mysqli_query($dbc, $query)) {
		$rows = mysqli_num_rows($result);
		print '<table>';
		print '<col width="40px" /><col width="150px" /><col /><col width="160px" /><col width="120px" />';
		print "\n<tr>\n";
		print "<th>Id</th>\n";
		print "<th>Name</a></th>\n";
		print "<th>Description</th>\n";	
		print "<th>Type</th>\n";	
		print "<th>Create Date</th>\n";
		//print "<th></th>\n";
		print "</tr>\n";
		// Retrieve the returned records:
		while ($row = mysqli_fetch_assoc($result)) {
			// Print the record:
			print "<tr onClick=\"document.location.href='index.php?command=viewobject&objectid=" . htmlspecialchars($row['objectid']) . "';\" style=\"cursor:pointer;\">\n";
			print "<td>{$row['objectid']}</td>\n";
			print "<td>{$row['objectname']}</td>\n";
			print "<td>" . htmlspecialchars($row['objectdescription']) . "</td>\n";
			print "<td>" . htmlspecialchars($row['typename']) . "</td>\n";
			print "<td>" . dateformat(htmlspecialchars($row['objecttimestamp'])) . "\n";
			print "</td>\n";			
			print "</tr>\n";
		} // End of while loop.
		print "</table>\n";
		print "<div class=\"result\">$rows rows returned</div>\n";
	} else 
	{ // Query didn't run.
		debugprint( '<p id="clickme" class="error">Could not retrieve the data because:<br />' . mysqli_error($dbc) . '. The query being run was: ' . $query . '</p>' );
	} // End of query IF.
}


/*******************************************************************/
/* function                                                        */
/*   propertyreport                                                */
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
//     none
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
function propertyreport($dbc)
{
	$report = "0,";
	foreach ($_POST as $key => $value) {
        $report = $report . $value . ",";
	}
	$report = substr_replace($report ,"",-1);
	// Define the query...
	$query = "SELECT object.id AS objectid, object.name AS objectname, type.name AS typename, " .
					"object.Description AS objectdescription, object.timestamp AS objecttimestamp, property.name as propertyname " .
					"FROM object_property " .
					"INNER JOIN property ON object_property.property_id = property.id " .
					"INNER JOIN object ON object.id = object_property.object_id " .
					"INNER JOIN type ON type.id = object.type_id " .
					"WHERE object_property.property_id IN ($report) " .
					"ORDER BY object.name, property.name";
	// Run the query:
	if ($result = mysqli_query($dbc, $query)) {
		$rows = mysqli_num_rows($result);
		print '<table>';
		print '<col width="120px" /><col width="150px" /><col /><col width="160px" /><col width="120px" />';
		print "\n<tr>\n";
		print "<th>Property</th>\n";
		print "<th>Name</a></th>\n";
		print "<th>Description</th>\n";	
		print "<th>Type</th>\n";	
		print "<th>Create Date</th>\n";
		//print "<th></th>\n";
		print "</tr>\n";
		// Retrieve the returned records:
		while ($row = mysqli_fetch_assoc($result)) {
			// Print the record:
			print "<tr onClick=\"document.location.href='index.php?command=viewobject&objectid=" . htmlspecialchars($row['objectid']) . "';\" style=\"cursor:pointer;\">\n";
			print "<td>" . htmlspecialchars($row['propertyname']) ."</td>\n";
			print "<td>" . htmlspecialchars($row['objectname']) . "</td>\n";
			print "<td>" . htmlspecialchars($row['objectdescription']) . "</td>\n";
			print "<td>" . htmlspecialchars($row['typename']) . "</td>\n";
			print "<td>" . dateformat(htmlspecialchars($row['objecttimestamp'])) . "\n";
			print "</td>\n";			
			print "</tr>\n";
		} // End of while loop.
		print "</table>\n";
		print "<div class=\"result\">$rows rows returned</div>\n";
	} else 
	{ // Query didn't run.
		debugprint( '<p id="clickme" class="error">Could not retrieve the data because:<br />' . mysqli_error($dbc) . '. The query being run was: ' . $query . '</p>' );
	} // End of query IF.
}

/*******************************************************************/
/* function                                                        */
/*   propertyallreport                                             */
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
//     none
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
function propertyallreport($dbc)
/* Scrapbook

SELECT DISTINCT t1.object_id
FROM object_property t1
INNER JOIN object_property t2
ON t1.object_id = t2.object_id
INNER JOIN object_property t3
ON t1.object_id = t3.object_id
INNER JOIN object_property t4
ON t1.object_id = t4.object_id
WHERE t1.property_id = 1
AND t2.property_id = 2
AND t3.property_id = 5
AND t4.property_id = 9

SELECT DISTINCT t1.object_id
FROM object_property t1
INNER JOIN object_property t2
ON t1.object_id = t2.object_id
WHERE t1.property_id = 1
AND t2.property_id = 5

	
*/
{
	$report = "0,";
	$reportnum = 0;
	foreach ($_POST as $key => $value) {
        $report = $report . $value . ",";
        $reportnum++;
	}
	$report = substr_replace($report ,"",-1);
	// Define the query...
	$query = "SELECT object_property.object_id AS objectpropertyobjectid, property.name AS propertyname, " .
  				"type.name AS typename, object.name AS objectname, object.description AS objectdescription, object.timestamp as objecttimestamp " .
				"FROM object_property " .
				"INNER JOIN object ON object.id = object_property.object_id " .
				"INNER JOIN type ON type.id = object.type_id " .
				"INNER JOIN property ON property.id = object_property.property_id ".
				"WHERE object_property.property_id IN ($report) " .
				"GROUP BY object_property.property_id " .
				"HAVING COUNT(object_property.object_id) >= $reportnum";
	// Run the query:
	if ($result = mysqli_query($dbc, $query)) {
	
		print '<table>';
		print "\n<tr>\n";
		print "<th>Property</th>\n";
		print "<th>Name</a></th>\n";
		print "<th>Description</th>\n";	
		print "<th>Type</th>\n";	
		print "<th>Create Date</th>\n";
		//print "<th></th>\n";
		print "</tr>\n";
		// Retrieve the returned records:
		while ($row = mysqli_fetch_assoc($result)) {
			// Print the record:
			print "<tr onClick=\"document.location.href='index.php?command=viewobject&objectid=" . htmlspecialchars($row['objectpropertyobjectid']) ."';\" style=\"cursor:pointer;\">\n";
			print "<td>" . htmlspecialchars($row['propertyname']) . "</td>\n";
			print "<td>" . htmlspecialchars($row['objectname']) . "</td>\n";
			print "<td>" . htmlspecialchars($row['objectdescription']) . "</td>\n";
			print "<td>" . htmlspecialchars($row['typename']) . "</td>\n";
			print "<td>" . dateformat(htmlspecialchars($row['objecttimestamp'])) . "\n";
			print "</td>\n";			
			print "</tr>\n";
		} // End of while loop.
		print "</table>\n";
		print "<div class=\"result\">$rows rows returned</div>\n";
	} else 
	{ // Query didn't run.
		debugprint( '<p id="clickme" class="error">Could not retrieve the data because:<br />' . mysqli_error($dbc) . '. The query being run was: ' . $query . '</p>' );
	} // End of query IF.
}

/*******************************************************************/
/* function                                                        */
/*   propertyclassreport                                           */
/*******************************************************************/
//
// Description:
//   List objects that have properties of any of the selected classes
// Inputs: 
//   function:
//	   ():
//       ($dbc) : database connection
//   POST:
//	   All : get whole array
//   GET:
//     none
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
function propertyclassreport($dbc)
{
	$report = "0,";
	foreach ($_POST as $key => $value) {
        $report = $report . $value . ",";
	}
	$report = substr_replace($report ,"",-1);
	// Define the query...
	$query = "SELECT object.id AS objectid, object.name AS objectname, type.name AS typename, " .
					"object.description AS objectdescription, object.timestamp AS objecttimestamp, property.name as propertyname " .
					"FROM object_property " .
					"INNER JOIN property ON object_property.property_id = property.id " .
					"INNER JOIN object ON object.Id = object_property.object_id " .
					"INNER JOIN type ON type.id = object.type_id " .
					"INNER JOIN property_class ON property_class.id = property.class_id " .
					"WHERE property.class_id IN ($report) " .
					"ORDER BY object.name, property.name";
	// Run the query:
	if ($result = mysqli_query($dbc, $query)) {
		$rows = mysqli_num_rows($result);
		print '<table>';
		print '<col width="120px" /><col width="150px" /><col /><col width="160px" /><col width="120px" />';
		print "\n<tr>\n";
		print "<th>Property</th>\n";
		print "<th>Name</a></th>\n";
		print "<th>Description</th>\n";	
		print "<th>Type</th>\n";	
		print "<th>Create Date</th>\n";
		print "</tr>\n";

		// Retrieve the returned records:
		while ($row = mysqli_fetch_assoc($result)) {
		
			// Print the record:
			print "<tr onClick=\"document.location.href='index.php?command=viewobject&objectid=" . htmlspecialchars($row['objectid']) . "';\" style=\"cursor:pointer;\">\n";
			print "<td>" . htmlspecialchars($row['propertyname']) . "</td>\n";
			print "<td>" . htmlspecialchars($row['objectname']) . "</td>\n";
			print "<td>" . htmlspecialchars($row['objectdescription']) . "</td>\n";
			print "<td>" . htmlspecialchars($row['typename']) . "</td>\n";
			print "<td>" . dateformat(htmlspecialchars($row['objecttimestamp'])) . "\n";
			print "</td>\n";			
			print "</tr>\n";
		} // End of while loop.
		print "</table>\n";
		print "<div class=\"result\">$rows rows returned</div>\n";
	} else 
	{ // Query didn't run.
		debugprint( '<p id="clickme" class="error">Could not retrieve the data because:<br />' . mysqli_error($dbc) . '. The query being run was: ' . $query . '</p>' );
	} // End of query IF.
}

/*******************************************************************/
/* function                                                        */
/*   propertyclassreport                                           */
/*******************************************************************/
//
// Description:
//   List objects that have properties of any of the selected classes
// Inputs: 
//   function:
//	   ():
//       ($dbc) : database connection
//   POST:
//	   All : get whole array
//   GET:
//     none
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
function issuetypereport($dbc)
{
	$filter = "";
	if (isset($_POST['issuetypeid']))
	{
		$issuetypeid = mysqli_real_escape_string($dbc, $_POST['issuetypeid']);
		if(is_numeric($issuetypeid))
		{
			$filter .= " AND issue.issuetype_id = $issuetypeid";
		}
	}
	if (isset($_POST['issuestatusid']))
	{
		$issuestatusid = mysqli_real_escape_string($dbc, $_POST['issuestatusid']);
		if(is_numeric($issuestatusid))
		{
			$filter .= " AND issue.issuestatus_id = $issuestatusid";
		}
	}
	// Define the query...
	$query = "SELECT issue.id as issueid, issue.subject as issuesubject, issue.timestamp as issuetimestamp, issuetype.name as issuetypename, " .
				 "issuestatus.name as issuestatusname, user.name as username, issue.reference as issuereference " .
				 "FROM issue JOIN issuetype ON (issuetype.id = issue.issuetype_id) " .
				 "JOIN issuestatus ON (issuestatus.id = issue.issuestatus_id) " .
				 "JOIN user ON (user.id = issue.user_id) " .
				 "WHERE issue.deleted = 0 $filter ORDER BY issue.issuetype_id, issue.issuestatus_id";

	// Run the query:
	if ($result = mysqli_query($dbc, $query)) {
		$rows = mysqli_num_rows($result);
		print "<table class=\"issues\">\n";
		print '<col width="150px" /><col width="80" /><col width="180px" /><col /><col width="12%" /><col width="120px" /><col width="99px" />';
		print "\n<tr>\n";
		print "<th>Issue type</th>\n";	
		print "<th>Status</th>\n";
		print "<th>Reference</th>\n";
		print "<th>Subject</th>\n";
		print "<th>Create Date</th>\n";
		print "<th>Issue owner</th>\n";	
		print "</tr>\n";

		// Retrieve the returned records:
		while ($row = mysqli_fetch_assoc($result)) {
		
			// Print the record:
			print "<tr class=\"issueshead\">\n";
			print "<td onClick=\"document.location.href='index.php?command=viewissue&issueid={$row['issueid']}';\" style=\"cursor:pointer;\">" . htmlspecialchars($row['issuetypename']);
			if(getdebugmode()){
				print " [" . htmlspecialchars($row['issueid']) . "]";
			}
			print "</td>\n";
			print "<td onClick=\"document.location.href='index.php?command=viewissue&issueid={$row['issueid']}';\" style=\"cursor:pointer;\">" . htmlspecialchars($row['issuestatusname']) . "</td>\n";
			print "<td><a href=\"index.php?command=viewissue&issueid={$row['issueid']}\" target=\"_blank\">" . htmlspecialchars($row['issuereference'] ) . "</a></td>\n";
			print "<td><a href=\"index.php?command=viewissue&issueid={$row['issueid']}\" target=\"_blank\">" . htmlspecialchars($row['issuesubject'] ) . "</a></td>\n";
			print "<td onClick=\"document.location.href='index.php?command=viewissue&issueid={$row['issueid']}';\" style=\"cursor:pointer;\">" . dateformat(htmlspecialchars($row['issuetimestamp'])) . "</td>\n";			
			print "<td onClick=\"document.location.href='index.php?command=viewissue&issueid={$row['issueid']}';\" style=\"cursor:pointer;\">" . htmlspecialchars($row['username']) . "</td>\n";
			print "</tr>\n";
			// Show last update
			$uquery = "SELECT issue_msg.user_id as issuemsguserid, issue_msg.subject as issuemsgsubject, issue_msg.detail as issuemsgdetail, " .
						"issue_msg.timestamp as issuemsgtimestamp, user.name as username, issuestatus.name AS issuestatusname FROM issue_msg " .
						"JOIN user ON (user.id = issue_msg.user_id) JOIN issuestatus ON (issue_msg.status_id = issuestatus.id) " .
						"WHERE issue_msg.deleted = 0 AND issue_msg.issue_id = {$row['issueid']} ORDER BY issue_msg.timestamp DESC LIMIT 1";
			if (($uresult = mysqli_query($dbc, $uquery)) and (mysqli_num_rows($uresult) > 0)) { // Run the query.
				while($urow = mysqli_fetch_assoc($uresult) ) {   // Retrieve the information.
					print "<tr>\n";
					print "<td>Last update</td>\n";
					print "<td>" . htmlspecialchars($urow['issuestatusname']) . "</td>\n";
					// List related objects
					$oquery = "SELECT object.name AS objectname, object.id as objectid FROM object " .
						"JOIN object_issues on (object_issues.object_id = object.id) WHERE object_issues.issue_id={$row['issueid']}";
					print "<td>\n";
					if ($oresult = mysqli_query($dbc, $oquery)) { // Run the query.
						if(mysqli_num_rows($oresult) <> 0) {
						}
						while($orow = mysqli_fetch_assoc($oresult)) {   // Retrieve the information.
							print " <a href=\"index.php?command=viewobject&objectid={$orow['objectid']}\" target=\"_blank\">" . htmlspecialchars($orow['objectname']) . "</a>";
							print " | ";
						}
					}
					print "</td>\n";
					print "<td colspan=\"1\">" . htmlspecialchars($urow['issuemsgsubject']) . "</td>\n";
					print "<td>" . dateformat(htmlspecialchars($urow['issuemsgtimestamp'])) . "</td>\n";
					print "<td>" . htmlspecialchars($urow['username']) . "</td>\n";
					print "</tr>\n";
				}		
			} else
			{
				print "<tr>\n";
				print "<td>No updates</td>\n";
				print "<td>-</td>\n";
				print "<td colspan=\"4\">\n";
				// List related objects
				$oquery = "SELECT object.name AS objectname, object.id as objectid FROM object " .
							"JOIN object_issues on (object_issues.object_id = object.id) WHERE object_issues.issue_id={$row['issueid']}";
				if ($oresult = mysqli_query($dbc, $oquery)) 
				{ // Run the query.
					if(mysqli_num_rows($oresult) <> 0) {
					}
					while($orow = mysqli_fetch_assoc($oresult)) {   // Retrieve the information.
					print " <a href=\"index.php?command=viewobject&objectid={$orow['objectid']}\" target=\"_blank\">" . htmlspecialchars($orow['objectname']) . "</a>";
						print " | ";
					}
				}
				print "</td>\n";
				print "</tr>\n";
			}
		} // End of while loop.
	print "</table>\n";
	print "<div class=\"result\">$rows rows returned</div>\n";	} else 
	{ // Query didn't run.
		debugprint( '<p id="clickme" class="error">Could not retrieve the data because:<br />' . mysqli_error($dbc) . '. The query being run was: ' . $query . '</p>' );
	} // End of query IF.
}

?>