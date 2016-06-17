<?php 

class Paging {

	private	$_tablename ='';
	private	$_columns ='*';
	private $_columnLabels = '';
	private	$_where ='';
	private	$_options = array('limit'=> 10,'stages'=>3);
	private	$_hasError = false;
	private $_result = '';
	private $_numrows = '';
	private $_pagenum = 0;
	private $_search ='';
	public function __construct($o){
		if(count($o) > 0){
			$this->_options = array_merge($this->_options,$o);
			
		}
	}
	public function setTableName($n=''){
		if(!$n){
			$this->_hasError = true;
		} else {
			$this->_tablename = $n;
			
			$this->_columns = array_keys(mysql_fetch_assoc(mysql_query("Select * from " . $this->_tablename . " limit 1")));
			$this->_columns = implode(',', $this->_columns);
		}
	}

	public function setPageNum($n=0){
		if(!$n){
			$this->_pagenum = 0;
		} else {
			$this->_pagenum = $n;
		}
	}
	public function setWhere($w=''){
		if(!$w){
			$this->_where = 'WHERE 1=1';
		} else {
			$this->_where = 'WHERE '.$w;
		}
	}
	public function setSearch($s=''){
		if(!$s){
			$this->_search = '';
		} else {
			$this->_search = ' and (';
			foreach (explode(',',$this->_columns) as $value) {
				$this->_search .=   $value.' like "%'.$s.'%" or ';
			}
			$this->_search = rtrim($this->_search,'or ');
			$this->_search .= ') ';
		}
	}
	public function setColumns($c){
		if($c && is_array($c) && count($c) > 0){
			$this->_columns = implode(',', $c);
		}
	}
	public function setColumnLabels($c){
		if($c && is_array($c)){
			$this->_columnLabels =$c;
		}
	}
	public function paginate(){
			
		
		if($this->_pagenum) {
			$start = ($this->_pagenum - 1) * $this->_options['limit'];
		} else {
			$start = 0;
		}
			$this->_numrows = mysql_result(mysql_query("Select count(*) from " . $this->_tablename . ' '.$this->_where . $this->_search),0);
			  $sql = "Select " . $this->_columns . " from " . $this->_tablename . ' '.$this->_where .$this->_search. " limit ".$start. ", " . $this->_options['limit'];
			$this->_result = mysql_query($sql);
			
			$this->getpagenavigation($this->_pagenum,$this->_numrows,$this->_options['limit'],$this->_options['stages']);
			if ($this->_numrows) {
				echo "<table class='table'>";
				if($this->_columns != '*'){
				echo "<tr>";
					if(!$this->_columnLabels){
						$trhead =explode(',',$this->_columns); 
					} else {
						$trhead = $this->_columnLabels;
					}
					foreach($trhead as $c){
						echo "<th>".ucfirst($c)."</th>";
					}
				echo "</tr>";
				$arrcol =explode(',',$this->_columns);
				}
				while($row = mysql_fetch_assoc($this->_result)){
					echo "<tr>";
					$arrcol = (isset($arrcol))?$arrcol: array_keys($row);
					foreach($arrcol as $c){
						echo "<td>".$row[$c]."</td>";
					}
					echo "</tr>";
				}	
				echo "</table>";
			} else {
				echo "<div class='alert alert-info'>No Record found.</div>";
			}
			}

	public function getpagenavigation($page,$total_pages,$limit,$stages){

		if ($page == 0){$page = 1;}
		$prev = $page - 1;
		$next = $page + 1;
		$lastpage = ceil($total_pages/$limit);
		$LastPagem1 = $lastpage - 1;


		$paginate = '';
		if($lastpage > 1)
		{
			$paginate .= '<div class="row"><div class="col-md-4" style="padding-top:20px;"></div>';
			$paginate .= "<div class='col-md-8 text-right'><ul class='pagination'>";

			if ($page > 1){
				$paginate.= "<li><a href='#'  class='paging' page='$prev' style='padding:5px'>PREVIOUS</a></li>";
			}else{
				$paginate.= "<li class='disabled'><span class='disabled' style='padding:5px'>PREVIOUS</span></li>"; }




			if ($lastpage < 7 + ($stages * 2))
			{
				for ($counter = 1; $counter <= $lastpage; $counter++)
				{
					if ($counter == $page){
						$paginate.= "<li class='active'><span class='current' style='padding:5px'>$counter</span></li>";
					}else{
						$paginate.= "<li><a href='#'  class='paging' page='$counter' style='padding:5px'>$counter</a></li>";}
				}
			}
			elseif($lastpage > 5 + ($stages * 2))
			{

				if($page < 1 + ($stages * 2))
				{
					for ($counter = 1; $counter < 4 + ($stages * 2); $counter++)
					{
						if ($counter == $page){
							$paginate.= "<li class='active'><span class='current' style='padding:5px'>$counter</span></li>";
						}else{
							$paginate.= "<li><a href='#'  class='paging' page='$counter' style='padding:5px'>$counter</a></li>";}
					}
					$paginate.= "<li><a>...</a></li>";
					$paginate.= "<li><a href='#'   class='paging' page='$LastPagem1' style='padding:5px'>$LastPagem1</a></li>";
					$paginate.= "<li><a href='#' class='paging' page='$lastpage' style='padding:5px'>$lastpage</a></li>";
				}

				elseif($lastpage - ($stages * 2) > $page && $page > ($stages * 2))
				{
					$paginate.= "<li><a href='#' class='paging' page='1'  style='padding:5px'>1</a></li>";
					$paginate.= "<li><a href='#' class='paging' page='2'  style='padding:5px'>2</a></li>";
					$paginate.= "<li><a>...</a></li>";
					for ($counter = $page - $stages; $counter <= $page + $stages; $counter++)
					{
						if ($counter == $page){
							$paginate.= "<li class='active'><span class='current' style='padding:5px'>$counter</span></li>";
						}else{
							$paginate.= "<li><a href='#' class='paging' page='$counter'  style='padding:5px'>$counter</a></li>";}
					}
					$paginate.= "<li><a>...</a></li>";
					$paginate.= "<li><a href='#' class='paging' page='$LastPagem1' style='padding:5px'>$LastPagem1</a></li>";
					$paginate.= "<li><a  href='#'  class='paging' page='$lastpage' style='padding:5px'>$lastpage</a></li>";
				}

				else
				{
					$paginate.= "<li><a href='#' class='paging' page='1' style='padding:5px'>1</a></li>";
					$paginate.= "<li><a href='#' class='paging' page='2' style='padding:5px'>2</a></li>";
					$paginate.= "<li><a>...</a></li>";
					for ($counter = $lastpage - (2 + ($stages * 2)); $counter <= $lastpage; $counter++)
					{
						if ($counter == $page){
							$paginate.= "<li class='active'><span class='current' style='padding:5px'>$counter</span></li>";
						}else{
							$paginate.= "<li><a href='#' class='paging' page='$counter'  style='padding:5px'>$counter</a></li>";}
					}
				}
			}


			if ($page < $counter - 1){
				$paginate.= "<li><a href='#' class='paging' page='$next' style='padding:5px'>NEXT</a></li>";
			}else{
				$paginate.= "<li class='disabled'><span class='disabled' style='padding:5px'>NEXT</span></li>";
			}

			$paginate.= "</ul></div></div><div style='clear: both;'></div>";


		}
		// echo $total_pages.' Results';
		echo $paginate;
	}

}