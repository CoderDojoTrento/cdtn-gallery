<!DOCTYPE html>
<html>
  <head>
      <title>CDTN Gallery</title>
    
        <!--  
            A simple directory index and image gallery with no extra-frills
        
            Features:
              - a single php file (css and images are embedded) 
              - Clicking an image simply opens the barebone pic in a new tab, no lightboxes
              - folders shown before files
              - Sorts folders, files
              - parent folder link
              - no js wizardry
              
              Credits:  CoderDojo Trento  coderdojotrento.it
                             David Leoni  info@davidleoni.it
                
              Graphics: 
              -  file:    https://www.svgrepo.com/svg/6994/file      (CC0 License)
              -  folder:  https://www.svgrepo.com/svg/22198/folder   (CC0 License)
              
        -->    
        
        <style>
            .gallery {
                display: grid;
                grid-template-columns: repeat(3, auto); /* IMAGES PER ROW */
                grid-gap: 10px;
                max-width: 1200px;
                margin: 0 auto; /* HORIZONTAL CENTER */
                }
                
                /* SMALL SCREENS */
                @media screen and (max-width: 640px) {
                .gallery {
                    grid-template-columns: repeat(2, auto); /* IMAGES PER ROW */
                }
            }

            .gallery img {
                max-width: 100%;
                cursor: pointer;
                object-fit: cover;
                }
                .gallery img:fullscreen { object-fit: contain; }
    
                body, html {
                    padding: 0;
                    margin: 0;
            }
        </style>
  </head>
  <body>

    <div class="gallery">
        
        <?php 
$folderSvg = <<<'EOD'
<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="150px"
	 viewBox="0 0 58 58" style="enable-background:new 0 0 58 58;" xml:space="preserve">
<path style="fill:#EFCE4A;" d="M46.324,52.5H1.565c-1.03,0-1.779-0.978-1.51-1.973l10.166-27.871
	c0.184-0.682,0.803-1.156,1.51-1.156H56.49c1.03,0,1.51,0.984,1.51,1.973L47.834,51.344C47.65,52.026,47.031,52.5,46.324,52.5z"/>
<g>
	<path style="fill:#EBBA16;" d="M50.268,12.5H25l-5-7H1.732C0.776,5.5,0,6.275,0,7.232V49.96c0.069,0.002,0.138,0.006,0.205,0.01
		l10.015-27.314c0.184-0.683,0.803-1.156,1.51-1.156H52v-7.268C52,13.275,51.224,12.5,50.268,12.5z"/>
</g>
</svg>
EOD;

$fileSvg = <<<'EOD'
                        <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="150px"
                        	 viewBox="0 0 410 410" style="enable-background:new 0 0 410 410;" xml:space="preserve">
                        <g id="XMLID_995_">
                        	<polygon id="XMLID_996_" style="fill:#DEDDE0;" points="125,340 125,310 205,310 205,280 125,280 125,250 205,250 205,220 125,220 
                        		125,190 205,190 205,160 125,160 125,130 205,130 205,100 205,70 205,0 125,0 125,70 56.707,70 55,71.75 55,410 205,410 205,340 	
                        		"/>
                        	<polygon id="XMLID_997_" style="fill:#CDCDD0;" points="205,0 205,70 285,70 285,100 205,100 205,130 285,130 285,160 205,160 
                        		205,190 285,190 285,220 205,220 205,250 285,250 285,280 205,280 205,310 205,340 205,410 355,410 355,0 	"/>
                        	<rect id="XMLID_998_" x="205" y="70" style="fill:#FFFFFF;" width="80" height="30"/>
                        	<rect id="XMLID_999_" x="125" y="310" style="fill:#FFFFFF;" width="80" height="30"/>
                        	<polygon id="XMLID_1000_" style="fill:#FFFFFF;" points="125,160 205,160 285,160 285,130 205,130 125,130 	"/>
                        	<polygon id="XMLID_1001_" style="fill:#FFFFFF;" points="125,220 205,220 285,220 285,190 205,190 125,190 	"/>
                        	<polygon id="XMLID_1002_" style="fill:#FFFFFF;" points="125,280 205,280 285,280 285,250 205,250 125,250 	"/>
                        	<polygon id="XMLID_1003_" style="fill:#ACABB1;" points="125,0 55,70 56.707,70 125,70 	"/>
                        </svg>
EOD;

        
        function makeThumb(string $fpath) {
            
            global $folderSvg, $fileSvg;
            
            $fname = basename($fpath);
            if ($fname == ".."){
                $caption = "Parent folder тон";
            } else {
                $caption = $fname;    
            }
            
            if (preg_match("/.*\.(jpg|jpeg|gif|png|bmp|webp)/i", $fname)) {
                
                $target = "_blank";
                $img = "<img src='$fname'/>";
                
            } else {
                
                $target = "";
                if (is_dir($fpath)) {
                    $img = $folderSvg;
                } else {
                    $img = $fileSvg;
                }
            }
            
            printf("<a href='$fname' target='$target'>\n");
            printf("<figure>$img<figcaption>$caption</figcaption></figure>\n");
            printf("</a>\n");
            
        }
        
        //printf("REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "<br>\n");
        //printf("__DIR__: %s <br>\n", __DIR__);
        $dir = realpath(__DIR__ .  $_SERVER['REQUEST_URI']);
        //printf("dir: $dir <br>\n");
        
        if (!preg_match("/^". preg_quote(__DIR__, "/") . "/", $dir)){
            printf("404 Unauthorized<br>\n");
            http_response_code(404);
        } else {
            
            $fpaths = glob("$dir/**", GLOB_BRACE);
        
            makeThumb("../");
        
            $dirs = array();
            $files = array();
            foreach ($fpaths as $fpath) {
                if (is_dir($fpath)){
                    array_push($dirs, $fpath);
                } else {
                    array_push($files, $fpath);
                }
            }
            
            sort($dirs);
            sort($files);
            
            foreach ($dirs as $d) {
                makeThumb($d);    
            }
            
            foreach ($files as $f) {
                makeThumb($f);    
            }
            
        }
        ?>
    </div>
  </body>
</html>
