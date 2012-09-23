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

// Includes
include('user.php');
include('issue.php');
include('type.php');
include('property.php');
include('object.php');
include('relation.php');
include('report.php');
include('debug.php');

/**************************************************************************************/
/* General                                                                            */
/**************************************************************************************/
//
// Functions to handle general stuff:
//
// getcommand: return the command from $_GET
// getnextrow: return the next database entry or same if none exist
// getprevrow: return the previous database entry or same if none exist
// getorder: retrun the order for a column based on sort and previous order
// getorderarrow : return the column arrow for an order
// showmenu: show the menu depending on the command
/**************************************************************************************/

/*******************************************************************/
/* function                                                        */
/*   getcommand                                                    */
/*******************************************************************/
//
// Description:
// Inputs: 
//   function:
//	   (): none
//   POST:
//	   none
//   GET:
//	   all
//
// Output:
//   return:
//     $command: returns the value of the $_GET['command']
//   HTML:
//     none
//   MYSQL:
//     none
//   GET:
//     none
//   POST:
//     none
//
// Security checks:
//   none
//
// Security risk:
//   $_GET['command'] can be invalid.
/********************************************************************/
function getcommand()
{
	$command = "";
		foreach ($_GET as $varname => $varvalue) {
		if ($varname == 'command') {
	 		$command = $varvalue;
 		}
	}
	return($command);
}

/*******************************************************************/
/* function                                                        */
/*   getnextrow                                                    */
/*******************************************************************/
//
// Description:
//   Get next row based on current row ($id)
// Inputs: 
//   function:
//	   ():
//       $dbc: database connection
//       $table: database table
//       $id: current id to find next 
//   POST:
//	   none
//   GET:
//	   none
//
// Output:
//   return:
//     $nextrow: return the result of the MYSQL query
//   HTML:
//     none
//   MYSQL:
//     SELECT id FROM $table WHERE id > $id AND deleted = 0 ORDER BY id ASC LIMIT 1
//       $dbc: database connection
//       $table: table to search in
//       $id: id to find next
//   GET:
//     none
//   POST:
//     none
//
// Security checks:
//   $nextrow is checked for numeric
//
// Security risk:
//   $table and $id need validation, however they come only from internal functions
/********************************************************************/
function getnextrow($dbc, $table, $id)
{
	$nextrow = mysqli_real_escape_string($dbc, $id);
	$query = "SELECT id FROM $table WHERE id > $id AND deleted = 0 ORDER BY id ASC LIMIT 1";
	if ($result = mysqli_query( $dbc, $query )) { // Run the query.
		$row = mysqli_fetch_assoc($result); // Retrieve the information.
		$nextrow = $row['id'];
	}
	if ( !is_numeric($nextrow) ) {$nextrow = $id;}
	return $nextrow;	
}

/*******************************************************************/
/* function                                                        */
/*   getprevrow                                                    */
/*******************************************************************/
//
// Description:
//   Get previous row based on current row ($id)
// Inputs: 
//   function:
//	   ():
//       $dbc: database connection
//       $table: database table
//       $id: current id to find previous 
//   POST:
//	   none
//   GET:
//	   none
//
// Output:
//   return:
//     $prevrow: return the result of the MYSQL query
//   HTML:
//     none
//   MYSQL:
//     SELECT id FROM $table WHERE id < $id AND deleted = 0 ORDER BY id ASC LIMIT 1
//       $dbc: database connection
//       $table: table to search in
//       $id: id to find previous
//   GET:
//     none
//   POST:
//     none
//
// Security checks:
//   $prevrow is checked for numeric
//
// Security risk:
//   $table and $id need validation, however they come only from internal functions
/********************************************************************/
function getprevrow($dbc, $table, $id)
{
	$prevrow = mysqli_real_escape_string($dbc, $id);
	$query = "SELECT id FROM $table WHERE id < $id AND deleted = 0 ORDER BY id DESC LIMIT 1";
	if ($result = mysqli_query( $dbc, $query )) { // Run the query.
		$row = mysqli_fetch_assoc($result); // Retrieve the information.
		$prevrow = $row['id'];
	}
	if ( !is_numeric($prevrow) ) {$prevrow = $id;}
	return $prevrow;	
}

/*******************************************************************/
/* function                                                        */
/*   getorder                                                      */
/*******************************************************************/
//
// Description:
//   Return the sort order for column depending on current sort and order
// Inputs: 
//   function:
//	   ():
//       $column:
//       $sort:
//       $order: 
//   POST:
//	   none
//   GET:
//	   none
//
// Output:
//   return:
//     $neworder:
//   HTML:
//     none
//   MYSQL:
//     none
//   GET:
//     none
//   POST:
//     none
//
// Security checks:
//   $neworder returns always ASC or DESC
//
// Security risk:
//   none
/********************************************************************/
function getorder($column, $sort, $order) {
	if ( $column == $sort )
	{
		$neworder = ( ($order == "DESC") ? "ASC" : "DESC" );
	} else {
		$neworder = "ASC";
	}
	return($neworder);
}

/*******************************************************************/
/* function                                                        */
/*   getorderarrow                                                 */
/*******************************************************************/
//
// Description:
//   Return the arrow to be displayed on ordered column
// Inputs: 
//   function:
//	   ():
//       $column:
//       $sort:
//       $order: 
//   POST:
//	   none
//   GET:
//	   none
//
// Output:
//   return:
//     $arrow:
//   HTML:
//     none
//   MYSQL:
//     none
//   GET:
//     none
//   POST:
//     none
//
// Security checks:
//   $arrow returns always "&and;" or "&or;" or ""
//
// Security risk:
//   none
/********************************************************************/
function getorderarrow($column, $sort, $order) {
	if ( $column == $sort )
	{
		$arrow = ( ($order == "DESC") ? " &and;" : " &or;" );
	} else {
		$arrow = "";
	}
	return($arrow);
}

/*******************************************************************/
/* function                                                        */
/*   getsearch                                                     */
/*******************************************************************/
//
// Description:
//   Build string to search
// Inputs: 
//   function:
//	   ():
//       $searchstr:
//   POST:
//	   none
//   GET:
//	   none
//
// Output:
//   return:
//     $search:
//   HTML:
//     none
//   MYSQL:
//     none
//   GET:
//     none
//   POST:
//     none
//
// Security checks:
//   $search returns always "&search=$searchstr" or ""
//   $searchstr is passed through mysqli_real_escape_string($dbc, )
//
// Security risk:
//   none
/********************************************************************/
function getsearch($searchstr) {
	if ( $searchstr <> "" ) {
		$search = "&search=" . mysqli_real_escape_string($dbc, $searchstr);
	} else {
		$search = "";
	}
	return($search);
}

/*******************************************************************/
/* function                                                        */
/*   showmenu                                                      */
/*******************************************************************/
//
// Description:
//   Show the menu
// Inputs: 
//   function:
//	   ():
//       $dbc: database connection
//   POST:
//	   $_POST['search']: prefill the search field with last search
//   GET:
//	   $_GET['search']: prefill the search field with last search
//     $_GET['objectid']: for previous and next buttons and view/edit
//     $_GET['propertyid']: for previous and next buttons and view/edit
//     $_GET['typeid']: for previous and next buttons and view/edit
//     $_GET['relationid']: for previous and next buttons and view/edit
//
// Output:
//   return:
//     none
//   HTML:
//     static html based on getcommand()
//   MYSQL:
//     none
//   GET:
//     several used...
//   POST:
//     none
//
// Security checks:
//   $_POST['search'] is checked for invalid characters, only alfanum and space allowed
//
// Security risk:
//   $_GET and $POST should not be direclty used in code but passed to a variable and checked for valid data
/********************************************************************/
function showmenu($dbc)
{
	print "<div class=\"menu\">\n";
	print " | ";
	if (getperm('listobjects')) { 
		print "<a class=\"menu\" href=\"index.php?command=listobjects\">Objects</a>\n";
		print " | ";
	};
	if (getperm('listtypes')) { 
		print "<a class=\"menu\" href=\"index.php?command=listtypes\">Types</a>\n";
		print " | ";
	};
	if (getperm('listproperties')) { 
		print "<a class=\"menu\" href=\"index.php?command=listproperties\">Properties</a>\n";
		print " | ";
		};
	if (getperm('listrelations')) { 
		print "<a class=\"menu\" href=\"index.php?command=listrelations\">Relations</a>\n";
		print " | ";
		};
	if (getperm('listissues')) { 
		print "<a class=\"menu\" href=\"index.php?command=listissues\">Issues</a>\n";
		print " | ";
		};
	if (getperm('reports')) { 
		print "<a class=\"menu\" href=\"index.php?command=reports\">Reports</a>\n";
		print " | ";
	};
	if (getperm('usermanagement')) { 
		print "<a class=\"menu\" href=\"index.php?command=listusers\">User Management</a>\n";
		print " | ";
	};
	if (getauth()) { 
		print "<a class=\"menu\" href=\"index.php?command=logout\">Logout</a>\n";
		print " | ";
	};
	if (getperm('aboutpage')) { 
		print "<a class=\"menu\" href=\"index.php?command=aboutpage\">About</a>\n";
		print " | ";
	};
	// Search function
	if (getperm('search')) {
		if ( isset($_POST['search']) OR isset($_GET['search']) ){
			$search = (isset($_POST['search']) ? $_POST['search'] : $_GET['search']);
		} else {
			$search = "";
		}
		print "<form style=\"float:right;\" action=\"index.php?command=listobjects\" method=\"post\">\n";
		print "<input class=\"searchbutton\" type=\"text\" name=\"search\" value=\"" . $search . "\"/>";
		print "<input class=\"searchbutton\" type=\"submit\" name=\"submit\" value=\"Search\" />\n";
		print "</form>\n";
	}
	print "</div>\n";
	// Get command and parameters from URI:
	$command = getcommand();
	
	switch ($command)
	{
		case 'listobjects':
			// Show objects
			print "<div class=\"menu\">";
			print "&raquo; Object: ";
			if ( getperm('addobject')) {
				print "<a class=\"menu\" href=\"index.php?command=addobject\"> Add</a>\n";
				print " | ";
				print "<a class=\"menu\" href=\"index.php?command=addmobject\"> Add Many</a>\n";
			};
			print "</div>\n";
			break;
		case 'listtypes':
			// Show types
			print "<div class=\"menu\">";
			print "&raquo; Type: ";
			if ( getperm('addtype')) {
				print "<a class=\"menu\" href=\"index.php?command=addtype\"> Add</a>\n";
			}
			print "</div>\n";
			break;
		case 'listproperties':
			// Show properties
			print "<div class=\"menu\">";
			print "&raquo; Property: ";
			if ( getperm('addproperty')) {
				print "<a class=\"menu\" href=\"index.php?command=addproperty\"> Add</a>\n";
			}
			print "</div>\n";
			break;
		case 'listrelations':
			// Show relations
			print "<div class=\"menu\">";
			print "&raquo; Relation: ";
			if ( getperm('addrelation')) {
				print "<a class=\"menu\" href=\"index.php?command=addrelation\"> Add</a>\n";
			}
			print "</div>\n";
			break;
		case 'listissues':
			// Show issues
			print "<div class=\"menu\">";
			print "&raquo; Issue: ";
			if ( getperm('addissue')) {
				print "<a class=\"menu\" href=\"index.php?command=addissue\"> Add</a>\n";
			}
			print "</div>\n";
			break;
		case 'viewobject':
			// Show object details
			print "<div class=\"menu\">";
			print "&raquo; Object View: ";
			if ( getperm('editobject')) {
				print "<a class=\"menu\" href=\"index.php?command=editobject&objectid={$_GET['objectid']}\"> Edit</a>\n";
			}
			if ( getperm('addobject') && getperm('editobject') ) {
				print " | ";
			}
			if ( getperm('addobject')) {
				print "<a class=\"menu\" href=\"index.php?command=addobject\"> Add</a>\n";
				print " | ";
				print "<a class=\"menu\" href=\"index.php?command=addmobject\"> Add Many</a>\n";
			}
			print "</div>\n";
			// Next - Prev buttons
			if ( getperm('viewobject') ) {
				print "<p class=\"prev\" OnClick=\"location.href='index.php?command=viewobject&objectid=" . getprevrow($dbc, "object", $_GET['objectid']) . "';\">&lt;</p>\n";
				print "<p class=\"next\" OnClick=\"location.href='index.php?command=viewobject&objectid=" . getnextrow($dbc, "object", $_GET['objectid']) . "';\">&gt;</p>\n";
			}
			break;
		case 'viewtype':
			// Show type details
			print "<div class=\"menu\">";
			print "&raquo; Type View: ";
			if ( getperm('edittype') ) {
				print "<a class=\"menu\" href=\"index.php?command=edittype&typeid={$_GET['typeid']}\"> Edit</a>\n";
			}
			print "</div>\n";
			// Next - Prev buttons
			if ( getperm('viewtype') ) {
				print "<p class=\"prev\" OnClick=\"location.href='index.php?command=viewtype&typeid=" . getprevrow($dbc, "type", $_GET['typeid']) . "';\">&lt;</p>\n";
				print "<p class=\"next\" OnClick=\"location.href='index.php?command=viewtype&typeid=" . getnextrow($dbc, "type", $_GET['typeid']) . "';\">&gt;</p>\n";
			}
			break;
		case 'viewproperty':
			// Show property details
			print "<div class=\"menu\">";
			print "&raquo; Property View: ";
			if ( getperm('editproperty') ) {
				print "<a class=\"menu\" href=\"index.php?command=editproperty&propertyid={$_GET['propertyid']}\"> Edit</a>\n";
			}
			print "</div>\n";
			// Next - Prev buttons
			if ( getperm('viewproperty') ) {
				print "<p class=\"prev\" OnClick=\"location.href='index.php?command=viewproperty&propertyid=" . getprevrow($dbc, "property", $_GET['propertyid']) . "';\">&lt;</p>\n";
				print "<p class=\"next\" OnClick=\"location.href='index.php?command=viewproperty&propertyid=" . getnextrow($dbc, "property", $_GET['propertyid']) . "';\">&gt;</p>\n";
			}
			break;
		case 'viewrelation':
			// Show Relation details
			print "<div class=\"menu\">";
			print "&raquo; Relation View: ";
			if ( getperm('editrelation') ) {
				print "<a class=\"menu\" href=\"index.php?command=editrelation&relationid={$_GET['relationid']}\"> Edit</a>\n";
			}
			print "</div>\n";
			// Next - Prev buttons
			if ( getperm('viewrelation') ) {
				print "<p class=\"prev\" OnClick=\"location.href='index.php?command=viewrelation&relationid=" . getprevrow($dbc, "relation", $_GET['relationid']) . "';\">&lt;</p>\n";
				print "<p class=\"next\" OnClick=\"location.href='index.php?command=viewrelation&relationid=" . getnextrow($dbc, "relation", $_GET['relationid']) . "';\">&gt;</p>\n";
			}
			break;
		case 'viewissue':
		case 'addissuemsg':
			// Show Relation details
			print "<div class=\"menu\">";
			print "&raquo; Issue View: ";
			if ( getperm('editissue') ) {
				print "<a class=\"menu\" href=\"index.php?command=editissue&issueid={$_GET['issueid']}\"> Edit</a>\n";
			}
			if ( getperm('addissue') ) {
				print "<a class=\"menu\" href=\"index.php?command=addissue\"> | Add</a>\n";
			}
			print "</div>\n";
			// Next - Prev buttons
			if ( getperm('viewissue') ) {
				print "<p class=\"prev\" OnClick=\"location.href='index.php?command=viewissue&issueid=" . getprevrow($dbc, "issue", $_GET['issueid']) . "';\">&lt;</p>\n";
				print "<p class=\"next\" OnClick=\"location.href='index.php?command=viewissue&issueid=" . getnextrow($dbc, "issue", $_GET['issueid']) . "';\">&gt;</p>\n";
			}
			break;
		case 'editobject':
		case 'updateobjectproperty':
		case 'storeobjectproperty':
		case 'addobjectrelation':
		case 'deleteobjectrelation':
			// Edit object details
			print "<div class=\"menu\">";
			print "&raquo; Object Edit: ";
			if ( getperm('viewobject') ) {
				print "<a class=\"menu\" href=\"index.php?command=viewobject&objectid={$_GET['objectid']}\"> View</a>\n";
			}
			if ( getperm('addobject') && getperm('viewobject') ) {
				print " | ";
			}
			if ( getperm('addobject')) {
				print "<a class=\"menu\" href=\"index.php?command=addobject\"> Add</a>\n";
				print " | ";
				print "<a class=\"menu\" href=\"index.php?command=addmobject\"> Add Many</a>\n";
			}
			print "</div>\n";
			// Next - Prev buttons
			if ( getperm('editobject') ) {
				print "<p class=\"prev\" OnClick=\"location.href='index.php?command=editobject&objectid=" . getprevrow($dbc, "object", $_GET['objectid']) . "';\">&lt;</p>\n";
				print "<p class=\"next\" OnClick=\"location.href='index.php?command=editobject&objectid=" . getnextrow($dbc, "object", $_GET['objectid']) . "';\">&gt;</p>\n";
			}
			break;
		case 'edittype':
			// Edit type details
			print "<div class=\"menu\">";
			print "&raquo; Type View: ";
			if ( getperm('viewtype') ) {
				print "<a class=\"menu\" href=\"index.php?command=viewtype&typeid={$_GET['typeid']}\"> View</a>\n";
			}
			print "</div>\n";
			// Next - Prev buttons
			if ( getperm('edittype') ) {
				print "<p class=\"prev\" OnClick=\"location.href='index.php?command=edittype&typeid=" . getprevrow($dbc, "type", $_GET['typeid']) . "';\">&lt;</p>\n";
				print "<p class=\"next\" OnClick=\"location.href='index.php?command=edittype&typeid=" . getnextrow($dbc, "type", $_GET['typeid']) . "';\">&gt;</p>\n";
			}
			break;
		case 'editproperty':
			// Edit property details
			print "<div class=\"menu\">";
			print "&raquo; Property View: ";
			if ( getperm('viewproperty') ) {
				print "<a class=\"menu\" href=\"index.php?command=viewproperty&propertyid={$_GET['propertyid']}\"> View</a>\n";
			}
			print "</div>\n";
			// Next - Prev buttons
			if ( getperm('editproperty') ) {
				print "<p class=\"prev\" OnClick=\"location.href='index.php?command=editproperty&propertyid=" . getprevrow($dbc, "type", $_GET['propertyid']) . "';\">&lt;</p>\n";
				print "<p class=\"next\" OnClick=\"location.href='index.php?command=editproperty&propertyid=" . getnextrow($dbc, "type", $_GET['propertyid']) . "';\">&gt;</p>\n";
			}
			break;
		case 'editrelation':
			// Edit relation details
			print "<div class=\"menu\">";
			print "&raquo; Relation View: ";
			if ( getperm('viewrelation') ) {
				print "<a class=\"menu\" href=\"index.php?command=viewrelation&relationid={$_GET['relationid']}\"> View</a>\n";
			}
			print "</div>\n";
			// Next - Prev buttons
			if ( getperm('editrelation') ) {
				print "<p class=\"prev\" OnClick=\"location.href='index.php?command=editrelation&relationid=" . getprevrow($dbc, "relation", $_GET['relationid']) . "';\">&lt;</p>\n";
				print "<p class=\"next\" OnClick=\"location.href='index.php?command=editrelation&relationid=" . getnextrow($dbc, "relation", $_GET['relationid']) . "';\">&gt;</p>\n";
			}
			break;
		case 'editissue':
		case 'addobjectissue':
		case 'deleteobjectissue':
		case 'updateissuemsg':
			// Edit relation details
			print "<div class=\"menu\">";
			print "&raquo; Issue Edit: ";
			if ( getperm('viewissue') ) {
				print "<a class=\"menu\" href=\"index.php?command=viewissue&issueid={$_GET['issueid']}\"> View</a>\n";
			}
			if ( getperm('addissue') ) {
				print "<a class=\"menu\" href=\"index.php?command=addissue\"> | Add</a>\n";
			}
			print "</div>\n";
			// Next - Prev buttons
			if ( getperm('editissue') ) {
				print "<p class=\"prev\" OnClick=\"location.href='index.php?command=editissue&issueid=" . getprevrow($dbc, "issue", $_GET['issueid']) . "';\">&lt;</p>\n";
				print "<p class=\"next\" OnClick=\"location.href='index.php?command=editissue&issueid=" . getnextrow($dbc, "issue", $_GET['issueid']) . "';\">&gt;</p>\n";
			}
			break;
		case 'reports':
			// Report
			print "<div class=\"menu\">";
			print "&raquo; Reports: ";
			print "</div>\n";
			break;
		case 'usermanagement':
		case 'listusers':
		case 'adduser':
		case 'edituser':
		case 'listgroups':
		case 'addgroup':
		case 'editgroup':
			// Usermanagement
			print "<div class=\"menu\">";
			print "&raquo; User Management:\n";
			if ( getperm('listusers') ) {
				print " | \n";
				print "<a class=\"menu\" href=\"index.php?command=listusers\">List Users</a>\n";
			}
			if ( getperm('adduser') ) {
				print " | \n";
				print "<a class=\"menu\" href=\"index.php?command=adduser\">Add User</a>\n";
			}
			if ( getperm('listgroups') ) {
				print " | \n";
				print "<a class=\"menu\" href=\"index.php?command=listgroups\">List groups</a>\n";
			}
			if ( getperm('addgroup') ) {
				print " | \n";
				print "<a class=\"menu\" href=\"index.php?command=addgroup\">Add Group</a>\n";
			}
			if ( getperm('adduser') ) {
				print " | \n";
			}
			
			print "</div>\n";
			break;
		default:
			print "<div class=\"menu\">&nbsp;</div>\n";
	}
}

/*******************************************************************/
/* function                                                        */
/*   showabout                                                    */
/*******************************************************************/
//
// Description:
//   Show the about screen
// Inputs: 
//   function:
//	   ():
//       none
//   POST:
//	   none
//   GET:
//	   none
// Output:
//   return:
//     none
//   HTML:
//     static html
//   MYSQL:
//     none
//   GET:
//     none
//   POST:
//     none
//
// Security checks:
//   ?
//
// Security risk:
//   User should not see PHP version or Server software version. Hide for normal users.
/********************************************************************/
function showabout() {
	print "<div class=\"splash\">\n";
	print "<div>\n";
	print "<h1>" . getappname() . "</h1>\n";
	print "<img alt=\"logo\" src=\"images/logo.svg\" width=\"150\" height=\"110\" />\n";
	print "</div>\n";
	print "<hr/>\n";
	print "<div>\n";
	print "<h3>Version: " . getappver() . "</h3>\n";
	print "</div>\n";
	print "<hr/>\n";
	print "<h2>Flexible database for storing object relations</h2>\n";
	print "<hr/>\n";
	print "<div class=\"splash\">\n";
	print "<div>Programming language: PHP</div>\n";
	print "<div>Database engine: MySQL</div>\n";
	print "<div>Dependencies: jQuery (v1.8.0| GNU General Public License (GPL) Version 2), LighBox2 (v2.51| Creative Commons Attribution 2.5 License) and jQuery Autosize (v1.11| MIT License)</div>\n";
	print "</div>\n";
	print "<hr/>\n";
	print "<div class=\"splash\">\n";
	print "<div>PHP Version: " . phpversion() . "</div>\n";
	print "<div>PHP Session ID: " . $_COOKIE["OR"] . "</div>\n";
	print "<div>PHP Session timout: " . (1800 - ( time () - $_SESSION['start'])) . "</div>\n";
	print "<div>PHP Session ID timer: " . (180 - ( time () - $_SESSION['start'])) . "</div>\n";
	print "<hr/>\n";
	print "<div>Server: " . $_SERVER['SERVER_SOFTWARE'] . "</div>\n";
	print "<hr/>\n";
	print "<div>User Agent: " . $_SERVER['HTTP_USER_AGENT'] . "</div>\n";
	print "</div>\n";
	print "<hr/>\n";
	print "<div class=\"splash\">\n";
	print "<div>\n";
	print "<p>Licence:</p>\n";
	print "<p>Object Relations: Flexible database for storing object relations</p>";
    print "<p>Copyright (C) 2012  Bruno Veldeman</p>";
    print "<p>This program is free software: you can redistribute it and/or modify ";
    print "it under the terms of the GNU General Public License as published by ";
    print "the Free Software Foundation, either version 3 of the License, or ";
    print "(at your option) any later version.</p>";
    print "<p>This program is distributed in the hope that it will be useful, ";
    print "but WITHOUT ANY WARRANTY; without even the implied warranty of ";
    print "MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the ";
    print "GNU General Public License for more details.</p>";

    print "<p>You should have received a copy of the GNU General Public License ";
    print "along with this program.  If not, see http://www.gnu.org/licenses/.</p>";
	print "</div>\n";
	print "</div>\n";
	print "</div>\n";
}

/*******************************************************************/
/* function                                                        */
/*   showsplash                                                    */
/*******************************************************************/
//
// Description:
//   Show the splash screen
// Inputs: 
//   function:
//	   ():
//       none
//   POST:
//	   none
//   GET:
//	   none
// Output:
//   return:
//     none
//   HTML:
//     static html
//   MYSQL:
//     none
//   GET:
//     none
//   POST:
//     none
//
// Security checks:
//   ?
//
// Security risk:
//   User should not see PHP version or Server software version. Hide for normal users.
/********************************************************************/
function showsplash() {
	print "<div style=\"margin: 0px auto;background-image: url(images/logo.svg); height: 350px; width: 500px;\">\n";
	print "</div>\n";
}

/*******************************************************************/
/* function                                                        */
/*   createthumbnail                                               */
/*******************************************************************/
//
// Description:
//   Generate a thumbnail image from image and store on filesystem
// Inputs: 
//   function:
//	   ():
//       $imagefile : path and filename of image
//   POST:
//	   none
//   GET:
//	   none
// Output:
//   return:
//     none
//   HTML:
//     static html
//   MYSQL:
//     none
//   GET:
//     none
//   POST:
//     none
//
// Security checks:
//   ?
//
// Security risk:
//   ?
/********************************************************************/
function createthumbnail($imagefile)
{
	// Get image an retreive size
	$filepath = getfilepath();
	$srcfile = $filepath . $imagefile;
	$image = imagecreatefromjpeg( $srcfile );
	$width = imagesx( $image );
	$height = imagesy( $image );
	// Calculate the new size
	$nheight = 100;
	$nwidth = floor( $width * ( 100 / $height ) );
	// Gererate the image
	$tmp_image = imagecreatetruecolor( $nwidth, $nheight );
	// Resize
	imagecopyresized( $tmp_image, $image, 0, 0, 0, 0, $nwidth, $nheight, $width, $height );
	// And save
	$destfile = $filepath . "thumb_" . $imagefile;
	imagejpeg( $tmp_image, $destfile );

}

/*******************************************************************/
/* function                                                        */
/*   dateformat                                                    */
/*******************************************************************/
//
// Description:
//   Print the date in the desired format
// Inputs: 
//   function:
//	   ():
//       $imagefile : path and filename of image
//   POST:
//	   none
//   GET:
//	   none
// Output:
//   return:
//     none
//   HTML:
//     static html
//   MYSQL:
//     none
//   GET:
//     none
//   POST:
//     none
//
// Security checks:
//   ?
//
// Security risk:
//   ?
/********************************************************************/
function dateformat($timestamp)
{
	$date = date_create($timestamp);
	$format = getdatetimeformat();
	return (date_format($date, $format));
}	
?>