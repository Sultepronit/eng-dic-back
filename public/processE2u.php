<?php
declare(strict_types=1);

function processE2u($pageContent, $word) {
    libxml_use_internal_errors(true);
    $html = new DOMDocument();
    $html->loadHTML($pageContent);

    function isArticleMain($article, $word) {
        $header = $article->getElementsByTagName('b')[0]?->textContent;
        if(!$header) return false;
        
        $header = explode('(-', $header)[0];
        $header = preg_split('/[0-9]/', $header)[0];
        $header = trim($header);
        $header = str_replace('|', '', $header);
        $header = str_replace('́', '', $header);
        
        return strtolower($header) === strtolower($word);
    }

    $articles = ['main' => [], 'other' => [], 'context' => []];
    foreach($html->getElementsByTagName('td') as $tag) {
        $class = $tag->getAttribute('class');
        // echo $class, '<br>';
        if($class === 'result_row') {
            $articles['context'][] = $tag;
            continue;
        }

        if(isArticleMain($tag, $word)) {
            $articles['main'][] = $tag;
        } else {
            $articles['other'][] = $tag;
        }
    }

    foreach($articles as $group => $list) {
        echo "<table class={$group}><tbody>";
        foreach($list as $article) {
            echo '<tr>' . $html->saveHTML($article) . '</tr>';
        }
        echo '</table></tbody>';
    }
}

