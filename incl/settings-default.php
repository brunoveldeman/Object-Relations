<?php // setting [Application settings]
/* Set some general constants */
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

// Location to store files
$targetfilepath = "filestore/";
// Application name
$appname = "Object Relations";
// Application Version
$appver = "v0.1.0.0 Ant";
// Database servername
$dbserver = "??????";
// Database name
$dbname = "??????";
// Database user name
$dbusername = "??????";
// Database user password
$dbuserpassword = "??????";
// Debug mode (Set false for normal operation)
$debugmode = false;

// Getters for the above variables
function getfilepath() {
	global $targetfilepath;
	return($targetfilepath);
}

function getappname() {
	global $appname;
	return($appname);
}

function getappver() {
	global $appver;
	return($appver);
}

function getdbserver() {
	global $dbserver;
	return($dbserver);
}

function getdbname() {
	global $dbname;
	return($dbname);
}

function getdbusername() {
	global $dbusername;
	return($dbusername);
}

function getdbuserpassword() {
	global $dbuserpassword;
	return($dbuserpassword);
}

function getdebugmode() {
	global $debugmode;
	return($debugmode);
}

?>