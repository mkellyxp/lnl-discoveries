<?php
    // Server side call for getting meta descriptions later in the code.
    if ( isset( $_GET['url'] ) ) {
        $l_asMetas = get_meta_tags( $_GET['url'] );
        echo $l_asMetas['description'];
        exit;
    }

    // Main part of the script starts here.
    $url = 'https://latenightlinux.com/feed/mp3';
    $rss = new DOMDocument();
    $rss->load($url);
    $feed = array();
    foreach ($rss->getElementsByTagName('item') as $node) {
        $l_sContent = $node->getElementsByTagName('encoded')->item(0)->nodeValue;
        $l_asContentPieces = explode( '<p><strong>Discoveries</strong></p>', $l_sContent );

        // Only break down shows with Discoveries
        if ( count( $l_asContentPieces ) > 1 ) {
            $l_asContentPieces2 = explode( '<p>&nbsp;</p>', $l_asContentPieces[1] );  // Detect end of discovery section
            $l_asDiscoveries = explode( "\n", $l_asContentPieces2[0] ); // Break discoveries into an array
            $l_asDiscoveriesClean = array();
            foreach( $l_asDiscoveries as $l_sDiscovery ) {
                if ( trim( $l_sDiscovery ) != '' ) {
                    $l_sLinks = array();
                    
                    // Extract the urls for each discovery
                    $dom = new DOMDocument;
                    $dom->loadHTML( $l_sDiscovery );
                    foreach ($dom->getElementsByTagName('a') as $l_xLink) {
                        $l_sLinks[] = $l_xLink->getAttribute( 'href' );
                    }

                    $l_asDiscoveriesClean[] = array( 'display' => $l_sDiscovery, 'links' => $l_sLinks );
                }
            }

            // Load up the array with the final data we need (minus the url descriptions)
            $item = array (
                'title' => str_replace( 'Late Night Linux – ', '', $node->getElementsByTagName('title')->item(0)->nodeValue ),
                'pubDate' => date("F j, Y", strtotime( $node->getElementsByTagName('pubDate')->item(0)->nodeValue ) ),
                'content' => $l_asDiscoveriesClean
            );
            array_push($feed, $item);
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Late Night Linux - Discoveries</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.8/tailwind.min.css" integrity="sha512-sP93un/6HzFSfkHZ4jCTbf4XgiMldakhz+/ibX+8sk6fVvkWvoGhqfFeVlFoY6aEPakF6zI4XvVGF5+t/ahHQg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <style>
        a{
            text-decoration: underline;
            cursor: pointer;
        }
    </style>

</head>
<body class="text-gray-300" style="background-color: #0d0d0d">
    <div class="max-w-5xl mx-auto p-4" style="background-color: #161616">
        <h1 class="text-5xl text-center mt-4 mb-8">Late Night Linux - Discoveries</h1>

        <?php
            foreach( $feed as $show ) {
        ?>
                <h2 class="text-2xl mb-4"><?php echo $show['title']; ?> - <?php echo $show['pubDate'] ?></h2>
                <ul class="list-outside list-disc ml-8">
                    <?php
                        foreach( $show['content'] as $discovery ) {
                    ?>
                            <li class="mb-2">
                                <?php echo $discovery['display']; ?>
                                <?php
                                    foreach( $discovery['links'] as $l_sLink ) {
                                ?>
                                        <div class="text-sm text-gray-400 rounded-lg bg-gray-800 bg-opacity-25 p-2 js_link" data-link="<?php echo $l_sLink ?>">. . .</div>
                                <?php
                                    }
                                ?>
                            </li>
                    <?php
                        }
                    ?>
                </ul>
                <hr class="mb-8 mt-8">
        <?php
            }
        ?>
    </div>


<script>
    // Once page loads, go through every URL and swap out the ... with the meta description of that URL.
    $( document ).ready(function() {
        $( ".js_link" ).each(function() {
            $( this ).load( '<?php echo $_SERVER['REQUEST_URI'] ?>?url=' + $(this).data('link') );
        });
    });
</script>
</body>
</html>