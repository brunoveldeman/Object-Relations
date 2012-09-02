<?php // perm [Permission layer]
/* Give users access permissions */



/* The permission array*/

$perm = array(
    "viewobject" => true,
    "addobject" => true,
    "editobject" => true,
    "deleteobject" => false,
    "viewtype" => true,
    "addtype" => true,
    "edittype" => true,
    "deletetype" => false,
    "viewproperty" => true,
    "addproperty" => true,
    "editproperty" => true,
    "deleteproperty" => false,
    "viewrelation" => true,
    "addrelation" => true,
    "editrelation" => true,
    "deleterelation" => false,
    "viewobjecttype" => true,
    "editobjecttype" => true,
    "viewobjectproperty" => true,
    "addobjectproperty" => true,
    "editobjectproperty" => true,
    "deleteobjectproperty" => false,
    "viewobjectrelation" => true,
    "addobjectrelation" => true,
    "editobjectrelation" => true,
    "deleteobjectrelation" => false,
);

/*
if ( $perm['deleteobject'] ) {
	print "OK";
} else {
	print "NOT OK";
}
*/

?>