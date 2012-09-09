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
function getfilepath() {
	$targetfilepath = "filestore/";
	return($targetfilepath);
}

function getappname() {
	$appname = "Object Relations";
	return($appname);
}

function getappver() {
	$appver = "v0.0.2.1 Beta release";
	return($appver);
}

?>