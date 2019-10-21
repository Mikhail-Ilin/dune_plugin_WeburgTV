<?php
/*

WeburgTV plugin (mik@mail.ru)

*/

class WeburgTVConfig
{

    const PLUGIN_VERSION 	   = '2.1.0';

    const TV_FAVORITES_SUPPORTED   = true;

    const ALL_CHANNEL_GROUP_CAPTION     = 'Все каналы';
    const ALL_CHANNEL_GROUP_ICON_PATH   = 'plugin_file://icons/all.png';
    const FAV_CHANNEL_GROUP_CAPTION     = 'Избранное';
    const FAV_CHANNEL_GROUP_ICON_PATH   = 'plugin_file://icons/favorites.png';

    const GROUP_ICON_PATH   = 'plugin_file://icons/%s.png';
    
    const USE_M3U_FILE = true;

    public static function get_group_name($gid,$group_names) 
    {

        $gid = array_key_exists($gid, $group_names ) ? $gid : 0;
        return array($gid, $group_names[$gid]);
    }

    ///////////////////////////////////////////////////////////////////////
    public static function get_group_id($group_name,&$group_names) 
    {


         if (!array_search($group_name,$group_names)) array_push($group_names,$group_name);
         if (!($gid = array_search($group_name,$group_names))) $gid = 0;
         
        return array($gid, $group_names[$gid]);
    }

    ///////////////////////////////////////////////////////////////////////
   public static function getIkonPath($gname){
    $ikon_name="0";
    
    if (strpos($gname,'порт')!==false) $ikon_name = "sport";
    else  if (stripos($gname,"етский")!==false) $ikon_name = "kinder";
    else  if (stripos($gname,"етям")!==false) $ikon_name = "kinder";
    else  if (stripos($gname,"иасат")!==false) $ikon_name = "viasat";
    else  if (stripos($gname,"vip")!==false) $ikon_name = "filmvip";
    else  if (stripos($gname,"агазин")!==false) $ikon_name = "shop";
    else  if (stripos($gname,"узыка")!==false) $ikon_name = "music";
    else  if (stripos($gname,"music")!==false) $ikon_name = "music";
    else  if (stripos($gname,"влечения")!==false) $ikon_name = "hobby";
    else  if (stripos($gname,"искавери")!==false) $ikon_name = "diskavery";
    else  if (stripos($gname,"естселлер")!==false) $ikon_name = "best";
    else  if (stripos($gname,"фир")!==false) $ikon_name = "air";
    else  if (stripos($gname,"адио")!==false) $ikon_name = "radio";
    else  if (stripos($gname,"ополнительный")!==false) $ikon_name = "add";
    else  if (stripos($gname,"нформационный")!==false) $ikon_name = "inform";
    else  if (stripos($gname,"ознавательный")!==false) $ikon_name = "science";
    else  if (stripos($gname,"азвлекательный")!==false) $ikon_name = "relax";
    else  if (stripos($gname,"взрослых")!==false) $ikon_name = "adult";
    else  if (stripos($gname,"ино")!==false) $ikon_name = "film";
    else  if (stripos($gname,"ланета")!==false) $ikon_name = "planeta";
    else  if (stripos($gname,"hd+")!==false) $ikon_name = "hdplus";
    else  if (stripos($gname,"hd")!==false) $ikon_name = "hd";
    
    $ikonpath = sprintf(WeburgTVConfig::GROUP_ICON_PATH,$ikon_name);
      
    return $ikonpath;
    } 
    

    public static function sort_channels_cb($a, $b)
    {
        // Sort by channel numbers.
       return strnatcasecmp($a->get_number(), $b->get_number());

        // Other options:
      // return strnatcasecmp($a->get_title(), $b->get_title());
    }

    ///////////////////////////////////////////////////////////////////////
    // Folder views.

    public static function GET_TV_GROUP_LIST_FOLDER_VIEWS()
    {
        return array(
            array
            (
                PluginRegularFolderView::async_icon_loading => true,

                PluginRegularFolderView::view_params => array
                (
                    ViewParams::num_cols => 2,
                    ViewParams::num_rows => 7,
                    ViewParams::paint_details => false,
                    ViewParams::zoom_detailed_icon => false,
                ),

                PluginRegularFolderView::base_view_item_params => array
                (
    		    ViewItemParams::item_paint_icon => true,
                //ViewItemParams::item_caption_wrap_enabled=> true,
    		    ViewItemParams::item_layout => HALIGN_LEFT,
    		    ViewItemParams::icon_valign => VALIGN_CENTER,
    		    ViewItemParams::icon_dx => 10,
    		    ViewItemParams::icon_dy => -5,
                ViewItemParams::icon_width =>84, 
                //120,
                ViewItemParams::icon_height =>86, 
                //123,
                ViewItemParams::item_caption_font_size => FONT_SIZE_NORMAL,
    		    ViewItemParams::item_caption_width => 700,

                ),

                PluginRegularFolderView::not_loaded_view_item_params => array (),
            ),

        );
    }

    public static function GET_TV_CHANNEL_LIST_FOLDER_VIEWS()
    {
        return array(
            array
            (
                PluginRegularFolderView::async_icon_loading => true,

                PluginRegularFolderView::view_params => array
                (
                    ViewParams::num_cols => 2,
                    ViewParams::num_rows => 8,
                    ViewParams::paint_details => false,
                    ViewParams::sandwich_base => 'plugin_file://icons/1.AAI',
                    ViewParams::sandwich_mask => 'cut_icon://{name=sandwich_mask}',
                    ViewParams::sandwich_cover => 'cut_icon://{name=sandwich_cover}',
                    
                ),

                PluginRegularFolderView::base_view_item_params => array
                (
    		    ViewItemParams::item_paint_icon => true,
                    ViewItemParams::item_caption_wrap_enabled=> true,
    		    ViewItemParams::item_layout => HALIGN_LEFT,
    		    ViewItemParams::icon_valign => VALIGN_CENTER,
    		    ViewItemParams::icon_dx => 10,
    		    ViewItemParams::icon_dy => -5,
                    ViewItemParams::icon_width => 175,
                    ViewItemParams::icon_height => 79,
    		    ViewItemParams::item_caption_font_size => FONT_SIZE_NORMAL,
    		    ViewItemParams::item_caption_width => 600,
                ),

                PluginRegularFolderView::not_loaded_view_item_params => array 
		(
		    ViewItemParams::icon_path => 'plugin_file://icons/1.AAI'
		),
            ),

        );
    }
    
}

?>
