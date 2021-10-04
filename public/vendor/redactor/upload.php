<?php

// This is a simplified example, which doesn't cover security of uploaded images.
// This example just demonstrate the logic behind the process.

exit;
$dir = '/data/web/www.demo.kkk5.com/web/editor/tmp/';
$filename = md5(date('YmdHis')).'.png';
$file = $dir.$filename;

$type = empty($_GET['type']) ? 'drop' : $_GET['type'];
if ($type == 'drop') 
{
    $contentType = $_FILES['file']['type'];
    rename($_FILES['file']['tmp_name'], $file);
}
else 
{
    $contentType = $_POST['contentType'];
    $data = base64_decode($_POST['data']);
    file_put_contents($file, $data);
}

echo json_encode(array('filelink' => 'tmp/'.$filename));

?>
