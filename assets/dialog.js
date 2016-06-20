function openDialog(event){
	var href = $('#editContentDialog').attr('dialog-url');
	
	$('#editContentDialog div.modal-body').html('Загрузка...');
	$('#editContentDialog #content_save_button').remove();
	$("#editContentDialog").modal("show");
	
	var postItems = {};
	$.each(this.attributes, function(i, attribute) {
		if (attribute.name!='class'){
			postItems[attribute.name]=attribute.value;
		}
	});
	postItems[yii.getCsrfParam()]=yii.getCsrfToken();

	setTimeout(function(){
		$.ajax({
			url: href,
			type: 'POST',
			dataType: 'text',				
			data: postItems,			
			success: function(response) {				
				$('#editContentDialog div.modal-body').html(response);					
				$('button.tooltips').tooltip();
			}
		});
	},500);
	return false;
}

$('#editContentDialog').on('hidden.bs.modal', function () {
	$('#editContentDialog div.modal-body').html('Загрузка...');
})

function setHover(i){
	i.hover(function(){
		$(this).parent().css({
			'border-style' : 'dashed',
			'border-color': "black",
			'border-width': "1px"
		});		
	}, function(){
		$(this).parent().css({
			'border-style' : 'none'
		});			
	});
}

function resetEdit(item, container){	
	container.prepend(item);
	setHover(item);
}

$(document).ready(function(){
	$(document).on('click', 'i[place]', openDialog);
	setHover($('i[place]'));
});