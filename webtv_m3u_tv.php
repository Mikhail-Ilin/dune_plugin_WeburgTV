??<?php
/*

WeburgTV plugin (mik@mail.ru)

*/

///////////////////////////////////////////////////////////////////////////

require_once 'lib/hashed_array.php';
require_once 'lib/tv/abstract_tv.php';
require_once 'lib/tv/default_epg_item.php';

require_once 'webtv_channel.php';

///////////////////////////////////////////////////////////////////////////

class WeburgHLSTv extends AbstractTv {

    public $group_names = array('Без группы');

    public function __construct() {
        parent::__construct(
                AbstractTv::MODE_CHANNELS_N_TO_M, WeburgTVConfig::TV_FAVORITES_SUPPORTED, false);
    }

    public function get_fav_icon_url() {
        return WeburgTVConfig::FAV_CHANNEL_GROUP_ICON_PATH;
    }

    public function get_tv_playback_url($channel_id, $archive_ts, $protect_code, &$plugin_cookies) {

        $offset = ($archive_ts <= 0) ? 0 : time()-$archive_ts;
        
        return "http://playlist.tv.planeta.tc/playlist/hls/$channel_id-live-$offset-master.m3u8?abs&videoOnly=true&quality=max";

    }

    public function get_tv_stream_url($media_url, &$plugin_cookies) {
         return $media_url;
    }

    function get_group_by_channel($groups, $id) {
        $group = array_key_exists($id, $groups) ? $groups[$id] : 0;
        return $group;
    }

    public function folder_entered(MediaURL $media_url, &$plugin_cookies) {
        if ($media_url->get_raw_string() == 'tv_group_list')
            $this->load_channels($plugin_cookies);
    }

    private static function get_future_epg_days($channel_id) {
        $days = ($channel_id == 0) ? 0 : 6;
        return $days;
    }

    
    function make_id_key($caption) {
        return md5(strtolower(str_replace(array("\r", "\n", "\"", " "), '', $caption)));
        }

    protected function load_channels(&$plugin_cookies) {
    
        $this->channels = new HashedArray();
        $this->groups = new HashedArray();
        $this->playback_url_is_stream = true;
        $promo_group = null;
        
        if ($this->is_favorites_supported()) {
            $this->groups->put(
                    new FavoritesGroup(
                    $this, '__favorites', WeburgTVConfig::FAV_CHANNEL_GROUP_CAPTION, WeburgTVConfig::FAV_CHANNEL_GROUP_ICON_PATH));
        }

        $all_channels_group = new AllChannelsGroup(
                $this, WeburgTVConfig::ALL_CHANNEL_GROUP_CAPTION, WeburgTVConfig::ALL_CHANNEL_GROUP_ICON_PATH);

        $this->groups->put($all_channels_group);

        $m3u_file = 'http://ott.tv.planeta.tc/playlist/channels.m3u?4k&fields=catchup,epg,group';
        $m3u_lines = file(
                $m3u_file,
                FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        for ($i = 0; $i < count($m3u_lines); ++$i)
	        {
	 	        if (preg_match('/#EXTM3U/', strtoupper($m3u_lines[$i]), $matches))
                continue;
                
                $media_url = null;
                $past_epg_days = 0;

		        if (preg_match('/^#EXTINF:[^,]*,(.+)$/', $m3u_lines[$i], $matches)) {
                    $caption = preg_replace('/\"/', '', $matches[1]);

			        if (preg_match('/^#EXTINF:.*tvg-logo=([^ ^,]+)/', $m3u_lines[$i], $matches)) {
			            $logo = preg_replace('/\"/', '', $matches[1]);			
			        }   
                    
			        if (preg_match('/^#EXTINF:.*tvg-id=([^ ^,]+)/', $m3u_lines[$i], $matches)) {
			            $id = preg_replace('/\"/', '', $matches[1]);			
                    }
            
			        if (preg_match('/^#EXTINF:.*group-title=\"([^\"]+)\"/', $m3u_lines[$i], $matches)) {
			            $group_name = preg_replace('/\"/', '', $matches[1]);			
                    }

                    if (preg_match('/^#EXTINF:.*catchup-days=\"([^\"]+)\"/', $m3u_lines[$i], $matches)) {
                        $past_epg_days = preg_replace('/\"/', '', $matches[1]);		
                    }
                }   

                if (preg_match('/^http:\/\//', $m3u_lines[++$i]) == 1)
                    {
                        $media_url = $m3u_lines[$i];
                    }
                
                if ($group_name == 'Промо') continue;

               

                $channel = new WeburgTVChannel($id, $caption, $logo, $media_url, -1, $past_epg_days, 3);
                $this->channels->put($channel);
 
                $group_id = self::make_id_key($group_name);

                $group_logo = WeburgTVConfig::getIkonPath($group_name);

                if (!($this->groups->has($group_id)))
                { 
                    $group = new DefaultGroup($group_id, $group_name, $group_logo);
                    $this->groups->put($group);
                }  
                else
                    $group = $this->groups->get($group_id);

                    $group->add_channel($channel);
                    $channel->add_group($group);
            }
    }

    public function get_day_epg_iterator($channel_id, $day_start_ts, &$plugin_cookies) {
        $epg_result = array();

        $doc = file_get_contents("http://ott.tv.planeta.tc/epg/program.xml?channelId=$channel_id&fields=desc&extended");

        $xml_tv_prog = simplexml_load_string($doc);


        foreach ($xml_tv_prog->programme as $programme){
            $start = strtotime($programme['start']);
            $stop  = strtotime($programme['stop']);
            if ($start>=$day_start_ts && $start<$day_start_ts+86400)
            {
                $epg_result[] = new DefaultEpgItem(
                strval($programme->title),
                strval($programme->desc),
                intval($start),
                intval($stop));
            }
        }

        return
                new EpgIterator(
                $epg_result, $day_start_ts, $day_start_ts + 100400);
    }

}

///////////////////////////////////////////////////////////////////////////
?>
