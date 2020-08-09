<?php
    //Number of Results per page
    $resPerpage = 5;
    
    //Total number of results in DB
    $image = new Image();

    $numRes = $image->retrieve(1, 'ALL');
    

    $numRes = $image->getCount();

    //Number of Total Pages
    $total_pages = ceil($numRes/$resPerpage);

    if($image->getCount())
    {
        $image->data()->resetAutoIncrement('uploads', 'ID', 'PRIMARY KEY');
    }

    //Determine the current page
    if (!isset($_GET['page']) || $_GET['page'] <= 0)
    {
        $page = 1;
    }
    else
    {
        if (is_numeric($_GET['page']))
        {
            $page = ($_GET['page']);
            if ($page > $total_pages)
            {
                $page = $total_pages;
                Session::flash('general', "Page does not exist");
            }
        }
    }

    //Limit per page
    $startingLimit = ($page - 1) * $resPerpage;
    
    //Index that changes per page
    $index = $startingLimit + 1;

    //generate token
    $token = Token::generate();

    //Conection to Comments in Database
    $comment = new Comment();

    //Connection to Database;
    $db = Database::getInstance();

    //printing the results out
    while ($index < ($resPerpage * $page + 1))
    {
        $image->retrieve($index, 'ID');
        //LIKES & DISLIKES

        $likes = $db->query("SELECT * FROM likes WHERE COMM_ID = ? AND LIKES = ?", array($image->data()->COMM_ID, 1))->getCount();
        $dislikes = $db->query("SELECT * FROM likes WHERE COMM_ID = ? AND DISLIKES = ?", array($image->data()->COMM_ID, 1))->getCount();

        if ($image->data()->IMAGE == null)
        {
            break ;
        }
        $comment->retrieve($image->data()->COMM_ID, 'COMM_ID');
        $comments = $comment->data()->results();

        if ($comment->data()->getCount())
        {
            $count = count($comments) + 1;
        }
        else
        {
            $count = 1;
        }

        showimage($image, $token, $comments, $count, $likes, $dislikes);
        $image->data()->IMAGE = null;
        $index++;
    }

    //display links of all pages 
    print '<div class="paginate">';
    print ' <div class="pages">';
    for ($page = 1; $page <= $total_pages; $page++)
    {
        print '     <a class="page_number" href="index.php?page='. $page . '">'. $page .'</a> ';
    }
    print ' </div>';
    print '</div>';
?>