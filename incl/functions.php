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

/* Scrapbook

  Sanityzing user input:
    Whenever you embed a string within foreign code, you must escape it, according to the rules of that language.
    For example, if you embed a string in some SQL targeting MySql, you must escape the string with MySql's function for this purpose (mysql_real_escape_string).
    If you embed strings within HTML markup, you must escape it with htmlspecialchars. This means that every single echo or print statement should use htmlspecialchars.
    If you are going to embed strings (Such as arguments) to external commands, and call them with exec, then you must use escapeshellcmd and escapeshellarg.

  Use objects:
  

*/

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
	$nextrow = $id;
	$query = "SELECT id FROM $table WHERE id > $id AND deleted = 0 ORDER BY id ASC LIMIT 1";
	if ($result = mysql_query($query, $dbc)) { // Run the query.
		$row = mysql_fetch_array($result); // Retrieve the information.
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
	$prevrow = $id;
	$query = "SELECT id FROM $table WHERE id < $id AND deleted = 0 ORDER BY id DESC LIMIT 1";
	if ($result = mysql_query($query, $dbc)) { // Run the query.
		$row = mysql_fetch_array($result); // Retrieve the information.
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
//   ?
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
//   ?
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
//
// Security risk:
//   ?
/********************************************************************/
function getsearch($searchstr) {
	if ( $searchstr <> "" ) {
		$search = "&search=$searchstr";
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
		case 'reports':
			// Report
			print "<div class=\"menu\">";
			print "&raquo; Reports: ";
			print "</div>\n";
			break;
		case 'usermanagement':
		case 'listusers':
		case 'adduser':
		case 'listgroups':
		case 'addgroup':
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
	print "</div>\n";
	print "<hr/>\n";
	print "<div>\n";
	print "<h3>Version: " . getappver() . "</h3>\n";
	print "</div>\n";
	print "<hr/>\n";
	print "<h2>Flexible database to store object relations</h2>\n";
	print "<hr/>\n";
	print "<div class=\"splash\">\n";
	print "<div>Programming language: PHP</div>\n";
	print "<div>Database engine: MySQL</div>\n";
	print "<div>Uses jQuery (GNU General Public License (GPL) Version 2) and LighBox2 (Some license I  could not find)</div>\n";
	print "</div>\n";
	print "<hr/>\n";
	print "<div class=\"splash\">\n";
	print "<div>PHP Version: " . phpversion() . "</div>\n";
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
	print "<div class=\"splash\">\n";
	print "<div style=\"margin: 0px auto;background-image: url(images/logo.svg); height: 350px; width: 500px;\">\n";
	//print "<img src=\"images/logo.svg\" />\n";
	print "</div>\n";
	print "</div>\n";
}

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
/*   listobjects                                                      */
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
	if ($result = mysql_query($query, $dbc)) {
		$rows = mysql_num_rows($result);
		print '<table>';
		print "\n<tr>\n";
		print "<th width=\"30px\"><a href=\"index.php?command=listobjects&sort=objectid&order=" . getorder("objectid", $sort, $order) . getsearch($searchstr) . "\">Id" . getorderarrow("objectid", $sort, $order) . "</a></th>\n";
		print "<th width=\"120px\"><a href=\"index.php?command=listobjects&sort=objectname&order=" . getorder("objectname", $sort, $order) . getsearch($searchstr) . "\">Name" . getorderarrow("objectname", $sort, $order) . "</a></th>\n";
		print "<th><a href=\"index.php?command=listobjects&sort=objectdescription&order=" . getorder("objectdescription", $sort, $order). getsearch($searchstr)  . "\">Description" . getorderarrow("objectdescription", $sort, $order) . "</a></th>\n";	
		print "<th><a href=\"index.php?command=listobjects&sort=typename&order=" . getorder("typename", $sort, $order) . getsearch($searchstr) . "\">Type" . getorderarrow("typename", $sort, $order) . "</a></th>\n";	
		print "<th><a href=\"index.php?command=listobjects&sort=objecttimestamp&order=" . getorder("objecttimestamp", $sort, $order) . getsearch($searchstr) . "\">Create Date" . getorderarrow("objecttimestamp", $sort, $order) . "</a></th>\n";
		print "<th></th>\n";
		print "</tr>\n";

		// Retrieve the returned records:
		while ($row = mysql_fetch_array($result)) {
		
			// Print the record:
			print "<tr onClick=\"document.location.href='index.php?command=viewobject&objectid={$row['objectid']}';\" style=\"cursor:pointer;\">\n";
			print "<td>" . htmlspecialchars($row['objectid']) . "</td>\n";
			print "<td>" . htmlspecialchars($row['objectname']) . "</td>\n";
			print "<td>" . htmlspecialchars(substr( $row['objectdescription'], 0, 60 )) . "</td>\n";
			print "<td>" . htmlspecialchars(substr( $row['typename'], 0, 25 )) . "</td>\n";
			print "<td>" . htmlspecialchars($row['objecttimestamp']) . "\n";
			print "</td>\n";			
			print "<td>\n";
			print "<form action=\"index.php?command=editobject&objectid=" . htmlspecialchars($row['objectid']) . "\" method=\"post\">\n";
			print "<input class=\"edit\" type=\"submit\" name=\"submit\" value=\"Edit\" />\n";
			print "</form>\n";
			print "</td>\n";			
			print "</tr>\n";
		} // End of while loop.
	print "</table>\n";
	print "<div class=\"result\">$rows rows returned</div>\n";
	if ( isset($_POST['search']) ) {
		print "<p id =\"fade\" class=\"info\">Found " . mysql_num_rows($result) . " result(s) matching \"" . $searchstr . "\".</p>\n";
	}
	} else 
	{ // Query didn't run.
		print '<p id="clickme" class="error">Could not retrieve the data because:<br />' . mysql_error($dbc) . '. The query being run was: ' . $query . '</p>';
	} // End of query IF.
}

/*******************************************************************/
/* function                                                        */
/*   ?                                                      */
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
function getobjectid($dbc)
{
	$objectid = "1";
	foreach ($_GET as $varname => $varvalue) {
		if ($varname == 'objectid') {
	 		$objectid = $varvalue;
	 		$query = "SELECT object.id FROM object WHERE object.id=$objectid";
	 		$result = mysql_query($query);
	 		if ( !mysql_num_rows($result) ) 
	 		{
		 		$objectid = "1";
	 		}
 		}
	}
	return($objectid);
}


/*******************************************************************/
/* function                                                        */
/*   ?                                                      */
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
function viewobject($dbc)
{
	if (isset($_GET['objectid']) && is_numeric($_GET['objectid']) && ($_GET['objectid'] > 0) ) { // Display the entry in a form:

		$query = "SELECT  object.id as objectid, object.name as objectname, object.description as objectdescription, object.type_id as typeid, type.name as typename FROM object JOIN type ON object.type_id = type.id WHERE object.id={$_GET['objectid']}";
		if ($result = mysql_query($query, $dbc)) { // Run the query.
		
			$row = mysql_fetch_array($result); // Retrieve the information.
			// Show Details:
			print "<table>\n";
			print "<th class=\"object\" colspan=\"3\">Object</th>\n";
			print "<tr>\n";
			print "<td class=\"object\" width=\"120px\">Name : </td>\n";
			print "<td class=\"object\">" . htmlspecialchars($row['objectname']) . "</td>\n";
			print "</tr>\n";
			print "<tr>\n";
			print "<td class=\"object\">Description : </td>\n";
			print "<td class=\"object\">" . nl2br(htmlspecialchars($row['objectdescription'])) . "</td>\n";
						print "</tr>\n";
			print "<tr>\n";
			print "<td class=\"object\">Type :  </td>\n";
			print "<td class=\"object\">" . htmlspecialchars($row['typename']) . "</td>\n";
			print "</tr>\n";
			print "</table>\n";
			// Show Properties:
			$query = "SELECT property.name as propertyname, property.type as propertytype, object_property.id as objectpropertyid, object_property.shared as objectpropertyshared FROM object_property JOIN property ON object_property.property_id = property.id WHERE object_property.object_id={$_GET['objectid']} ORDER BY property.name ASC";
			if ($result = mysql_query($query, $dbc)) { // Run the query.
				if ( mysql_num_rows($result) > 0 ) {
					print "<table class=\"properties\">\n";
					print "<th class=\"properties\" colspan=\"2\">Properties</th>";
				
					while ( $row = mysql_fetch_array($result) ) {
						print "<tr>\n";
						print "<td class=\"properties\" width=\"120px\">" . htmlspecialchars($row['propertyname']);
						if ($row['objectpropertyshared']) {
							print " &#42;";
						}
						print "</td>\n";
						print "<td class=\"properties\">\n";
						getpropertydata($dbc, $row['propertytype'], $row['objectpropertyid']);
						print "</td>\n";								
						print "</tr>\n";
					}
					print "</table>\n";
				}
			} else {
				print '<p id="clickme" class="error">Could not retrieve the property because:<br />' . mysql_error($dbc) . '. The query being run was: ' . $query . '</p>';
			}
			
			// Show relations:
			$query = "SELECT object1.name as objectname1, object2.name as objectname2, object1.id as objectid1, object2.id as objectid2, relation.name as relationname, relation.description as relationdescription , relation.unidirectional as unidirectional " .
					  "FROM object_relation " .
					  "JOIN relation ON object_relation.relation_id = relation.id " .
					  "JOIN object object2 ON object2.id = object_relation.object2_id " .
					  "JOIN object object1 ON object1.id = object_relation.object1_id " .
					  "WHERE object_relation.object1_id = {$_GET['objectid']} OR object_relation.object2_id = {$_GET['objectid']} ORDER BY relation.name";
			if ($result = mysql_query($query, $dbc)) { // Run the query.
				if ( mysql_num_rows($result) > 0 ) {
					print "<table class=\"relations\">\n";
					print "<th class=\"relations\">Relation</th>\n";
					print "<th class=\"relations\">Related object</th>\n";

					while ( $row = mysql_fetch_array($result) ) {
						print "<tr>\n";
						print "<td class=\"relations\" width=\"120px\"> ". htmlspecialchars($row['relationname']);
						print "<td class=\"relations\"> <a href=\"index.php?command=viewobject&objectid=" . htmlspecialchars($row['objectid1']) . "\">" . htmlspecialchars($row['objectname1']) . "</a> " . htmlspecialchars($row['relationdescription']) . " <a href=\"index.php?command=viewobject&objectid=" . htmlspecialchars($row['objectid2']) . "\">" . htmlspecialchars($row['objectname2']) . "</a></td>\n";
						print "</tr>\n";
					}
					print "</table>\n";
				}
			print "<div class=\"result\">&#42; shared property</div>\n";
			} else {
				print '<p id="clickme" class="error">Could not retrieve the object because:<br />' . mysql_error($dbc) . '. The query being run was: ' . $query . '</p>';
			}
			
		} else { // Couldn't get the information.
			print '<p id="clickme" class="error">Could not retrieve the relation because:<br />' . mysql_error($dbc) . '. The query being run was: ' . $query . '</p>';
		}
	
	} else { // No ID set.
		print '<p id="clickme" class="error">This page has been accessed in error.</p>';
	} // End of main IF.
}


/*******************************************************************/
/* function                                                        */
/*   ?                                                      */
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
function addobject($dbc)
{
	$error = false;
	if ($_SERVER['REQUEST_METHOD'] == 'POST') 
	{ // Handle the form
		if ( !empty($_POST['name']) && !empty($_POST['description']) ) {

						// Prepare the values for storing:
			$name = mysql_real_escape_string(trim(strip_tags($_POST['name'])), $dbc);
			$description = mysql_real_escape_string(trim(strip_tags($_POST['description'])), $dbc);
			$type = mysql_real_escape_string(trim(strip_tags($_POST['type'])), $dbc);
		
			$query = "INSERT INTO object (name, description, type_id ) VALUES ('$name', '$description', '$type')";
			$result = mysql_query($query, $dbc);
			
			if (mysql_affected_rows($dbc) == 1){
				// Print a message:
				$usedid = mysql_insert_id();
				print '<p id="fade" class="info">Object has been saved with id=' . $usedid . '.</p>';
			} else {
				print '<p id="clickme" class="error">Could not store the object because:<br />' . mysql_error($dbc) . '. The query being run was: ' . $query . '</p>';
			}
			
		} else { // Failed to enter data.
			print '<p id="clickme" class="error">Please enter a name and a description!</p>';
			$error=true;
		}
		listobjects($dbc);
	} elseif (!$error)
	{	// Show the form
		print "<form action=\"index.php?command=addobject\" method=\"post\">\n";
		print "<p><label>Name <input type=\"text\" name=\"name\" /></label></p>";
		print "<p><label>Description <textarea name=\"description\" rows=\"5\" cols=\"70\"></textarea></label></p>";
		
		// Define query for type
		$query = "SELECT  id, name FROM type ORDER BY id ASC";
		if ($result = mysql_query($query, $dbc)) { // Run the query.
			print '<p><label>Type <select name="type">' . "\n";
			while ($row = mysql_fetch_array($result)) {
			print '<option';
			print ' value ="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['name']) . '</option>' ."\n";
			} 
		} else { // Couldn't get the information.
			print '<p id="clickme" class="error">Could not retrieve the object because:<br />' . mysql_error($dbc) . '. The query being run was: ' . $query . '</p>';
		}
		print "</select>\n";
		print "</label></p>\n";
		print "<p><input class=\"save\" type=\"submit\" name=\"submit\" value=\"Save\" /></p>\n";
		print "</form>\n";
	}
}


/*******************************************************************/
/* function                                                        */
/*   ?                                                      */
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
function addmobject($dbc)
{
	$error = false;
	if ($_SERVER['REQUEST_METHOD'] == 'POST') 
	{ // Handle the form
		if ( !empty($_POST['name']) && !empty($_POST['description']) ) {

						// Prepare the values for storing:
			$name = mysql_real_escape_string(trim(strip_tags($_POST['name'])), $dbc);
			$description = mysql_real_escape_string(trim(strip_tags($_POST['description'])), $dbc);
			$type = mysql_real_escape_string(trim(strip_tags($_POST['type'])), $dbc);
		
			$query = "INSERT INTO object (name, description, type_id ) VALUES ('$name', '$description', '$type')";
			$result = mysql_query($query, $dbc);
			
			if (mysql_affected_rows($dbc) == 1){
				// Print a message:
				$usedid = mysql_insert_id();
				print '<p id="fade" class="info">Object has been saved.</p>';
			} else {
				print '<p id="clickme" class="error">Could not store the object because:<br />' . mysql_error($dbc) . '. The query being run was: ' . $query . '</p>';
			}
			
		} else { // Failed to enter data.
			print '<p id="clickme" class="error">Please enter a name and a description!</p>';
			$error=true;
		}
	}
	if (!$error)
	{	// Show the form
		print "<form action=\"index.php?command=addmobject\" method=\"post\">\n";
		print "<p><label>Name <input type=\"text\" name=\"name\" /></label></p>";
		print "<p><label>Description <textarea name=\"description\" rows=\"5\" cols=\"70\"></textarea></label></p>";
		
		// Define query for type
		$query = "SELECT  id, name FROM type ORDER BY id ASC";
		if ($result = mysql_query($query, $dbc)) { // Run the query.
			print '<p><label>Type <select name="type">' . "\n";
			while ($row = mysql_fetch_array($result)) {
			print '<option';
			print ' value ="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['name']) . '</option>' ."\n";
			} 
		} else { // Couldn't get the information.
			print '<p id="clickme" class="error">Could not retrieve the object because:<br />' . mysql_error($dbc) . '. The query being run was: ' . $query . '</p>';
		}
		print "</select>\n";
		print "</label></p>\n";
		print "<p><input class=\"save\" type=\"submit\" name=\"submit\" value=\"Save and next\" /></p>\n";
		print "</form>\n";
	}
}


/*******************************************************************/
/* function                                                        */
/*   ?                                                      */
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
function editobject($dbc)
{
	$error = false;
	if (isset($_POST['objectid']) && is_numeric($_POST['objectid']) && ($_POST['objectid'] > 0)) { // Handle the form.

		// Validate and secure the form data:
		if ( !empty($_POST['name']) && !empty($_POST['description']) && !empty($_POST['type']) ) {
	
			// Prepare the values for storing:
			$name = mysql_real_escape_string(trim(strip_tags($_POST['name'])), $dbc);
			$description = mysql_real_escape_string(trim(strip_tags($_POST['description'])), $dbc);
			$type = mysql_real_escape_string(trim(strip_tags($_POST['type'])), $dbc);
			// Define the query.
			$query = "UPDATE object SET name='$name', description='$description', type_id='$type'  WHERE id={$_POST['objectid']}";
			if ($result = mysql_query($query, $dbc)) {
				print '<p id="fade" class="info">The object has been updated.</p>';
			} else {
				print '<p id="clickme" class="error">Could not update the object because:<br />' . mysql_error($dbc) . '. The query being run was: ' . $query . '</p>';
				$error = true;
			}	
		} // No problem!
	}
	
	if ( isset($_GET['objectid']) && is_numeric($_GET['objectid']) && ($_GET['objectid'] > 0) ) { // Display the entry in a form:

		$query = "SELECT  object.id as objectid, object.name as objectname, object.description as objectdescription, object.type_id as typeid, type.name as typename FROM object JOIN type ON object.type_id = type.id WHERE object.id={$_GET['objectid']}";
		if ($result = mysql_query($query, $dbc)) { // Run the query.
		
			$row = mysql_fetch_array($result); // Retrieve the information.
			$typeid = $row['typeid'];
			// Make the form:
			print "<form action=\"index.php?command=editobject&objectid=" . $_GET['objectid'] . "\" method=\"post\">";
			print "<table>";
			print "<th colspan=\"2\" class=\"object\">";
			print "Object";
			print "<input class=\"save\" type=\"submit\" name=\"submit\" value=\"Save Object\"/>\n";
			print "</th>\n";
			print "<tr>\n";
			print "<td class=\"object\" width=\"120px\">Name :</td>\n";
			print "<td class=\"object\"><input class=\"field\" type=\"text\" name=\"name\" value=\"" . htmlspecialchars($row['objectname']) . "\" /></td>";
			print "</tr>\n";
			print "<tr>\n";
			print "<td class=\"object\">Description :</td>\n";
			// Check how many lines in the string and adjust the textarea accordingly
			$lines=explode("\n",htmlentities($row['objectdescription']));
			$rowcount = count($lines);
			print "<td class=\"object\"><textarea class=\"field\" name=\"description\" rows=\"$rowcount\">" . htmlspecialchars($row['objectdescription']) . "</textarea></td>\n";
			print "</tr>\n";
			// Define query for type
			$query = "SELECT  id, name FROM type ORDER BY id ASC";
			if ($result = mysql_query($query, $dbc)) { // Run the query.
				print "<tr>\n";
				print "<td class=\"object\">Type :</td>\n";
				print "<td class=\"object\"><select width=\"100px\" class=\"field\" name=\"type\">\n";
				while ($row = mysql_fetch_array($result)) {
				print '<option';
				if ( $row['id'] == $typeid ) {
					print ' selected="true"';
			}
				print ' value ="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['name']) . '</option>' ."\n";
				} 
			} else { // Couldn't get the information.
				print '<p id="clickme" class="error">Could not retrieve the object because:<br />' . mysql_error($dbc) . '. The query being run was: ' . $query . '</p>';
			}
			print "</select>\n";
			print "</td>\n";
			print "</tr>\n";
			print "</table>\n";
			print '<input type="hidden" name="objectid" value="' . $_GET['objectid'] . '" />';
			print '</form>';
	
			// Show Properties:
			print "<table class=\"properties\">";
			print "<th width=\"120px\" class=\"properties\">Properties</th>\n";
			print "<th>\n";
			print "<form action=\"index.php?command=addobjectproperty&objectid=" . $_GET['objectid'] . "\" method=\"post\">\n";
			print '<input type="hidden" name="objectid" value="' . $_GET['objectid'] . '" />' . "\n";
			// Define query for property
			$query = "SELECT  id, name FROM property ORDER BY name ASC";
			if ($result = mysql_query($query, $dbc)) { // Run the query.
				print '<select class="edit" name="propertyid">' . "\n";
				while ($row = mysql_fetch_array($result)) {
				print '<option';
				print ' value ="' . $row['id'] . '">' . $row['name'] . '</option>' ."\n";
				} 
			} else { // Couldn't get the information.
				print '<p id="clickme" class="error">Could not retrieve the object because:<br />' . mysql_error($dbc) . '. The query being run was: ' . $query . '</p>';
				$error = true;
			}
			print "</select>\n";
			print '<input class="edit" type="submit" name="submit" value="Add" />';
			print '</form>';
			print "</th>";
			$query = "SELECT object_property.object_id, property.name as propertyname, object_property.id as objectpropertyid, property.type as propertytype, object_property.shared as objectpropertyshared FROM object_property JOIN property ON object_property.property_id = property.id WHERE object_property.object_id={$_GET['objectid']} ORDER BY property.name ASC";
			if ($result = mysql_query($query, $dbc)) { // Run the query.
			while ( $row = mysql_fetch_array($result) ) {
				print "<tr>\n";
				print "<td class=\"properties\">\n";
				print htmlentities($row['propertyname']);
				if ($row['objectpropertyshared']) {
					print " &#42;";
				}
				print "</td>\n";
				print "<td class=\"properties\">\n";		
				getpropertydata($dbc, $row['propertytype'], $row['objectpropertyid']);
				if (!$row['objectpropertyshared']) {
					print "<form style=\"float:right;\" action=\"index.php?command=deleteobjectproperty&objectpropertyid=" . $row['objectpropertyid'] . "&objectid=" . $_GET['objectid'] . "\" method=\"post\">";
					print '<input type="hidden" name="objectid" value="' . $_GET['objectid'] . '" />';
					print '<input type="hidden" name="propertytype" value="' . htmlspecialchars($row['propertytype']) . '" />';
					print '<input type="hidden" name="objectpropertyid" value="' . htmlspecialchars($row['objectpropertyid']) . '" />';
					print '<input type="hidden" name="objectpropertyshared" value="' . htmlspecialchars($row['objectpropertyshared']) . '" />';
					print "<input style=\"float:left;\" class=\"delete\" type=\"submit\" name=\"submit\" value=\"Delete\" />";
					print '</form>';
				}
				print "<form style=\"float:right;\" action=\"index.php?command=editobjectproperty&objectpropertyid=" . htmlspecialchars($row['objectpropertyid']) . "&objectid=" . $_GET['objectid'] . "\" method=\"post\">";
				print '<input type="hidden" name="objectid" value="' . $_GET['objectid'] . '" />';
				print '<input type="hidden" name="objectpropertyid" value="' . htmlspecialchars($row['objectpropertyid']) . '" />';
				print '<input class="save" type="submit" name="submit" value="Edit" />';
				print '</form>';
				print "</td>\n";
				print "</tr>\n";
				}
			} else {
				print '<p id="clickme" class="error">Could not retrieve the property because:<br />' . mysql_error($dbc) . '. The query being run was: ' . $query . '</p>';
				$error = true;
			}
			print "</table>\n";
			print "<table class=\"relations\">\n";
			print "<th class=\"relations\">Relation</th>\n";
			print "<th class=\"relations\">\n";
			// Listbox relations
			print "<form action=\"index.php?command=addobjectrelation&objectid=" . $_GET['objectid'] . "\" method=\"post\">\n";
			print '<input type="hidden" name="objectid" value="' . $_GET['objectid'] . '" />' . "\n";
			// Define query for relation
			$query = "SELECT  id, name FROM relation ORDER BY name ASC";
			if ($result = mysql_query($query, $dbc)) { // Run the query.
				print '<select class="edit" name="relationid">' . "\n";
				while ($row = mysql_fetch_array($result)) {
				print '<option';
				print ' value ="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['name']) . '</option>' ."\n";
				} 
			} else { // Couldn't get the information.
				print '<p id="clickme" class="error">Could not retrieve the relation because:<br />' . mysql_error($dbc) . '. The query being run was: ' . $query . '</p>';
				$error = true;
			}
			print "</select>\n";
			// Define query for objects
			$query = "SELECT  id, name FROM object WHERE id <> {$_GET['objectid']} ORDER BY name ASC";
			if ($result = mysql_query($query, $dbc)) { // Run the query.
				print '<select class="edit" name="robjectid">' . "\n";
				while ($row = mysql_fetch_array($result)) {
				print '<option';
				print ' value ="' . $row['id'] . '">' . $row['name'] . '</option>' ."\n";
				} 
			} else { // Couldn't get the information.
				print '<p id="clickme" class="error">Could not retrieve the object because:<br />' . mysql_error($dbc) . '. The query being run was: ' . $query . '</p>';
				$error = true;
			}
			print "</select>\n";
			print '<input class="edit" type="submit" name="submit" value="Add" />';
			print '</form>';
			print "</th>\n";
			// Show relations:
			$query = "SELECT object1.name as objectname1, object2.name as objectname2, object1.id as objectid1, object2.id as objectid2, relation.name as relationname, object_relation.id as objectrelationid, relation.description as relationdescription , relation.unidirectional as unidirectional, object_relation.id as objectrelationid " .
					  "FROM object_relation " .
					  "JOIN relation ON object_relation.relation_id = relation.id " .
					  "JOIN object object2 ON object2.id = object_relation.object2_id " .
					  "JOIN object object1 ON object1.id = object_relation.object1_id " .
					  "WHERE object_relation.object1_id = {$_GET['objectid']} OR object_relation.object2_id = {$_GET['objectid']} ORDER BY relation.name";
			if ($result = mysql_query($query, $dbc)) { // Run the query.
				while ( $row = mysql_fetch_array($result) ) {
					print "<tr>\n";
					print "<td class=\"relations\" width=\"120px\">";
					print htmlspecialchars($row['relationname']);
					print "</td>\n";
					print "<td class=\"relations\">\n";
					print "<form action=\"index.php?command=deleteobjectrelation&objectid=" . $_GET['objectid'] . "\" method=\"post\">";
					print "<a href=\"index.php?command=viewobject&objectid=" . htmlspecialchars($row['objectid1']) . "\">" . htmlspecialchars($row['objectname1']) . "</a> " . htmlspecialchars($row['relationdescription']) . " <a href=\"index.php?command=viewobject&objectid=" . htmlspecialchars($row['objectid2']) . "\">" . htmlspecialchars($row['objectname2']) . "</a>";
					print '<input type="hidden" name="objectid" value="' . $_GET['objectid'] . '" />';
					print '<input type="hidden" name="objectrelationid" value="' . htmlspecialchars($row['objectrelationid']) . '" /> ';
					print '<input style="float:right;" class="delete" type="submit" name="submit" value="Delete" />';
					print '</form>';
					print "</td>\n";
					print "</tr>\n";
				}
			print "</table>\n";
			} else {
				print '<p id="clickme" class="error">Could not retrieve the object because:<br />' . mysql_error($dbc) . '. The query being run was: ' . $query . '</p>';
			}
		print "<div class=\"result\">&#42; shared property</div>\n";	
		} else { // Couldn't get the information.
			print '<p class="error">Could not retrieve the object because:<br />' . mysql_error($dbc) . '. The query being run was: ' . $query . '</p>';
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
/*   ?                                                      */
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
function addobjectproperty($dbc)
{
	$error = false;
	if ( (!$error) && ($_SERVER['REQUEST_METHOD'] == 'POST') )
	{	// Show the form
		if ( isset($_GET['objectid']) && is_numeric($_GET['objectid']) && ($_GET['objectid'] > 0) && isset($_POST['propertyid']) ) { 
			// Define query for property
			$query = "SELECT  id, name, type FROM property WHERE id={$_POST['propertyid']} LIMIT 1";
			// Run the query
			if ($result = mysql_query($query, $dbc)) { 
				$row = mysql_fetch_array($result);
				}
			$propertytype = htmlspecialchars($row['type']);	
			// New property
			print "<p>Create new property</p>\n";
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
			print "<p>" . $row['name'] . ":";
			geteditpropertydata($dbc, $propertytype, 0);
			print "\n";
			print '<input type="hidden" name="objectid" value="' . $_GET['objectid'] . '" />';
			print '<input type="hidden" name="propertyid" value="' . $_POST['propertyid'] . '" />';
			print '<input type="hidden" name="propertytype" value="' . $propertytype . '" />';
			print '<input type="checkbox" name="newshared">Shared property';
			print "<p class=\"note\">(Shared properties can be used by several objects and editing them will change the value for every object)</p>\n";
			print '<input type="hidden" name="shared" value="0" />';
			print "<input class=\"save\" type=\"submit\" name=\"submit\" value=\"Save\" /></p>\n";
			print "</form>\n";
			// Use existing property
			print "<p>Use existing property</p>\n";
			print "<form action=\"index.php?command=storeobjectproperty&id=" . $_GET['objectid'] . "\" method=\"post\"";
			print ">\n";
			print "<p>" . $row['name'] . ":";
			print "\n";
			print "<select name=\"propertydata\">\n";
			$query = "SELECT object_property.id as objectpropertyid, object_property.property_id as objectpropertypropertyid, object_property.object_id as objectpropertyobjectid, object.name as objectname, property.name as propertyname FROM object_property JOIN object ON object.id = object_property.object_id JOIN property ON property.id = object_property.property_id WHERE object_property.property_id={$_POST['propertyid']} AND object_property.shared = 1 AND object_property.object_id <> {$_POST['objectid']} ORDER BY object.name";
			// Run the query
			if ($result = mysql_query($query, $dbc)) { 
				while ($row = mysql_fetch_array($result)) {
					print "<option value=\"" . htmlspecialchars($row['objectpropertyid']) . "\">" . htmlspecialchars($row['propertyname']) . " from \"" . htmlspecialchars($row['objectname']) . "\"</option>\n";
				};
			}
			print "</select>\n";
			print '<input type="hidden" name="objectid" value="' . $_GET['objectid'] . '" />';
			print '<input type="hidden" name="propertyid" value="' . $_POST['propertyid'] . '" />';
			print '<input type="hidden" name="propertytype" value="' . $propertytype . '" />';
			print '<input type="hidden" name="shared" value="1" />';
			if ( mysql_num_rows($result) < 1 ) {
				print " No shared properties of this type\n";
			} else {
				print "<input class=\"save\" type=\"submit\" name=\"submit\" value=\"Save\" /></p>\n";
			}
			
			print "</form>\n";
		}
	}
}


/*******************************************************************/
/* function                                                        */
/*   ?                                                      */
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
function editobjectproperty($dbc)
{
	$error = false;
	if ( (!$error) && ($_SERVER['REQUEST_METHOD'] == 'POST') )
	{	// Show the form
		if ( isset($_GET['objectpropertyid']) && is_numeric($_GET['objectpropertyid']) && ($_GET['objectpropertyid'] > 0) && isset($_GET['objectid'])   ) { 
			// Define query for property
			$query = "SELECT object_property.object_id, property.name as propertyname, object_property.id as objectpropertyid, object_property.shared as objectpropertyshared, property.type as propertytype FROM object_property JOIN property ON object_property.property_id = property.id WHERE object_property.id={$_GET['objectpropertyid']}";
			// Run the query
			if ($result = mysql_query($query, $dbc)) { 
				$row = mysql_fetch_array($result);
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
			print "<p>" . $row['propertyname'] . ":";
			if ($row['objectpropertyshared']) {
				print " &#8853; (Shared property)";
			}

			geteditpropertydata($dbc, $row['propertytype'], $_POST['objectpropertyid']);
			print '<input type="hidden" name="objectid" value="' . $_GET['objectid'] . '" />';
			print '<input type="hidden" name="objectpropertyid" value="' . $_POST['objectpropertyid'] . '" />';
			print '<input type="hidden" name="propertytype" value="' . $propertytype . '" />';
			print "<input class=\"save\"type=\"submit\" name=\"submit\" value=\"Save\" /></p>\n";
			print "</form>\n";
			// Used by these objects if shared
			if ($row['objectpropertyshared']) {
				print "<p class=\"note\">Changing it will change the property for all objects that share it</p>\n";
				print "<table>\n";
				print "<th>Shared by these objects</th>";
				$query = "SELECT object.name as objectname, object.id as objectid FROM object_property JOIN object ON object.id = object_property.object_id JOIN property ON property.id = object_property.property_id WHERE object_property.id={$_GET['objectpropertyid']} AND object_property.shared = 1";
				// Run the query
				if ($result = mysql_query($query, $dbc)) { 
					while ($row = mysql_fetch_array($result)) {
						print "<tr><td><a href=\"index.php?command=viewobject&id=" . htmlspecialchars($row['objectid']) . "\">" . htmlspecialchars($row['objectname']) . "</a></td></tr>\n";
					};
				}
				print "</table>\n";
				if ( mysql_num_rows($result) == 1 ) {
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
/*   ?                                                      */
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
function storeobjectproperty($dbc)
{
	$error = false;
	if ( ($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_GET['objectid']) && isset($_POST['propertyid'])) 
	{ // Handle the form
		if ( !empty($_POST['propertyid']) && !empty($_POST['objectid']) && !empty($_POST['propertydata']) && !empty($_POST['propertytype']) && isset($_POST['shared'])  ) {

			// Prepare the values for storing:
			$data = mysql_real_escape_string(trim(strip_tags($_POST['propertydata'])), $dbc);
			$objectid = mysql_real_escape_string(trim(strip_tags($_POST['objectid'])), $dbc);
			$propertyid = mysql_real_escape_string(trim(strip_tags($_POST['propertyid'])), $dbc);
			$propertytype = mysql_real_escape_string(trim(strip_tags($_POST['propertytype'])), $dbc);
			if (isset($_POST['shared'])) {
				$shared = mysql_real_escape_string(trim(strip_tags($_POST['shared'])), $dbc);
			} else {
				$shared = 0;
			}
			if (isset($_POST['newshared']) && $_POST['newshared'] == 'on') {
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
			
			$result = mysql_query($query, $dbc);
			// Get the ID used in object_property table
			$usedid = mysql_insert_id();
			if ( !mysql_affected_rows($dbc) == 1){
				$error = true;
			}
			// Insert the data into object_property_data_text table if not shared
			if ($_POST['shared'] == 0) {
				switch ( $propertytype )
				{
					case 1:
					case 2:
					case 3:
						$query = "INSERT INTO object_property_data_text (object_property_id, data) VALUES ('$usedid', '$data')";
						$result = mysql_query($query, $dbc);
						if(!mysql_affected_rows($dbc) == 1){
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
						$query = "INSERT INTO object_property_data_binary (object_property_id, data, filename, filesize, filetype) " .
								"VALUES ('$usedid', '$filedata', '$filename', '$filesize', '$filetype')";
						$result = mysql_query($query, $dbc);
						if(!mysql_affected_rows($dbc) == 1){
							$error=true;;
						}
						break;
					case 13:
					case 14:
						$filename = $_FILES["uploadfile"]["name"];
						$filetype = $_FILES["uploadfile"]["type"];
						$filesize = $_FILES["uploadfile"]["size"];
						$file = $_FILES["uploadfile"]["tmp_name"];
						$filepath = getfilepath() . "file" . $usedid . "." . pathinfo($filename, PATHINFO_EXTENSION);
						if(!(move_uploaded_file($_FILES['uploadfile']['tmp_name'], $filepath))) {
	 						$error = true;
						}
						if($error == false) {
							$query = "INSERT INTO object_property_data_binary (object_property_id, data, filename, filesize, filetype) " .
								"VALUES ('$usedid', '$filepath', '$filename', '$filesize', '$filetype')";
							$result = mysql_query($query, $dbc);
							if(!mysql_affected_rows($dbc) == 1){
								$error=true;;					
							}
						}
						break;
				}
			}
			
			if ( !$error ) {
				print '<p id="fade" class="info">Object property has been saved with id=' . $usedid . '.</p>';
			} else {
				print '<p id="clickme" class="error">Could not store the object property because:<br />' . mysql_error($dbc) . '. The query being run was: ' . $query . '</p>';
			}
			
		} else { // Failed to enter data.
			print '<p class="error">Please enter some data!</p>';
			$error=true;
		}
		editobject($dbc);
		//header( 'Location: index.php?command=editobject&id=' . $_GET['id'] );
	} 
}


/*******************************************************************/
/* function                                                        */
/*   ?                                                      */
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
function updateobjectproperty($dbc)
{
	$error = false;
	if ( ($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_GET['objectpropertyid']) && isset($_POST['objectpropertyid'])) 
	{ // Handle the form
		if ( !empty($_POST['objectpropertyid']) && !empty($_POST['objectid']) && !empty($_POST['propertydata']) && !empty($_POST['propertytype']) ) {

			// Prepare the values for storing:
			$data = mysql_real_escape_string(trim(strip_tags($_POST['propertydata'])), $dbc);
			$objectid = mysql_real_escape_string(trim(strip_tags($_POST['objectid'])), $dbc);
			//$propertyid = mysql_real_escape_string(trim(strip_tags($_POST['propertyid'])), $dbc);
			$propertytype = mysql_real_escape_string(trim(strip_tags($_POST['propertytype'])), $dbc);
			$objectpropertyid = ($_GET['objectpropertyid']);
			
			switch ( $propertytype )
			{
				case 1:
				case 2:
				case 3:
					$query = "UPDATE object_property_data_text SET data='$data' WHERE object_property_id={$_GET['objectpropertyid']}";
					$result = mysql_query($query, $dbc);
					if(!mysql_affected_rows($dbc) == 1){
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
					$result = mysql_query($query, $dbc);
					if(!mysql_affected_rows($dbc) == 1){
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
					if ($result = mysql_query($query, $dbc)) { // Run the query.
						if ( mysql_num_rows($result) > 0 ) {
							$row = mysql_fetch_array($result);
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
						$result = mysql_query($query, $dbc);
						if ( !mysql_affected_rows($dbc) == 1){
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
				print '<p id="clickme" class="error">Could not store the object property because:<br />' . mysql_error($dbc) . '. The query being run was: ' . $query . '</p>';
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
/*   ?                                                      */
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
			if ($result = mysql_query($query, $dbc)) { // Run the query.
				if ( mysql_num_rows($result) > 0 ) {
					while ( $row = mysql_fetch_array($result) ) {
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
			if ($result = mysql_query($query, $dbc)) { // Run the query.
				if ( mysql_num_rows($result) > 0 ) {
					while ( $row = mysql_fetch_array($result) ) {
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
			if ($result = mysql_query($query, $dbc)) { // Run the query.
				if ( mysql_num_rows($result) > 0 ) {
					while ( $row = mysql_fetch_array($result) ) {
  						print "[Filename: " . htmlspecialchars($row['filename']) . "]";
						//print "[Filetype: {$row['filetype']}] ";
						//print "[Filesize: {$row['filesize']}]</div>\n";
					}
				} else {
					print "<p id=\"clickme\" class=\"error\">Property data not found</p>\n";
				}
			}
		break;
		case 12:
			// Image in database
			$query = "SELECT object_property_data_binary.filetype as filetype, object_property_data_binary.filename as filename, object_property_data_binary.filesize as filesize FROM object_property_data_binary WHERE object_property_data_binary.object_property_id = $objectpropertyid";
			if ($result = mysql_query($query, $dbc)) { // Run the query.
				if ( mysql_num_rows($result) > 0 ) {
					$row = mysql_fetch_array($result);
					print "<a href=\"rend/showpropimg.php?command=show&id=$objectpropertyid\" rel=\"lightbox[propertygalery]\" title=\"{$row['filename']}\"><img alt=\"Image\" src=\"rend/showpropimg.php?command=show&id=$objectpropertyid\" width=\"200\"/></a>\n";
  					print "[Filename: " . htmlspecialchars($row['filename']) . "]";
					//print "[Filetype: {$row['filetype']}] ";
					//print "[Filesize: {$row['filesize']}]</div>\n";
				} else {
					print "<p id=\"clickme\" class=\"error\">Property data not found</p>\n";
				}
			}
			break;
		case 13:
			// File on filesystem
			$query = "SELECT object_property_data_binary.data as filedata, object_property_data_binary.filetype as filetype, object_property_data_binary.filename as filename, object_property_data_binary.filesize as filesize FROM object_property_data_binary WHERE object_property_data_binary.object_property_id = $objectpropertyid";
			if ($result = mysql_query($query, $dbc)) { // Run the query.
				if ( mysql_num_rows($result) > 0 ) {
					$row = mysql_fetch_array($result);
					print "<a href=\"" . htmlspecialchars($row['filedata']) . "\" target=\"_blank\">Download</a>\n";
  					print "[Filename: " . htmlspecialchars($row['filename']) . "]";
					//print "[Filetype: {$row['filetype']}] ";
					//print "[Filesize: {$row['filesize']}]</div>\n";
				} else {
					print "<p id=\"clickme\" class=\"error\">Property data not found</p>\n";
				}
			}
		break;
		case 14:
			// Image on filesystem
			$query = "SELECT object_property_data_binary.data as filedata, object_property_data_binary.filetype as filetype, object_property_data_binary.filename as filename, object_property_data_binary.filesize as filesize FROM object_property_data_binary WHERE object_property_data_binary.object_property_id = $objectpropertyid";
			if ($result = mysql_query($query, $dbc)) { // Run the query.
				if ( mysql_num_rows($result) > 0 ) {
					$row = mysql_fetch_array($result);
					print "<a href=\"" . htmlspecialchars($row['filedata']) . "\" rel=\"lightbox[propertygalery]\" title=\"" . htmlspecialchars($row['filename']) . "\"><img alt=\"Image\" src=\"" . htmlspecialchars($row['filedata']) . "\" width=\"200\"/></a>\n";
  					print "[Filename: " . $row['filename'] . "]";
					//print "[Filetype: {$row['filetype']}] ";
					//print "[Filesize: {$row['filesize']}]</div>\n";
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
/*   ?                                                      */
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
				if ($result = mysql_query($query, $dbc)) { // Run the query.
					if ( mysql_num_rows($result) > 0 ) {
						$row = mysql_fetch_array($result);
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
				if ($result = mysql_query($query, $dbc)) { // Run the query.
					if ( mysql_num_rows($result) > 0 ) {
						$row = mysql_fetch_array($result);
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
				print "<a href=\"rend/showpropimg.php?command=show&id=$objectpropertyid\" target=\"_blank\"><img alt=\"Image\" src=\"rend/showpropimg.php?command=show&id=$objectpropertyid\" width=\"200\"/></a>\n";
				$query = "SELECT object_property_data_binary.filetype as filetype, object_property_data_binary.filename as filename, object_property_data_binary.filesize as filesize FROM object_property_data_binary WHERE object_property_data_binary.object_property_id = $objectpropertyid";
				if ($result = mysql_query($query, $dbc)) { // Run the query.
					if ( mysql_num_rows($result) > 0 ) {
						$row = mysql_fetch_array($result);
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
				if ($result = mysql_query($query, $dbc)) { // Run the query.
					if ( mysql_num_rows($result) > 0 ) {
						$row = mysql_fetch_array($result);
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
				if ($result = mysql_query($query, $dbc)) { // Run the query.
					if ( mysql_num_rows($result) > 0 ) {
						$row = mysql_fetch_array($result);
						print "<a href=\"{$row['filedata']}\" target=\"_blank\"><img alt=\"Image\" src=\"" . htmlspecialchars($row['filedata']) . "\" width=\"200\"/></a>\n";
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
/*   ?                                                      */
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
function deleteobjectproperty($dbc)
{
	$error = false;
	if ( ($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_GET['objectpropertyid']) && isset($_POST['objectpropertyid'])) 
	{ // Handle the form
		if ( !empty($_POST['objectpropertyid']) && !empty($_POST['propertytype']) ) {

			$propertytype = mysql_real_escape_string(trim(strip_tags($_POST['propertytype'])), $dbc);
			
			// delete the data from object_property_data_text or binary table
			
			switch ( $propertytype )
			{
				case 1:
				case 2:
				case 3:
					$query = "DELETE FROM object_property_data_text WHERE object_property_id={$_GET['objectpropertyid']}";
					$result = mysql_query($query, $dbc);
					if ( !mysql_affected_rows($dbc) == 1){
						$error = true;
					}
					break;
				case 11:
				case 12:
					$query = "DELETE FROM object_property_data_binary WHERE object_property_id={$_GET['objectpropertyid']}";
					$result = mysql_query($query, $dbc);
					if ( !mysql_affected_rows($dbc) == 1){
						$error = true;
					}
					break;
			}
			$query = "DELETE FROM object_property WHERE id={$_GET['objectpropertyid']}";
			$result = mysql_query($query, $dbc);
				if ( !mysql_affected_rows($dbc) == 1){
					$error = true;
				}
			if ( !$error ) {
				print '<p id="fade" class="info">Object property has been deleted</p>';
			} else {
				print '<p id="clickme" class="error">Could not delete the object property because:</ br>' . mysql_error($dbc) . '.The query being run was: ' . $query . '</p>';
			}
			
		} else { // Failed to enter data.
			print '<p class="error">Something went wrong!</p>';
			$error=true;
		}
		editobject($dbc);
		//header( 'Location: index.php?command=editobject&id=' . $_GET['objectid'] );
	} 
}

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
	$sql = "SELECT id, name, description, timestamp FROM type WHERE deleted = 0 $search ORDER BY $sort";

	// Run the query:
	if ($result = mysql_query($sql, $dbc)) {
	
		print '<table>';
		print "\n<tr>\n";
		print "<th><a href=\"index.php?command=listtypes&sort=id\">Id</a></th>\n";
		print "<th><a href=\"index.php?command=listtypes&sort=name\">Name</a></th>\n";
		print "<th><a href=\"index.php?command=listtypes&sort=description\">Description</a></th>\n";	
		print "<th><a href=\"index.php?command=listtypes&sort=timestamp\">Create Date</a></th>\n";
		print "<th></th>\n";
		print "</tr>\n";

		// Retrieve the returned records:
		while ($row = mysql_fetch_array($result)) {
			$rows = mysql_num_rows($result);
			// Print the record:
			print "<tr onClick=\"document.location.href='index.php?command=viewtype&typeid={$row['id']}';\" style=\"cursor:pointer;\">\n";
			print "<td>" . htmlspecialchars($row['id']) . "</td>\n";
			print "<td>" . htmlspecialchars($row['name']) . "</td>\n";
			print "<td>" . substr( htmlspecialchars($row['description']), 0, 80 ) . "\n";
			print "<td>" . htmlspecialchars($row['timestamp']) . "\n";
			print "</td>\n";			
			print "<td>\n";
			print "<form action=\"index.php?command=edittype&typeid=" . htmlspecialchars($row['id']) . "\" method=\"post\">\n";
			print "<input class=\"edit\" type=\"submit\" name=\"submit\" value=\"Edit\" />\n";
			print "</form>\n";
			print "</td>\n";			
			print "</tr>\n";
		} // End of while loop.
		print "</table>\n";
		print "<div class=\"result\">$rows rows returned</div>\n";
	} else 
	{ // Query didn't run.
		print '<p id="clickme" class="error">Could not retrieve the data because:<br />' . mysql_error($dbc) . '. The query being run was: ' . $query . '</p>';
	} // End of query IF.
}


/*******************************************************************/
/* function                                                        */
/*   ?                                                      */
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
function viewtype($dbc)
{
	if (isset($_GET['typeid']) && is_numeric($_GET['typeid']) && ($_GET['typeid'] > 0) ) { // Display the entry in a form:

		$query = "SELECT  type.id as typeid, type.name as typename, type.description as typedescription FROM type WHERE type.id={$_GET['typeid']}";
		if ($result = mysql_query($query, $dbc)) { // Run the query.
		
			$row = mysql_fetch_array($result); // Retrieve the information.
			// Show Details:
			print "<table>\n";
			print "<th class=\"object\" colspan=\"3\">Type</th>\n";
			print "<tr>\n";
			print "<td class=\"object\" width=\"120px\">Name : </td>\n";
			print "<td class=\"object\">" . htmlspecialchars($row['typename']) . "</td>\n";
			print "</tr>\n";
			print "<tr>\n";
			print "<td class=\"object\">Description : </td>\n";
			print "<td class=\"object\">" . nl2br(htmlspecialchars($row['typedescription'])) . "</td>\n";
			print "</tr>\n";
			print "</table>\n";
		} else { // Couldn't get the information.
			print '<p id="clickme" class="error">Could not retrieve the relation because:<br />' . mysql_error($dbc) . '. The query being run was: ' . $query . '</p>';
		}
	
	} else { // No ID set.
		print '<p id="clickme" class="error">This page has been accessed in error.</p>';
	} // End of main IF.
}


/*******************************************************************/
/* function                                                        */
/*   ?                                                      */
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
function edittype($dbc)
{
	$error = false;
	if (isset($_POST['typeid']) && is_numeric($_POST['typeid']) && ($_POST['typeid'] > 0)) { // Handle the form.

		// Validate and secure the form data:
		if ( !empty($_POST['name']) && !empty($_POST['description']) ) {
	
			// Prepare the values for storing:
			$name = mysql_real_escape_string(trim(strip_tags($_POST['name'])), $dbc);
			$description = mysql_real_escape_string(trim(strip_tags($_POST['description'])), $dbc);
			// Define the query.
			$query = "UPDATE type SET name='$name', description='$description'  WHERE id={$_POST['typeid']}";
			if ($result = mysql_query($query, $dbc)) {
				print "<p id=\"fade\" class=\"info\">The type has been updated.</p>\n";
			} else {
				print '<p class="error">Could not update the type because:<br />' . mysql_error($dbc) . '. The query being run was: ' . $query . '</p>';
				$error = true;
			}	
		} // No problem!
	}
	
	if ( isset($_GET['typeid']) && is_numeric($_GET['typeid']) && ($_GET['typeid'] > 0) ) { // Display the entry in a form:

		$query = "SELECT  type.id as typeid, type.name as typename, type.description as typedescription  FROM type WHERE type.id={$_GET['typeid']}";
		if ($result = mysql_query($query, $dbc)) { // Run the query.
		
			$row = mysql_fetch_array($result); // Retrieve the information.
			$typeid = $row['typeid'];
			// Make the form:
			print "<form action=\"index.php?command=edittype&typeid=" . $_GET['typeid'] . "\" method=\"post\">";
			print "<table>";
			print "<th colspan=\"2\" class=\"object\">";
			print "Type";
			print "<input class=\"save\" type=\"submit\" name=\"submit\" value=\"Save Type\"/>\n";
			print "</th>\n";
			print "<tr>\n";
			print "<td class=\"object\" width=\"120px\">Name :</td>\n";
			print "<td class=\"object\"><input class=\"field\" type=\"text\" name=\"name\" value=\"" . htmlspecialchars($row['typename']) . "\" /></td>";
			print "</tr>\n";
			print "<tr>\n";
			print "<td class=\"object\">Description :</td>\n";
			// Check how many lines in the string and adjust the textarea accordingly
			$lines=explode("\n",htmlspecialchars($row['typedescription']));
			$rowcount = count($lines);
			print "<td class=\"object\"><textarea class=\"field\" name=\"description\" rows=\"$rowcount\">" . htmlentities($row['typedescription']) . "</textarea></td>\n";
			print "</tr>\n";
			print "</table>\n";
			print '<input type="hidden" name="typeid" value="' . $_GET['typeid'] . '" />';
			print '</form>';
	
		
		} else { // Couldn't get the information.
			print '<p id="clickme" class="error">Could not retrieve the type because:<br />' . mysql_error($dbc) . '. The query being run was: ' . $query . '</p>';
		}
	} else { // No ID set.
		print '<p id="clickme" class="error">This page has been accessed in error.</p>';
	} // End of main IF.
}


/*******************************************************************/
/* function                                                        */
/*   ?                                                      */
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
function addtype($dbc)
{
	$error = false;
	if ($_SERVER['REQUEST_METHOD'] == 'POST') 
	{ // Handle the form
		if ( !empty($_POST['name']) && !empty($_POST['description']) ) {

						// Prepare the values for storing:
			$name = mysql_real_escape_string(trim(strip_tags($_POST['name'])), $dbc);
			$description = mysql_real_escape_string(trim(strip_tags($_POST['description'])), $dbc);
		
			$query = "INSERT INTO type (name, description) VALUES ('$name', '$description')";
			$result = mysql_query($query, $dbc);
			
			if (mysql_affected_rows($dbc) == 1){
				// Print a message:
				$usedid = mysql_insert_id();
				print '<p id="fade" class="info">Type has been saved with id=' . $usedid . '.</p>';
			} else {
				print '<p id="clickme" class="error">Could not store the type because:<br />' . mysql_error($dbc) . '. The query being run was: ' . $query . '</p>';
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
		print "<p><label>Name <input type=\"text\" name=\"name\" /></label></p>";
		print "<p><label>Description <textarea name=\"description\" rows=\"2\" cols=\"70\"></textarea></label></p>";
		print "<p><input class=\"save\" type=\"submit\" name=\"submit\" value=\"Save\" /></p>\n";
		print "</form>\n";
	}
}

/**************************************************************************************/
/* Properties                                                                              */
/**************************************************************************************/
//
// Functions to handle type:
// listproperties : List properties, sorted by $_GET['sort'] comumn
// viewproperty : Show property detail
// editproperty : Edit property detail
// getpropertytype : Get property type name from type id
// selectedtrue : return selected="true" if values match
//
/**************************************************************************************/

/* Scrapbook code

..

*/


/*******************************************************************/
/* function                                                        */
/*   ?                                                      */
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
/*   ?                                                      */
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
function selectedtrue($value1, $value2)
{
	if ( $value1 == $value2 ) {
		$returnstring = "selected=\"true\"";
	} else {
		$returnstring = "";
	}
	return $returnstring;
}


/*******************************************************************/
/* function                                                        */
/*   ?                                                      */
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
	$sql = "SELECT property.id AS propertyid, property.name AS propertyname, property.description AS propertydescription, property.type AS propertytype, property.timestamp AS propertytimestamp, property_class.name as propertyclassname FROM property JOIN property_class ON (property_class.id = property.class_id) WHERE property.deleted = 0 $search ORDER BY $sort";

	// Run the query:
	if ($result = mysql_query($sql, $dbc)) {
		$rows = mysql_num_rows($result);
		print '<table>';
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
		while ($row = mysql_fetch_array($result)) {
		
			// Print the record:
			print "<tr onClick=\"document.location.href='index.php?command=viewproperty&propertyid={$row['propertyid']}';\" style=\"cursor:pointer;\">\n";
			print "<td>" . htmlspecialchars($row['propertyid']) . "</td>\n";
			print "<td>" . htmlspecialchars($row['propertyname']) . "</td>\n";
			print "<td>" . substr( htmlspecialchars($row['propertydescription']), 0, 80 ) . "\n";
			print "<td>" . getpropertytype(htmlspecialchars($row['propertytype'])) . "</td>\n";
			print "<td>" . htmlspecialchars($row['propertyclassname']) . "\n";
			print "<td>" . htmlspecialchars($row['propertytimestamp']) . "\n";
			print "</td>\n";			
			print "<td>\n";
			print "<form action=\"index.php?command=editproperty&propertyid=" . htmlspecialchars($row['propertyid']) . "\" method=\"post\">\n";
			print "<input class=\"edit\" type=\"submit\" name=\"submit\" value=\"Edit\" />\n";
			print "</form>\n";
			print "</td>\n";			
			print "</tr>\n";
		} // End of while loop.
		print "</table>\n";
		print "<div class=\"result\">$rows rows returned</div>\n";
	} else 
	{ // Query didn't run.
		print '<p id="clickme" class="error">Could not retrieve the data because:<br />' . mysql_error($dbc) . '. The query being run was: ' . $query . '</p>';
	} // End of query IF.
}


/*******************************************************************/
/* function                                                        */
/*   ?                                                      */
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
function viewproperty($dbc)
{
	if (isset($_GET['propertyid']) && is_numeric($_GET['propertyid']) && ($_GET['propertyid'] > 0) ) { // Display the entry in a form:

		$query = "SELECT property.id as propertyid, property.name as propertyname, property.description as propertydescription, property.type as propertytype, property_class.name as propertyclassname FROM property JOIN property_class ON ( property_class.id = property.class_id) WHERE property.id={$_GET['propertyid']}";
		if ($result = mysql_query($query, $dbc)) { // Run the query.
		
			$row = mysql_fetch_array($result); // Retrieve the information.
			// Show Details:
			print "<table>\n";
			print "<th class=\"object\" colspan=\"3\">Type</th>\n";
			print "<tr>\n";
			print "<td class=\"object\" width=\"120px\">Name : </td>\n";
			print "<td class=\"object\">" . htmlspecialchars($row['propertyname']) . "</td>\n";
			print "</tr>\n";
			print "<tr>\n";
			print "<td class=\"object\">Description : </td>\n";
			print "<td class=\"object\">" . nl2br(htmlspecialchars($row['propertydescription'])) . "</td>\n";
			print "</tr>\n";
			print "<tr>\n";
			print "<td class=\"object\">Type :  </td>\n";
			print "<td class=\"object\">" . getpropertytype(htmlspecialchars($row['propertytype'])) . "</td>\n";
			print "</tr>\n";
			print "<tr>\n";
			print "<td class=\"object\">Class :  </td>\n";
			print "<td class=\"object\">" . htmlspecialchars($row['propertyclassname']) . "</td>\n";
			print "</tr>\n";
			print "</table>\n";
			
		} else { // Couldn't get the information.
			print '<p id="clickme" class="error">Could not retrieve the relation because:<br />' . mysql_error($dbc) . '. The query being run was: ' . $query . '</p>';
		}
	
	} else { // No ID set.
		print '<p id="clickme" class="error">This page has been accessed in error.</p>';
	} // End of main IF.
}


/*******************************************************************/
/* function                                                        */
/*   ?                                                      */
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
function editproperty($dbc)
{
	$error = false;
	if (isset($_POST['propertyid']) && is_numeric($_POST['propertyid']) && ($_POST['propertyid'] > 0)) { // Handle the form.

		// Validate and secure the form data:
		if ( !empty($_POST['name']) && !empty($_POST['description']) && !empty($_POST['type']) ) {
	
			// Prepare the values for storing:
			$name = mysql_real_escape_string(trim(strip_tags($_POST['name'])), $dbc);
			$description = mysql_real_escape_string(trim(strip_tags($_POST['description'])), $dbc);
			$type = mysql_real_escape_string(trim(strip_tags($_POST['type'])), $dbc);
			$classid = mysql_real_escape_string(trim(strip_tags($_POST['class'])), $dbc);
			// Define the query.
			$query = "UPDATE property SET name='$name', description='$description', type='$type', class_id='$classid'  WHERE id={$_POST['propertyid']}";
			if ($result = mysql_query($query, $dbc)) {
				print '<p id="fade" class="info">The property has been updated.</p>';
			} else {
				print '<p class="error">Could not update the property because:<br />' . mysql_error($dbc) . '. The query being run was: ' . $query . '</p>';
				$error = true;
			}	
		} // No problem!
	}
	
	if ( isset($_GET['propertyid']) && is_numeric($_GET['propertyid']) && ($_GET['propertyid'] > 0) ) { // Display the entry in a form:

		$query = "SELECT  property.id as propertyid, property.name as propertyname, property.description as propertydescription, property.type as propertytype, property.class_id as propertyclassid  FROM property JOIN property_class ON ( property_class.id = property.class_id) WHERE property.id={$_GET['propertyid']}";
		if ($result = mysql_query($query, $dbc)) { // Run the query.
		
			$row = mysql_fetch_array($result); // Retrieve the information.
			$propertyid = htmlspecialchars($row['propertyid']);
			$propertyclassid = htmlspecialchars($row['propertyclassid']);
			// Make the form:
			print "<form action=\"index.php?command=editproperty&propertyid=" . $_GET['propertyid'] . "\" method=\"post\">";
			print "<table>";
			print "<th colspan=\"2\" class=\"object\">";
			print "Property";
			print "<input class=\"save\" type=\"submit\" name=\"submit\" value=\"Save Property\"/>\n";
			print "</th>\n";
			print "<tr>\n";
			print "<td class=\"object\" width=\"120px\">Name :</td>\n";
			print "<td class=\"object\"><input class=\"field\" type=\"text\" name=\"name\" value=\"" . htmlspecialchars($row['propertyname']) . "\" /></td>";
			print "</tr>\n";
			print "<tr>\n";
			print "<td class=\"object\">Description :</td>\n";
			// Check how many lines in the string and adjust the textarea accordingly
			$lines=explode("\n",htmlspecialchars($row['propertydescription']));
			$rowcount = count($lines);
			print "<td class=\"object\"><textarea class=\"field\" name=\"description\" rows=\"$rowcount\">" . htmlspecialchars($row['propertydescription']) . "</textarea></td>\n";
			print "</tr>\n";
			print "<tr>\n";
			print "<td class=\"object\">Type :</td>\n";
			// Make a select with types
			print "<td class=\"object\">\n";
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
			if ($result = mysql_query($query, $dbc)) { // Run the query.
				print "<tr>\n";
				print "<td class=\"object\">Class :</td>\n";
				print "<td class=\"object\"><select width=\"100px\" class=\"field\" name=\"class\">\n";
				while ($row = mysql_fetch_array($result)) {
				print '<option';
				if ( htmlspecialchars($row['id']) == $propertyclassid ) {
					print ' selected="true"';
			}
				print ' value ="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['name']) . '</option>' ."\n";
				} 
			} else { // Couldn't get the information.
				print '<p id="clickme" class="error">Could not retrieve the object because:<br />' . mysql_error($dbc) . '. The query being run was: ' . $query . '</p>';
			}
			print "</select>\n";
			print "</td>\n";
			print "</tr>\n";

			print "</table>\n";
			print '<input type="hidden" name="propertyid" value="' . $_GET['propertyid'] . '" />';
			print '</form>';
	
		
		} else { // Couldn't get the information.
			print '<p id="clickme" class="error">Could not retrieve the property because:<br />' . mysql_error($dbc) . '. The query being run was: ' . $query . '</p>';
		}
	} else { // No ID set.
		print '<p id="clickme" class="error">This page has been accessed in error.</p>';
	} // End of main IF.
}


/*******************************************************************/
/* function                                                        */
/*   ?                                                      */
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
function addproperty($dbc)
{
	$error = false;
	if ($_SERVER['REQUEST_METHOD'] == 'POST') 
	{ // Handle the form
		if ( !empty($_POST['name']) && !empty($_POST['description']) && !empty($_POST['type']) ) {

						// Prepare the values for storing:
			$name = mysql_real_escape_string(trim(strip_tags($_POST['name'])), $dbc);
			$description = mysql_real_escape_string(trim(strip_tags($_POST['description'])), $dbc);
			$type = mysql_real_escape_string(trim(strip_tags($_POST['type'])), $dbc);
			$classid = mysql_real_escape_string(trim(strip_tags($_POST['class_id'])), $dbc);
			$query = "INSERT INTO property (name, description, type, class_id) VALUES ('$name', '$description', '$type', '$classid')";
			$result = mysql_query($query, $dbc);
			
			if (mysql_affected_rows($dbc) == 1){
				// Print a message:
				$usedid = mysql_insert_id();
				print '<p id="fade" class="info">Property has been saved with id=' . $usedid . '.</p>';
			} else {
				print '<p id="clickme" class="error">Could not store the property because:<br />' . mysql_error($dbc) . '. The query being run was: ' . $query . '</p>';
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
		print "<p><label>Name <input type=\"text\" name=\"name\" /></label></p>";
		print "<p><label>Description <textarea name=\"description\" rows=\"2\" cols=\"70\"></textarea></label></p>";
		print "<p>Property type<select class=\"field\" name=\"type\" width=\"100px\">\n";
		print "<option value=\"0\">" . getpropertytype(0) . "</option>\n";
		print "<option value=\"1\">" . getpropertytype(1) . "</option>\n";
		print "<option value=\"2\">" . getpropertytype(2) . "</option>\n";
		print "<option value=\"3\">" . getpropertytype(3) . "</option>\n";
		print "<option value=\"4\">" . getpropertytype(4) . "</option>\n";
		print "<option value=\"11\">" . getpropertytype(11) . "</option>\n";
		print "<option value=\"12\">" . getpropertytype(12) . "</option>\n";
		print "<option value=\"13\">" . getpropertytype(13) . "</option>\n";
		print "<option value=\"14\">" . getpropertytype(14) . "</option>\n";
		print "</select></p>\n";
				// Define query for class
		$query = "SELECT  id, name FROM property_class ORDER BY id ASC";
		if ($result = mysql_query($query, $dbc)) { // Run the query.
			print '<p>Property class <select class="field" name="class_id">' . "\n";
			while ($row = mysql_fetch_array($result)) {
			print '<option';
			print ' value ="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['name']) . '</option>' ."\n";
			} 
		} else { // Couldn't get the information.
			print '<p id="clickme" class="error">Could not retrieve the object because:<br />' . mysql_error($dbc) . '. The query being run was: ' . $query . '</p>';
		}
		print "</select>\n";
		print "<p><input class=\"save\" type=\"submit\" name=\"submit\" value=\"Save\" /></p>\n";
		print "</form>\n";
	}
}


/**************************************************************************************/
/* Relations                                                                          */
/**************************************************************************************/
//
// Functions to handle type:
// listrelations : List types, sorted by $_GET['sort'] comumn
// viewrelation : Show type detail
// editrelation : Edit type detail
//
/**************************************************************************************/

/* Scrapbook code

..

*/


/*******************************************************************/
/* function                                                        */
/*   ?                                                      */
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
	$sql = "SELECT id, name, description, timestamp FROM relation WHERE deleted = 0 $search ORDER BY $sort";

	// Run the query:
	if ($result = mysql_query($sql, $dbc)) {
		$rows = mysql_num_rows($result);
		print '<table>';
		print "\n<tr>\n";
		print "<th><a href=\"index.php?command=listtypes&sort=id\">Id</a></th>\n";
		print "<th><a href=\"index.php?command=listtypes&sort=name\">Name</a></th>\n";
		print "<th><a href=\"index.php?command=listtypes&sort=description\">Description</a></th>\n";	
		print "<th><a href=\"index.php?command=listtypes&sort=timestamp\">Create Date</a></th>\n";
		print "<th></th>\n";
		print "</tr>\n";

		// Retrieve the returned records:
		while ($row = mysql_fetch_array($result)) {
		
			// Print the record:
			print "<tr onClick=\"document.location.href='index.php?command=viewrelation&relationid={$row['id']}';\" style=\"cursor:pointer;\">\n";
			print "<td>" . htmlspecialchars($row['id']) . "</td>\n";
			print "<td>" . htmlspecialchars($row['name']) . "</td>\n";
			print "<td>" . substr( htmlspecialchars($row['description']), 0, 80 ) . "\n";
			print "<td>" . htmlspecialchars($row['timestamp']) . "\n";
			print "</td>\n";			
			print "<td>\n";
			print "<form action=\"index.php?command=editrelation&relationid=" . htmlspecialchars($row['id']) . "\" method=\"post\">\n";
			print "<input class=\"edit\" type=\"submit\" name=\"submit\" value=\"Edit\" />\n";
			print "</form>\n";
			print "</td>\n";			
			print "</tr>\n";
		} // End of while loop.
		print "</table>\n";
		print "<div class=\"result\">$rows rows returned</div>\n";
	} else 
	{ // Query didn't run.
		print '<p id="clickme" class="error">Could not retrieve the data because:<br />' . mysql_error($dbc) . '. The query being run was: ' . $query . '</p>';
	} // End of query IF.
}


/*******************************************************************/
/* function                                                        */
/*   ?                                                      */
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
function viewrelation($dbc)
{
	if (isset($_GET['relationid']) && is_numeric($_GET['relationid']) && ($_GET['relationid'] > 0) ) { // Display the entry in a form:

		$query = "SELECT  relation.id as relationid, relation.name as relationname, relation.description as relationdescription FROM relation WHERE relation.id={$_GET['relationid']}";
		if ($result = mysql_query($query, $dbc)) { // Run the query.
			$row = mysql_fetch_array($result); // Retrieve the information.
			// Show Details:
			print "<table>\n";
			print "<th class=\"object\" colspan=\"3\">Relation</th>\n";
			print "<tr>\n";
			print "<td class=\"object\" width=\"120px\">Name : </td>\n";
			print "<td class=\"object\">" . htmlspecialchars($row['relationname']) . "</td>\n";
			print "</tr>\n";
			print "<tr>\n";
			print "<td class=\"object\">Description : </td>\n";
			print "<td class=\"object\">" . nl2br(htmlspecialchars($row['relationdescription'])) . "</td>\n";
			print "</tr>\n";
			print "</table>\n";
		} else { // Couldn't get the information.
			print '<p id="clickme" class="error">Could not retrieve the relation because:<br />' . mysql_error($dbc) . '. The query being run was: ' . $query . '</p>';
		}
	
	} else { // No ID set.
		print '<p id="clickme" class="error">This page has been accessed in error.</p>';
	} // End of main IF.
}


/*******************************************************************/
/* function                                                        */
/*   ?                                                      */
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
function editrelation($dbc)
{
	$error = false;
	if (isset($_POST['relationid']) && is_numeric($_POST['relationid']) && ($_POST['relationid'] > 0)) { // Handle the form.

		// Validate and secure the form data:
		if ( !empty($_POST['name']) && !empty($_POST['description']) ) {
	
			// Prepare the values for storing:
			$name = mysql_real_escape_string(trim(strip_tags($_POST['name'])), $dbc);
			$description = mysql_real_escape_string(trim(strip_tags($_POST['description'])), $dbc);
			// Define the query.
			$query = "UPDATE relation SET name='$name', description='$description'  WHERE id={$_POST['relationid']}";
			if ($result = mysql_query($query, $dbc)) {
				print "<p id=\"fade\" class=\"info\">The relation has been updated.</p>\n";
			} else {
				print '<p class="error">Could not update the relation because:<br />' . mysql_error($dbc) . '. The query being run was: ' . $query . '</p>';
				$error = true;
			}	
		} // No problem!
	}
	
	if ( isset($_GET['relationid']) && is_numeric($_GET['relationid']) && ($_GET['relationid'] > 0) ) { // Display the entry in a form:

		$query = "SELECT  relation.id as relationid, relation.name as relationname, relation.description as relationdescription  FROM relation WHERE relation.id={$_GET['relationid']}";
		if ($result = mysql_query($query, $dbc)) { // Run the query.
		
			$row = mysql_fetch_array($result); // Retrieve the information.
			$relationid = $row['relationid'];
			// Make the form:
			print "<form action=\"index.php?command=editrelation&relationid=" . $_GET['relationid'] . "\" method=\"post\">";
			print "<table>";
			print "<th colspan=\"2\" class=\"object\">";
			print "Relation";
			print "<input class=\"save\" type=\"submit\" name=\"submit\" value=\"Save Relation\"/>\n";
			print "</th>\n";
			print "<tr>\n";
			print "<td class=\"object\" width=\"120px\">Name :</td>\n";
			print "<td class=\"object\"><input class=\"field\" type=\"text\" name=\"name\" value=\"" . htmlspecialchars($row['relationname']) . "\" /></td>";
			print "</tr>\n";
			print "<tr>\n";
			print "<td class=\"object\">Description :</td>\n";
			// Check how many lines in the string and adjust the textarea accordingly
			$lines=explode("\n",htmlspecialchars($row['relationdescription']));
			$rowcount = count($lines);
			print "<td class=\"object\"><textarea class=\"field\" name=\"description\" rows=\"$rowcount\">" . htmlspecialchars($row['relationdescription']) . "</textarea></td>\n";
			print "</tr>\n";
			print "</table>\n";
			print '<input type="hidden" name="relationid" value="' . $_GET['relationid'] . '" />';
			print '</form>';
	
		
		} else { // Couldn't get the information.
			print '<p id="clickme" class="error">Could not retrieve the relation because:<br />' . mysql_error($dbc) . '. The query being run was: ' . $query . '</p>';
		}
	} else { // No ID set.
		print '<p id="clickme" class="error">This page has been accessed in error.</p>';
	} // End of main IF.
}


/*******************************************************************/
/* function                                                        */
/*   ?                                                      */
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
function addrelation($dbc)
{
	$error = false;
	if ($_SERVER['REQUEST_METHOD'] == 'POST') 
	{ // Handle the form
		if ( !empty($_POST['name']) && !empty($_POST['description']) ) {

						// Prepare the values for storing:
			$name = mysql_real_escape_string(trim(strip_tags($_POST['name'])), $dbc);
			$description = mysql_real_escape_string(trim(strip_tags($_POST['description'])), $dbc);
		
			$query = "INSERT INTO relation (name, description) VALUES ('$name', '$description')";
			$result = mysql_query($query, $dbc);
			
			if (mysql_affected_rows($dbc) == 1){
				// Print a message:
				$usedid = mysql_insert_id();
				print '<p id="fade" class="info">Relation has been saved.</p>';
			} else {
				print '<p id="clickme" class="error">Could not store the relation because:<br />' . mysql_error($dbc) . '. The query being run was: ' . $query . '</p>';
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
		print "<p><label>Name <input type=\"text\" name=\"name\" /></label></p>";
		print "<p><label>Description <textarea name=\"description\" rows=\"2\" cols=\"70\"></textarea></label></p>";
		print "<p><input class=\"save\" type=\"submit\" name=\"submit\" value=\"Save\" /></p>\n";
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
/*   ?                                                      */
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
function addobjectrelation($dbc)
{
	$error = false;
	if ( $_SERVER['REQUEST_METHOD'] == 'POST' )
	{	// Add
		if ( isset($_GET['objectid']) && is_numeric($_GET['objectid']) && ($_GET['objectid'] > 0) && isset($_POST['relationid']) && isset($_POST['robjectid']) ) { 
			
			// Prepare the values for storing:
			$objectid = mysql_real_escape_string(trim(strip_tags($_POST['objectid'])), $dbc);
			$relationid = mysql_real_escape_string(trim(strip_tags($_POST['relationid'])), $dbc);
			$robjectid = mysql_real_escape_string(trim(strip_tags($_POST['robjectid'])), $dbc);
			// Define the query.
			$query = "INSERT INTO object_relation (relation_id, object1_id, object2_id) VALUES ('$relationid', '$objectid', '$robjectid')";
			if ($result = mysql_query($query, $dbc)) {
				print "<p id=\"fade\" class=\"info\">The object relation has been updated.</p>\n";
			} else {
				print '<p id="clickme" class="error">Could not save the object relation because:<br />' . mysql_error($dbc) . '. The query being run was: ' . $query . '</p>';
				$error = true;
			}	
		} // No problem!
	}
	editobject($dbc);
}


/*******************************************************************/
/* function                                                        */
/*   ?                                                      */
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
function deleteobjectrelation($dbc)
{
	$error = false;
	if ( isset($_GET['objectid']) && is_numeric($_GET['objectid']) && ($_GET['objectid'] > 0) && ($_SERVER['REQUEST_METHOD'] == 'POST') )
	{	// Delete
		if ( isset($_POST['objectrelationid']) &&  is_numeric($_POST['objectrelationid']) && ($_POST['objectrelationid']) ) { 
			// Prepare the values for storing:
			$objectrelationid = mysql_real_escape_string(trim(strip_tags($_POST['objectrelationid'])), $dbc);
			// Define the query.
			$query = "DELETE FROM object_relation WHERE id=$objectrelationid";
			if ($result = mysql_query($query, $dbc)) {
				print "<p id=\"fade\" class=\"info\">The object relation has been deleted.</p>\n";
			} else {
				print '<p id="clickme" class="error">Could not delete the object relation because:<br />' . mysql_error($dbc) . '. The query being run was: ' . $query . '</p>';
				$error = true;
			}	
		} // No problem!
	}
	editobject($dbc);
}


/*******************************************************************/
/* function                                                        */
/*   ?                                                      */
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
function reports($dbc) {
	$error = false;
	// Report by type
	print "<div class=\"group\">\n";
	print "Report by type: Show objects that have any of the selected types.<hr/>\n";
	print"<form name=\"input\" action=\"index.php?command=typereport\" method=\"post\">\n";
	$query = "SELECT type.name as typename, type.id as typeid FROM type ORDER BY type.name";
	if ($result = mysql_query($query, $dbc)) {
		while ($row = mysql_fetch_array($result)) {	
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
	if ($result = mysql_query($query, $dbc)) {
		while ($row = mysql_fetch_array($result)) {	
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
	if ($result = mysql_query($query, $dbc)) {
		while ($row = mysql_fetch_array($result)) {	
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
	if ($result = mysql_query($query, $dbc)) {
		while ($row = mysql_fetch_array($result)) {	
			print "<input type=\"checkbox\" name=\"propertyclassid" . htmlspecialchars($row['propertyclassid']) . "\" value=\"" . htmlspecialchars($row['propertyclassid']) . "\"/>" . htmlspecialchars($row['propertyclassname']) . "<br/>\n";
		}
	}
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
	if ($result = mysql_query($query, $dbc)) {
		$rows = mysql_num_rows($result);
		print '<table>';
		print "\n<tr>\n";
		print "<th>Id</th>\n";
		print "<th>Name</a></th>\n";
		print "<th>Description</th>\n";	
		print "<th>Type</th>\n";	
		print "<th>Create Date</th>\n";
		//print "<th></th>\n";
		print "</tr>\n";
		// Retrieve the returned records:
		while ($row = mysql_fetch_array($result)) {
			// Print the record:
			print "<tr onClick=\"document.location.href='index.php?command=viewobject&objectid=" . htmlspecialchars($row['objectid']) . "';\" style=\"cursor:pointer;\">\n";
			print "<td>{$row['objectid']}</td>\n";
			print "<td>{$row['objectname']}</td>\n";
			print "<td>" . substr( htmlspecialchars($row['objectdescription']), 0, 60 ) . "</td>\n";
			print "<td>" . substr( htmlspecialchars($row['typename']), 0, 25 ) . "</td>\n";
			print "<td>" . htmlspecialchars($row['objecttimestamp']) . "\n";
			print "</td>\n";			
			print "</tr>\n";
		} // End of while loop.
		print "</table>\n";
		print "<div class=\"result\">$rows rows returned</div>\n";
	} else 
	{ // Query didn't run.
		print '<p id="clickme" class="error">Could not retrieve the data because:<br />' . mysql_error($dbc) . '. The query being run was: ' . $query . '</p>';
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
	if ($result = mysql_query($query, $dbc)) {
		$rows = mysql_num_rows($result);
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
		while ($row = mysql_fetch_array($result)) {
			// Print the record:
			print "<tr onClick=\"document.location.href='index.php?command=viewobject&objectid=" . htmlspecialchars($row['objectid']) . "';\" style=\"cursor:pointer;\">\n";
			print "<td>" . htmlspecialchars($row['propertyname']) ."</td>\n";
			print "<td>" . htmlspecialchars($row['objectname']) . "</td>\n";
			print "<td>" . substr( htmlspecialchars($row['objectdescription']), 0, 60 ) . "</td>\n";
			print "<td>" . substr( htmlspecialchars($row['typename']), 0, 25 ) . "</td>\n";
			print "<td>" . htmlspecialchars($row['objecttimestamp']) . "\n";
			print "</td>\n";			
			print "</tr>\n";
		} // End of while loop.
		print "</table>\n";
		print "<div class=\"result\">$rows rows returned</div>\n";
	} else 
	{ // Query didn't run.
		print '<p id="clickme" class="error">Could not retrieve the data because:<br />' . mysql_error($dbc) . '. The query being run was: ' . $query . '</p>';
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


		SELECT object_id FROM object_property WHERE property_id IN (1,5) GROUP BY object_id HAVING COUNT(object_id)>=2
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
	if ($result = mysql_query($query, $dbc)) {
	
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
		while ($row = mysql_fetch_array($result)) {
			// Print the record:
			print "<tr onClick=\"document.location.href='index.php?command=viewobject&objectid=" . htmlspecialchars($row['objectpropertyobjectid']) ."';\" style=\"cursor:pointer;\">\n";
			print "<td>" . htmlspecialchars($row['propertyname']) . "</td>\n";
			print "<td>" . htmlspecialchars($row['objectname']) . "</td>\n";
			print "<td>" . substr( htmlspecialchars($row['objectdescription']), 0, 60 ) . "</td>\n";
			print "<td>" . substr( htmlspecialchars($row['typename']), 0, 25 ) . "</td>\n";
			print "<td>" . htmlspecialchars($row['objecttimestamp']) . "\n";
			print "</td>\n";			
			print "</tr>\n";
		} // End of while loop.
		print "</table>\n";
		print "<div class=\"result\">$rows rows returned</div>\n";
	} else 
	{ // Query didn't run.
		print '<p id="clickme" class="error">Could not retrieve the data because:<br />' . mysql_error($dbc) . '. The query being run was: ' . $query . '</p>';
	} // End of query IF.
}


/*******************************************************************/
/* function                                                        */
/*   propertyclassreport                                                      */
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
	if ($result = mysql_query($query, $dbc)) {
		$rows = mysql_num_rows($result);
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
		while ($row = mysql_fetch_array($result)) {
		
			// Print the record:
			print "<tr onClick=\"document.location.href='index.php?command=viewobject&objectid=" . htmlspecialchars($row['objectid']) . "';\" style=\"cursor:pointer;\">\n";
			print "<td>" . htmlspecialchars($row['propertyname']) . "</td>\n";
			print "<td>" . htmlspecialchars($row['objectname']) . "</td>\n";
			print "<td>" . substr( htmlspecialchars($row['objectdescription']), 0, 60 ) . "</td>\n";
			print "<td>" . substr( htmlspecialchars($row['typename']), 0, 25 ) . "</td>\n";
			print "<td>" . htmlspecialchars($row['objecttimestamp']) . "\n";
			print "</td>\n";			
			print "</tr>\n";
		} // End of while loop.
		print "</table>\n";
		print "<div class=\"result\">$rows rows returned</div>\n";
	} else 
	{ // Query didn't run.
		print '<p id="clickme" class="error">Could not retrieve the data because:<br />' . mysql_error($dbc) . '. The query being run was: ' . $query . '</p>';
	} // End of query IF.
}

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
	if ($result = mysql_query($query, $dbc)) {
	
		print '<table>';
		print "\n<tr>\n";
		print "<th><a href=\"index.php?command=listusers&sort=userid\">Id</a></th>\n";
		print "<th><a href=\"index.php?command=listusers&sort=userlogin\">Login</a></th>\n";
		print "<th><a href=\"index.php?command=listusers&sort=username\">Full Name</a></th>\n";	
		print "<th><a href=\"index.php?command=listusers&sort=usergroupname\">User Group</a></th>\n";	
		print "<th><a href=\"index.php?command=listusers&sort=usertimestamp\">Create Date</a></th>\n";
		print "<th></th>\n";
		print "</tr>\n";

		// Retrieve the returned records:
		while ($row = mysql_fetch_array($result)) {
			$rows = mysql_num_rows($result);
			// Print the record:
			print "<tr onClick=\"document.location.href='index.php?command=edituser&userid={$row['userid']}';\" style=\"cursor:pointer;\">\n";
			print "<td>" . htmlspecialchars($row['userid']) . "</td>\n";
			print "<td>" . htmlspecialchars($row['userlogin']) . "</td>\n";
			print "<td>" . substr( htmlspecialchars($row['username']), 0, 80 ) . "</td>\n";
			print "<td>" . substr( htmlspecialchars($row['usergroupname']), 0, 80 ) . "</td>\n";
			print "<td>" . htmlspecialchars($row['usertimestamp']) . "\n";
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
		print '<p id="clickme" class="error">Could not retrieve the data because:<br />' . mysql_error($dbc) . '. The query being run was: ' . $query . '</p>';
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
			$login = mysql_real_escape_string(trim(strip_tags($_POST['login'])), $dbc);
			$name = mysql_real_escape_string(trim(strip_tags($_POST['name'])), $dbc);
			$groupid = mysql_real_escape_string(trim(strip_tags($_POST['group_id'])), $dbc);
			$password = md5(mysql_real_escape_string(trim(strip_tags($_POST['password'])), $dbc));
			$query = "INSERT INTO user (login, name, group_id, password) VALUES ('$login', '$name', '$groupid', '$password')";
			$result = mysql_query($query, $dbc);
			if (mysql_affected_rows($dbc) == 1){
				// Print a message:
				$usedid = mysql_insert_id();
				print '<p id="fade" class="info">User has been saved with id=' . $usedid . '.</p>';
			} else {
				print '<p id="clickme" class="error">Could not store the user because:<br />' . mysql_error($dbc) . '. The query being run was: ' . $query . '</p>';
			}
			
		} else { // Failed to enter data.
			print '<p id="clickme" class="error">Please enter a login and name and select a group!</p>';
			$error=true;
		}
		listusers($dbc);
	} elseif (!$error)
	{	// Show the form
		print "<form action=\"index.php?command=adduser\" method=\"post\">\n";
		print "<p><label>Login <input type=\"text\" name=\"login\" /></label></p>";
		print "<p><label>Full Name <textarea name=\"name\" rows=\"2\" cols=\"70\"></textarea></label></p>";
		print "<p><label>Password <input type=\"password\" name=\"password\" /></label></p>";
				// Define query for group
		$query = "SELECT  id, name FROM usergroup ORDER BY name ASC";
		if ($result = mysql_query($query, $dbc)) { // Run the query.
			print '<p>User Group<select class="field" name="group_id">' . "\n";
			while ($row = mysql_fetch_array($result)) {
			print '<option';
			print ' value ="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['name']) . '</option>' ."\n";
			} 
		} else { // Couldn't get the information.
			print '<p id="clickme" class="error">Could not retrieve the user because:<br />' . mysql_error($dbc) . '. The query being run was: ' . $query . '</p>';
		}
		print "</select>\n";
		print "<p><input class=\"save\" type=\"submit\" name=\"submit\" value=\"Save\" /></p>\n";
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
			$login = mysql_real_escape_string(trim(strip_tags($_POST['login'])), $dbc);
			$name = mysql_real_escape_string(trim(strip_tags($_POST['name'])), $dbc);
			$groupid = mysql_real_escape_string(trim(strip_tags($_POST['groupid'])), $dbc);
			
			// Define the query.
			if(!empty($_POST['password'])) {
				$password = md5(mysql_real_escape_string(trim(strip_tags($_POST['password'])), $dbc));
				$query = "UPDATE user SET login='$login', name='$name', group_id='$groupid', password='$password'  WHERE id={$_POST['userid']}";
			} else {
				$query = "UPDATE user SET login='$login', name='$name', group_id='$groupid'  WHERE id={$_POST['userid']}";
			}
			if ($result = mysql_query($query, $dbc)) {
				print '<p id="fade" class="info">The user has been updated.</p>';
			} else {
				print '<p class="error">Could not update the user because:<br />' . mysql_error($dbc) . '. The query being run was: ' . $query . '</p>';
				$error = true;
			}	
		} // No problem!
	}

	if ( isset($_GET['userid']) && is_numeric($_GET['userid']) && ($_GET['userid'] > 0) ) { // Display the entry in a form:

		$query = "SELECT user.id as userid, user.login as userlogin, user.name as username, user.timestamp as usertimestamp, usergroup.name as usergroupname, usergroup.id as usergroupid FROM user " .
				"JOIN usergroup ON (usergroup.id = user.group_id) WHERE user.id = " . $_GET['userid'];
		if ($result = mysql_query($query, $dbc)) { // Run the query.
		
			$row = mysql_fetch_array($result); // Retrieve the information.
			$userid = htmlspecialchars($row['userid']);
			$usergroupid = htmlspecialchars($row['usergroupid']);
			// Make the form:
			print "<form action=\"index.php?command=edituser&userid=" . $_GET['userid'] . "\" method=\"post\">";
			print "<table>";
			print "<th colspan=\"2\" class=\"object\">";
			print "User";
			print "<input class=\"save\" type=\"submit\" name=\"submit\" value=\"Save User\"/>\n";
			print "</th>\n";
			print "<tr>\n";
			print "<td class=\"object\" width=\"120px\">Login :</td>\n";
			print "<td class=\"object\"><input class=\"field\" type=\"text\" name=\"login\" value=\"" . htmlspecialchars($row['userlogin']) . "\" /></td>";
			print "</tr>\n";
			print "<tr>\n";
			print "<td class=\"object\">Full Name :</td>\n";
			print "<td class=\"object\"><input class=\"field\" type=\"text\" name=\"name\" value=\"" . htmlspecialchars($row['username']) . "\" /></td>\n";
			print "</tr>\n";
			print "<tr>\n";
			print "<td class=\"object\">Password :</td>\n";
			print "<td class=\"object\"><input class=\"field\" type=\"password\" name=\"password\" value=\"\" /></td>\n";
			print "</tr>\n";
			print "<tr>\n";
			// Define query for group
			$query = "SELECT  id, name FROM usergroup ORDER BY name ASC";
			if ($result = mysql_query($query, $dbc)) { // Run the query.
				print "<tr>\n";
				print "<td class=\"object\">User Group :</td>\n";
				print "<td class=\"object\"><select width=\"100px\" class=\"field\" name=\"groupid\">\n";
				while ($row = mysql_fetch_array($result)) {
				print '<option';
				if ( htmlspecialchars($row['id']) == $usergroupid ) {
					print ' selected="true"';
			}
				print ' value ="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['name']) . '</option>' ."\n";
				} 
			} else { // Couldn't get the information.
				print '<p id="clickme" class="error">Could not retrieve the user group because:<br />' . mysql_error($dbc) . '. The query being run was: ' . $query . '</p>';
			}
			print "</select>\n";
			print "</td>\n";
			print "</tr>\n";

			print "</table>\n";
			print '<input type="hidden" name="userid" value="' . $_GET['userid'] . '" />';
			print '</form>';
	
		
		} else { // Couldn't get the information.
			print '<p id="clickme" class="error">Could not retrieve the user because:<br />' . mysql_error($dbc) . '. The query being run was: ' . $query . '</p>';
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
	if ($result = mysql_query($query, $dbc)) {
	
		print '<table>';
		print "\n<tr>\n";
		print "<th><a href=\"index.php?command=listusers&sort=groupid\">Id</a></th>\n";
		print "<th><a href=\"index.php?command=listusers&sort=groupname\">Group Name</a></th>\n";	
		print "<th><a href=\"index.php?command=listusers&sort=groupdescription\">Group Description</a></th>\n";	
		print "<th><a href=\"index.php?command=listusers&sort=usertimestamp\">Create Date</a></th>\n";
		print "<th></th>\n";
		print "</tr>\n";

		// Retrieve the returned records:
		$rows = 0;
		while ($row = mysql_fetch_array($result)) {
			$rows = mysql_num_rows($result);
			// Print the record:
			print "<tr onClick=\"document.location.href='index.php?command=editgroup&groupid={$row['groupid']}';\" style=\"cursor:pointer;\">\n";
			print "<td>" . htmlspecialchars($row['groupid']) . "</td>\n";
			print "<td>" . htmlspecialchars($row['groupname']) . "</td>\n";
			print "<td>" . substr( htmlspecialchars($row['groupdescription']), 0, 80 ) . "</td>\n";
			print "<td>" . htmlspecialchars($row['grouptimestamp']) . "\n";
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
		print '<p id="clickme" class="error">Could not retrieve the data because:<br />' . mysql_error($dbc) . '. The query being run was: ' . $query . '</p>';
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
			$name = mysql_real_escape_string(trim(strip_tags($_POST['name'])), $dbc);
			$description = mysql_real_escape_string(trim(strip_tags($_POST['description'])), $dbc);
			$query = "INSERT INTO usergroup (name, description) VALUES ('$name', '$description')";
			$result = mysql_query($query, $dbc);
			if (mysql_affected_rows($dbc) == 1){
				// Print a message:
				$usedid = mysql_insert_id();

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
					$permid = mysql_real_escape_string($key);
					$permset = mysql_real_escape_string($value);
					$query = "INSERT INTO usergroup_permissions(group_id, permission_id, value) VALUES ('$usedid', '$permid', '$permset')";
					$result = mysql_query($query, $dbc);
					if (!mysql_affected_rows($dbc) == 1){
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
		print "<p>Group Name <input type=\"text\" name=\"name\" /></p>";
		print "<p>Group Description <input type=\"text\" size=\"50\" name=\"description\"></p>";
				// Define query for permissions
		$query = "SELECT  id, name, description FROM permissions ORDER BY displayorder ASC";
		if ($result = mysql_query($query, $dbc)) { // Run the query.
			print "<table><th colspan=\"3\">Group permissions:</th>\n";
			while ($row = mysql_fetch_array($result)) {
				print "<tr><td>" . $row['description'] . "</td>\n";
				print "<td><input type=\"radio\" name=\"" . $row['id'] . "\" value=\"1\" />Allow</td>";
				print "<td><input type=\"radio\" name=\"" . $row['id'] . "\" value=\"0\" checked=\"checked\" />Deny</td>";
				print "</tr>\n";
			} 
			print "</table>\n";
		} else { // Couldn't get the information.
			print '<p id="clickme" class="error">Could not retrieve the user because:<br />' . mysql_error($dbc) . '. The query being run was: ' . $query . '</p>';
		}
		print "<p><input class=\"save\" type=\"submit\" name=\"submit\" value=\"Save\" /></p>\n";
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
			$name = mysql_real_escape_string(trim(strip_tags($_POST['name'])), $dbc);
			$description = mysql_real_escape_string(trim(strip_tags($_POST['description'])), $dbc);

			$query = "UPDATE usergroup SET name='$name', description='$description' WHERE id={$_POST['groupid']}";
			if (!$result = mysql_query($query, $dbc)){
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
					$permid = mysql_real_escape_string($key);
					$permset = mysql_real_escape_string($value);
					$query = "UPDATE usergroup_permissions SET value=$permset WHERE group_id = {$_POST['groupid']} AND permission_id = $permid";
					if (!$result = mysql_query($query, $dbc)){
						$error = true;
					}
				}
			}
		}
		if($error) {
			// Problem
			print '<p id="clickme" class="error">Could not retrieve the permissions because:<br />' . mysql_error($dbc) . '. The query being run was: ' . $query . '</p>';
		} else {
			// OK
			listgroups($dbc);
		}
	} elseif (!$error)
	{	
		if ( isset($_GET['groupid']) && is_numeric($_GET['groupid']) && ($_GET['groupid'] > 0) ) { // Display the entry in a form:
		$query = "SELECT name, description FROM usergroup WHERE id = {$_GET['groupid']}";
		if ($result = mysql_query($query , $dbc)) {
			$row = mysql_fetch_array($result);
			print "<form action=\"index.php?command=editgroup&groupid=" . $_GET['groupid'] . "\" method=\"post\">\n";
			print "<p>Group Name <input type=\"text\" name=\"name\" value=\"" . $row['name'] . "\" /></p>";
			print "<p>Group Description <input type=\"text\" size=\"50\" name=\"description\" value=\"" . $row['description'] . "\"></p>";
			print "<input type=\"hidden\" name=\"groupid\" value=\"" . $_GET['groupid'] . "\" /></p>";
				// Define query for permissions
			$query = "SELECT  permissions.id as permissionsid, permissions.name as permissionsname, permissions.description as permissionsdescription, usergroup_permissions.value as usergrouppermissionsvalue FROM permissions JOIN usergroup_permissions ON usergroup_permissions.permission_id = permissions.id WHERE usergroup_permissions.group_id = {$_GET['groupid']} ORDER BY permissions.displayorder ASC";
			if ($result = mysql_query($query, $dbc)) { // Run the query.
				print "<table><th colspan=\"3\">Group permissions:</th>\n";
				while ($row = mysql_fetch_array($result)) {
					print "<tr><td>" . $row['permissionsdescription'] . "</td>\n";
					if ($row['usergrouppermissionsvalue'] == 1){
						$allowchecked = "checked=\"checked\"";
						$denychecked = "";
					} else {
						$allowchecked = "";
						$denychecked = "checked=\"checked\"";
					};
					print "<td><input type=\"radio\" name=\"" . $row['permissionsid'] . "\" value=\"1\" $allowchecked />Allow</td>";
					print "<td><input type=\"radio\" name=\"" . $row['permissionsid'] . "\" value=\"0\" $denychecked />Deny</td>";
					print "</tr>\n";
				} 
				print "</table>\n";
			} else { // Couldn't get the information.
				print '<p id="clickme" class="error">Could not retrieve the permissions because:<br />' . mysql_error($dbc) . '. The query being run was: ' . $query . '</p>';
			}
			print "<p><input class=\"save\" type=\"submit\" name=\"submit\" value=\"Save\" /></p>\n";
			print "</form>\n";
			}
		}
	}
}

?>