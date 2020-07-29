<?php
/**
 * Created by 317149766@qq.com.
 * User: keepup
 * Date: 2018/9/7
 * Time: 11:37
 */
class Loader
{

    private $html;

    /**
     * @return string
     */
    public function getHtml()
    {
        return $this->html;
    }

    /**
     * @param string $html
     */
    public function setHtml($html)
    {
        $this->html = $html;
    }

    public function __construct()
    {
    }

    /**
     * 加载html
     * @param $html
     * @return $this
     */
    public function loadCss()
    {

        $hunkiCss = __DIR__ . '/hunki/loader/mui.css';
        if (file_exists($hunkiCss)) {
            $time = time();
            $html = str_ireplace('</head>', "<link rel='stylesheet' href='hunki/loader/mui.css?v={$time}' /></head>", $this->html);
            $this->setHtml($html);
        }
        //替换header
        return $this;
    }

    /**
     * 加载js
     * @param $html
     * @return $this
     */
    public function loadJs()
    {
        $hunkiJs = __DIR__ . '/hunki/loader/common.js';
        if (file_exists($hunkiJs)) {
            $time = time();
            $html = str_ireplace('</head>', "<script src='hunki/loader/common.js?v={$time}'></script></head>", $this->html);
            $this->setHtml($html);
        }
        //替换js
        return $this;
    }

    /**
     * 加载php脚本
     * @param $html
     * @return $this
     */
    public function loadPhp()
    {
        //特殊需求需加载php
        $this->replaceHeader();
        //替换body
        $this->replaceBody();
        return $this;
    }

    /**
     * 获取html
     */
    public function getContent(){
        if(empty($this->html)){
            exit("请设置html");
        }
        $this->loadCss()->loadJs()->loadPhp();
        return $this->getHtml();
    }

    /**
     * 替换header
     */
    public function replaceHeader(){
        $header =  __DIR__."/loaderHtml/header.html";
        if(file_exists($header)){
            $match='/<header class="icon_header">([\s\S]+?)<\/header>/i';//标签正则匹配
            $html = preg_replace($match,file_get_contents($header),$this->html);
            $this->setHtml($html);
        }
    }

    /**
     * 替换body
     */
    public function replaceBody(){
        $hunkiJs = __DIR__ . '/loaderHtml/alert.html';
        if (file_exists($hunkiJs)) {
            $html = str_ireplace('</body>', $hunkiJs."</body>", $this->html);
            $this->setHtml($html);
        }
    }

}
