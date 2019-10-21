<?php
/*

WeburgTV plugin (mik@mail.ru)

*/

///////////////////////////////////////////////////////////////////////////

require_once 'lib/default_dune_plugin.php';
require_once 'lib/utils.php';

require_once 'lib/tv/tv_group_list_screen.php';
require_once 'lib/tv/tv_favorites_screen.php';

require_once 'webtv_config.php';
require_once 'webtv_m3u_tv.php';
require_once 'webtv_setup_screen.php';
require_once 'webtv_tv_channel_list_screen.php';

///////////////////////////////////////////////////////////////////////////

class WeburgTVPlugin extends DefaultDunePlugin
{
    public function __construct()
    {
        $this->tv = new WeburgHLSTv();

        $this->add_screen(new WeburgTvChannelListScreen($this->tv,
                WeburgTVConfig::GET_TV_CHANNEL_LIST_FOLDER_VIEWS()));
        $this->add_screen(new TvFavoritesScreen($this->tv,
                WeburgTVConfig::GET_TV_CHANNEL_LIST_FOLDER_VIEWS()));
        $this->add_screen(new TvGroupListScreen($this->tv,
                WeburgTVConfig::GET_TV_GROUP_LIST_FOLDER_VIEWS()));
        $this->add_screen(new WeburgTVSetupScreen());
    }

}

///////////////////////////////////////////////////////////////////////////
?>
