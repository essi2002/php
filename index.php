<?php
$msg = "سوال خود را بپرس!";
$question = '';
$message = fopen("messages.txt", "r");
$jsonfile = file_get_contents('people.json');
$jsondecode = json_decode($jsonfile);

$index = 0;
$arr = array();
while( ! feof($message))
{
$arr[$index] = fgets($message);
}

$arraye = array();
$counter = 0;

foreach($jsondecode as $key => $value)
{
$arraye[$counter] = $key;
$counter = $counter + 1;
}

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $en_name = $_POST["person"];
    $question = $_POST["question"];
    $rooz = hash('adler32', $question . " " . $en_name);
    $rooz = hexdec($rooz);
    $rooz = ($rooz % 16);
    $msg = $arr[$rooz];
    foreach($jsondecode as $key => $value)
    {
        if($key == $en_name)
        {
            $fa_name = $value;
        }
    }
}
else
{
    $rand = array_rand($arraye);
    $en_name = arraye[$rand];
    foreach($jsondecode as $key => $value)
    {
        if($key == $en_name)
        {
            $fa_name = $value;
        }
    }
}
$s = "/^آیا/iu";
$lf = "/\?$/i";
$ls = "/؟$/u";
if(!(preg_match($lf , $question) || preg_match($ls , $question)) || ! preg_match($s , $question))
{
    $msg = "سوال درستی پرسیده نشده";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="styles/default.css">
    <title>مشاوره بزرگان</title>
</head>
<body>
<p id="copyright">تهیه شده برای درس کارگاه کامپیوتر،دانشکده کامییوتر، دانشگاه صنعتی شریف</p>
<div id="wrapper">
    <div id="title">
        <span id="label">
        <?php
                if ($question != "")
                {
                    echo "پرسش:";
                }
        ?>
        </span>
        <span id="question"><?php echo $question; ?></span>
    </div>
    <div id="container">
        <div id="message">
            <p><?php 
             if ($question != "") 
             {
                echo $msg;
            } 
            else
            {
            echo "سوال خود را بپرس!";
            }
            ?></p>
        </div>
        <div id="person">
            <div id="person">
                <img src="images/people/<?php echo "$en_name.jpg"; ?>"/>
                <p id="person-name"><?php echo $fa_name; ?></p>
            </div>
        </div>
    </div>
    <div id="new-q">
        <form method="post">
            سوال
            <input type="text" name="question" value="<?php echo $question; ?>" maxlength="150" placeholder="..."/>
            را از
            <select name="person" value="<?php echo $fa_name ?>" action="<?php echo $_SERVER['PHP_SELF']; ?>">

                <?php
                $jsondecode = json_decode($jsonfile);
                foreach ($jsondecode as $key => $value)
                 {
                    if ($en_name == $key) 
                    {
                        echo "<option value=$key selected> $value</option> ";
                    } 
                    else 
                    {
                        echo "<option value=$key > $value</option> ";
                    }
                }
                ?>
            </select>
            <input type="submit" value="بپرس"/>
        </form>
    </div>
</div>
</body>
</html>