<?php 
	
	$con = mysql_connect('localhost','root','');
	mysql_select_db("testdb",$con);
	include 'classes/Paging.php';
	$tableName = !empty($_POST['tableName']) ? $_POST['tableName'] :'';
	$where = !empty($_POST['where']) ? $_POST['where'] :'';
	$columns =  json_decode($_POST['columns'],true);
	$columnLabels = json_decode($_POST['columnLabels'],true);
	$options = json_decode($_POST['options'],true);
	$page = $_POST['page'];
	$search = $_POST['search'];

	$paging = new Paging($options);
	$paging->setPageNum($page);
	$paging->setTableName($tableName);
	$paging->setColumns($columns);
	$paging->setColumnLabels($columnLabels);
	$paging->setWhere($where);
	$paging->setSearch($search);
	$paging->paginate();

	
?>