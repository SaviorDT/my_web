// 4001234567 蔡東霖 第4次作業 11/17
// 4001234567 Tony Tsai The Fourth Homework 11/17
class Option {
    text = '';
    callback = ()=>{};

    constructor(text, callback = ()=>{}) {
        this.text = text;
        this.callback = callback;
    }
}

class CardInfo {
    cards = [];
    dealer = [];
    player = [];
    dealer_card_visible = false;
    min_card = 26;
    card_set = 1;

    constructor(data) {
        if(!data) {
            return; 
        }
        this.cards = data.cards;
        this.dealer = data.dealer;
        this.player = data.player;
        this.dealer_card_visible = data.dealer_card_visible;
        this.card_set = data.card_set;
        this.min_card = Math.max(this.card_set*26, 10); // If half of cards have used, shuffle.
    }

    toJSON() {
        let data = {
            cards: this.cards,
            dealer: this.dealer,
            player: this.player,
            dealer_card_visible: this.dealer_card_visible,
            card_set: this.card_set
        }
        return data;
    }

    gameSet() {
        this.dealer = [];
        this.player = [];
        this.dealer_card_visible = false;
        if(this.cards.length < this.min_card) {
            this.shuffle();
        }
    }

    shuffle() {
        this.cards = [];
        let card_count = this.card_set * 52;

        // Init
        for(let i=0; i<card_count; i++) {
            this.cards.push(i % 52);
        }

        // Shuffle
        for(let i=0; i<card_count; i++) {
            let swap_index = Math.floor(Math.random() * (card_count - i)) + i;
            // Swap
            [this.cards[i], this.cards[swap_index]] = [this.cards[swap_index], this.cards[i]];
        }
    }

    deal(who) {
        if(this.cards.length == 0) {
            throw new Error('There is no card.');
        }

        let dealed_card = this.cards.pop();

        if(who == DEALER) {
            this.dealer.push(dealed_card);
            if(this.dealer.length > 5) {
                throw new Error('A person is at most 5 cards');
                
            }
        }
        else if(who == PLAYER) {
            this.player.push(dealed_card);
            if(this.player.length > 5) {
                throw new Error('A person is at most 5 cards');
            }
        }
        else {
            throw new Error('Choose You want to deal to DEALER or PLAYER.');
        }

        return dealed_card;
    }

    countTotalPoints(who) {
        let points = 0, ace = 0;
        let counting_cards = (who == DEALER) ? this.dealer : this.player;
        for(let card of counting_cards) {
            let point = Card.parseCard(card).point;
            if (point >= 10) {
                points += 10;
            }
            else {
                points += point;
            }

            if (point == 1) {
                ace++;
            }
        }

        while (points <= 11 && ace > 0) {
            ace--;
            points += 10;
        }

        return points;
    }
}

class MoneyInfo {
    player = 1000;
    in_game = 0;

    constructor(data) {
        if(!data) {
            return;
        }
        this.player = data.player;
        this.in_game = data.in_game;
    }

    toJSON() {
        let data = {player: this.player, in_game:this.in_game};
        return data;
    }

    signal(money, force) {
        let int_money = parseInt(money);
        if(!force && (int_money != money || this.player < money || money <= 0)) {
            return false;
        }

        this.player -= money;
        this.in_game += money * 2;
        return true;
    }

    gameSet(winner) {
        if(winner == PLAYER) {
            this.player += this.in_game;
        }
        else if(winner != DEALER) {
            throw new RangeError('winner should be DEALER or PLAYER');
        }
        this.in_game = 0;
    }
}

const BLACK_JOKER = 53;
const RED_JOKER = 54;
const CARD_BACK = 55;
const CARD_DIR = './images/cards/';

class Card {
    static parseCard(card_num) {
        let point = card_num % 13;
        let suit = (card_num - point) / 13;
        point++;
        suit++;
        return {point:point, suit:suit};
    }
    
    static findCardImgSrc(card_num) {
        if(card_num == RED_JOKER) {
            return CARD_DIR + 'red_joker.svg';
        }
        if (card_num == BLACK_JOKER) {
            return CARD_DIR + 'black_joker.svg';
        }
        if (card_num == CARD_BACK) {
            return CARD_DIR + 'back.png';
        }
        let {point, suit} = this.parseCard(card_num);
        return CARD_DIR + point + '_' + suit + '.svg';
    }

    static cardName(card_num) {
        let {point, suit} = this.parseCard(card_num);
        const point_name = ['error', 'A', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K'];
        const suit_name = ['error', '梅花', '紅磚', '紅心', '黑桃'];
        return suit_name[suit] + point_name[point];
    }
}

const DEAL = 0;
const SIGNAL = 1;
const GAME_SET = 2;
class GameRecord {
    record = [];
    constructor(data) {
        if(!data) {
            return;
        }
        this.record = data.record;
    }

    push(action, args) {
        switch (action) {
            case DEAL:
                this.checkArgs(args, ["who", "card"]);
                this.record.push(this.normalizeRecord({
                    "動作": "發牌", 
                    "參數": (args.who==DEALER ? "莊家": "閒家") + ": " + Card.cardName(args.card),
                    "玩家籌碼": args.player_money,
                    "場上籌碼": args.in_game_money,
                    dealer_cards: args.dealer,
                    player_cards: args.player}));
                break;
            case SIGNAL:
                this.checkArgs(args, ["amount"]);
                this.record.push(this.normalizeRecord({
                    "動作": "加注", 
                    "參數": args.amount,
                    "玩家籌碼": args.player_money,
                    "場上籌碼": args.in_game_money,
                    dealer_cards: args.dealer,
                    player_cards: args.player}));
                break;
            case GAME_SET:
                this.checkArgs(args, ["winner"]);
                this.record.push(this.normalizeRecord({
                    "動作": "遊戲結算", 
                    "參數": (args.winner==DEALER ? "莊家": "閒家") + "勝",
                    "玩家籌碼": args.player_money,
                    "場上籌碼": args.in_game_money,
                    dealer_cards: args.dealer,
                    player_cards: args.player}));
                this.record.push(this.normalizeRecord({"動作": "空行", player_cards: [], dealer_cards: []}));
                this.record.push(this.normalizeRecord({"動作": "空行", player_cards: [], dealer_cards: []}));
                break;
        }
    }

    toTable() {
        let table = Template.createTemplate(DATA_TABLE, {}, {});
        let tbody = table.getElementsByTagName('tbody')[0];
        for (let row_data of this.record) {
            let row = Template.createTemplate(DATA_ROW, row_data, {});
            tbody.append(row);
        }

        return table;
    }

    checkArgs(args, needs) {
        needs = needs.concat(["player_money", "in_game_money", "dealer", "player"]);
        for(let need of needs) {
            if(! need in Object.keys(args)) {
                console.error("Missing column: " + need);
            }
        }
    }

    normalizeRecord(row_data) {
        return {
            "動作": row_data["動作"] ?? '未知動作', 
            "參數": row_data["參數"] ?? '',
            "玩家籌碼": row_data["玩家籌碼"] ?? '0',
            "場上籌碼": row_data["場上籌碼"] ?? '0',
            "玩家手牌1": row_data.player_cards[0] ? Card.cardName(row_data.player_cards[0]) : '',
            "玩家手牌2": row_data.player_cards[1] ? Card.cardName(row_data.player_cards[1]) : '',
            "玩家手牌3": row_data.player_cards[2] ? Card.cardName(row_data.player_cards[2]) : '',
            "玩家手牌4": row_data.player_cards[3] ? Card.cardName(row_data.player_cards[3]) : '',
            "玩家手牌5": row_data.player_cards[4] ? Card.cardName(row_data.player_cards[4]) : '',
            "莊家手牌1": row_data.dealer_cards[0] ? Card.cardName(row_data.dealer_cards[0]) : '',
            "莊家手牌2": row_data.dealer_cards[1] ? Card.cardName(row_data.dealer_cards[1]) : '',
            "莊家手牌3": row_data.dealer_cards[2] ? Card.cardName(row_data.dealer_cards[2]) : '',
            "莊家手牌4": row_data.dealer_cards[3] ? Card.cardName(row_data.dealer_cards[3]) : '',
            "莊家手牌5": row_data.dealer_cards[4] ? Card.cardName(row_data.dealer_cards[4]) : '',
        }
    }
}