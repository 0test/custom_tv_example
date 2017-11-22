<?php 
if(IN_MANAGER_MODE != true){
	die('Hacker?');
}
global $content;
global $row;
echo '<input id="tv'.$row['id'].'" name="tv'.$row['id'].'" type="text" value="'.$row['value'].'"><input type="button" class="btn btn-success btn-parseurl">';

?>
<script>
	$j(document).ready(function(){
		$j('body').on('click','.btn-parseurl',function(){
			$j.ajax({
				method:"POST",
				url:'/assets/tvs/blog_copy/psto.php',
				data:{
					url:$j('#tv'+<?php echo $row['id'];?>).val()
				},
				success:function(data){
					console.log(data);
					$j('input[name=pagetitle]').val(data.pagetitle);
					$j('textarea[name=ta]').text(data.entry);
					$j('input[name=tv7]').val(data.img);
					tinymce.activeEditor.setContent(data.entry);
				}
				
			});
			
		});
		
	});
	
</script>