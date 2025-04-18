<?php
// 111550008 蔡東霖 第5次作業 12/6
// 111550008 Tony Tsai The Fifth Homework 12/6

require_once "./template.php";
require_once "./models.php";

class GUI{
    private $html = null;
    private $table = null;
    private static $instance = null;

    private function __construct() {
        $this->html = Template::createBackground();

        $table = Template::createGameTable()->documentElement;
        $this->table = $this->html->importNode($table, true);
        $this->html->getElementsByTagName('body')[0]->append($this->table);

        header("Content-Type: text/html; charset=utf-8");
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function addSelectDialog($title, $content, $callback_function, $options) {
        $callback_url = "./callback_functions.php?function={$callback_function}&amp;type=select_dialog";

        $dialog = Template::createSelectDialog($title, $content, $callback_url, $options);
        $imported_dialog = $this->html->importNode($dialog->documentElement, true);
        $this->html->getElementsByTagName('body')[0]->append($imported_dialog);

        return $this;
    }

    public function addInputDialog($title, $content, $callback_function, $options) {
        $callback_url = "./callback_functions.php?function={$callback_function}&amp;type=input_dialog";

        $dialog = Template::createInputDialog($title, $content, $callback_url, $options);
        $imported_dialog = $this->html->importNode($dialog->documentElement, true);
        $this->html->getElementsByTagName('body')[0]->append($imported_dialog);

        return $this;
    }

    public function addDoubleInputDialog($title, $content, $callback_function, $options) {
        $callback_url = "./callback_functions.php?function={$callback_function}&amp;type=double_input_dialog";

        $dialog = Template::createDoubleInputDialog($title, $content, $callback_url, $options);
        $imported_dialog = $this->html->importNode($dialog->documentElement, true);
        $this->html->getElementsByTagName('body')[0]->append($imported_dialog);

        return $this;
    }

    public function setCard($who, $cards) {
        $card_deck = ($who == DEALER) ? $this->html->getElementById("dealer-deck") : $this->html->getElementById("player-deck");
        $card_deck->textContent = '';
        foreach($cards as $card) {
            $img = $this->html->createElement('img');
            $src = Card::findCardImgSrc($card);
            $img->setAttribute('src', $src);
            $img->setAttribute('class', 'card movin-card');
            $img->setAttribute('style', 'position: static;');
            $card_deck->appendChild($img);
        }
        
        return $this;
    }

    public function dealCard($who, $card, $callback_url) {
        $src = Card::findCardImgSrc($card);
        
        $card = $this->html->createElement('img');
        $card->setAttribute('src', $src);
        $card->setAttribute('class', 'card moving-card');
        $card->setAttribute('id', 'moving-card');
        $this->html->getElementsByTagName('body')[0]->appendChild($card);

        $dealer_const = DEALER;
        $script = $this->html->createElement('script', <<<JS
            // 初始位置（從發牌點開始）
            onload = () => {
                let card = document.getElementById("moving-card");
                const dealer = document.getElementById("dealer");
                const dealer_rect = dealer.getBoundingClientRect();
                card.style.left = dealer_rect.left + "px";
                card.style.top = dealer_rect.top + "px";
    
                // 目標位置（玩家牌組區域）
                const card_deck = ($who == {$dealer_const}) ? document.getElementById("dealer-deck") : document.getElementById("player-deck");
                const target_rect = card_deck.getBoundingClientRect();
    
                // 使用 setTimeout 來觸發動畫
                setTimeout(() => {
                    card.style.left = target_rect.left + card_deck.children.length * 90 + "px";
                    card.style.top = target_rect.top + "px";
                    card.style.transform = "translateY(0)";
                }, 10);
    
                // 等動畫結束後，將卡片添加到玩家的牌組內
                card.addEventListener("transitionend", async () => {
                    // card.style.position = "static";
                    // card_deck.appendChild(card);
                    // resolve();
                    window.location.href = "$callback_url";
                });
            }
            JS
        );
        $script->setAttribute('type', 'text/javascript');
        $this->html->getElementsByTagName('body')[0]->appendChild($script);

        echo $this->getHtmlStr();
        exit(0);
    }

    public function getHtmlStr() {
        return $this->html->saveHTML();
    }
}
?>