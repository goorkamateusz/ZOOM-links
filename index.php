<!doctype html>
<html lang="pl">
<head>
   <meta charset="utf-8">
   <title>ZOOM-links</title>
   <meta name="Author" content="Gorka Mateusz">
   <meta name="robots" content="noindex,nofollow">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="Short icon" href=""/>
   <link rel="Stylesheet" type="text/css" href="css/main.css"/>
</head>
<body>

    Lista zaproszeń:<br/>

    <?php
        include( "class/Invitation.php" );

        $invArr = Invitation::load();

        foreach( $invArr as $std ){
            $inv = new Invitation( $std );

            echo ":<br/>";
            $inv->display();
            echo "<br/>";
        }

    ?>

    <script src="js/main.js"></script>
</body>
</html>
