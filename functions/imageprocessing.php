<?php

function getWidth($image)
{
    return (int) imagesx($image);
}

function getHeight($image)
{
    return (int) imagesy($image);
}

function getOrientation($image)
{
    $width = getWidth($image);
    $height = getHeight($image);

    if ($width > $height)
    {
        return 'Landscape';
    }
    if ($width < $height)
    {
        return 'Potrait';
    }
    return 'Square';
}

function keepWithin($value, $min, $max)
{
    if ($value < $min) return $min;
    if ($value > $max) return $max;
    return $value;
}

function getAspectRatio($image)
{
    return getWidth($image)/getHeight($image);
}

function crop($x1, $y1, $x2, $y2, $image)
{
    $x1 = keepWithin($x1, 0, getWidth($image));
    $x2 = keepWithin($x2, 0, getWidth($image));
    $y1 = keepWithin($x1, 0, getHeight($image));
    $y2 = keepWithin($x2, 0, getHeight($image));

    $image = imagecrop($image, [
        'x' => min($x1, $x2),
        'y' => min($y1, $y2),
        'width' => abs($x2 - $x1),
        'height' => abs($y2 - $y1)
    ]);

    return $image;
}

function resize($width = null, $height = null, $image)
{
    if (!$width && !$height)
    {
        return $image;
    }

    if ($width && !$height)
    {
        $height = $width / getAspectRatio($image);
    }

    if (!$width && $height)
    {
        $width = $height * getAspectRatio($image);
    }

    if (getWidth($image) === $width && getHeight($image) === $height)
    {
        return $image;
    }

    $newImage = imagecreatetruecolor($width, $height);
    $transparentColor = imagecolorallocatealpha($newImage, 0, 0, 0, 127);
    imagecolortransparent($newImage, $transparentColor);
    imagefill($newImage, 0, 0, $transparentColor);
    imagecopyresampled($newImage, $image, 0, 0, 0, 0, $width, $height, getWidth($image), getHeight($image));
    $image = $newImage;

    return $image;
}

function bestFit($maxWidth, $maxHeight, $image)
{
    if (getWidth($image) <= $maxWidth && getHeight($image) <= $maxHeight)
    {
        return $this;
    }

    if (getOrientation($image) === 'Potrait')
    {
        $height = $maxheight;
        $width = $maxheight * getAspectRatio($image);
    }
    else
    {
        $width = $maxWidth;
        $height = $maxWidth / getAspectRatio($image);
    }

    if ($width > $maxWidth)
    {
        $width = $maxWidth;
        $height = $height / getAspectRatio($image);
    }

    if ($height > $maxHeight)
    {
        $height = $maxHeight;
        $width = $height * getAspectRatio($image);
    }

    return resize($width, $height, $image);
}

function imageCopyMergeAlpha($dstIm, $srcIm, $dstX, $dstY, $srcX, $srcY, $srcW, $srcH, $pct)
{
    if ($pct < 100)
    {
        imagealphablending($srcIm, false);
        imagefilter($srcIm, IMG_FILTER_COLORIZE, 0, 0, 0, 127 * ((100 - $pct) / 100));
    }

    imagecopy($dstIm, $srcIm, $dstX, $dstY, $srcX, $srcY, $srcW, $srcH);
    return true;
}
?>