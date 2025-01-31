<?php
class sitedata extends Plugin
{



    public function siteHead()
    {


        global $page;

        $content = html_entity_decode($page->content());

        function siteDataReturn($matches)
        {
            $match = $matches[1];
            $file = file_get_contents(PATH_CONTENT . 'SiteDataSettings.json');
            $jsfile = json_decode($file, true);

            foreach ($jsfile as $item) {
                if ($item['id'] == $match) {
                    return str_replace("\u0027", "'", $item['data']);
                    ;
                }
                ;
            }
            ;
        }
        ;

        $newcontent = preg_replace_callback(
            '/\\[% siteData=(.*) %\\]/i',
            "siteDataReturn",
            $content
        );
        $content = $newcontent;

        $page->setField('content', $content);


    }


    public function adminController()
    {
        global $layout;
        $file = PATH_CONTENT . 'SiteDataSettings.json';
        if (!file_exists($file)) {
            file_put_contents($file, '[]');
        }
        ;
        $datas = file_get_contents(PATH_CONTENT . 'SiteDataSettings.json');

        if (isset($_POST['saver'])) {
            $jsonData = $_POST['json'];
            $decodedData = json_decode($jsonData, true);
            $decodedData = array_map(function ($value) {
                return str_replace("\'", "\u0027", $value);
            }, $decodedData);
            file_put_contents($file, json_encode($decodedData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            echo ("<meta http-equiv='refresh' content='0'>");
        }
        ;
    }

    public function adminView()
    {
        global $security;
        $tokenCSRF = $security->getTokenCSRF();
        include($this->phpPath() . 'PHP/backend.php');
    }

    public function adminSidebar()
    {
        $pluginName = Text::lowercase(__CLASS__);
        $url = HTML_PATH_ADMIN_ROOT . 'plugin/' . $pluginName;
        $html = '<a id="current-version" class="nav-link" href="' . $url . '">🧠 SiteData</a>';
        return $html;
    }
};


function siteData($matches){
	$match = $matches;
	$file = file_get_contents(PATH_CONTENT . 'SiteDataSettings.json');
	$jsfile = json_decode($file, true);

	foreach ($jsfile as $item) {
		if ($item['id'] == $match) {
			echo str_replace("\u0027", "'",$item['data']);
		};
	};
};

?>