<?php

namespace AppBundle\Model;

use Symfony\Component\DomCrawler\Crawler;

class SuckModel
{
    public function suckWithWr($word)
    {
        $url = 'http://www.wordreference.com/enfr/' . $word;
        $curl_handle = curl_init();
        \curl_setopt($curl_handle, CURLOPT_URL, $url);
        \curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
        \curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        \curl_setopt($curl_handle, CURLOPT_USERAGENT, 'googlebot');
        $html = \curl_exec($curl_handle);
        \curl_close($curl_handle);
        
        return $html;
    }
    
    public function htmlToArray($html)
    {
        $crawler = new Crawler($html);
        $crawler = $crawler->filter('table.WRD > tr');

        $class = '';
        $newWord = true;

        $key = $k = $i = 0;

        $arrayTrans = $senses = $global = array();
        $additional = 0;
        foreach ($crawler as $domElement) {
            $sense = '';
            if ($domElement->getAttribute('class') == 'wrtopsection' && $key > 0) {
                $additional = 1;
            }
            if ($domElement->getAttribute('class') == 'even' || $domElement->getAttribute('class') == 'odd') {
                $tr = new Crawler($domElement);
                if ($class != $domElement->getAttribute('class')) {
                    $key++;
                    $k = $k + 0.1;
                    $priority = 0;
                    $class = $domElement->getAttribute('class');
                    if (null !== ($newWord = $tr->filter('strong')->eq(0)) && count($newWord)) {
                        $t = '';
                        if (null !== ($type = $tr->filter('em')->eq(0))) {

                            $type->filter('span')->each(function (Crawler $crawler) {
                                foreach ($crawler as $node) {
                                    $node->parentNode->removeChild($node);
                                }
                            });
                            $t = '';
                            if (count($type)) {
                                $t = $type->html();
                            }
                            
                        }
                        $newWord = explode(',', $newWord->html());
                        $w = $this->cleanString($newWord['0']);
                    }
                    
                    
                    if($w){
                        if ((null !== ($senseValue = $tr->filter('td')->eq(1))) && count($senseValue) > 0) {
    
                            $senseValue->filter('span')->each(function (Crawler $crawler) {
                                foreach ($crawler as $node) {
                                    $node->parentNode->removeChild($node);
                                }
                            });
                            $senseValue->filter('i')->each(function (Crawler $crawler) {
                                foreach ($crawler as $node) {
                                    $node->parentNode->removeChild($node);
                                }
                            });
                            $sensesArrayValue = explode(',', $senseValue->html());
                            $converted = strtr($sensesArrayValue['0'], array_flip(get_html_translation_table(HTML_ENTITIES, ENT_QUOTES)));
                            $converted = trim($converted, chr(0xC2).chr(0xA0));
    
                            $sense = $this->cleanSense($converted);
    
                        }
    
                        if (null !== ($trans = $tr->filter('td.ToWrd')->eq(0)) && count($trans)) {
                            if (null == $trans->filter('span[title*="translation unavailable"]')->eq(0)) {
                                continue;
                            }
    
                            $t_trans = '';
                            if (null !== ($type_trans = $trans->filter('em')->eq(0))) {
                                $type_trans->filter('span')->each(function (Crawler $crawler) {
                                    foreach ($crawler as $node) {
                                        $node->parentNode->removeChild($node);
                                    }
                                });
                                $t_trans = $type->html();
                            }
    
                            $trans->filter('em')->each(function (Crawler $crawler) {
                                foreach ($crawler as $node) {
                                    $node->parentNode->removeChild($node);
                                }
                            });
                            $trans->filter('a')->each(function (Crawler $crawler) {
                                foreach ($crawler as $node) {
                                    $node->parentNode->removeChild($node);
                                }
                            });
                            
                            $concat = $trans->html();
                            $senses[$key] = array('w' => $w, 'additional' => $additional, 'category' => $t, 'sense' => $sense, 'concat' => $trans->html());
                        }
                    }
                } else if (null !== ($trans = $tr->filter('td.ToWrd')->eq(0)) && count($trans) && isset($senses[$key])) {
                    $trans->filter('em')->each(function (Crawler $crawler) {
                        foreach ($crawler as $node) {
                            $node->parentNode->removeChild($node);
                        }
                    });
                    $trans->filter('a')->each(function (Crawler $crawler) {
                        foreach ($crawler as $node) {
                            $node->parentNode->removeChild($node);
                        }
                    });
                    if (count($trans)) {
                            $senses[$key]['concat'] = $senses[$key]['concat'] . ',' . $trans->html();
                        
                    }
                }
            }
        }

        return $senses;
    }

    protected function cleanString($string)
    {
        if (mb_detect_encoding($string) != 'UTF-8') {
            $string = iconv('ASCII', 'UTF-8', $string);
        }
        
        $endash = html_entity_decode('&#x2013;', ENT_COMPAT, 'UTF-8');
        $string = str_replace('*', '', $string);
        $string = str_replace('...', '', $string);
        $string = str_replace('‐', '-', $string);
        $string = str_replace('<br>', '', $string);
        $string = str_replace('<span title="something">[sth]</span>', '[sth]', $string);
        $string = str_replace('<span title="somebody">[sb]</span>', '[sb]', $string);
        $string = str_replace('<span title="somebody or something">[sb/sth]</span>', '[sb/sth]', $string);

        if (!preg_match('/^[-\'\p{L}\p{M}\s-]+$/u', $string)) {
            //echo "\n". 'not accepted: '. $string;
            return null;
        }

        return trim($string);
    }

    protected function cleanSense($string)
    {
        $string = str_replace('(', ' ', $string);
        $string = str_replace(')', ' ', $string);
        $string = str_replace('&nbsp;', ' ', $string);

        if (!preg_match('/^[\p{L}-\s\-\']*$/u', $string)) {
            //echo "\n". 'wrong sense' .$string;
            return null;
        }

        return trim($string);
    }
}