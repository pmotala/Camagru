<?php
    require_once 'core/init.php';
    
   if (Input::exists())
    {
        if (Token::check(Input::get('token')))
        {
            if (!empty(Input::get('web_upload')))
            {
                $validate = new Validate();
                $validation = $validate->check($_POST, array(
                    'comment' => array(
                        'required' => true,
                        'max' => 200,
                        'min' => 2
                    ),
                    'title' => array(
                        'required' => true,
                        'max' => 40,
                        'min' => 2
                    ),
                ));

                if (empty(Input::get('baseimg')) || strlen(Input::get('baseimg')) < 2 || !isset($_POST['baseimg']))
                {
                    $validation->insertError("Please take a webcam Image before Uploading");
                    $validation->setPassed(false);
                }

                if ($validation->passed())

                {   $main_image = explode(',', $_POST['baseimg']);
                    $image = $main_image[1]; 
                    $image = base64_decode($image);
        
                    $main_image = imagecreatefromstring($image);
                    $user = new User();
                    $comment = new Comment();
                    $image = new Image();
                    $comm_id = $user->data()->USERNAME.time();
                    $save_string = "none.png";
                    $main_image = crop(0, 100, getWidth($main_image), getHeight($main_image), $main_image);

                    foreach (Input::get('sticker') as $stickerarray)
                    {
                        if ($stickerarray !== 'none')
                        {  
                            $url = explode(">", $stickerarray);
                            $url = $url[1];
                            $arr = explode("/", $url);
                            $img_file = dirname(__FILE__)."/temp/".$arr[count($arr) - 1];
                            $data = file_get_contents($url);
                            $fp = fopen($img_file, "w");
                            fwrite($fp, $data);
                            fclose($fp);
                            $sticker = imagecreatefrompng($img_file);
                        }

                        //RESIZE STICKERS
                        if ($stickerarray !== 'none')
                        {
                            $sticker_type = explode(">", $stickerarray);
                            $sticker_type = $sticker_type[0];
                            if ($sticker_type === 'balloons')
                            {
                                $save_string = "balloons.png";
                                $sticker = bestFit(getWidth($main_image), getHeight($main_image), $sticker);
                                $check = imageCopyMergeAlpha($main_image, $sticker, (getWidth($main_image) - getWidth($sticker))/2, 0, 0, 0, getWidth($sticker), getHeight($sticker), 100);
                            }
                            else if ($sticker_type === 'groot')
                            {
                                $save_string = "groot.png";
                                $sticker = resize(null, getHeight($main_image)/2, $sticker);
                                $check = imageCopyMergeAlpha($main_image, $sticker, 0, getHeight($main_image) - getHeight($sticker), 0, 0, getWidth($sticker), getHeight($sticker), 100);
                            }
                            else if ($sticker_type === 'dice')
                            {
                                $save_string = "dice.png";
                                $sticker = resize(null, getHeight($main_image)/2, $sticker);
                                $check = imageCopyMergeAlpha($main_image, $sticker, 0, getHeight($main_image) - getHeight($sticker), 0, 0, getWidth($sticker), getHeight($sticker), 100);
                            }
                            else if ($sticker_type === 'apple')
                            {
                                $save_string = "apple.png";
                                $sticker = resize(null, getHeight($main_image)/3, $sticker);
                                $check = imageCopyMergeAlpha($main_image, $sticker, 0, 0, 0, 0, getWidth($sticker), getHeight($sticker), 100);
                            }
                            imagesavealpha($main_image, true);
                            unlink($img_file);
                        }
                    }
                    if ($check !== false || Input::get('sticker')[0] === 'on')
                    {
                        $final_img_dir = dirname(__FILE__)."/temp/final/".$save_string;
                        $fp = fopen($final_img_dir, "w");
                        fclose($fp);
                        $final_image = resize(getWidth($main_image)/1.5, getHeight($main_image)/1.5, $main_image);
                        imagepng($final_image, $final_img_dir, 9);
                        imagedestroy($main_image);
                        imagedestroy($final_image);
                        if ($sticker)
                        {
                            imagedestroy($sticker);
                        }
                        $final_image = base64_encode(file_get_contents($final_img_dir));
                        try
                        {
                            $image->upload(array(
                                'USERID'	=> $user->data()->ID,
                                'USERNAME'	=> $user->data()->USERNAME,
                                'COMM_ID'	=> $comm_id,
                                'IMAGE'		=> $final_image,
                                'TYPE'		=> "image/png",
                                'TITLE'		=> Input::get('title'),
                                'DATE'		=> date('Y-m-d H:i:s')
                            ));

                            $comment->create(array(
                                'USERID' 	=> $user->data()->ID,
                                'USERNAME'	=> $user->data()->USERNAME,
                                'COMM_ID'	=> $comm_id,
                                'COMMENT'	=> Input::get('comment')
                            ));

                            unlink($final_img_dir);

                            Session::flash('takesnap', 'Uploaded Successfully');
                            header("Location: takesnap.php");
                        }
                        catch(Exception $e)
                        {
                            alert($e->getMessage());
                        }
                    }
                }
                else
                {
                    $errors = $validation->errors();
                    Session::flash("takesnap_errors", $errors);
                    header("Location: takesnap.php");
                }
            }
            else if (!empty(Input::get('image_upload')))
            {
                $validate = new Validate();
                $validation = $validate->check($_POST, array(
                    'uploadcomment' => array(
                        'required' => true,
                        'max' => 200,
                        'min' => 2
                    ),
                    'uploadtitle' => array(
                        'required' => true,
                        'max' => 40,
                        'min' => 2
                    ),
                ));

                if (!isset($_FILES['image']) || empty($_FILES['image']['tmp_name']))
                {
                    $validation->insertError('Image is required');
                    echo "THIS ERROR. <br>";
                }

                if (empty($validation->errors()))
                {
                    $validation->setPassed(true);
                }

                if ($validation->passed())
                {
                    $allowed = array('jpg', 'png', 'jpeg');

                    if (Image::checkFile($allowed, $_FILES['image']))
                    {
                        $image = new Image($_FILES['image']);
                        $user = new User();
                        $comment = new Comment();
                        $comm_id = $user->data()->USERNAME.time();
                        $main_image = base64_decode($image->imageData());
                        $main_image = imagecreatefromstring($main_image);
                        $save_string = "none.png";

                        foreach (Input::get('sticker') as $stickerarray)
                        {
                            if ($stickerarray !== 'none')
                            {  
                                $url = explode(">", $stickerarray);
                                $url = $url[1];
                                $arr = explode("/", $url);
                                $img_file = dirname(__FILE__)."/temp/".$arr[count($arr) - 1];
                                $data = file_get_contents($url);
                                $fp = fopen($img_file, "w");
                                fwrite($fp, $data);
                                fclose($fp);
                                $sticker = imagecreatefrompng($img_file);
                            }

                            //RESIZE STICKERS
                            if ($stickerarray !== 'none')
                            {
                                $sticker_type = explode(">", $stickerarray);
                                $sticker_type = $sticker_type[0];
                                if ($sticker_type === 'balloons')
                                {
                                    $save_string = "balloons.png";
                                    $sticker = bestFit(getWidth($main_image), getHeight($main_image), $sticker);
                                    $check = imageCopyMergeAlpha($main_image, $sticker, (getWidth($main_image) - getWidth($sticker))/2, 0, 0, 0, getWidth($sticker), getHeight($sticker), 100);
                                }
                                else if ($sticker_type === 'groot')
                                {
                                    $save_string = "groot.png";
                                    $sticker = resize(null, getHeight($main_image)/2, $sticker);
                                    $check = imageCopyMergeAlpha($main_image, $sticker, 0, getHeight($main_image) - getHeight($sticker), 0, 0, getWidth($sticker), getHeight($sticker), 100);
                                }
                                else if ($sticker_type === 'dice')
                                {
                                    $save_string = "dice.png";
                                    $sticker = resize(null, getHeight($main_image)/2, $sticker);
                                    $check = imageCopyMergeAlpha($main_image, $sticker, 0, getHeight($main_image) - getHeight($sticker), 0, 0, getWidth($sticker), getHeight($sticker), 100);
                                }
                                else if ($sticker_type === 'apple')
                                {
                                    $save_string = "apple.png";
                                    $sticker = resize(null, getHeight($main_image)/3, $sticker);
                                    $check = imageCopyMergeAlpha($main_image, $sticker, 0, 0, 0, 0, getWidth($sticker), getHeight($sticker), 100);
                                }
                                imagesavealpha($main_image, true);
                                unlink($img_file);
                            }
                        }
                        if ($check !== false || Input::get('sticker')[0] === 'on')
                        {
                            $final_img_dir = dirname(__FILE__)."/temp/final/".$save_string;
                            $fp = fopen($final_img_dir, "w");
                            fclose($fp);
                            $final_image = resize(getWidth($main_image)/1.5, getHeight($main_image)/1.5, $main_image);
                            imagepng($final_image, $final_img_dir, 9);
                            imagedestroy($main_image);
                            imagedestroy($final_image);
                            if ($sticker)
                            {
                                imagedestroy($sticker);
                            }
                            $final_image = base64_encode(file_get_contents($final_img_dir));
                            try
                            {
                                $image->upload(array(
                                    'USERID'	=> $user->data()->ID,
                                    'USERNAME'	=> $user->data()->USERNAME,
                                    'COMM_ID'	=> $comm_id,
                                    'IMAGE'		=> $final_image,
                                    'TYPE'		=> "image/png",
                                    'TITLE'		=> Input::get('uploadtitle'),
                                    'DATE'		=> date('Y-m-d H:i:s')
                                ));

                                $comment->create(array(
                                    'USERID' 	=> $user->data()->ID,
                                    'USERNAME'	=> $user->data()->USERNAME,
                                    'COMM_ID'	=> $comm_id,
                                    'COMMENT'	=> Input::get('uploadcomment')
                                ));
                                
                                unlink($final_img_dir);

                                Session::flash('takesnap', 'Uploaded Successfully');
                                header("Location: takesnap.php");
                            }
                            catch(Exception $e)
                            {
                                alert($e->getMessage());
                            }
                        }
                        else
                        {
                        Session::flash("takesnap_errors", "Type not Supported!");
                        header("Location: takesnap.php");
                        }
                    }
                }
                else
                {
                    Session::flash("takesnap_errors", $validation->errors());
                    header("Location: takesnap.php");
                }
            }
        }
    }
?>