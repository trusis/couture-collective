
	<div class="bordered-dark-top">
		<?php

			$id = $GLOBALS['CC_POST_DATA']['id'];
			$user = $GLOBALS['CC_POST_DATA']['logged_in'];
		
			$owned = $GLOBALS['CC_POST_DATA']['owned'];

			$title = $GLOBALS['CC_POST_DATA']['title'];
			$description = $GLOBALS['CC_POST_DATA']['description'];
			$designer = $GLOBALS['CC_POST_DATA']['designer'];
	
			$size = $GLOBALS['CC_POST_DATA']['size'];

			echo ws_ifdef_do( $designer, ws_ifdef_concat('<h1 class="uppercase dress-designer">',$designer,'</h1>') );

			echo ws_ifdef_do( $title, ws_ifdef_concat('<h6 class="dress-description">',$description,'</h6>') );
	
			echo ws_ifdef_do( $size, ws_ifdef_concat('<p class="h7">SIZE: <span class="numerals h8">',$size,'</span></p>') );

			?>

			<?php if( $owned ){ ?>
		
	
				<p class="h7 uppercase">
				<a href="<?php bloginfo('url'); ?>/closet"><span class="icon svg"><?php get_template_part('_icons/delivery'); ?></span>In My Closet</a>
				</p>
					
			
			<?php }?>
				
	</div>
