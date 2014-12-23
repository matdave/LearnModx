<?php
$xpdo_meta_map['modLearnModx']= array (
  'package' => 'learnmodx',
  'version' => '1.1',
  'table' => 'learnmodx',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'stage' => 1,
    'substage' => 1,
    'data' => NULL,
  ),
  'fieldMeta' => 
  array (
    'stage' => 
    array (
      'dbtype' => 'int',
      'precision' => '3',
      'phptype' => 'integer',
      'null' => false,
    ),
    'substage' => 
    array (
      'dbtype' => 'int',
      'precision' => '3',
      'phptype' => 'integer',
      'null' => false,
    ),
    'data' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
      'default' => NULL,
    ),
  ),
);