$.paging = function(opt){ 
	var o = {};
		o.tableName ='';
		o.columns =[];
		o.columnLabels = [];
		o.where ='';
		o.page=0;
		o.search='';
		o.isajaxsent = false;
	o.options = {
		limit:15,
		stages:3,
		selector:'body' // should be an empty div
	}
	o.setTableName= function(n){
		this.tableName = n;
	}
	o.setColumns = function(c){
		this.columns = c;
	}
	o.setColumnLabels = function(c){
		this.columnLabels = c;
	}
	o.setWhere = function(w){
		this.where = w;
	}
	o.paginate = function(){
		
		if($(this.options.selector + " .pholder").length == 0) $(this.options.selector).append('<input type="text" style="width:50%;" placeholder="Search record" class="form-control page_search"><div class="pholder"></div>'); 
		var contexto = this;
		if(contexto.isajaxsent){
			return;
		}
		contexto.isajaxsent = true;
		$.ajax({
			url:'paging.php',
			type:'POST',
			data:{
				tableName: this.tableName,
				columns: JSON.stringify(this.columns),
				columnLabels: JSON.stringify(this.columnLabels),
				where: this.where,
				page:this.page,
				search:this.search,
				options:JSON.stringify(this.options)
			},
			success: function(data){
				$(contexto.options.selector + '> .pholder').html(data);
				$(contexto.options.selector + '> .pholder .paging').click(function(e){
						e.preventDefault();
						contexto.page =  $(this).attr('page');
						contexto.paginate();
					});
					contexto.isajaxsent = false;
				},
				error:function(){
					contexto.isajaxsent = false;
				}
		});


				$(contexto.options.selector + '> .page_search').keyup(function(e){
					contexto.page = 0;
					contexto.search =  $(this).val();
					contexto.paginate();
				});
	};





	$.extend(o.options,opt);
	
	return o;
}