// 4001234567 蔡東霖 第4次作業 11/17
// 4001234567 Tony Tsai The Fourth Homework 11/17
class GUI {
    static setBackground() {
        alert("Not implemented.")
    }

    static Dialog = class {
        static async showSelectDialog(title, content, texts) {
            return await new Promise((resolve) => {
                let options = [];
                for(let text of texts) {
                    let option = new Option(text, resolve);
                    options.push(option);
                }
                let dialog = Template.createSelectDialog(title, content, options);
                et('body')[0].append(dialog);
            });
        }
        
        static async showInputDialog(title, content, texts) {
            return await new Promise((resolve) => {
                let options = [];
                for(let text of texts) {
                    let option = new Option(text, resolve);
                    options.push(option);
                }
                let dialog = Template.createInputDialog(title, content, options);
                et('body')[0].append(dialog);   
            });
        }
    }
    
    static GameTable = class {
        static table = null;

        static showGameTable() {
            if(!et('#game-table')) {
                this.table = Template.createGameTable();
                et('body')[0].append(this.table);
            }
        }

        static async dealCard(who, card) {
            this.showGameTable();

            let src = Card.findCardImgSrc(card);
            await new Promise((resolve) => {
                const card = document.createElement("img");
                card.src = src;
                card.classList.add("card");
                card.classList.add("moving-card");
                et('body')[0].appendChild(card);

                // 初始位置（從發牌點開始）
                const dealer = et("#dealer");
                const dealer_rect = dealer.getBoundingClientRect();
                card.style.left = dealer_rect.left + "px";
                card.style.top = dealer_rect.top + "px";

                // 目標位置（玩家牌組區域）
                const card_deck = (who == DEALER) ? et("#dealer-deck") : et("#player-deck");
                const target_rect = card_deck.getBoundingClientRect();

                // 使用 setTimeout 來觸發動畫
                setTimeout(() => {
                    card.style.left = target_rect.left + card_deck.children.length * 90 + "px";
                    card.style.top = target_rect.top + "px";
                    card.style.transform = "translateY(0)";
                }, 10);

                // 等動畫結束後，將卡片添加到玩家的牌組內
                card.addEventListener("transitionend", async () => {
                    card.style.position = "static";
                    card_deck.appendChild(card);
                    resolve();
                });
            });
        }

        static setCard(who, cards) {
            this.showGameTable();
            let card_deck = (who == DEALER) ? et("#dealer-deck") : et("#player-deck");
            card_deck.innerHTML = '';
            for(let card of cards) {
                const img = document.createElement("img");
                let src = Card.findCardImgSrc(card);
                img.src = src;
                img.classList.add("card");
                img.classList.add("moving-card");
                img.style.position = "static";
                card_deck.appendChild(img);
            }
        }

        static clearCard() {
            this.setCard(DEALER, []);
            this.setCard(PLAYER, []);
        }
    }
}
