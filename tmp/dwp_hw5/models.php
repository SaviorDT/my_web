<?php
// 111550008 蔡東霖 第5次作業 12/6
// 111550008 Tony Tsai The Fifth Homework 12/6

require_once "./database.php";

const DEALER = 0;
const PLAYER = 1;

class CardInfo {
    public $cards = [];
    public $dealer = [];
    public $player = [];
    public $dealer_card_visible = false;
    public $min_card = 26;
    public $card_set = 1;

    public function __construct($data = null) {
        if ($data == null) {
            $this->shuffle();
            return; 
        }
        $this->cards = $data['cards'];
        $this->dealer = $data['dealer'];
        $this->player = $data['player'];
        $this->dealer_card_visible = $data['dealer_card_visible'];
        $this->card_set = $data['card_set'];
        $this->min_card = max($this->card_set * 26, 10); // If half of cards have used, shuffle.
    }

    public function toJSON() {
        return [
            'cards' => $this->cards,
            'dealer' => $this->dealer,
            'player' => $this->player,
            'dealer_card_visible' => $this->dealer_card_visible,
            'card_set' => $this->card_set
        ];
    }

    public function gameSet() {
        $this->dealer = [];
        $this->player = [];
        $this->dealer_card_visible = false;
        if (count($this->cards) < $this->min_card) {
            $this->shuffle();
        }
    }

    public function shuffle() {
        $this->cards = [];
        $card_count = $this->card_set * 52;

        // Init
        for ($i = 0; $i < $card_count; $i++) {
            $this->cards[] = $i % 52;
        }

        // Shuffle
        for ($i = 0; $i < $card_count; $i++) {
            $swap_index = rand($i, $card_count - 1);
            // Swap
            [$this->cards[$i], $this->cards[$swap_index]] = [$this->cards[$swap_index], $this->cards[$i]];
        }
    }

    public function deal($who) {
        if (count($this->cards) == 0) {
            throw new Exception('There is no card.');
        }

        $dealed_card = array_pop($this->cards);

        if ($who == DEALER) {
            $this->dealer[] = $dealed_card;
            if (count($this->dealer) > 5) {
                throw new Exception('A person is at most 5 cards');
            }
        } elseif ($who == PLAYER) {
            $this->player[] = $dealed_card;
            if (count($this->player) > 5) {
                throw new Exception('A person is at most 5 cards');
            }
        } else {
            throw new Exception('Choose You want to deal to DEALER or PLAYER.');
        }

        return $dealed_card;
    }

    public function countTotalPoints($who) {
        $points = 0;
        $ace = 0;
        $counting_cards = ($who == DEALER) ? $this->dealer : $this->player;

        foreach ($counting_cards as $card) {
            $point = Card::parseCard($card)['point'];
            if ($point >= 10) {
                $points += 10;
            } else {
                $points += $point;
            }

            if ($point == 1) {
                $ace++;
            }
        }

        while ($points <= 11 && $ace > 0) {
            $ace--;
            $points += 10;
        }

        return $points;
    }

    private function countArrayPoints($counting_cards) {
        $points = 0;
        $ace = 0;

        foreach ($counting_cards as $card) {
            $point = Card::parseCard($card)['point'];
            if ($point >= 10) {
                $points += 10;
            } else {
                $points += $point;
            }

            if ($point == 1) {
                $ace++;
            }
        }

        while ($points <= 11 && $ace > 0) {
            $ace--;
            $points += 10;
        }

        return $points;
    }

    private function findPoint($point) {
        $found = null;
        foreach ($this->cards as $key => $value) {
            if ($value % 13 === $point - 1) {
                $found = $key;
                break;
            }
            if ($point == 10 && in_array($value % 13, [10, 11, 12])) {
                $found = $key;
                break;
            }
        }

        return $found;
    }

    private function permuteTest($target, $now_list, $next_index) {
        if ($target == 22) {
            $target = 21;
        }

        for (;array_key_exists($next_index, $this->cards); $next_index++) {
            $now_list []= $this->cards[$next_index] % 13;
            $now_point = $this->countArrayPoints($now_list);
            if ($now_point < 17 && count($now_list) < 5) {
                $ret = $this->permuteTest($target, $now_list, $next_index + 1);
                if ($ret !== null) {
                    return [$next_index, ...$ret];
                }
            }
            else if ($now_point <= 21 && $now_point >= $target) {
                return [$next_index];
            }
            array_pop($now_list);
        }
        return null;
    }

    public function dealerCheat() {
        
        $player_point = $this->countTotalPoints(PLAYER);
        
        if($player_point > 21) {
            return;
        }
        $this->cards = [...$this->dealer, ...$this->cards];
        $this->dealer = [];

        $player_blackjack = count($this->player) == 2 && $player_point == 21 ? true : false;

        if ($player_blackjack) {
            $ind0 = $this->findPoint(10);
            $ind1 = $this->findPoint(1);
            if (!is_int($ind0) || !is_int($ind1)) {
                return;
            }
            if ($ind0 < $ind1) {
                [$ind0, $ind1] = [$ind1, $ind0];
            }

            $this->dealer = [$this->cards[$ind0], $this->cards[$ind1]];

            unset($this->cards[$ind0]);
            unset($this->cards[$ind1]);
            $this->cards = array_values($this->cards);
        }
        else {
            $indexes = $this->permuteTest(max($player_point + 1, 17), [], 0);

            if ($indexes === null) {
                echo "failed!";
                exit(0);
                return;
            }

            $tmp = [];

            foreach ($indexes as $index=>$key) {
                if (isset($this->cards[$key])) {
                    if ($index < 2) {
                        $this->dealer[] = $this->cards[$key];
                    } else {
                        $tmp[] = $this->cards[$key];
                    }
                    unset($this->cards[$key]);
                }
            }


            $this->cards = [...array_values($this->cards), ...array_reverse($tmp)];
        }
    }
}


class MoneyInfo {
    public $player = 1000;
    public $in_game = 0;

    public function __construct($data = null) {
        if (!$data) {
            return;
        }
        $this->player = $data['player'];
        $this->in_game = $data['in_game'];
    }

    public function toJSON() {
        $data = ['player' => $this->player, 'in_game' => $this->in_game];
        return $data;
    }

    public function signal($money, $force = false) {
        $int_money = (int)$money;
        if (!$force && ($int_money != $money || $this->player < $money || $money <= 0)) {
            return false;
        }

        $this->player -= $money;
        $this->in_game += $money * 2;
        return true;
    }

    public function gameSet($winner) {
        if ($winner == PLAYER) {
            $this->player += $this->in_game;
        } elseif ($winner != DEALER) {
            throw new RangeException('winner should be DEALER or PLAYER');
        }
        $this->in_game = 0;
    }
}


const BLACK_JOKER = 53;
const RED_JOKER = 54;
const CARD_BACK = 55;
const CARD_DIR = './images/cards/';

class Card {
    public static function parseCard($card_num) {
        $point = $card_num % 13;
        $suit = ($card_num - $point) / 13;
        $point++;
        $suit++;
        return ['point' => $point, 'suit' => $suit];
    }
    
    public static function findCardImgSrc($card_num) {
        if ($card_num == RED_JOKER) {
            return CARD_DIR . 'red_joker.svg';
        }
        if ($card_num == BLACK_JOKER) {
            return CARD_DIR . 'black_joker.svg';
        }
        if ($card_num == CARD_BACK) {
            return CARD_DIR . 'back.png';
        }
        $card = self::parseCard($card_num);
        return CARD_DIR . $card['point'] . '_' . $card['suit'] . '.svg';
    }

    public static function cardName($card_num) {
        $card = self::parseCard($card_num);
        $point_name = ['error', 'A', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K'];
        $suit_name = ['error', '梅花', '紅磚', '紅心', '黑桃'];
        return $suit_name[$card['suit']] . $point_name[$card['point']];
    }
}

const DEAL = 0;
const SIGNAL = 1;
const GAME_SET = 2;

class GameRecord {
    private $user_id = null;
    private $record = [];

    public function getUserId() {
        return $this->user_id;
    }

    public function setUserId($id) {
        if (is_int($id)) {
            $this->user_id = $id;
        }
    }

    // public function pushData($data) {
    //     Database::getInstance()->add('games', ['user_id' => $this->user_id] + $data);
    // }

    public function __construct($data = null) {
        if ($data) {
            $this->record = $data['record'];
            $this->user_id = $data['user_id'];
        }
    }

    public function toJSON() {
        $data = ['record' => $this->record, 'user_id' => $this->user_id];
        return $data;
    }

    public function push($action, $args) {
        switch ($action) {
            case DEAL:
                $this->checkArgs($args, ["who", "card"]);
                $this->record[] = $this->normalizeRecord([
                    "動作" => "發牌", 
                    "參數" => ($args['who'] == DEALER ? "莊家" : "閒家") . ": " . Card::cardName($args['card']),
                    "玩家籌碼" => $args['player_money'],
                    "場上籌碼" => $args['in_game_money'],
                    "dealer_cards" => $args['dealer'],
                    "player_cards" => $args['player']
                ]);
                break;
            case SIGNAL:
                $this->checkArgs($args, ["amount"]);
                $this->record[] = $this->normalizeRecord([
                    "動作" => "加注", 
                    "參數" => $args['amount'],
                    "玩家籌碼" => $args['player_money'],
                    "場上籌碼" => $args['in_game_money'],
                    "dealer_cards" => $args['dealer'],
                    "player_cards" => $args['player']
                ]);
                break;
            case GAME_SET:
                $this->checkArgs($args, ["winner"]);
                $this->record[] = $this->normalizeRecord([
                    "動作" => "遊戲結算", 
                    "參數" => ($args['winner'] == DEALER ? "莊家" : "閒家") . "勝",
                    "玩家籌碼" => $args['player_money'],
                    "場上籌碼" => $args['in_game_money'],
                    "dealer_cards" => $args['dealer'],
                    "player_cards" => $args['player']
                ]);
                // $this->record[] = $this->normalizeRecord(["動作" => "空行", "player_cards" => [], "dealer_cards" => []]);
                // $this->record[] = $this->normalizeRecord(["動作" => "空行", "player_cards" => [], "dealer_cards" => []]);
                $this->gameSet($args['dealer'], $args['player'], $args['player_money']);
                break;
        }
    }

    // public function toTable() {
    //     $database_record = Database::getInstance()->where('games', ['user_id' => $this->user_id])['record'];
    //     $table = Template::createTemplate(DATA_TABLE, [], []);
    //     $tbody = $table->getElementsByTagName('tbody')[0];
    //     foreach ($database_record as $row_data) {
    //         $row = Template::createTemplate(DATA_ROW, $row_data, []);
    //         $imported_row = $table->importNode($row->documentElement, true);
    //         $tbody->append($imported_row);
    //     }

    //     return $table;
    // }

    private function checkArgs($args, $needs) {
        $needs = array_merge($needs, ["player_money", "in_game_money", "dealer", "player"]);
        foreach ($needs as $need) {
            if (!array_key_exists($need, $args)) {
                error_log("Missing column: " . $need);
            }
        }
    }

    private function normalizeRecord($row_data) {
        return [
            "動作" => $row_data["動作"] ?? '未知動作', 
            "參數" => $row_data["參數"] ?? '',
            "玩家籌碼" => $row_data["玩家籌碼"] ?? '0',
            "場上籌碼" => $row_data["場上籌碼"] ?? '0',
            // "玩家手牌1" => isset($row_data['player_cards'][0]) ? Card::cardName($row_data['player_cards'][0]) : '',
            // "玩家手牌2" => isset($row_data['player_cards'][1]) ? Card::cardName($row_data['player_cards'][1]) : '',
            // "玩家手牌3" => isset($row_data['player_cards'][2]) ? Card::cardName($row_data['player_cards'][2]) : '',
            // "玩家手牌4" => isset($row_data['player_cards'][3]) ? Card::cardName($row_data['player_cards'][3]) : '',
            // "玩家手牌5" => isset($row_data['player_cards'][4]) ? Card::cardName($row_data['player_cards'][4]) : '',
            // "莊家手牌1" => isset($row_data['dealer_cards'][0]) ? Card::cardName($row_data['dealer_cards'][0]) : '',
            // "莊家手牌2" => isset($row_data['dealer_cards'][1]) ? Card::cardName($row_data['dealer_cards'][1]) : '',
            // "莊家手牌3" => isset($row_data['dealer_cards'][2]) ? Card::cardName($row_data['dealer_cards'][2]) : '',
            // "莊家手牌4" => isset($row_data['dealer_cards'][3]) ? Card::cardName($row_data['dealer_cards'][3]) : '',
            // "莊家手牌5" => isset($row_data['dealer_cards'][4]) ? Card::cardName($row_data['dealer_cards'][4]) : '',
        ];
    }

    public function gameSet($dealer, $player, $chip) {
        $old_record = Database::getInstance()->where('games', ['user_id' => $this->user_id])[0]['record'];
        $new_record = json_encode([...array_values(json_decode($old_record)), [...$this->record, 'dealer' => $dealer, 'player' => $player]], JSON_UNESCAPED_UNICODE);
        Database::getInstance()->update('games', ['user_id' => $this->user_id], ['record' => $new_record]);

        Database::getInstance()->update('users', ['id' => $this->user_id], ['chip' => $chip]);

        $this->record = [];
    }
}
?>