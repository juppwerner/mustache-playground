<?php
require 'vendor/autoload.php';

$examples = array(
    array(
        'name' => 'Hello World',
        'description' => 'The usual hello World example',
        'template' => 'Hello, {{planet}}!',
        'data' => '{"planet":"World"}',
    ),
    array(
        'name' => 'Conditional',
        'description' => 'Only show a block on a condition',
        'template' => "Hello, {{ planet }}!\r\n\r\n{{# onspecial }}\r\n<p><b>On Special</b>!</p>\r\n{{/ onspecial }}",
        'data' => '{"planet":"World","onspecial":true}',
    ),
);

$template = $examples[0]['template'];
if(isset($_POST['template']))
    $template = $_POST['template'];

$data = $examples[0]['data'];
if(isset($_POST['data']))
    $data = json_decode($_POST['data']);

if(isset($_GET['example'])) {
    if(isset($examples[(int)$_GET['example']-1])) {
        $template = $examples[(int)$_GET['example']-1]['template'];
        $data = json_decode($examples[(int)$_GET['example']-1]['data']);
    }
}


// Parse template + data
$m = new Mustache_Engine;
$html = $m->render($template, $data); // "Hello, World!"
$code = htmlspecialchars($html); 
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>mustache Test Page</title>
    </head>
    <body>
        <h1>mustache Test Page</h1>
<?php if(isset($_GET['example'])) : ?>
<?php if(isset($examples[(int)$_GET['example']-1])) : ?>
        <h2>Example: <?php echo $examples[(int)$_GET['example']-1]['name']; ?></h2>
        <p>Description: <?php echo $examples[(int)$_GET['example']-1]['description']; ?></p>
<?php endif; ?>
<?php endif; ?>
        
        <h2>Examples</h2>
        <ol>
        <?php foreach($examples as $n=>$example) : ?>
        <li><a href="index.php?example=<?php echo $n+1 ?>"><?php echo $example['name'] . ' / '.$example['description'] ?></a></li>
        <?php endforeach; ?>
        </ol>
        <form method="post" action="index.php">
        <table width="100%">
            <tr>
                <td width="50%">
                    <h2>mustache Template</h2>
                    <textarea name="template" rows="10" cols="70"><?php echo $template; ?></textarea>
                </td>
                <td>
                    <h2>Data (JSON)</h2>
                    <textarea name="data" rows="10" cols="70"><?php echo json_encode($data, JSON_PRETTY_PRINT); ?></textarea>
                </td>
            </tr>
            <tr>
                <td>
                    <h2>Compiled HTML</h2>
                    <textarea name="code" rows="10" cols="70"><?php echo $code; ?></textarea>
                </td>
                <td>
                    <h2>Preview</h2>
                    <div style="border:1px solid darkgrey;width:512px;height: 150px"><?php echo $html; ?></div>
                </td>
            </tr>
        </table>
        <input type="submit" value="Submit" />
        </form>
    </body>

</html>
