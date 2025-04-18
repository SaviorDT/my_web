<?php
// 111550008 蔡東霖 第5次作業 12/6
// 111550008 Tony Tsai The Fifth Homework 12/6

require_once "./gui.php";
require_once "./models.php";
require_once "./database.php";

class BlackJackGame {
    private static ?BlackJackGame $instance = null;
    private CardInfo $card_info;
    private MoneyInfo $money_info;
    private GameRecord $game_record;

    private function __construct() {
        $this->card_info = new CardInfo();
        $this->money_info = new MoneyInfo();
        $this->game_record = new GameRecord();

        $this->loadFromCookie();
        GUI::getInstance()->setCard(DEALER, array_fill(0, count($this->card_info->dealer), CARD_BACK));
        GUI::getInstance()->setCard(PLAYER, $this->card_info->player);
    }

    public static function getInstance(): BlackJackGame {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function isStart(): bool {
        return count($this->card_info->dealer) > 0;
    }

    public function isLogin(): bool {
        return is_int($this->game_record->getUserId());
    }

    public function addCard(int $num): void {
        if (abs($num) != 1) {
            throw new RangeException("num should be +-1.");
        }

        if ($this->card_info->card_set < 1) {
            $num /= 4;
        } elseif ($this->card_info->card_set == 1 && $num == -1) {
            $num /= 4;
        }
        $this->card_info->card_set += $num;

        if ($this->card_info->card_set <= 0.25) {
            $this->card_info->card_set = 0.25;
        }

        $this->card_info->shuffle();
        $this->saveToCookie();
    }

    public function addMoney($money): void {
        $money = intval($money);
        if (is_nan($money)) {
            return;
        }
        $this->money_info->player += $money;
        $this->saveToCookie();
    }

    public function getMoney(): int {
        return $this->money_info->player;
    }

    public function getCardSet(): float {
        return $this->card_info->card_set;
    }

    public function clearGameRecord(): void {
        $this->game_record->clear();
    }

    public function start(): void {
        $this->card_info->dealer_card_visible = false;
        if (count($this->card_info->cards) < $this->card_info->min_card) {
            $this->card_info->shuffle();
        }

        $this->assignChip();
    }

    public static function assignChip_call() {
        self::getInstance()->assignChip();
    }

    public function assignChip() {
        echo GUI::getInstance()->addInputDialog("荷官何冠", "請輸入要賭多少?<br>
            您目前還有 {$this->money_info->player} 籌碼 <br>
            目前賭桌上總共有 {$this->money_info->in_game} 籌碼", 'BlackJackGame::assignChip_callback', 
            [
                "確定",
                "返回"
            ])
            ->getHtmlStr();
    }

    public static function assignChip_callback($option, $input) {
        self::getInstance()->assignChip_callback2($option, $input);
    }

    private function assignChip_callback2($option, $input) {
        if($option == "確定") {
            if(!$this->money_info->signal($input)) {
                echo GUI::getInstance()->addSelectDialog("下注失敗", 
                    "您剛剛想要下注 {$input} 籌碼但是失敗了<br>
                    您目前還有 {$this->money_info->player} 籌碼<br>", 
                    'BlackJackGame::assignChip_call',
                    ["知道了"])->getHtmlStr();
            } else {
                $this->game_record->push(SIGNAL, 
                    ['amount' => $input, 
                    'player_money' => $this->money_info->player, 
                    'in_game_money' => $this->money_info->in_game, 
                    'dealer' => $this->card_info->dealer, 
                    'player' => $this->card_info->player]);
                $this->saveToCookie();
                if ($this->isStart()) {
                    $this->dealPlayer();
                } else {
                    $this->resumeGame();
                }
            }
        } else if ($this->isStart()) {
            $this->playerAction();
        } else {
            Hall::showEntryPoint();
        }
    }

    public function playerAction() {
        if (count($this->card_info->player) >= 5 || $this->card_info->countTotalPoints(PLAYER) >= 21) {
            echo GUI::getInstance()->addSelectDialog("邪惡商人", "是否要讓莊家必勝?", 'BlackJackGame::playerActionDone_call', 
            [
                "是",
                "否"
            ])
            ->getHtmlStr();
            return;
        }
        else {
            $this->resumeGame();
            echo GUI::getInstance()->addSelectDialog("荷官何冠", "
            您目前還有 {$this->money_info->player} 籌碼 <br>
            目前賭桌上總共有 {$this->money_info->in_game} 籌碼<br>
            牌數還剩下 ".count($this->card_info->cards)." 張<br>
            請選擇要進行的動作", 'BlackJackGame::playerAction_callback', 
            [
                "要牌",
                "停止",
                "加注"
            ])
            ->getHtmlStr();
        }
    }

    public static function playerActionDone_call($option) {
        self::getInstance()->playerActionDone($option);
    }

    private function playerActionDone($option) {
        if ($option == '是') {
            $this->card_info->dealerCheat();
        }
        $this->dealerAction();
    }

    public static function playerAction_callback($option) {
        self::getInstance()->playerAction_callback2($option);
    }

    private function playerAction_callback2($option) {
        switch($option) {
            case "要牌":
                $this->dealPlayer();
                break;
            case "停止":
                echo GUI::getInstance()->addSelectDialog("邪惡商人", "是否要讓莊家必勝?", 'BlackJackGame::playerActionDone_call', 
                [
                    "是",
                    "否"
                ])
                ->getHtmlStr();
                break;
            case "加注":
                $this->assignChip();
                break;
        }
    }

    public static function dealerAction_call() {
        self::getInstance()->dealerAction();
    }

    private function dealerAction() {
        $this->showDealCards();
        if ($this->card_info->countTotalPoints(DEALER) < 17 && count($this->card_info->dealer) < 5) {
            $this->dealDealer(true);
            return;
        }

        $this->gameSet();
    }

    private function showDealCards() {
        $this->card_info->dealer_card_visible = true;
        GUI::getInstance()->setCard(DEALER, $this->card_info->dealer);
    }

    private function dealDealer($dealer_turn = false) {
        $card = $this->card_info->deal(DEALER);
        
        $this->game_record->push(DEAL, [
            'who' => DEALER, 
            'card' => $card, 
            'player_money' => $this->money_info->player, 
            'in_game_money' => $this->money_info->in_game, 
            'dealer' => $this->card_info->dealer, 
            'player' => $this->card_info->player]);
            
        $card = $this->card_info->dealer_card_visible ? $card : CARD_BACK;
        $this->saveToCookie();

        $callback_url = $dealer_turn ? "./callback_functions.php?function=BlackJackGame::dealerAction_call&amp;type=call" : "./index.php";
        GUI::getInstance()->dealCard(DEALER, $card, $callback_url);
    }

    private function dealPlayer() {
        $card = $this->card_info->deal(PLAYER);

        $this->game_record->push(DEAL, [
            'who' => PLAYER, 
            'card' => $card, 
            'player_money' => $this->money_info->player, 
            'in_game_money' => $this->money_info->in_game, 
            'dealer' => $this->card_info->dealer, 
            'player' => $this->card_info->player]);
            
        $this->saveToCookie();
        
        GUI::getInstance()->dealCard(PLAYER, $card, "./index.php");
    }

    public function resumeGame() {
        if (count($this->card_info->player) < 2 && count($this->card_info->player) < count($this->card_info->dealer)) {
            $this->dealPlayer();
        }
        if (count($this->card_info->dealer) < 2) {
            $this->dealDealer(false);
        }
    }

    private function gameSet() {
        $player_points = $this->card_info->countTotalPoints(PLAYER);
        $dealer_points = $this->card_info->countTotalPoints(DEALER);
        $player_blackjack = ($player_points == 21 && count($this->card_info->player) == 2) ? true : false;
        $dealer_blackjack = ($dealer_points == 21 && count($this->card_info->dealer) == 2) ? true : false;
        $winner = -1;
    
        $message = "出現未知錯誤";
        if ($player_blackjack && !$dealer_blackjack) {
            $winner = PLAYER;
            $this->money_info->signal(floor($this->money_info->in_game / 4));
            $message = "挖屋，你是布雷克捷克耶，你可以獲得1.5倍的獎金，這是你的 {$this->money_info->in_game} 籌碼
                <br>，你現在總共有 " . ($this->money_info->player + $this->money_info->in_game) . " 籌碼
                <br>請選擇一個動作";
        } elseif ($player_blackjack && $dealer_blackjack) {
            $winner = DEALER;
            $message = "恭喜你遇到了極低概率事件，為了安慰你，莊家當著你的面清點了 {$this->money_info->in_game} 籌碼，希望你會開心一點
                <br>，你現在總共有 {$this->money_info->player} 籌碼
                <br>請選擇一個動作";
        } elseif ($dealer_blackjack) {
            $winner = DEALER;
            $this->money_info->signal(ceil($this->money_info->in_game / 4));
            $message = "恭喜你遇到了 blackjack，為了慶祝，你主動包了 " . ceil($this->money_info->in_game / 6) . " 籌碼的紅包給莊家
                <br>你現在總共有 {$this->money_info->player} 籌碼
                <br>請選擇一個動作";
        } elseif ($player_points > 21) {
            $winner = DEALER;
            $message = "你爆炸了，恭喜你喜提 -" . ($this->money_info->in_game / 2) . " 籌碼，
                <br>你現在總共有 {$this->money_info->player} 籌碼
                <br>請選擇一個動作";
        } elseif ($dealer_points > 21) {
            $winner = PLAYER;
            $message = "你煮了一道金光閃閃的料理讓莊家爆牌了，你得到了桌面上的 {$this->money_info->in_game} 籌碼
                <br>你現在總共有 " . ($this->money_info->player + $this->money_info->in_game) . " 籌碼
                <br>請選擇一個動作";
        } elseif ($player_points > $dealer_points) {
            $winner = PLAYER;
            $message = "恭喜你比莊家大，你把桌上根號 " . ($this->money_info->in_game ** 2) . " 的籌碼通通收入囊中，
                <br>你現在總共有 " . ($this->money_info->player + $this->money_info->in_game) . " 籌碼
                <br>請選擇一個動作";
        } elseif ($dealer_points > $player_points) {
            $winner = DEALER;
            $message = "恭喜你比莊家小，莊家把桌上根號 " . ($this->money_info->in_game ** 2) . " 的籌碼通通收入囊中，
                <br>你現在總共有 {$this->money_info->player} 籌碼
                <br>請選擇一個動作";
        } elseif ($dealer_points == $player_points) {
            $winner = PLAYER;
            $this->money_info->signal(-$this->money_info->in_game / 2, true);
            $message = "很不幸的你與莊家大戰300回合後雙方平手，各自拿回屬於自己的薯片
                <br>你現在總共有 {$this->money_info->player} 籌碼
                <br>請選擇一個動作";
        } else {
            $winner = PLAYER;
            $this->money_info->signal(-$this->money_info->in_game / 2, true);
            $message = "出現了意料之外的情境，本局作廢，請截圖傳給開發者維修
                <br>你現在總共有 {$this->money_info->player} 籌碼
                <br>閒家莊家點數、閒家莊家blackjack: {$player_points}, {$dealer_points}, {$player_blackjack}, {$dealer_blackjack}
                <br>請選擇一個動作";
        }
    
        $this->money_info->gameSet($winner);
        $this->game_record->push(GAME_SET, [
            'winner' => $winner, 
            'player_money' => $this->money_info->player, 
            'in_game_money' => $this->money_info->in_game, 
            'dealer' => $this->card_info->dealer, 
            'player' => $this->card_info->player]);
        $this->card_info->gameSet();
        
        $this->saveToCookie();

        echo GUI::getInstance()->addSelectDialog("賭局結束", $message, 'Hall::showEntryPoint', [
            "閱"
        ])
        ->getHtmlStr();
    }    

    public function requestLogin() {
        echo GUI::getInstance()->addDoubleInputDialog(
            '註冊或登入',
            '請輸入帳號密碼',
            'BlackJackGame::login_call',
            ['確認']
        )
        ->getHtmlStr();
        exit(0);
    }

    public static function login_call($option, $input1, $input2) {
        self::getInstance()->login($input1, $input2);
    }

    private function login($input1, $input2) {
        $user = Database::getInstance()->where('users', ['name' => $input1])[0] ?? [];
        if(empty($user)) {
            Database::getInstance()->add('users', ['name' => $input1, 'password' => $input2]);
            $user = Database::getInstance()->where('users', ['name' => $input1])[0];
            Database::getInstance()->add('games', ['user_id' => $user['id'], 'record' => '[]']);

            echo GUI::getInstance()->addSelectDialog("登入", "使用者名稱不存在，已創建新使用者，請重新登入", 'Hall::showEntryPoint', [
                "確定"
            ])
            ->getHtmlStr();
        }
        else {
            if ($input2 == $user['password']) {
                $this->game_record->setUserId($user['id']);
                $this->money_info->player = $user['chip'];
                $this->saveToCookie();
                Hall::showEntryPoint();
            }
            else {
                echo GUI::getInstance()->addSelectDialog("登入", "密碼錯誤，請重新登入", 'Hall::showEntryPoint', [
                    "確定"
                ])
                ->getHtmlStr();
            }
        }
    }

    private function loadFromCookie() {
        $json_str = $_COOKIE['game_data'] ?? '';
        $json_str = $json_str;

        $data = json_decode($json_str, true);
        $this->money_info = new MoneyInfo($data['money_info'] ?? null);
        $this->card_info = new CardInfo($data['card_info'] ?? null);
        $this->game_record = new GameRecord($data['game_record'] ?? null);
    }

    private function saveToCookie() {
        $json_str = json_encode([
            'money_info' => $this->money_info->toJson(), 
            'card_info' => $this->card_info->toJson(), 
            'game_record' => $this->game_record->toJson()]);
        setcookie('game_data', $json_str, time() + 3600, "/");
    }
}

class Hall {
    public static function newGame() {
        BlackJackGame::getInstance()->start();
    }
    
    public static function showEntryPoint() {
        if(BlackJackGame::getInstance()->isStart()) {
            BlackJackGame::getInstance()->playerAction();
            return;
        }

        if(!BlackJackGame::getInstance()->isLogin()) {
            BlackJackGame::getInstance()->requestLogin();
            return;
        }

        echo GUI::getInstance()->addSelectDialog("篤昶賭場", "歡迎來到篤昶賭場，請選擇你要進行的動作", "Hall::showEntryPoint_callback", 
            [
                "開始遊戲",
                "設定籌碼",
                "調整牌數",
                // "歷史紀錄",
                "規則"
            ])
            ->getHtmlStr();
    }

    public static function showEntryPoint_callback($option) {
        switch($option) {
            case "開始遊戲":
                Hall::newGame();
                break;
            case "設定籌碼":
                Hall::showSetChip();
                break;
            case "調整牌數":
                Hall::showAdjustCardSet();
                break;
            // case "歷史紀錄":
            //     Hall::showHistory();
            //     break;
            case "規則":
                Hall::showRule(PLAY_RULE ,PLAY_RULE_CONTENT);
                break;
        }
    }

    public static function showRule($rule, $rule_content) {
        echo GUI::getInstance()->addSelectDialog($rule, $rule_content, "Hall::showRule_callback", 
            [
                "基本玩法",
                "詳細規則",
                "返回"
            ])->getHtmlStr();
    }

    public static function showRule_callback($option) {
        switch ($option) {
            case "基本玩法":
                Hall::showRule(PLAY_RULE, PLAY_RULE_CONTENT);
                break;
            case "詳細規則":
                Hall::showRule(DETAIL_RULE, DETAIL_RULE_CONTENT);
                break;
            case "返回":
                Hall::showEntryPoint();
                break;
        }
    }

    // public static function showHistory() {
    //     echo GUI::getInstance()->addSelectDialog("監視器", BlackJackGame::getInstance()->getGameRecordTable()->saveHTML(), 'Hall::showHistory_callback', 
    //         [
    //             "清空",
    //             "返回"
    //         ])
    //         ->getHtmlStr();
    // }

    public static function showHistory_callback($option) {
        if($option == "清空") {
            BlackJackGame::getInstance()->clearGameRecord();
            Hall::showHistory();
        }
        else {
            Hall::showEntryPoint();
        }
    }
    
    public static function showSetChip() {
        echo GUI::getInstance()->addSelectDialog("籌碼兌換處", "你目前擁有 ".BlackJackGame::getInstance()->getMoney()." 籌碼\n請選擇你要進行的動作", 'Hall::showSetChip_callback',
            [
                "-100",
                "+100",
                // "自訂數量",
                "返回"
            ])
            ->getHtmlStr();
    }

    public static function showSetChip_callback($option) {
        switch($option) {
            case "-100":
                BlackJackGame::getInstance()->addMoney(-100);
                Hall::showSetChip();
                break;
            case "+100":
                BlackJackGame::getInstance()->addMoney(100);
                Hall::showSetChip();
                break;
            // case "自訂數量":
            //     Hall.showCustomChip(() => {Hall.showSetChip(back)});
            //     break;
            case "返回":
                Hall::showEntryPoint();
                break;
        }
    }

    static function showAdjustCardSet() {
        echo GUI::getInstance()->addSelectDialog("荷官何冠", "目前使用 ".BlackJackGame::getInstance()->getCardSet()." 副牌進行遊玩\n請選擇你要進行的動作", 'Hall::showAdjustCardSet_callback', 
            [
                "-",
                "+",
                "返回"
            ])
            ->getHtmlStr();
    }

    static function showAdjustCardSet_callback($option) {
        switch ($option) {
            case "-":
                BlackJackGame::getInstance()->addCard(-1);
                Hall::showAdjustCardSet();
                break;
            case "+":
                BlackJackGame::getInstance()->addCard(1);
                Hall::showAdjustCardSet();
                break;
            case "返回":
                Hall::showEntryPoint();
                break;
        }
    }
    
    // static function showCustomChip(back) {
    //     let {option, input} = await GUI.Dialog.showInputDialog("籌碼兌換處", `你目前擁有 ${BlackJackGame.getInstance().getMoney()} 籌碼\n請選擇你要增加的數量`,
    //         [
    //             "確定",
    //             "返回"
    //         ]);
    
    //     switch(option) {
    //         case "確定":
    //             BlackJackGame.getInstance().addMoney(input);
    //             Hall.showCustomChip(back);
    //             break;
    //         case "返回":
    //             back();
    //             break;
    //     }
    // }
}

const PLAY_RULE = "基本玩法";

const PLAY_RULE_CONTENT = "
    點擊設定籌碼可以調整你擁有的籌碼<br>
    <br>
    點擊調整牌數可以調整你希望賭場用幾副牌進行遊戲，最低為 1/4 副，最高不設限 <br>
    <br>
    點擊歷史紀錄可以查看加注、發牌、結算紀錄，每局遊戲以兩行空行隔開 <br>
    <br>
    點擊開始遊戲可以玩遊戲:<br>
    <br>
    首先需要繳納入場費，入場費與加注一樣，必須是正整數<br>
    <br>
    繳納完入場費後，荷官會開始發牌，莊家與玩家各兩張，玩家回合結束前無法得知莊家的牌 <br>
    <br>
    荷官發完牌後，如果玩家不到五張牌且不到21點，可以選擇是否加注，如果有加注必定會再抽一張牌 <br>
    <br>
    荷官發完牌後，如果玩家不到五張牌且不到21點，可以選擇是否要牌，如果要牌會再得到一張牌 <br>
    <br>
    當玩家選擇停止，或是玩家超過五張牌，或是玩家達到21點以上，會輪到莊家的回合 <br>
    <br>
    在進入莊家回合之前，會詢問是否讓莊家必勝，如果選擇是的話就會開始偷換牌<br>
    <br>
    但是如果剩餘牌組無論如何莊家都贏不了，為了避免出千被發現，玩家還是會贏<br>
    <br>
    莊家會一直要牌，直到莊家達到17點以上 <br>
    <br>
    莊家停止後，會開始根據規則比大小，結算雙方得失，並詢問玩家要玩下一場還是返回首頁。
";

const DETAIL_RULE = "詳細規則";

const DETAIL_RULE_CONTENT = "
    建議輸入偶數籌碼，奇數籌碼會在觸發 1.5 倍時以莊家有利的方向取整。 <br>
    <br>
    A 可能是1點或11點，會在不爆的前提下取最大值 <br>
    <br>
    如果只有兩張牌並且21點，就是 blackjack <br>
    <br>
    任何一方 blackjack 都會獲得賭注金額 1.5 倍的收益 <br>
    <br>
    如果雙方都 blackjack，莊家會獲得 1 倍的收益 <br>
    <br>
    如果點數超過 21 點即為爆牌 <br>
    <br>
    如果閒家爆牌，不論莊家是否爆牌，都算閒家輸，莊家獲得 1 倍收益 <br>
    <br>
    莊家爆牌則算閒家贏，閒家獲得 1 倍收益 <br>
    <br>
    如果是上述以外的情況，則比較雙方點數大小，點數大者贏，獲得 1 倍收益，平手則退還賭金 <br>
    <br>
";