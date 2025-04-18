<?php
// 111550008 蔡東霖 第5次作業 12/6
// 111550008 Tony Tsai The Fifth Homework 12/6

const SELECT_DIALOG = "
    <form class=\"dialog-container\" method=\"post\" action =\"{callback_url}\">
        <div class=\"dialog-background\"></div>
        <div class=\"dialog\">
            <div class=\"title\">{title}</div>
            <div class=\"content\">{content}</div>
            <div class=\"option-buttons\" id=\"option-buttons\"></div>
        </div>
    </form>
";

const DIV_BUTTON = "
    <input type=\"submit\" class=\"button\" name=\"option\" value=\"{option}\" />
";

const INPUT_DIALOG = "
    <form class=\"dialog-container\" method=\"post\" action =\"{callback_url}\">
        <div class=\"dialog-background\"></div>
        <div class=\"dialog\">
            <div class=\"title\">{title}</div>
            <div class=\"content\">{content}</div>
            <input class=\"input\" contenteditable=true name=\"input\" />
            <div class=\"option-buttons\" id=\"option-buttons\"></div>
        </div>
    </form>
";

const DOUBLE_INPUT_DIALOG = "
    <form class=\"dialog-container\" method=\"post\" action =\"{callback_url}\">
        <div class=\"dialog-background\"></div>
        <div class=\"dialog\">
            <div class=\"title\">{title}</div>
            <div class=\"content\">{content}</div>
            <input class=\"input\" contenteditable=true name=\"input1\" />
            <input class=\"input\" contenteditable=true name=\"input2\" />
            <div class=\"option-buttons\" id=\"option-buttons\"></div>
        </div>
    </form>
";

const GAME_TABLE = "
    <div id=\"game-table\">
        <div id=\"dealer-deck\" class=\"card-deck\"></div>
        <div id=\"dealer\">
            <img src=\"./images/cards/back.png\" alt=\"Card Back\" class=\"card\">
        </div>
        <div id=\"player-deck\" class=\"card-deck\"></div>
    </div>
";

const DATA_TABLE = "
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
";

const DATA_ROW = "
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
";

const BACKGROUND = "
<!DOCTYPE html>
<html>
    <head>
        <meta charset=\"utf-8\">
        <title>
            HW5_111550008_蔡東霖      
        </title>
    </head>
    <body>
        <link rel=\"stylesheet\" href=\"./dialog.css\"/>
        <link rel=\"stylesheet\" href=\"./game_table.css\"/>
    </body>
</html>
";

class Template {
    public static function createTemplate($template, $filler) {
        $processedString = preg_replace_callback('/{([^}]+)}/', function($matches) use ($filler) {
            return $filler[$matches[1]] ?? '';
        }, $template);
        
        $element = new DOMDocument();
        $element->loadHTML('<?xml encoding="utf-8" ?>' . trim($processedString), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        return $element;
    }

    public static function createSelectDialog($title, $content, $callback_url, $options) {
        $dialog_filler = ['title' => $title, 'content' => $content, 'callback_url' => $callback_url];
        $dialog = self::createTemplate(SELECT_DIALOG, $dialog_filler, []);

        $options_container = $dialog->getElementById("option-buttons");
        foreach ($options as $option) {
            $option_button = self::createTemplate(DIV_BUTTON, ['option' => $option]);
            $imported_button = $dialog->importNode($option_button->documentElement, true);
            $options_container->appendChild($imported_button);
        }

        return $dialog;
    }

    public static function createInputDialog($title, $content, $callback_url, $options) {
        $dialog_filler = ['title' => $title, 'content' => $content, 'callback_url' => $callback_url];
        $dialog = self::createTemplate(INPUT_DIALOG, $dialog_filler, []);

        $options_container = $dialog->getElementById("option-buttons");
        foreach ($options as $option) {
            $option_button = self::createTemplate(DIV_BUTTON, ['option' => $option]);
            $imported_button = $dialog->importNode($option_button->documentElement, true);
            $options_container->appendChild($imported_button);
        }

        return $dialog;
    }

    public static function createDoubleInputDialog($title, $content, $callback_url, $options) {
        $dialog_filler = ['title' => $title, 'content' => $content, 'callback_url' => $callback_url];
        $dialog = self::createTemplate(DOUBLE_INPUT_DIALOG, $dialog_filler, []);

        $options_container = $dialog->getElementById("option-buttons");
        foreach ($options as $option) {
            $option_button = self::createTemplate(DIV_BUTTON, ['option' => $option]);
            $imported_button = $dialog->importNode($option_button->documentElement, true);
            $options_container->appendChild($imported_button);
        }

        return $dialog;
    }

    public static function createGameTable() {
        return self::createTemplate(GAME_TABLE, []);
    }

    public static function createBackground() {
        return self::createTemplate(BACKGROUND, []);
    }
}


?>