define(function(require, exports, module) {

	var $ = require('jquery'); //加载Jquery 1.11.1
	var common = require('common'); //加载公共函数文件（语言文字获取等）

	/*弹出框*/
	require('own/admin/templates/remodal/jquery.remodal.css');
	require('own/admin/templates/remodal/jquery.remodal.min');
	if($(".temset").length>0){
		function schange(dm){
			var tr = dm.parents("tr"),
				id = tr.find("input[name='id']").val(),
				vl = dm.val();
			if(vl==1){
				$("input[name='defaultvalue-"+id+"']").hide();
				$("input[name='tips-"+id+"']").hide();
				$("input[name='name-"+id+"']").hide();
				tr.addClass('fenqu');
				tr.find(".nowaddlist").show();
				tr.find("i").show();
			}else{
				$("input[name='defaultvalue-"+id+"']").show();
				$("input[name='tips-"+id+"']").show();
				$("input[name='name-"+id+"']").show();
				tr.removeClass('fenqu');
				tr.find(".nowaddlist").hide();
				tr.find("i").hide();
			}
			if(vl==4||vl==6){
				tr.find(".selectd").show();
			}else{
				tr.find(".selectd").hide();
			}
		}
		
		$(document).on( 'init.dt', function (e, settings, json) {
			if($(".temset tr.fenqu").length==0){
				$(".temset tr").removeClass('xuanxiang');
			}
			$(".temset tr.fenqu:eq(0)").prevAll().removeClass('xuanxiang');
			$("select.temset_select").each(function(){
				schange($(this));
			});
			/*拖曳排序*/
			require('own/admin/templates/js/jquery.dragsort-0.5.2.min');
			$("table.ui-table tbody").dragsort({ 
				dragSelector: "tr", 
				dragBetween: false ,
				dragSelectorExclude:"input,textarea,select,a,i",
				dragEnd: function() {
					var pid = $(this).prev('tr'),
						id  = $(this).find("input[name='id']").val();
					$("input[name='id']").attr("checked",true);
					$("input[name='no_order-"+id+"']").val(pid.find("input[name='id']").val());
				}
			}); 
		});
		
		$(document).on('change',"select.temset_select",function(){
			schange($(this));
		});
			
		var box = $('[data-remodal-id=modal]');

		$(document).on('click',"a.selectd",function(){
			var url = $(this).attr('href'),
				tr = $(this).parents("tr"),
				id = tr.find("input[name='id']").val(),
				da = {
					selectd:$("input[name='selectd-"+id+"']").val(),
					type:$("select[name='type-"+id+"']").val(),
					style:$("input[name='style-"+id+"']").val(),
					id:id
				};
				
				box.find(".temset_box").load(url,da,function(){
					var inst = $.remodal.lookup[box.data('remodal')];
					common.defaultoption($(this));
					inst.open();
					common.AssemblyLoad($(this));
				});
			return false;
		});
		
		$(document).on('click',".temsetlist input[name='style-submit']",function(){
			var vl = $(".temsetlist input[name='temstyle']:checked").val();
			$("input[name='style-"+$(".temsetlist").attr("data-temid")+"']").val(vl);
			var inst = $.remodal.lookup[box.data('remodal')];
			inst.close();
			return false;
		});
		
		$(document).on('click',".temsetlist input[name='selectd-submit']",function(){
			var vl = $(".temsetlist input[name='selectd']").val();
				vl = vl.replace(/\|/g,'$T$');
			$("input[name='selectd-"+$(".temsetlist").attr("data-temid")+"']").val(vl);
			var inst = $.remodal.lookup[box.data('remodal')];
			inst.close();
			return false;
		});
		$(document).on('click',".temset input[name='save']",function(){
			$("input[name='id").attr('checked',true);
			var dom = $(".temset .dataTable tr i");
			$('.temset .dataTable tbody tr').removeClass('xuanxiang');
			dom.attr('class','fa fa-caret-down');
		});
		
		$(document).on('click',".temset tr.fenqu i",function(){
			var tr = $(this).parents('tr.fenqu');
			var trlist = tr.nextUntil('tr.fenqu');
			if($(this).attr('class')=='fa fa-caret-right'){
				trlist.removeClass('xuanxiang');
				$(this).attr('class','fa fa-caret-down');
			}else{
				trlist.addClass('xuanxiang');
				$(this).attr('class','fa fa-caret-right');
			}
		});
		
		$(document).on('mousedown',".temset tr.fenqu",function(e){
			if(e.target.tagName!='I'){
				var i = $(this).find('i');
				var trlist = $(this).nextUntil('tr.fenqu');	
				trlist.removeClass('xuanxiang');
				i.attr('class','fa fa-caret-down');
			}
		});
		
		$(document).on('click',".temset .dataTable tfoot tr i,.temset .dataTable thead tr i",function(){
			var dom = $(".temset .dataTable tr i");
			var tr = $('.temset .dataTable tbody tr.fenqu');
			if($(this).attr('class')=='fa fa-caret-right'){
				$('.temset .dataTable tbody tr').removeClass('xuanxiang');
				dom.attr('class','fa fa-caret-down');
			}else{
				$('.temset .dataTable tbody tr').not(tr).addClass('xuanxiang');
				dom.attr('class','fa fa-caret-right');
			}
		});
		
		var ainew = 10000;
		$(document).on('click',".nowaddlist:visible",function(){
			var i = $(this).parents('tr.fenqu');
			var d = i.nextUntil('tr.fenqu');
			d.removeClass('xuanxiang');
			i.find('i').attr('class','fa fa-caret-down');
			d = d.length>0?d.last():i;
			//AJAX获取HTML并追加到页面
			d.after('<tr><td colspan="'+d.find('td').length+'">Loading...</td></tr>');
			
			$.ajax({
				url: $(this).attr("href"),//新增行的数据源
				type: "POST",
				data: 'ai=' + ainew,
				success: function(data) {
					d.next("tr").remove();
					d.after(data);
					d.next("tr").find("input[type='text']").eq(0).focus();
					common.defaultoption(d.next("tr"));
				}
			});
			
			ainew++;
			return false;
		});
	}else{
		$(document).on('click',"a.addtem",function(){
			var url = $(this).attr('href');
			var box = $('[data-remodal-id=modal]');
			box.find(".temset_box").load(url,'',function(){
				var inst = $.remodal.lookup[box.data('remodal')];
				common.defaultoption($(this));
				inst.open();
				common.modexecution();
			});
			return false;
		});
	}
	

});
