<?php
/**
 * \file index.php
 * \brief Wyświetla listę zebranych zaproszeń.
 */
?>

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

    <div class="menu">

        <span class="switch" id="future">
            <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="48px" height="24px" version="1.1" style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd"
                viewBox="0 0 4800 2400"
                xmlns:xlink="http://www.w3.org/1999/xlink">
                <defs>
                <style type="text/css">
                    <![CDATA[
                    .str0 {stroke:black;stroke-width:300}
                    .str1 {stroke:black;stroke-width:400}
                    .fil1 {fill:#fff}
                    .fil0 {fill:#11989B;opacity:0.5}
                    ]]>
                </style>
                </defs>
                <g>
                    <metadata id="CorelCorpID_0Corel-Layer"/>
                    <rect class="fil1 str1" x="249" y="200" width="4301" height="2000" rx="831" ry="1000"/>
                    <rect class="fil0 str0" x="249" y="200" width="2001" height="2000" rx="831" ry="1000"/>
                </g>
            </svg>
            <a>Ukryj przyszłe</a>
        </span>

        <span class="switch" id="passed">
            <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="48px" height="24px" version="1.1" style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd"
                viewBox="0 0 4800 2400"
                xmlns:xlink="http://www.w3.org/1999/xlink">
                <defs>
                <style type="text/css">
                    <![CDATA[
                    .str0 {stroke:black;stroke-width:300}
                    .str1 {stroke:black;stroke-width:400}
                    .fil1 {fill:#fff}
                    .fil0 {fill:#11989B;opacity:0.5}
                    ]]>
                </style>
                </defs>
                <g>
                    <metadata id="CorelCorpID_0Corel-Layer"/>
                    <rect class="fil1 str1" x="249" y="200" width="4301" height="2000" rx="831" ry="1000"/>
                    <rect class="fil0 str0" x="249" y="200" width="2001" height="2000" rx="831" ry="1000"/>
                </g>
            </svg>
            <a>Ukryj minione</a>
        </span>

    </div>

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
        foreach( $invArr as $inv ):
        ?>
            <?php
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

    <script src="js/jquery.js"></script>
    <script src="js/main.js"></script>
</body>
</html>