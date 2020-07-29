<?php

//decode by QQ:270656184 http://www.yunlu99.com/
error_reporting(0);
date_default_timezone_set('PRC');
require dirname(__FILE__) . DIRECTORY_SEPARATOR . 'hunkikey.php';
function hunkiHtml($html)
{
	$hunki_json = gethunkiJson();
	$hunki = new hunkiHelper($hunki_json);
	$html = $hunki->getHtml($html);
	return $html;
}
class hunkiHelper
{
	protected $hunki_json;
	protected $html_dom;
	public function __construct($hunki_json)
	{
		$this->hunki_json = $hunki_json;
	}
	function getHtml($html)
	{
		$requestUrl = @$_SERVER["REQUEST_URI"];
		if (strstr($requestUrl, "r=p/d")) {
			require_once 'libs/simple_html_dom.php';
			$this->html_dom = str_get_html($html);
			return $this->doDetail($html);
		}
		if (strpos($requestUrl, 'wap') or strstr($requestUrl, 'http://pdd.yyh008.cn/') or strstr($requestUrl, 'index/classify') or strstr($requestUrl, 'index/r') or strstr($requestUrl, 'index/cat') or strstr($requestUrl, 'index/9') or strstr($requestUrl, 'r=index/e') or strstr($requestUrl, 'r=every/e')) {
			if (strstr($requestUrl, 'r=index/wap')) {
				if ($this->hunki_json['dataoke_navGrid'] == 'on') {
					$html = $this->loadNavGrid($html);
				}
			}
			return $this->doIndex($html);
		}
		if (strpos($requestUrl, 'customService') or strstr($requestUrl, 'search')) {
			return $this->doIndex($html);
		}
		$fileBody = dirname(__FILE__) . '/hunkihtml/pc/body.html';
		if (file_exists($fileBody)) {
			$body = file_get_contents($fileBody);
			$html = str_ireplace('</body>', $body . '</body>', $html);
		}
		return $html;
	}
	private function doDetail($html)
	{
		$html = $this->inithunkiHtml($html, "detail");
		if ($this->hunki_json["dataoke_navButton"] == 'on') {
			$html = $this->loadNavButton($html);
		}
		if ($this->hunki_json["dataoke_kouLing"] == 'on') {
			$html = $this->loadKouLingHtml($html);
		}
		return $html;
	}
	private function doIndex($html)
	{
		$html = $this->inithunkiHtml($html, "index");
		if ($this->hunki_json["dataoke_menu"] == 'on') {
			$html = $this->loadMenu($html);
		}
		return $html;
	}
	private function inithunkiHtml($html, $page)
	{
		$fileBody = __DIR__ . '/hunkihtml/' . $page . 'Body.html';
		if (file_exists($fileBody)) {
			$body = file_get_contents($fileBody);
			$html = str_ireplace('</body>', $body . '</body>', $html);
		}
		$fileHead = __DIR__ . '/hunkihtml/' . $page . 'Head.html';
		if (file_exists($fileHead)) {
			$head = file_get_contents($fileHead);
			$html = str_ireplace('</head>', $head . '</head>', $html);
		}
		$hunkiJs = __DIR__ . '/script/hunki.js';
		if (file_exists($hunkiJs)) {
			$html = str_ireplace('</body>', '<script src="hunki/script/hunki.js"></script></body>', $html);
		}
		return $html;
	}
	private function loadMenu($html)
	{
		$file = HUNKI_PATH . 'menu.html';
		if (file_exists($file)) {
			$body = file_get_contents($file);
			$requestUrl = @$_SERVER["REQUEST_URI"];
			if (strstr($requestUrl, "r=a/t")) {
				$html = str_ireplace("</body>", $body . "</body>", $html);
			} else {
				$t = $this->strCut("</head>", "<div", $html);
				$html = str_ireplace($t, $t . $body, $html);
			}
		}
		return $html;
	}
	private function loadNavButton($html)
	{
		$file = HUNKI_PATH . 'detailNavButton.html';
		if (file_exists($file)) {
			$body = file_get_contents($file);
			$t = $this->strCut('</head>', '<div', $html);
			$html = str_ireplace($t, $t . $body, $html);
		}
		return $html;
	}
	private function loadNavGrid($html)
	{
		$file = HUNKI_PATH . 'indexNavGrid.html';
		if (file_exists($file)) {
			$body = file_get_contents($file);
			$target = '<!--顶部非专业版用户结束-->';
			$html = $this->strReplace('<!--顶部非专业版用户开始-->', $target, $html, $body);
			return $html;
		}
	}
	private function loadHtmlPartial($html, $name)
	{
		$file = HUNKI_PATH . 'detailNavButton.html';
		if (file_exists($file)) {
			$body = file_get_contents($file);
			$html = str_ireplace('<body>', '<body>' . $body, $html);
		}
		return $html;
	}
	private function loadKouLingHtml($html)
	{
		$file = HUNKI_PATH . 'detailKouLing.html';
		if (!file_exists($file)) {
			return $html;
		}
		$tag = '<div class="recommend-wrapper">';
		$lianjie = $this->getLianJie($html);
		if (empty($lianjie) && strpos($lianjie, 'uland.taobao.com') === false) {
			return $html;
		}
		$kouling = $this->getKouLing($lianjie['link'], $lianjie['img'], $lianjie['title']);
		$koulingHtml = str_ireplace('{{kouling}}', $kouling, file_get_contents($file));
		$koulingHtml = str_ireplace('{{title}}', $lianjie['title'], $koulingHtml);
		$koulingHtml = str_ireplace('{{price}}', $lianjie['price'], $koulingHtml);
		return str_ireplace($tag, $tag . $koulingHtml, $html);
	}
	private function getLianJie()
	{
		try {
			$a = $this->html_dom->find('.img .ui-link', 0);
			$link = $a->href;
			$eTitle = $this->html_dom->find('.title-wrapper', 0);
			$eprice = $this->html_dom->find('.goods-price b', 0);
			$eImage = $eTitle->prev_sibling();
			$src = '';
			if ($eImage != null) {
				$eImage2 = $eImage->first_child();
				$src = $eImage2->src;
			}
			$title = str_replace(' ', '', trim($eTitle->plaintext));
			$price = str_replace('￥', '', trim($eprice->plaintext));
		} catch (Exception $e) {
			$title = '';
			$src = '';
			$link = '';
			$price = '';
		}
		return array('title' => $title, 'img' => $src, 'link' => $link, 'price' => $price);
	}
	private function getKouLing($url, $tupian, $neirong)
	{
		include __DIR__ . "/libs/topsdk/TopSdk.php";
		$c = new TopClient();
		$c->appkey = $this->hunki_json['taobao_appKey'];
		$c->secretKey = $this->hunki_json['taobao_secretKey'];
		$req = new WirelessShareTpwdCreateRequest();
		if (!empty($tupian)) {
			$logo = $tupian;
		} else {
			$logo = "http://bkebo.cn/1.png";
		}
		if (!empty($neirong)) {
			$text = $neirong;
		} else {
			$text = "粉丝福利购，立即领券~";
		}
		$user_id = "1628092147";
		$req = new TbkTpwdCreateRequest();
		$req->setUserId($user_id);
		$req->setText($text);
		$req->setUrl($url);
		$req->setLogo($logo);
		$req->setExt("{}");
		$resp = $c->execute($req);
		$kouling = $resp->data->model;
		return $kouling;
	}
	private function strCut($begin, $end, $str)
	{
		$b = mb_strpos($str, $begin) + mb_strlen($begin);
		$e = mb_strpos($str, $end) - $b;
		return mb_substr($str, $b, $e);
	}
	private function strReplace($begin, $end, $str, $replacement)
	{
		$b = mb_strpos($str, $begin);
		$e = mb_strpos($str, $end) - $b;
		$cut = mb_substr($str, $b, $e);
		return str_ireplace($cut, $replacement, $str);
	}
}