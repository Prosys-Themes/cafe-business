<?php
/**
 * Cafe Business Functions
*/

add_action( 'wp_enqueue_scripts', 'cafe_business_enqueue_styles',999 );
function cafe_business_enqueue_styles() {

    $parent_style = 'parent-style'; // This is 'twentyseventeen-style' for the Twenty Seventeen theme.

    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );

    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );
}

/**
 * Use parent theme setting in child theme without loosing already set options.
 * @link https://core.trac.wordpress.org/ticket/27177#comment:3
*/
if ( get_stylesheet() !== get_template() ) {
    add_filter( 'pre_update_option_theme_mods_' . get_stylesheet(), function ( $value, $old_value ) {
         update_option( 'theme_mods_' . get_template(), $value );
         return $old_value; // prevent update to child theme mods
    }, 10, 2 );
    add_filter( 'pre_option_theme_mods_' . get_stylesheet(), function ( $default ) {
        return get_option( 'theme_mods_' . get_template(), $default );
    } );
}




function cafe_business_customizer_theme_info( $wp_customize ) {
    
    $wp_customize->add_section( 'theme_info' , array(
        'title'       => __( 'Theme Information' , 'cafe-business' ),
        'priority'    => 6,
        ));

     // Theme info
    $wp_customize->add_setting(
        'setup_instruction',
        array(
            'sanitize_callback' => 'wp_kses_post'
        )
    );

    $wp_customize->add_control(
        new bakery_shop_Theme_Info( 
            $wp_customize,
            'setup_instruction',
            array(
                'settings'      => 'setup_instruction',
                'section'       => 'theme_info',
                'label' => __('Information Links','cafe-business'),
            )
        )
    );

    $wp_customize->add_setting('theme_info_theme',array(
        'default' => '',
        'sanitize_callback' => 'wp_kses_post',
        ));
    
    $theme_info = '';
    $theme_info .= '<h3 class="sticky_title">' . __( 'Need help?', 'cafe-business' ) . '</h3>';
    $theme_info .= '<span class="sticky_info_row"><label class="row-element">' . __( 'Theme Documentation', 'cafe-business' ) . ': </label><a href="' . esc_url( 'http://docs.prosysthemes.com/bakery-shop/' ) . '" target="_blank">' . __( 'here', 'cafe-business' ) . '</a></span><br />';
    $theme_info .= '<span class="sticky_info_row"><label class="row-element">' . __( 'Theme Demo', 'cafe-business' ) . ': </label><a href="' . esc_url( 'http://prosysthemes.com/theme-demos/?theme_demos=cafe-business' ) . '" target="_blank">' . __( 'here', 'cafe-business' ) . '</a></span><br />';
    $theme_info .= '<span class="sticky_info_row"><label class="row-element">' . __( 'Support Forum', 'cafe-business' ) . ': </label><a href="' . esc_url( 'http://prosysthemes.com/support-forum/' ) . '" target="_blank">' . __( 'here', 'cafe-business' ) . '</a></span><br />';
    $theme_info .= '<span class="sticky_info_row"><label class="row-element">' . __( 'Rate this theme', 'cafe-business' ) . ': </label><a href="' . esc_url( 'https://wordpress.org/support/theme/bakery-shop/reviews' ) . '" target="_blank">' . __( 'here', 'cafe-business' ) . '</a></span><br />';


    $wp_customize->add_control( 
        new Bakery_Shop_Theme_Info( 
            $wp_customize,
            'theme_info_theme',
            array(
                'section' => 'theme_info',
                'description' => $theme_info
            )
        )
    );

    $wp_customize->add_setting('theme_info_more_theme',array(
        'default' => '',
        'sanitize_callback' => 'wp_kses_post',
        ));

}

add_action( 'customize_register', 'cafe_business_customizer_theme_info', 9999 );


if( ! function_exists( 'bakery_shop_header_bottom' ) ) :
/**
 * Header Site Branding
 * 
 * @since 1.0.1
*/

function bakery_shop_header_bottom(){
    ?>
    <div class="header-bottom">    
        <div class="container">
            <div id="mobile-header">
                <a id="responsive-menu-button" href="#sidr-main"><i class="fa fa-bars"></i></a>
            </div>
            <nav id="site-navigation" class="main-navigation">
                <?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_id' => 'primary-menu' ) ); ?>
            </nav><!-- #site-navigation -->
        </div>
        <div class="curtain"><div class="curtain-holder"></div></div>
    </div>
    <?php 
}
endif;

if( ! function_exists( 'bakery_shop_header_top' ) ) :
/**
 * Header Site Branding
 * 
 * @since 1.0.1
*/
function bakery_shop_header_top(){

    $header_phone = get_theme_mod( 'bakery_shop_header_phone', __( 'Make A Quick Call', 'cafe-business') );
    $header_phone_no = get_theme_mod( 'bakery_shop_header_phone_no' );

    ?>
    <div class="header-top">
        <div class="container">
            <div class="site-branding">
                <?php 
                    if( function_exists( 'has_custom_logo' ) && has_custom_logo() ){
                              the_custom_logo();
                          } 
                ?>
                <div class="text-logo">
                    <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
                  <?php
                        $description = get_bloginfo( 'description', 'display' );
                        if ( !empty( $description ) || is_customize_preview() ) { ?>
                          <p class="site-description"><?php echo esc_html( $description ); /* WPCS: xss ok. */ ?></p>
                  <?php } ?>
                </div>  
            </div><!-- .site-branding -->


       <?php if( !empty( $header_phone_no ) ){ ?>
                <div class="header-info-holder">
                    <?php if( !empty( $header_phone_no ) ){ ?>
                        <div class="header-callus-holder">
                            <a href="tel:<?php echo esc_attr( $header_phone_no ); ?>" class="header-font-icon"><i class="fa fa-phone"></i></a>
                            <div class="header-phone">
                                <?php if( !empty( $header_phone ) ){ ?>
                                <div class="header-phone-text">
                                    <?php echo esc_attr( $header_phone ); ?>
                                </div>
                                <?php } if( !empty( $header_phone_no ) ){  ?>
                                <div class="header-phone-number">
                                    <a href="tel:<?php echo esc_attr( $header_phone_no ); ?>"><?php echo esc_html( $header_phone_no ); ?></a>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <?php } ?>
        </div>
    </div>
    <?php 
}
endif;

/**
 * Header Phone Options
 *
 * @package cafe_business
 */
 
function bakery_shop_customize_register_header_phone( $wp_customize ) {

    /** Header Phone Settings */
    
    $wp_customize->add_section(
        'bakery_shop_header_phone_settings',
        array(
            'title' => __( 'Header Phone Settings', 'cafe-business' ),
            'priority' => 50,
            'capability' => 'edit_theme_options',
        )
    );
    
   /** Home Text */
    $wp_customize->add_setting(
        'bakery_shop_header_phone',
        array(
            'default' => __( 'Make A Quick Call', 'cafe-business' ),
            'sanitize_callback' => 'sanitize_text_field',
        )
    );
    
    $wp_customize->add_control(
        'bakery_shop_header_phone',
        array(
            'label' => __( 'Header Phone Text', 'cafe-business' ),
            'section' => 'bakery_shop_header_phone_settings',
            'type' => 'text',
        )
    );
    
    /** Header Phone Separator */
    $wp_customize->add_setting(
        'bakery_shop_header_phone_no',
        array(
            'default' => '',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );
    
    $wp_customize->add_control(
        'bakery_shop_header_phone_no',
        array(
            'label' => __( 'Header Phone Number', 'cafe-business' ),
            'section' => 'bakery_shop_header_phone_settings',
            'type' => 'text',
        )
    );
    /** BreadCrumb Settings Ends */
    
    }
add_action( 'customize_register', 'bakery_shop_customize_register_header_phone' );


/** Template function */

if( ! function_exists( 'bakery_shop_slider' ) ) :
/**
 * Home Page Slider Section
 * 
 * @since 1.0.1
*/
function bakery_shop_slider(){
    global $bakery_shop_default_post;
    
    $slider_enable      = get_theme_mod( 'bakery_shop_ed_slider','1' );
    $slider_caption     = get_theme_mod( 'bakery_shop_slider_caption', '1' );
    $slider_readmore    = get_theme_mod( 'bakery_shop_slider_readmore', __( 'Learn More', 'cafe-business' ) );
    $slider_contact     = get_theme_mod( 'bakery_shop_slider_contact_text', __( 'Contact Us', 'cafe-business' ) );
    $slider_contact_url = get_theme_mod( 'bakery_shop_slider_contact_url', '#' );
   
    if( $slider_enable ){
        echo '<section id="banner" class="banner">';
            echo '<div class="fadeout owl-carousel owl-theme clearfix">';
            for( $i=1; $i<=3; $i++){  
                $bakery_shop_slider_post_id = get_theme_mod( 'bakery_shop_slider_post_'.$i, $bakery_shop_default_post ); 
                if( $bakery_shop_slider_post_id ){
                    $qry = new WP_Query ( array( 'p' => absint( $bakery_shop_slider_post_id ) ) );            
                    if( $qry->have_posts() ){ 
                        while( $qry->have_posts() ){
                        $qry->the_post();
                            ?>
                            <div class="item">
                                <?php 
                                if( has_post_thumbnail() ){ 
                                    the_post_thumbnail( 'bakery-shop-without-sidebar' );
                                }else{
                                    echo '<img src="'. esc_url( get_template_directory_uri() ).'/images/banner.png">';
                                } 
                                        if( $slider_caption ){ ?>
                                            <div class="banner-text">
                                                <div class="banner-item-holder">
                                                    <strong class="title"><h1><?php the_title(); ?></h1></strong>
                                                    <?php the_excerpt(); ?>
                                                    <div class="button-holder">
                                                        <?php if( $slider_readmore ){ ?> 
                                                            <a class="btn blank" href="<?php the_permalink(); ?>">
                                                            <?php echo esc_attr( $slider_readmore );?></a>
                                                        <?php } 
                                                            if( $slider_contact && $slider_contact_url ){ ?> 
                                                            <a class="btn white" href="<?php echo esc_url( $slider_contact_url);  ?>">
                                                            <?php echo esc_html( $slider_contact ); ?></a>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                    <?php } ?>
                                </div>
                        <?php 
                        }
                    }
                }
            }
            wp_reset_postdata();  
            echo '</div>';
        echo '</section>';
    }    
}
endif;




if( ! function_exists( 'bakery_shop_team' ) ) :
/**
 * Home Page Teams Section
 * 
 * @since 1.0.1
*/
function bakery_shop_team(){
    global $bakery_shop_default_page;

    $team_enable    = get_theme_mod( 'bakery_shop_ed_teams_section', '1' );
    $team_title     = get_theme_mod( 'bakery_shop_teams_section_title', $bakery_shop_default_page); 
    $team_category  = get_theme_mod( 'bakery_shop_team_category' ); 
   
    if( $team_enable ){
        $args = array( 
            'post_type'          => 'post', 
            'post_status'        => 'publish',
            'cat'                => absint( $team_category ),
            'posts_per_page'     => 4,       
            'orderby'            => 'post_in', 
            'ignore_sticky_post' => true  
        );

        if( $team_category ){
            $args[ 'cat' ] = absint( $team_category );
        }
        $qry = new WP_Query( $args );

        echo '<section id="teams" class="team-section">';
            echo '<div class="container">';

            if( $team_title ) {  bakery_shop_template_header( $team_title ); }
           
                echo '<div class="row latest-activities">';

                    if( $qry->have_posts() ){ ?>
                        <?php
                        while( $qry->have_posts() ){
                            $qry->the_post();
                        ?>
                        <div class="col-3">
                            <div class="team-item">
                                <?php if( has_post_thumbnail() ){ the_post_thumbnail( 'bakery-shop-teams' ); }
                                    else{
                                        echo '<img src="' . esc_url( get_template_directory_uri() ).'/images/team-one.png">';
                                    } ?>
                                <div class="team-mask">
                                    <div class="team-info">
                                        <a href="<?php the_permalink(); ?>"><?php the_title( '<h3>', '</h3>'); ?></a>
                                        <span class="team-designation"><?php if( has_excerpt() ){ the_excerpt(); } ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                        }
                    }
                    wp_reset_postdata();  
                echo '</div>'; 
            echo '</div>'; 
        echo '</section>';     
    }    
 
}
endif;


if( ! function_exists( 'bakery_shop_blog' ) ) :
/**
 * Home Page Latest Post Section
 * 
 * @since 1.0.1
*/
function bakery_shop_blog(){
    global $bakery_shop_default_page;
    
    $blog_enable    = get_theme_mod( 'bakery_shop_ed_blog_section','1' );
    $blog_meta      = get_theme_mod( 'bakery_shop_ed_blog_date','1' );
    $blog_title     = get_theme_mod( 'bakery_shop_blog_section_title', $bakery_shop_default_page ); 
    $blog_category  = get_theme_mod( 'bakery_shop_blog_section_category' ); 
   
    if( $blog_enable ){
        $args = array( 
            'post_type'          => 'post', 
            'post_status'        => 'publish',
            'posts_per_page'     => 3,        
            'ignore_sticky_post' => true  
        );

        if( $blog_category ){
            $args[ 'cat' ] = absint( $blog_category );
        }
        
        $qry = new WP_Query( $args );

        echo '<section id="latest-activity"  class="latest-activity-section">';
            echo '<div class="container">';

            if( $blog_title ) {  bakery_shop_template_header( $blog_title ); }
           
                echo '<div class="row latest-activities">';

                    if( $qry->have_posts() ){ ?>
                        <?php
                        while( $qry->have_posts() ){
                            $qry->the_post();
                        ?>
                            <div class="col-4"> 
                                <div class="activity-items">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php if( has_post_thumbnail() ){ the_post_thumbnail( 'bakery-shop-three-col' ); 
                                        }else{
                                            echo '<img src="'. esc_url( get_template_directory_uri() ).'/images/default-thumb-3col.png">';
                                        } ?>
                                    </a>
                                    <div class="activity-text">
                                        <header class="entry-header">
                                            <?php if( isset( $blog_meta ) ){ ?>
                                            <div class="entry-meta">
                                                <?php 
                                                    bakery_shop_posted_on();
                                                ?>
                                            </div>
                                            <?php } ?>
                                            <a href="<?php the_permalink(); ?>"><?php the_title('<h3 class="entry-title">','</h3>'); ?></a>
                                        </header>

                                        <?php the_excerpt(); ?>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                    }
                    wp_reset_postdata();  
                echo '</div>';
            echo '</div>'; 
        echo '</section>';     
    }    
 
}
endif;


if( ! function_exists( 'bakery_shop_footer_credit' ) ) :
/**
 * Footer Credits 
 */
function bakery_shop_footer_credit(){
    echo '<div class="footer-b">';
        echo '<div class="container">'; 
            echo '<div class="site-info">';
                echo esc_html( '&copy;&nbsp;'. date_i18n( 'Y' ), 'cafe-business' );
                echo esc_html( get_bloginfo( 'name' ) );

                printf( '&nbsp;%s', '<a href="'. esc_url( __( 'http://prosysthemes.com/wordpress-themes/cafe-business/', 'cafe-business' ) ) .'" target="_blank">'. esc_html__( 'Cafe Business By Prosys Theme. ', 'cafe-business' ) .'</a>' );
                printf( esc_html__( 'Powered by %s', 'cafe-business' ), '<a href="'. esc_url( __( 'https://wordpress.org/', 'cafe-business' ) ) .'" target="_blank">'. esc_html__( 'WordPress', 'cafe-business' ) . '</a>' );
            echo '</div>';
        echo '</div>';
    echo '</div>';
}
endif;