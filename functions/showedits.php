<?php
    require_once 'core/init.php';

    function showedits($image, $token)
    {
		//Get total number of Comments
		$index = 0;
		
		//print Out Result
        print '	<div class="edit_container">';
        print '		<div class="edit_image">';
        print '			<img class="result" src="data:image;base64,'.escape($image->data()->IMAGE).'">';
		print '		</div>';
        print '		<div class="edit_form">';
		print '			<form class="delete" action="delete.php" method="POST">';
		print '				<input type="hidden" name="comment_id" value="'.escape($image->data()->COMM_ID).'" />';
		print '				<input type="hidden" name="token" value="'. escape($token) .'">';
		print '				<input type="submit" name="delete" value="delete" />';
		print '			</form>';
        print '		</div>';
		print '	</div>';
    }
?>