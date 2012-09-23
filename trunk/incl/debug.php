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
/*   debugprint                                                    */
/*******************************************************************/
//
// Description:
//   Print only if debug flag is on
//
// Inputs: 
//   function:
//	   ($string): String to print
//   POST:
//	   none
//   GET:
//	   all
//
// Output:
//   return:
//     none
//   HTML:
//     print $string
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
//   
/********************************************************************/
function debugprint($string)
{
	if(getdebugmode())
	{
		print $string;
	}
}


?>