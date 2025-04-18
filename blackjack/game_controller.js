// 4001234567 蔡東霖 第4次作業 11/17
// 4001234567 Tony Tsai The Fourth Homework 11/17
const DEALER = 0;
const PLAYER = 1;

class BlackJackGame {
    static #instance = null;
    static #private = false;
    #card_info = null;
    #money_info = null;
    #game_record = null;
    #hard_blacklist = false;
    soft_blacklist = false;
    constructor() {
        if(BlackJackGame.#private) {
            this.#card_info = new CardInfo();
            this.#money_info = new MoneyInfo();
            this.#game_record = new GameRecord();
            
            BlackJackGame.#instance = this;
            BlackJackGame.#private = false;

            this.loadFromStorage();
            if(this.#card_info.dealer.length > 0) {
                this.resumeGame();
            }
        }
        else {
            throw new Error('Please Use getInstance() Instead of constructor.')
        }
    }

    static getInstance() {
        if(!BlackJackGame.#instance) {
            BlackJackGame.#private = true;
            new BlackJackGame();
        }
        return BlackJackGame.#instance;
    }

    isStart() {
        return this.#card_info.dealer.length > 0;
    }

    addCard(num) {
        if(Math.abs(num) != 1) {
            throw new RangeError("num should be +-1.")
        }

        if(this.#card_info.card_set < 1) {
            num /= 4;
        }
        else if(this.#card_info.card_set == 1 && num == -1) {
            num /= 4;
        }
        this.#card_info.card_set += num;

        if(this.#card_info.card_set <= 0.25) {
            this.#card_info.card_set = 0.25;
        }

        this.#card_info.shuffle();
        this.saveToStorage();
    }

    addMoney(money) {
        money = parseInt(money);
        if(isNaN(money)) {
            return;
        }
        this.#money_info.player += Number(money);
        this.saveToStorage();
    }

    getMoney() {
        return this.#money_info.player;
    }

    getCardSet() {
        return this.#card_info.card_set;
    }

    clearGameRecord() {
        this.#game_record.record = [];
        this.saveToStorage();
    }

    async start() {
        if(this.#hard_blacklist) {
            GUI.Dialog.showSelectDialog("原作者", "你已經被 ban 了，請好好反省一分鐘", ["對不起我知道錯了"]);
            await new Promise((resolve) => {
                setTimeout(() => {
                    resolve();
                }, 60000);
            });
            this.#hard_blacklist = false;
            this.saveToStorage();
        }

        this.#card_info.dealer_card_visible = false;
        if(this.#card_info.cards.length < this.#card_info.min_card) {
            this.#card_info.shuffle();
        }

        if(!await this.assignChip()) {
            Hall.showEntryPoint();
            return;
        }

        await this.dealDealer();
        await this.dealPlayer();
        await this.dealDealer();
        await this.dealPlayer();

        this.playerAction();
    }

    async assignChip() {
        let failed_times = 0;
        let failed_message = [
            "請輸入不大於你持有籌碼的正整數",
            "請輸入不大於你持有籌碼的正整數，不接受任何數字以外的輸入",
            "請仔細檢查你的輸入是否有誤",
            "請不要再亂輸入了",
            "請不要挑戰我的耐心",
            "如果你再亂輸入，我會請你離開",
            "媽的你聽不懂人話是不是?"
        ]
        while (failed_times < 8) {
            let {option, input} = await GUI.Dialog.showInputDialog("荷官何冠", `請輸入要賭多少?<br>
                您目前還有 ${this.#money_info.player} 籌碼 <br>
                目前賭桌上總共有 ${this.#money_info.in_game} 籌碼`,
                [
                    "確定",
                    "返回"
                ]);
    
            if(option == "確定") {
                if(!this.#money_info.signal(input)) {
                    if(failed_times == 7) {
                        break;
                    }
                    await GUI.Dialog.showSelectDialog("下注失敗", `您剛剛想要下注 ${input} 籌碼但是失敗了<br>
                        您目前還有 ${this.#money_info.player} 籌碼<br>`
                        + failed_message[failed_times++],
                        ["知道了"]);
                    continue;
                }
                this.#game_record.push(SIGNAL, {amount: input, player_money: this.#money_info.player, in_game_money: this.#money_info.in_game, dealer: this.#card_info.dealer, player: this.#card_info.player});
                this.saveToStorage();
                return true;
            }
            else {
                return false;
            }
        }

        await GUI.Dialog.showSelectDialog("你死了", "你看到幾個黑衣人從角落走出來，每人拿著一根大棒，經過了12個小時不間段的凌辱以後，你終於壞掉了。", []);
    }

    async playerAction() {
        if (this.#card_info.player.length >= 5 || this.#card_info.countTotalPoints(PLAYER) >= 21) {
            this.dealerAction();
            return;
        }
        let option = await GUI.Dialog.showSelectDialog("荷官何冠", `
            您目前還有 ${this.#money_info.player} 籌碼 <br>
            目前賭桌上總共有 ${this.#money_info.in_game} 籌碼<br>
            牌數還剩下 ${this.#card_info.cards.length} 張<br>
            請選擇要進行的動作`, 
            [
                "要牌",
                "停止",
                "加注"
            ]);

        switch(option) {
            case "要牌":
                await this.dealPlayer();
                this.playerAction();
                break;
            case "停止":
                this.dealerAction();
                break;
            case "加注":
                if(await this.assignChip()) {
                    await this.dealPlayer();
                }
                this.playerAction();
                break;
        }
    }

    async dealerAction() {
        await this.showDealCards();
        while(this.#card_info.countTotalPoints(DEALER) < 17 && this.#card_info.dealer.length < 5) {
            await this.dealDealer();
            await new Promise((resolve) => {setTimeout(()=>{resolve()}, 500)});
        }

        this.gameSet();
    }

    async showDealCards() {
        this.#card_info.dealer_card_visible = true;
        GUI.GameTable.setCard(DEALER, this.#card_info.dealer);
    }

    async dealDealer() {
        let card = this.#card_info.deal(DEALER);
        this.#game_record.push(DEAL, {who: DEALER, card: card, player_money: this.#money_info.player, in_game_money: this.#money_info.in_game, dealer: this.#card_info.dealer, player: this.#card_info.player});
        card = this.#card_info.dealer_card_visible ? card : CARD_BACK;
        this.saveToStorage();
        await GUI.GameTable.dealCard(DEALER, card);
    }

    async dealPlayer() {
        let card = this.#card_info.deal(PLAYER);
        this.#game_record.push(DEAL, {who: PLAYER, card: card, player_money: this.#money_info.player, in_game_money: this.#money_info.in_game, dealer: this.#card_info.dealer, player: this.#card_info.player});
        this.saveToStorage();
        await GUI.GameTable.dealCard(PLAYER, card);
    }

    async gameSet() {
        let player_points = this.#card_info.countTotalPoints(PLAYER);
        let dealer_points = this.#card_info.countTotalPoints(DEALER);
        let player_blackjack = (player_points == 21 && this.#card_info.player.length == 2) ? true : false;
        let dealer_blackjack = (dealer_points == 21 && this.#card_info.dealer.length == 2) ? true : false;
        let winner = -1;

        let message = "出現未知錯誤";
        if (player_blackjack && !dealer_blackjack) {
            winner = PLAYER;
            this.#money_info.signal(Math.floor(this.#money_info.in_game / 4));
            message = `挖屋，你是布雷克捷克耶，你可以獲得1.5倍的獎金，這是你的 ${this.#money_info.in_game} 籌碼
                <br>，你現在總共有 ${this.#money_info.player + this.#money_info.in_game} 籌碼
                <br>請選擇一個動作`;
        }
        else if (player_blackjack && dealer_blackjack) {
            winner = DEALER;
            message = `恭喜你遇到了極低概率事件，為了安慰你，莊家當著你的面清點了 ${this.#money_info.in_game} 籌碼，希望你會開心一點
                <br>，你現在總共有 ${this.#money_info.player} 籌碼
                <br>請選擇一個動作`;
        }
        else if (dealer_blackjack) {
            winner = DEALER;
            this.#money_info.signal(Math.ceil(this.#money_info.in_game / 4));
            message = `恭喜你遇到了 blackjack，為了慶祝，你主動包了 ${Math.ceil(this.#money_info.in_game / 6)} 籌碼的紅包給莊家
                <br>你現在總共有 ${this.#money_info.player} 籌碼
                <br>請選擇一個動作`;
        }
        else if(player_points > 21) {
            winner = DEALER;
            message = `你爆炸了，恭喜你喜提 -${this.#money_info.in_game / 2} 籌碼，
                <br>你現在總共有 ${this.#money_info.player} 籌碼
                <br>請選擇一個動作`;
        }
        else if (dealer_points > 21) {
            winner = PLAYER;
            message = `你煮了一道金光閃閃的料理讓莊家爆牌了，你得到了桌面上的 ${this.#money_info.in_game} 籌碼
                <br>你現在總共有 ${this.#money_info.player + this.#money_info.in_game} 籌碼
                <br>請選擇一個動作`;
        }
        else if(player_points > dealer_points) {
            winner = PLAYER;
            message = `恭喜你比莊家大，你把桌上根號 ${this.#money_info.in_game**2} 的籌碼通通收入囊中，
                <br>你現在總共有 ${this.#money_info.player + this.#money_info.in_game} 籌碼
                <br>請選擇一個動作`;
        }
        else if(dealer_points > player_points) {
            winner = DEALER;
            message = `恭喜你比莊家小，莊家把桌上根號 ${this.#money_info.in_game**2} 的籌碼通通收入囊中，
                <br>你現在總共有 ${this.#money_info.player} 籌碼
                <br>請選擇一個動作`;
        }
        else if(dealer_points == player_points) {
            winner = PLAYER;
            this.#money_info.signal(-this.#money_info.in_game / 2, true);
            message = `很不幸的你與莊家大戰300回合後雙方平手，各自拿回屬於自己的薯片
                <br>你現在總共有 ${this.#money_info.player} 籌碼
                <br>請選擇一個動作`;
        }
        else {
            winner = PLAYER;
            this.#money_info.signal(-this.#money_info.in_game / 2, true);
            message = `出現了意料之外的情境，本局作廢，請截圖傳給開發者維修
                <br>你現在總共有 ${this.#money_info.player} 籌碼
                <br>閒家莊家點數、閒家莊家blackjack: ${player_points}, ${dealer_points}, ${player_blackjack}, ${dealer_blackjack}
                <br>請選擇一個動作`;
        }

        this.#money_info.gameSet(winner);
        this.#card_info.gameSet();
        this.#game_record.push(GAME_SET, {winner: winner, player_money: this.#money_info.player, in_game_money: this.#money_info.in_game, dealer: this.#card_info.dealer, player: this.#card_info.player});
        this.saveToStorage();

        let option = await GUI.Dialog.showSelectDialog("賭局結束", message, [
            "重新一局",
            "返回首頁",
            "媽的破game"
        ])

        switch (option) {
            case "重新一局":
                this.start();
                break;
            case "返回首頁":
                Hall.showEntryPoint();
                break;
            case "媽的破game":
                GUI.Dialog.showSelectDialog("", "媽的不要玩啊。", []);
                this.#hard_blacklist = true;
                this.saveToStorage();
                break;
        }
        
        GUI.GameTable.clearCard();
    }

    getGameRecordTable() {
        return this.#game_record.toTable();
    }

    loadFromStorage() {
        let json_str = localStorage.getItem('game_data');
        
        if(!json_str) {
            return;
        }

        let data = JSON.parse(json_str);
        this.#hard_blacklist = data.blacklisted;
        this.#money_info = new MoneyInfo(data.money_info);
        this.#game_record = new GameRecord(data.game_record);
        this.#card_info = new CardInfo(data.card_info);
    }

    saveToStorage() {
        let json_str = JSON.stringify(this);
        localStorage.setItem('game_data', json_str);
        sessionStorage.setItem('game_data', json_str);
    }

    toJSON() {
        return {
            card_info: this.#card_info,
            money_info: this.#money_info,
            game_record: this.#game_record,
            blacklisted: this.#hard_blacklist
        }
    }

    async resumeGame() {
        GUI.GameTable.setCard(PLAYER, this.#card_info.player);
        if(this.#card_info.dealer_card_visible) {
            GUI.GameTable.setCard(DEALER, this.#card_info.dealer);
        }
        else {
            GUI.GameTable.setCard(DEALER, this.#card_info.dealer.map(() => {return CARD_BACK}));
        }

        while(this.#card_info.player.length < 2 && this.#card_info.player.length < this.#card_info.dealer.length) {
            await this.dealPlayer();
        }
        while(this.#card_info.dealer.length < 2) {
            await this.dealDealer();
            await this.dealPlayer();
        }

        this.playerAction();
    }
}

class Hall {
    static async newGame() {
        BlackJackGame.getInstance().start();
    }
    
    static async showEntryPoint() {
        if(BlackJackGame.getInstance().isStart()) {
            return;
        }

        let option = await GUI.Dialog.showSelectDialog("篤昶賭場", "歡迎來到篤昶賭場，請選擇你要進行的動作", 
            [
                "開始遊戲",
                "設定籌碼",
                "調整牌數",
                "歷史紀錄",
                "規則"
            ]);
    
        switch(option) {
            case "開始遊戲":
                Hall.newGame();
                break;
            case "設定籌碼":
                Hall.showSetChip(Hall.showEntryPoint);
                break;
            case "調整牌數":
                Hall.showAdjustCardSet(Hall.showEntryPoint);
                break;
            case "歷史紀錄":
                Hall.showHistory(Hall.showEntryPoint);
                break;
            case "規則":
                Hall.showRule(Hall.showEntryPoint, PLAY_RULE ,PLAY_RULE_CONTENT);
                break;
        }
    }

    static async showRule(back, rule, rule_content) {
        let option = await GUI.Dialog.showSelectDialog(rule, rule_content,
            [
                "基本玩法",
                "詳細規則",
                "玩家守則",
                "返回"
            ]);
        switch (option) {
            case "基本玩法":
                Hall.showRule(back, PLAY_RULE, PLAY_RULE_CONTENT);
                break;
            case "詳細規則":
                Hall.showRule(back, DETAIL_RULE, DETAIL_RULE_CONTENT);
                break;
            case "玩家守則":
                Hall.showRule(back, BAN_RULE, BAN_RULE_CONTENT);
                break;
            case "返回":
                back();
                break;
        }
    }

    static async showHistory(back) {
        let option = await GUI.Dialog.showSelectDialog("監視器", BlackJackGame.getInstance().getGameRecordTable().outerHTML, 
            [
                "清空",
                "返回"
            ]);

        if(option == "清空") {
            BlackJackGame.getInstance().clearGameRecord();
            this.showHistory(back);
        }
        else {
            back();
        }
    }
    
    static async showSetChip(back) {
        let option = await GUI.Dialog.showSelectDialog("籌碼兌換處", `你目前擁有 ${BlackJackGame.getInstance().getMoney()} 籌碼\n請選擇你要進行的動作`, 
            [
                "-100",
                "+100",
                "自訂數量",
                "返回"
            ]);
    
        switch(option) {
            case "-100":
                BlackJackGame.getInstance().addMoney(-100);
                Hall.showSetChip(back);
                break;
            case "+100":
                BlackJackGame.getInstance().addMoney(100);
                Hall.showSetChip(back);
                break;
            case "自訂數量":
                Hall.showCustomChip(() => {Hall.showSetChip(back)});
                break;
            case "返回":
                back();
                break;
        }
    }

    static async showAdjustCardSet(back) {
        let option = await GUI.Dialog.showSelectDialog("荷官何冠", `目前使用 ${BlackJackGame.getInstance().getCardSet()} 副牌進行遊玩\n請選擇你要進行的動作`, 
            [
                "-",
                "+",
                "返回"
            ]);

        switch (option) {
            case "-":
                BlackJackGame.getInstance().addCard(-1);
                Hall.showAdjustCardSet(back);
                break;
            case "+":
                BlackJackGame.getInstance().addCard(1);
                Hall.showAdjustCardSet(back);
                break;
            case "返回":
                back();
                break;
        }
    }
    
    static async showCustomChip(back) {
        let {option, input} = await GUI.Dialog.showInputDialog("籌碼兌換處", `你目前擁有 ${BlackJackGame.getInstance().getMoney()} 籌碼\n請選擇你要增加的數量`,
            [
                "確定",
                "返回"
            ]);
    
        switch(option) {
            case "確定":
                BlackJackGame.getInstance().addMoney(input);
                Hall.showCustomChip(back);
                break;
            case "返回":
                back();
                break;
        }
    }
}

const PLAY_RULE = "基本玩法";

const PLAY_RULE_CONTENT = `
    點擊設定籌碼可以調整你擁有的籌碼，如果不想要以100為單位，可以選擇自訂數量，並輸入整數 <br>
    <br>
    點擊調整牌數可以調整你希望賭場用幾副牌進行遊戲，最低為 1/4 副，最高不設限 <br>
    <br>
    點擊歷史紀錄可以查看加注、發牌、結算紀錄，每局遊戲以兩行空行隔開 <br>
    <br>
    點擊開始遊戲可以玩遊戲:<br>
    <br>
    首先需要繳納入場費，入場費與加注一樣，必須輸入正整數 <br>
    <br>
    繳納完入場費後，荷官會開始發牌，莊家與玩家各兩張，玩家回合結束前無法得知莊家的牌 <br>
    <br>
    荷官發完牌後，如果玩家不到五張牌且不到21點，可以選擇是否加注，如果有加注必定會再抽一張牌 <br>
    <br>
    荷官發完牌後，如果玩家不到五張牌且不到21點，可以選擇是否要牌，如果要牌會再得到一張牌 <br>
    <br>
    當玩家選擇停止，或是玩家超過五張牌，或是玩家達到21點以上，會輪到莊家的回合 <br>
    <br>
    莊家會一直要牌，直到莊家達到17點以上 <br>
    <br>
    莊家停止後，會開始根據規則比大小，結算雙方得失，並詢問玩家要玩下一場還是返回首頁。
`;

const DETAIL_RULE = "詳細規則";

const DETAIL_RULE_CONTENT = `
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
`;

const BAN_RULE = "玩家守則";

const BAN_RULE_CONTENT = `
    下注時請勿輸入不合理的金額，如果錯誤多次，將會被踢出賭場，必須刷新瀏覽器才能進入 <br>
    <br>
    如果侮辱作者，將會受到 1 分鐘禁止進入遊戲的懲罰，從下次開始遊戲時開始計時
`;