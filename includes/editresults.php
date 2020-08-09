<?php
    //Number of Results per page
    $resPerpage = 6;

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
    $token = Token::generate("edits_token");

    print '<div class="edits">';
    //printing the results out
    while ($index < ($resPerpage * $page + 1))
    {
        $image->retrieve($index, 'ID');

        if ($image->data()->USERID == Session::get(Config::get('session/session_name')))
        {
            if ($image->data()->IMAGE == null)
            {
                break ;
            }
            showedits($image, $token);
            $image->data()->IMAGE = null;
        }
        $index++;
    }
    print '</div>';
    //display links of all pages
    print '<div class="paginate">';
    print ' <div class="pages">';
    for ($page = 1; $page <= $total_pages; $page++)
    {
        print '     <a class="page_number" href="editpics.php?page='. $page . '">'. $page .'</a> ';
    }
    print ' </div>';
    print '</div>';
?>