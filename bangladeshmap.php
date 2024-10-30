<?php
/*
Plugin Name: Interactive Bangladesh Map
Plugin URI: https://profiles.wordpress.org/emrannet/#content-plugins
Description: Free WordPress plugin for embedding an interactive map of Bangladesh with clickable divisions. 
Version: 2.0.0
Author: Emran Hossen
Author URI: https://emran.net
License: GPLv2 or later
*/



add_action('admin_menu', 'free_bangladesh_map_plugin_menu');

function free_bangladesh_map_plugin_menu() {

    add_menu_page(__('Bangladesh Map Settings','free-bangladesh-html5-map'), __('Bangladesh Map Settings','free-bangladesh-html5-map'), 'manage_options', 'free-bangladesh-map-plugin-options', 'free_bangladesh_map_plugin_options' , plugins_url( '/files/img/icon.png', __FILE__));
    
}



function free_bangladesh_map_plugin_options() {

    ?>
    

        
 
<div class="wrap freebangladesh-html5-map main full">
<h1>Installation</h1>
<h2>Insert the tag <strong>[bangladeshmap71]</strong> into the text of a page or a post where you want the map to be..<br /></h2>

<div class="left-block">
    <h1>Map Preview</h1>
    <?php

    echo free_bangladesh_map_plugin_content('[bangladeshmap71]');
	

    ?>
	

   </div>
        <div class="right-block">
		<h1>Get Premium Version Now</h1>
            
<h2>&#128203; Features</h2>
    
    &#9733; Clickable divisions <br>
    &#9733; Adjustable colors of the map text<br>
    &#9733; Adjustable font size of the map text<br>
    &#9733; Customizable click behavior<br>
    &#9733; Customizable landing page links<br>
    &#9733; Works on iPad, iPhone, Android. No Flash<br>
	&#9733; Tooltips windows that show when the mouse cursor is over it


<h2>&#127989; Contact Us</h2>
&#128222; +8801779520000<br>
&#128231; <a href="mailto:jamunaitbd@gmail.com">jamunaitbd@gmail.com</a> <br>
&#127760; <a href=" https://bdmap.xyz/" target="_blank">Bangladesh Map</a> 


        </div>

        <div class="clear"></div>
    </div>
<?php

}


add_action('admin_init','free_bangladesh_map_plugin_scripts');

function free_bangladesh_map_plugin_scripts(){
    if ( is_admin() ){

        wp_register_style('jquery-tipsy', plugins_url('/files/css/tipsy.css', __FILE__));
        wp_enqueue_style('jquery-tipsy');
        wp_register_style('free-bangladesh-html5-mapadm', plugins_url('/files/css/mapadm.css', __FILE__));
        wp_enqueue_style('free-bangladesh-html5-mapadm');
        wp_enqueue_style('farbtastic');
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('farbtastic');
        wp_enqueue_script('tiny_mce');
        wp_register_script('jquery-tipsy', plugins_url('/files/js/jquery.tipsy.js', __FILE__));
        wp_enqueue_script('jquery-tipsy');
        wp_enqueue_style('thickbox');
        wp_enqueue_script('thickbox');

    }
    else {

    }
}

add_action('wp_enqueue_scripts', 'free_bangladesh_map_plugin_scripts_method');

function free_bangladesh_map_plugin_scripts_method() {
    wp_enqueue_script('jquery');
}

add_filter('the_content', 'free_bangladesh_map_plugin_content', 15);

function free_bangladesh_map_plugin_content($content) {

    $dir = plugins_url('/files/img/bangladesh.png', __FILE__);
    $siteURL = get_site_url();

    $fontSize = get_option('freefreebangladeshhtml5map_nameFontSize', '12');
    $fontColor = get_option('freefreebangladeshhtml5map_nameColor', '#000');
    $freeMapData = get_option('freefreebangladeshhtml5map_map_data', '{}');
    $freeMapDataJ = json_decode($freeMapData, true);

    foreach($freeMapDataJ as $k=>$v) {
        if($v['link'] == '') {
            $freeMapDataJ[$k]['link'] = '';
            $freeMapDataJ[$k]['target'] = '';
        }
        else {
            $freeMapDataJ[$k]['target'] = '_top';

        }

    }

    $mapInit = "
        <div class='freebangladeshHtmlMapbottom'>
            <style>
            .over-area {
                z-index: 1;
                background-image: url('{$dir}');
                width: 1px;
                height: 1px;
                position: absolute;
            }

            .freefreebangladesh1.over-area { background-position: -11px -379px; width: 85px; height: 86px; left: 11px; top: 9px; }
            .freefreebangladesh2.over-area { background-position: -11px -502px; width: 88px; height: 81px; left: 8px; top: 81px; }
            .freefreebangladesh3.over-area { background-position: -136px -395px; width: 88px; height: 67px; left: 72px; top: 73px; }
            .freefreebangladesh4.over-area { background-position: -135px -515px; width: 78px; height: 66px;left: 140px; top: 84px; }
            .freefreebangladesh5.over-area { background-position: -270px -561px; width: 79px; height: 135px; left: 32px; top: 139px; }
            .freefreebangladesh6.over-area { background-position: -21px -606px; width: 59px; height: 71px; left: 89px; top: 195px; }
            .freefreebangladesh7.over-area { background-position: -265px -398px; width: 98px; height: 105px;; left: 66px; top: 109px; }
            .freefreebangladesh8.over-area { background-position: -133px -619px; width: 104px; height: 192px; left: 125px; top: 128px; }

            #toolTip {
                display: none;
                position: absolute;
                z-index: 4 ;
                min-width:250px;
            }
            body .ToolTipFrameClass {
                background-color: #fff;
                border: 2px solid #bbb;
                border-radius: 10px;
                padding: 5px;
                opacity: .90;
                max-width: 300px;
                border-collapse: separate;
            /* test */
                line-height: 15px;
                margin: 0;
            }
            .ToolTipFrameClass TD {
                background-color:inherit;
            /* test */
                padding: 0px;
                margin: 0px;
                border:0px none;
                vertical-align: top;
            }

            .ToolTipFrameClass TD:last-child {
                padding-left: 5px;
            }

            .toolTipCommentClass {
                font-size: 11px;
                font-family: arial;
                color: #000000;
            }
            body #toolTipName {
                color: {$fontColor};
                text-shadow: -1px 0 white, 0 1px white, 1px 0 white, 0 -1px white;
                font-size: {$fontSize};
                font-weight:bold;
                padding: 5px;
                font-family: arial;
                margin: 0px;
            }
            </style>
            <script>
                var IsIE		= navigator.userAgent.indexOf(\"MSIE\")		!= -1;
                var freeMapData = {$freeMapData};
                function moveToolTipFree(e) {
                    var elementToolTip = document.getElementById(\"toolTip\");
                    var	floatTipStyle = elementToolTip.style;
                    var	X;
                    var	Y;
                    if (IsIE){
                        if(e) {
                            X = e.layerX - document.documentElement.scrollLeft;
                            Y = e.layerY - document.documentElement.scrollTop;
                        }
                        else {
                            X = window.event.x;
                            if(prevX != 0 && X - prevX > 100) {
                                X = prevX;
                            }
                            prevX = X;

                            Y = window.event.y;
                            if(prevY != 0 && Y - prevY > 100) {
                                Y = prevY;
                            }
                            prevY = Y;
                        }
                    }else{
                        X = e.layerX;
                        Y = e.layerY;
                    };

                    if( X+Y > 0 ) {
                        floatTipStyle.left = X + \"px\";
                        floatTipStyle.top = Y + 20 + \"px\";
                    }
                };

                function toolTipFree(img, msg, name, linkUrl, linkName, isLink) {
                    var	floatTipStyle = document.getElementById(\"toolTip\").style;

                    if (msg || name) {

                        if (name){
                            document.getElementById(\"toolTipName\").innerHTML = name;
                            document.getElementById(\"toolTipName\").style.display = \"block\";
                        } else {
                            document.getElementById(\"toolTipName\").style.display = \"none\";
                        };

                        if (msg) {
                            var repReg = new RegExp(String.fromCharCode(13), 'g')
                            var repReg2 = new RegExp(\"\\r\\n\", 'g')
                            var repReg3 = new RegExp(\"\\n\", 'g')
                            document.getElementById(\"toolTipComment\").innerHTML = msg.replace(repReg2,\"<br>\").replace(repReg3,\"<br>\").replace(repReg,\"<br>\");
                            document.getElementById(\"ToolTipFrame\").style.display = \"block\";
                        } else {
                            document.getElementById(\"ToolTipFrame\").style.display = \"none\";
                        };

                        if (img){
                            document.getElementById(\"toolTipImage\").innerHTML = \"<img src='\" + img + \"'>\";
                        } else{
                            document.getElementById(\"toolTipImage\").innerHTML = \"\";
                        };

                        floatTipStyle.display = \"block\";
                    } else {
                        floatTipStyle.display = \"none\";
                    }
                };


                function freebangladeshMapIn(num) {
                    var el = document.getElementById('freebangladesh-over-area');
                    el.className = 'freefreebangladesh'+num+' over-area';

                    var areaData = freeMapData['st'+num];

                    toolTipFree(areaData.image, areaData.comment, areaData.name, areaData.link);
                }

                function freebangladeshMapOut() {
                    var el = document.getElementById('freebangladesh-over-area');
                    el.className = 'over-area';

                    toolTipFree();
                }
            </script>
            
            <div style=\"position: relative\">
                <div id=\"toolTip\"><table id=\"ToolTipFrame\" class=\"ToolTipFrameClass\"><tr id=\"ToolTipFrame\" class=\"ToolTipFrameClass\" valign=\"top\"><td id=\"toolTipImage\"></td><td id=\"toolTipComment\" class=\"toolTipCommentClass\"></td></tr></table><div id=\"toolTipName\"></div></div>
                <div style=\"width: 230px; height: 335px; background-image: url('{$dir}')\"></div>
                <img style=\"position: absolute; top: 0; left: 0; z-index: 2; box-shadow: none !important;\" width=\"230\" height=\"335\" src=\"data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7\" usemap=\"#us_imageready_Map\" border=0 />
                <map onmousemove='moveToolTipFree(event);' name=\"us_imageready_Map\">
                    <area onmouseover=\"freebangladeshMapIn(1)\" onmouseout=\"freebangladeshMapOut()\" shape=\"poly\" alt=\"div1\" coords=\"23,31,27,25,29,23,32,22,30,18,27,18,25,20,23,19,23,14,28,9,32,17,40,17,43,25,43,28,46,26,48,29,50,27,52,28,48,23,49,20,53,18,57,19,58,23,61,25,61,29,61,31,62,34,67,38,71,42,79,42,81,37,81,33,82,30,86,28,89,34,94,43,96,52,96,60,96,73,92,75,88,76,89,83,88,88,87,91,83,93,78,93,71,92,65,91,63,87,62,83,56,84,52,82,48,81,44,76,44,71,41,72,33,71,28,70,27,65,26,61,22,58,20,55,15,56,12,57,11,52,10,45,14,41,14,37,16,34,19,30\" target='{$freeMapDataJ['st1']['target']}' a href=\"{$freeMapDataJ['st1']['link']}\">
                    <area onmouseover=\"freebangladeshMapIn(2)\" onmouseout=\"freebangladeshMapOut()\" shape=\"poly\" alt=\"div2\" coords=\"29,133,38,133,41,137,44,141,52,140,56,147,59,151,69,153,78,157,87,156,86,145,88,138,90,130,91,120,89,112,88,106,82,103,82,96,82,93,73,93,67,91,63,90,62,84,53,83,49,84,43,84,38,83,29,84,27,86,26,94,21,99,18,96,13,98,11,103,7,110,9,118,11,123,17,124,23,129\" target='{$freeMapDataJ['st2']['target']}' a href=\"{$freeMapDataJ['st2']['link']}\">
                    <area onmouseover=\"freebangladeshMapIn(3)\" onmouseout=\"freebangladeshMapOut()\" shape=\"poly\" alt=\"div3\" coords=\"117,84,120,86,142,86,145,92,144,97,146,102,154,102,154,111,158,112,157,119,154,120,152,118,147,120,142,120,140,121,139,127,136,123,134,124,133,127,129,126,131,131,131,139,126,138,121,134,116,137,111,139,110,127,107,122,107,115,105,112,102,109,99,109,97,113,95,111,94,112,94,118,91,117,89,107,82,101,83,94,88,91,90,83,90,76,94,76,95,79\" target='{$freeMapDataJ['st3']['target']}' a href=\"{$freeMapDataJ['st3']['link']}\">
                    <area onmouseover=\"freebangladeshMapIn(4)\" onmouseout=\"freebangladeshMapOut()\" shape=\"poly\" alt=\"div4\" coords=\"196,85,203,86,206,90,211,92,214,96,217,99,217,104,211,106,208,102,206,103,205,110,204,116,202,121,202,122,201,128,196,130,193,132,191,132,190,143,185,137,184,138,182,142,180,142,176,145,169,145,165,145,163,150,162,138,162,134,157,135,155,133,158,130,158,123,156,120,157,118,159,111,155,110,154,101,149,101,146,100,145,96,145,93,144,86,151,84,162,84,167,86,171,88,180,87,187,84\" target='{$freeMapDataJ['st4']['target']}' a href=\"{$freeMapDataJ['st4']['link']}\">
                    <area onmouseover=\"freebangladeshMapIn(5)\" onmouseout=\"freebangladeshMapOut()\" shape=\"poly\" alt=\"div5\" coords=\"44,200,50,190,39,190,37,186,39,179,42,176,37,174,34,171,32,169,30,163,33,154,37,151,39,149,40,144,37,141,37,138,42,139,47,142,51,141,55,147,58,151,69,154,69,162,71,165,74,166,76,170,77,174,80,174,82,179,82,180,81,181,82,185,85,188,88,191,89,192,87,194,90,201,95,205,97,207,97,216,96,220,97,225,96,228,96,232,94,241,94,248,95,254,95,261,93,263,87,265,85,267,83,267,81,271,78,271,76,264,78,259,76,260,75,266,73,269,69,269,69,265,70,261,68,261,67,263,67,271,64,275,61,274,61,268,61,265,58,267,59,269,54,267,54,260,54,254,53,246,54,243,50,232,48,224,48,216,48,209\" target='{$freeMapDataJ['st5']['target']}' a href=\"{$freeMapDataJ['st5']['link']}\">
                    <area onmouseover=\"freebangladeshMapIn(6)\" onmouseout=\"freebangladeshMapOut()\" shape=\"poly\" alt=\"div6\" coords=\"117,260,125,260,134,257,139,245,143,233,144,224,145,221,139,211,134,204,132,197,128,192,125,195,120,196,118,197,116,196,115,196,113,203,111,199,109,196,109,195,105,198,102,200,101,204,98,207,97,209,97,220,97,226,97,229,95,239,94,246,95,254,96,259,98,255,102,254,101,258,101,261,104,265,110,266,115,261\" target='{$freeMapDataJ['st6']['target']}' a href=\"{$freeMapDataJ['st6']['link']}\">
                    <area onmouseover=\"freebangladeshMapIn(7)\" onmouseout=\"freebangladeshMapOut()\" shape=\"poly\" alt=\"div7\" coords=\"127,175,133,172,136,167,136,161,135,154,142,153,146,151,148,145,149,140,147,138,150,136,154,133,156,131,158,126,156,120,153,118,148,118,142,120,140,126,136,124,135,127,130,126,132,133,132,139,125,138,121,134,117,138,111,139,110,130,108,126,108,124,105,117,105,112,102,109,99,109,98,112,96,113,95,113,94,118,92,118,91,119,90,127,88,135,86,144,86,155,81,156,74,156,70,154,69,154,70,162,74,166,78,172,83,177,84,181,82,182,86,186,89,193,88,196,96,206,101,206,104,199,111,195,113,199,114,200,117,195,121,196,128,194,131,192,130,185,127,182,125,180,127,177,126,177,129,174\" target='{$freeMapDataJ['st7']['target']}' a href=\"{$freeMapDataJ['st7']['link']}\">
                    <area onmouseover=\"freebangladeshMapIn(8)\" onmouseout=\"freebangladeshMapOut()\" shape=\"poly\" alt=\"div8\" coords=\"221,221,224,246,225,264,226,274,228,289,227,294,221,293,220,286,214,286,213,279,211,284,209,285,207,289,208,294,208,297,211,306,211,312,215,316,215,319,211,318,204,310,198,297,196,290,193,285,190,280,186,278,187,274,187,265,186,256,188,253,187,248,184,246,182,238,179,228,178,224,174,225,176,232,176,235,169,235,168,230,167,226,167,222,168,219,171,222,173,225,175,223,175,221,170,217,168,216,165,214,161,218,157,223,150,223,147,223,142,217,140,212,137,204,133,196,131,193,132,186,128,181,127,177,136,169,138,166,137,159,137,155,141,154,147,154,149,145,151,141,150,137,155,134,161,136,163,137,163,148,160,153,159,160,158,163,157,164,156,168,159,175,161,180,162,184,164,195,167,195,165,188,166,186,172,195,175,200,177,201,182,199,187,196,185,191,183,186,184,180,192,174,192,169,190,161,195,162,197,164,203,159,206,161,206,163,209,166,209,171,211,176,213,179,216,183,214,187,214,189,213,191,213,193,213,194\" target='{$freeMapDataJ['st8']['target']}' a href=\"{$freeMapDataJ['st8']['link']}\">
                </map>
                <div id=\"freebangladesh-over-area\" class=\"over-area\"></div>
            </div>
            <div style='clear: both'></div>
		</div>
		<script>
		    toolTipFree();
		</script>
    ";

    $content = str_ireplace(array(
        '<bangladeshmap71></bangladeshmap71>',
        '<bangladeshmap71 />',
        '[bangladeshmap71]'
    ), $mapInit, $content);

    return $content;
}

$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'free_bangladesh_map_plugin_settings_link' );

function free_bangladesh_map_plugin_settings_link($links) {
    $settings_link = '<a href="admin.php?page=free-bangladesh-map-plugin-options">Settings</a>';
    array_push($links, $settings_link);
    return $links;
}


add_action( 'parse_request', 'free_bangladesh_map_plugin_wp_request' );

function free_bangladesh_map_plugin_wp_request( $wp ) {
    if( isset($_GET['freefreebangladeshmap_js_data']) ) {
        header( 'Content-Type: application/javascript' );
       ?>
    var
        nameColor		= "<?php echo get_option('freefreebangladeshhtml5map_nameColor')?>",
        nameFontSize		= "<?php echo get_option('freefreebangladeshhtml5map_nameFontSize')?>",
        map_data = <?php echo get_option('freefreebangladeshhtml5map_map_data')?>;
        <?php
        exit;
    }

    if(isset($_GET['freefreebangladeshmap_get_state_info'])) {
        $stateId = (int) $_GET['freefreebangladeshmap_get_state_info'];
        echo nl2br(get_option('freefreebangladeshhtml5map_state_info_'.$stateId));
        exit;
    }
}

register_activation_hook( __FILE__, 'free_bangladesh_map_plugin_activation' );

function free_bangladesh_map_plugin_activation() {
    $initialStatesPath = dirname(__FILE__).'/files/settings_tpl.json';
    add_option('freefreebangladeshhtml5map_map_data', file_get_contents($initialStatesPath));
    add_option('freefreebangladeshhtml5map_nameColor', "#000000");
    add_option('freefreebangladeshhtml5map_nameFontSize', "12px");

    for($i = 1; $i <= 8; $i++) {
        add_option('freefreebangladeshhtml5map_state_info_'.$i, '');
    }
}

register_deactivation_hook( __FILE__, 'free_bangladesh_map_plugin_deactivation' );

function free_bangladesh_map_plugin_deactivation() {

}

register_uninstall_hook( __FILE__, 'free_bangladesh_map_plugin_uninstall' );

function free_bangladesh_map_plugin_uninstall() {
    delete_option('freefreebangladeshhtml5map_map_data');
    delete_option('freefreebangladeshhtml5map_nameColor');
    delete_option('freefreebangladeshhtml5map_nameFontSize');

    for($i = 1; $i <= 8; $i++) {
        delete_option('freefreebangladeshhtml5map_state_info_'.$i);
    }
}

// Register and load the widget
function free_bangladesh_map_load_widget() {
    register_widget( 'free_bangladesh_map_widget' );
}
add_action( 'widgets_init', 'free_bangladesh_map_load_widget' );
 
// Creating the widget 
class free_bangladesh_map_widget extends WP_Widget {
 
function __construct() {
parent::__construct(
 
// Base ID of your widget
'free_bangladesh_map_widget', 
 
// Widget name will appear in UI
__('Interactive Bangladesh Map', 'free_bangladesh_map_widget_domain'), 
 
// Widget description
array( 'description' => __( 'Free Interactive Bangladesh Map Widget', 'free_bangladesh_map_widget_domain' ), ) 
);
}
 
// Creating widget front-end
 
public function widget( $args, $instance ) {
$title = apply_filters( 'widget_title', $instance['title'] );
 
// before and after widget arguments are defined by themes
echo $args['before_widget'];
if ( ! empty( $title ) )
echo $args['before_title'] . $title . $args['after_title'];
 
// This is where you run the code and display the output


echo free_bangladesh_map_plugin_content('[bangladeshmap71]') ;

echo $args['after_widget'];
}
         
// Widget Backend 
public function form( $instance ) {
if ( isset( $instance[ 'title' ] ) ) {
$title = $instance[ 'title' ];
}
else {
$title = __( 'New title', 'free_bangladesh_map_widget_domain' );
}
// Widget admin form
?>
<p>
<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>
<?php 
}
     
// Updating widget replacing old instances with new
public function update( $new_instance, $old_instance ) {
$instance = array();
$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
return $instance;
}
} // Class free_bangladesh_map_widget ends here