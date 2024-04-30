<?php
//$wpLoadFile = "../../../../../../wp-load.php";
//if (file_exists($wpLoadFile)) {
//    require_once($wpLoadFile);
//}
$imageSrc = !empty($_GET['imgSrc']) ? filter_var($_GET['imgSrc'], FILTER_SANITIZE_URL) : '';
$height = !empty($_GET['height']) ? filter_var($_GET['height'], FILTER_SANITIZE_URL) : '';

if (empty($imageSrc)) {
    echo "Please provide valid image url!!";
    exit;
} ?>
<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panoroma view</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.css"/>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.js"></script>
    <style>
        html, body {
            border: 0;
            padding: 0;
            margin: 0;
        }

        #panorama {
            width: 100%;
            height: <?php echo $height ?: "400px" ?>;
        }
    </style>
</head>
<body>
<div id="panorama"></div>
<script>
    pannellum.viewer('panorama', {
        "panorama": "<?php echo $imageSrc ?>",
        "showControls": <?php echo !empty($_GET['showControl']) ? "true": "false" ?>,
        "autoLoad": <?php echo !empty($_GET['autoLoad']) ? "true": "false" ?>,
    });
</script>

</body>
</html>