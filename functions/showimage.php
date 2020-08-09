<?php
    require_once 'core/init.php';

    function showimage($image, $token, $comments, $count, $likes, $dislikes)
    {
		//Get total number of Comments
		$index = 0;
		
		//echo Out Result
        echo '<div class="res-container">';
        echo '	<div class="image">';
        echo '		<img class="result" src="data:image;base64,'.escape($image->data()->IMAGE).'" style="width:100%">';
		echo '	</div>';
        echo '	<div class="comments-container">';
        echo '		<div class="image-title">';
		echo '			<div class="comment">';
		echo '				<p class="image-by">'.escape($image->data()->USERNAME).'</p>';
		echo '				<p class="message">'.escape($image->data()->TITLE).'</p>';
		echo '			</div>';
		echo '		</div>';
		echo '		<div class="comments">';
		while ($index <= $count)
        {
            echo '			<div class="comment">';
			echo '				<p class="sent-by">'.escape($comments[$index]->USERNAME).'</p>';
			echo '				<p class="message">'.escape($comments[$index]->COMMENT).'</p>';
			echo '			</div>';
			$index++;
		}
		echo '		</div>';
		echo '		<form class="likes-form" action="likes.php" method="POST">';
		echo '			<input type="hidden" name="comment_id" value="'.escape($image->data()->COMM_ID).'" />';
		echo '			<input type="hidden" name="token" value="'. escape($token) .'">';
		echo '			<input type="submit" id="like" name="like" value="like" />';
		echo '			<a>'.escape($likes).'</a>';	
		echo '			<input type="submit" id="dislike" name="dislike" value="dislike" />';
		echo '			<a>'.escape($dislikes).'</a><br>';
		echo '		</form>';
		echo '		<form class="comment-form" action="comments.php" method="POST">';
        echo '			<input type="hidden" name="comment_id" value="'.escape($image->data()->COMM_ID).'" />';
		echo '			<input type="hidden" name="token" value="'. escape($token) .'">';
        echo '			<input type="text" name="comment" id="comment_text" placeholder="Write Comment" /><br>';
        echo '			<input type="submit" name="submit" id="send" value="Send" />';
        echo '		</form>';
        echo '	</div>';
		echo '</div>';
		return 0;
    }
?>