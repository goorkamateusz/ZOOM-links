<!doctype html>
<html lang="pl">
<head>
   <meta charset="utf-8">
   <title>ZOOM-links</title>
   <meta name="Author" content="Gorka Mateusz">
   <link rel="short icon" href=""/>
   <link rel="stylesheet" type="text/css" href="css/main.css"/>
   <meta name="robots" content="noindex,nofollow">
   <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>

    <main>
    <?php

        include( "class/Invitation.php" );

        ///- Pobiera i sortuje listę zaproszeń
        $invArr = Invitation::load();

        // Konweruje z stdClass na Invitation class
        function conv($a){ return new Invitation($a); }
        $invArr = array_map( 'conv', $invArr );

        // Sortuje
        usort(
            $invArr,
            function( $a, $b ){
                if ($a->date == $b->date) return 0;
                else return $a->date < $b->date ? 1 : -1;
            }
        );

        ///- Wyświetla wszystkie zaproszenia
        foreach( $invArr as $std ):
        ?>
            <?php
                $inv = new Invitation( $std );

                // Ustawienie klasy: dzisiaj, mineło, przyszłość
                $class = "future";

                if( date_format( new DateTime( $inv->date->date ), "Y-m-d" ) == date_format( new DateTime(), "Y-m-d" ) )
                    { $class = "today"; }
                elseif( new DateTime($inv->date->date) < new DateTime() )
                    { $class = "passed"; }
                else
                    {
                        //?
                    }
            ?>

            <div class="invite <?php echo $class; ?>">

                <?php echo $inv->html(); ?>

            </div>

        <?php endforeach;
    ?>
    </main>

    <script src="js/main.js"></script>
</body>
</html>
