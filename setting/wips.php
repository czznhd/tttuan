<?php
/**
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package php
 * @name wips.php
 * @date 2013-07-31 16:18:09
 */
 


$config["wips"] =  array (
  'sql' => 
  array (
    'enabled' => true,
    'dfunction' => 'load_file,hex,substring,if,ord,char,ascii,mid',
    'daction' => 'intooutfile,intodumpfile,unionselect,unionall,uniondistinct,(select',
    'dnote' => '#,--',
    'afullnote' => 'true',
    'dlikehex' => 'true',
    'foradm' => 'false',
    'autoups' => 'true',
  ),
);
?>