// 4001234567 蔡東霖 第4次作業 11/17
// 4001234567 Tony Tsai The Fourth Homework 11/17
const SELECT_DIALOG = `
    <div class="dialog-container">
        <div class="dialog-background"></div>
        <div class="dialog">
            <div class="title">{title}</div>
            <div class="content">{content}</div>
            <div class="option-buttons"></div>
        </div>
    </div>
`;

const DIV_BUTTON = `
    <div class="button">{option}</div>
`;

const INPUT_DIALOG = `
    <div class="dialog-container">
        <div class="dialog-background"></div>
        <div class="dialog">
            <div class="title">{title}</div>
            <div class="content">{content}</div>
            <div class="input" contenteditable=true></div>
            <div class="option-buttons"></div>
        </div>
    </div>
`;

const GAME_TABLE = `
    <div id="game-table">
        <div id="dealer-deck" class="card-deck"></div>
        <div id="dealer">
            <img src="./images/cards/back.png" alt="Card Back" class="card">
        </div>
        <div id="player-deck" class="card-deck"></div>
    </div>
`;

const DATA_TABLE = `
    <table>
        <thead>
            <tr>
                <th>動作</th>
                <th>參數</th>
                <th>玩家籌碼</th>
                <th>場上籌碼</th>
                <th>玩家手牌1</th>
                <th>玩家手牌2</th>
                <th>玩家手牌3</th>
                <th>玩家手牌4</th>
                <th>玩家手牌5</th>
                <th>莊家手牌1</th>
                <th>莊家手牌2</th>
                <th>莊家手牌3</th>
                <th>莊家手牌4</th>
                <th>莊家手牌5</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
`;

const DATA_ROW = `
    <tr>
        <td>{動作}</td>
        <td>{參數}</td>
        <td>{玩家籌碼}</td>
        <td>{場上籌碼}</td>
        <td>{玩家手牌1}</td>
        <td>{玩家手牌2}</td>
        <td>{玩家手牌3}</td>
        <td>{玩家手牌4}</td>
        <td>{玩家手牌5}</td>
        <td>{莊家手牌1}</td>
        <td>{莊家手牌2}</td>
        <td>{莊家手牌3}</td>
        <td>{莊家手牌4}</td>
        <td>{莊家手牌5}</td>
    </tr>
`;

class Template {
    static createTemplate(template, filler, events) {
        const processedString = template.replace(/{([^}]+)}/g, (match, key) => {return filler[key] ?? ''});
        
        const element = document.createElement('template');
        element.innerHTML = processedString.trim();

        if(events) {
            for(let [key, value] of Object.entries(events)) {
                element.content.firstChild.addEventListener(key, value);
            }
        }

        return element.content.firstChild;
    }

    static createSelectDialog(title, content, options) {
        let dialog_filler = {title: title, content: content};
        let dialog = Template.createTemplate(SELECT_DIALOG, dialog_filler);

        let options_container = dialog.getElementsByClassName("option-buttons")[0];
        for(let option of options) {
            options_container.insertAdjacentElement(
                'beforeend',
                Template.createTemplate(DIV_BUTTON, {option: option.text}, {
                    click: () => {
                        option.callback(option.text);
                        dialog.remove();
                    }
                }));
        }

        return dialog;
    }

    static createInputDialog(title, content, options) {
        let dialog_filler = {title: title, content: content};
        let dialog = Template.createTemplate(INPUT_DIALOG, dialog_filler);
        let input = dialog.getElementsByClassName('input')[0];

        let options_container = dialog.getElementsByClassName("option-buttons")[0];
        for(let option of options) {

            options_container.insertAdjacentElement(
                'beforeend',
                Template.createTemplate(DIV_BUTTON, {option: option.text}, {
                    click: () => {
                        option.callback({option:option.text, input:input.innerText});
                        dialog.remove();
                    }
                }));
        }

        return dialog;
    }

    static createGameTable() {
        return Template.createTemplate(GAME_TABLE, {}, {});
    }
}