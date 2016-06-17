<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Paging Plugin New</title>
	<link rel="stylesheet" href="stylesheets/bootstrap.min.css">
</head>
<body>
	<h1>Paging Plugin Fixed</h1>
	<div class="container">
	<div id="listno"></div>
<div id="listno2"></div>
	</div>
<script src="scripts/jquery.js"></script>
<script src="scripts/paging.js"></script>
<script>
	$(function(){
	var paging = $.paging({limit:1,selector:'#listno'});
	paging.setTableName('users');
	paging.setColumns(['name','age']);
	paging.setColumnLabels(['My name','My Age']);
	paging.paginate();

	var paging2 = $.paging({limit:1,selector:'#listno2'});
	paging2.setTableName('users');
	paging2.setColumns(['name','age']);
	paging2.setColumnLabels(['My name','My Age']);
	paging2.paginate();
	});
	
</script>
</body>
</html>