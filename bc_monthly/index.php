<head>
    <title>貓咪大戰爭-月間任務搜尋器 BY蜜糖情人</title>
</head>
<?php


// session_start();
// $file=fopen("p5.txt","r");
// $num=fgets($file);
// fclose($file);
// if($_SESSION['come']!='v'){
//  $num++;
//  $file=fopen("p5.txt","w");
//  fwrite($file,$num);
//  fclose($file);
//  $_SESSION['come']='v';
// }

//toutzn
    $mysqli = new mysqli('mariadb', 'bc_monthly', 'bc_monthly', 'bc_monthly');
    if ($mysqli->connect_errno) {
        echo "連結資料庫失敗0";
        exit;
    }
	mysqli_query($mysqli , "set names utf8");

		 $enc1=$_POST['enc1'];
		 $enc2=$_POST['enc2'];
		 $enc3=$_POST['enc3'];
		 $schedulec=$_POST['schedulec'];
		 $sql = "SELECT * FROM world WHERE schedule like '$schedulec' and(( enemy like '%$enc1%' or enemyJP like '%$enc1%' or  kide like '%$enc1%')  and (enemy like '%$enc2%' or enemyJP like '%$enc2%' or kide like '%$enc2%' ) and (enemy like '%$enc3%' or enemyJP like '%$enc3%' or kide like '%$enc3%'))"; 
		 $sq2 = "SELECT * FROM enemybook WHERE Japanese like '%$enc1%' or Chinese like '%$enc1%' or kind like '%$enc1%'"; 
		 $sq3 = "SELECT * FROM enemybook WHERE Japanese like '%$enc2%' or Chinese like '%$enc2%' or kind like '%$enc2%'"; 
		 $sq4 = "SELECT * FROM enemybook WHERE Japanese like '%$enc3%' or Chinese like '%$enc3%' or kind like '%$enc3%'"; 
		 $sq5 = "SELECT * FROM world WHERE schedule like '$schedulec' and(( enemy like '%$enc1%' or enemyJP like '%$enc1%' or  kide like '%$enc1%')  and (enemy like '%$enc2%' or enemyJP like '%$enc2%' or kide like '%$enc2%' ))";
		 $sq6 = "SELECT * FROM world WHERE schedule like '$schedulec' and(( enemy like '%$enc1%' or enemyJP like '%$enc1%' or  kide like '%$enc1%')  and (enemy like '%$enc3%' or enemyJP like '%$enc3%' or kide like '%$enc3%'))";
		 $sq7 = "SELECT * FROM world WHERE schedule like '$schedulec' and(( enemy like '%$enc2%' or enemyJP like '%$enc2%' or kide like '%$enc2%' ) and (enemy like '%$enc3%' or enemyJP like '%$enc3%' or kide like '%$enc3%'))";
		 $sq8 = "SELECT * FROM world WHERE schedule like '$schedulec' and(( enemy like '%$enc1%' or enemyJP like '%$enc1%' or kide like '%$enc1%'))"; 
		 $sq9 = "SELECT * FROM world WHERE schedule like '$schedulec' and(( enemy like '%$enc2%' or enemyJP like '%$enc2%' or kide like '%$enc2%'))"; 
		 $sqA = "SELECT * FROM world WHERE schedule like '$schedulec' and((enemy like '%$enc3%' or enemyJP like '%$enc3%' or kide like '%$enc3%'))"; 


$res = "select count from counter"; 
$retvalB = mysqli_query( $mysqli, $res ); 
$num = mysqli_fetch_assoc($retvalB); 
$counter = $num['count']; 

// if($_SESSION['counted'] != 'yes') 
// {
//  $counter++;
//  $_SESSION['counted'] = 'yes';
// }

$num2 = $counter; 
$upsql = "UPDATE counter SET count='$num2'";
  if (mysqli_query($mysqli, $upsql)) {
      echo "歡迎第 $num2 位貓友";
  } else {
      echo "Error updating record: " . mysqli_error($mysqli);
  }

?>

<style type="text/css">
body {
	background-color: #AFF5E0;
}
</style>


<form name="form1" method="post" action="">
  <a href="http://www.toutzn.byethost24.com/index-old.php">回到舊站</a><br>
  <p>
    <input name="schedulec" type="radio" id="schedule_0" value="%" checked="checked" >
    不拘
  <input name="schedulec" type="radio" id="schedule_0" value="傳說">傳說</p>
      <p>
      <input type="radio" name="schedulec" value="世界一" id="schedule_1">世界一
  	  <input type="radio" name="schedulec" value="世界二" id="schedule_2">世界二
  	  <input type="radio" name="schedulec" value="世界三" id="schedule_3">世界三</label>
     </p><p>
      <input type="radio" name="schedulec" value="未來一" id="schedule_1">未來一
  	  <input type="radio" name="schedulec" value="未來二" id="schedule_2">未來二
  	  <input type="radio" name="schedulec" value="未來三" id="schedule_3">未來三</label>
     </p><p>
      <input type="radio" name="schedulec" value="宇宙一" id="schedule_1">宇宙一
  	  <input type="radio" name="schedulec" value="宇宙二" id="schedule_2">宇宙二
  	  <input type="radio" name="schedulec" value="宇宙三" id="schedule_3">宇宙三</label>
  </p>
  	<label>敵人A：</label>
<input list="area" name="enc1" value="<?php echo $enc1 ?>" type="text" />
<datalist id="area">
<option value="白狗">狗仔(わんこ)-白</option>
<option value="白蛇">扭扭蛇(にょろ)-白</option>
<option value="白三小人">團結筷子幫(例のヤツ)-白</option>
<option value="白河馬">河馬將(カバちゃん)-白</option>
<option value="紅豬">豬野郎(ブタヤロウ)-紅</option>
<option value="白企鵝">企鵝大哥(ジャッキー・ペン)-白</option>
<option value="白猩猩">大猩猩(ゴリさん)-白</option>
<option value="白羊">咩咩(メェメェ)-白</option>
<option value="紅海豹">斑海豹桑(ゴマさま)-紅</option>
<option value="白大象">澎湃(パオン)-白</option>
<option value="白袋鼠">袋拳(カ・ンガリュ)-白</option>
<option value="紅犀牛">一角君(一角くん)-紅</option>
<option value="白熊">熊熊老師(クマ先生)-白</option>
<option value="白鱷魚">鱷魚宅宅(ワニック)-白</option>
<option value="紅兔">臭老兔(ウサ銀)-紅</option>
<option value="白松鼠">松鼠阿嘎(リッスントゥミー)-白</option>
<option value="黑熊">黑熊(ブラックマ)-黑</option>
<option value="紅臉君">赤面歌王(赤羅我王)-紅 / 漂浮</option>
<option value="白臉君">臉君(カオル君)-漂浮</option>
<option value="15分鐘舉牌">看板小妹(カンバン娘)-白</option>
<option value="白蛾">蛾蛾蛾蛾(ガガガガ)-漂浮</option>
<option value="紅帝王">惡之帝王貓彈(悪の帝王ニャンダム)-紅</option>
<option value="白噴噴">噴噴老師(ぶんぶん先生)-漂浮</option>
<option value="白食蟻獸">師匠(師匠)-白</option>
<option value="白鴕鳥">鴕鳥愛美惠(ダチョウ同好会)-白</option>
<option value="白海獺">破壞一族(フルぼっこ)-白</option>
<option value="白無尾熊">無尾熊球(コアラッキョ)-白</option>
<option value="白駱駝">駝背(こぶへい)-白</option>
<option value="白鴨子">鴨子倫倫(アヒルンルン)-白</option>
<option value="白貓頭鷹">眉毛鳥(まゆげどり)-漂浮</option>
<option value="紅山豬">獅豬(イノシャシ)-紅</option>
<option value="白樹懶">樹懶賴(ナマルケモルル)-白</option>
<option value="白馴鹿">粥董(ナカイくん)-白</option>
<option value="白臘腸狗">拉腸狗(だっふんど)-白</option>
<option value="白貴賓狗">貴賓(セレブ)-白</option>
<option value="白狼">鬣狼(ハイ・エナジー)-白</option>
<option value="杜賓狗">瀟灑哥(ダディ)-白</option>
<option value="狼妹">狼與少女(ウルフとウルルン)-白</option>
<option value="黑狗">殺意狗(殺意のわんこ)-黑</option>
<option value="鋼鐵河馬">鋼鐵河馬將(メタルカバちゃん)-鋼鐵</option>
<option value="紅皇冠豬">伊麗莎白2世(エリザベス2世)-紅</option>
<option value="紅噴噴">赤井噴太郎(赤井ブン太郎)-紅 / 漂浮</option>
<option value="黑噴噴">黑色噴噴(ブラックブンブン)-黑 / 漂浮</option>
<option value="黑猩猩">黑猩猩(ブラッゴリ)-黑</option>
<option value="黑袋鼠">影子拳擊手(シャドウボクサー)-黑</option>
<option value="黑帝王">黑澤導演(クロサワ監督)-黑</option>
<option value="紫電河馬">超級鋼鐵河馬將(超メタルカバちゃん)-鋼鐵</option>
<option value="鋼鐵犀牛">鋼鐵犀一角(メタルサイボーグ)-鋼鐵</option>
<option value="鋼鐵熊">鋼鐵熊(メタックマ)-鋼鐵</option>
<option value="鋼鐵臉君">鋼鐵臉君(メタルカオル君)-鋼鐵</option>
<option value="鋼鐵海豹">鋼鐵斑海豹桑(メタルゴマさま)-鋼鐵</option>
<option value="紅渦">赤色旋風(レッドサイクロン)-紅 / 漂浮</option>
<option value="白渦">白色旋風(ホワイトサイクロン)-漂浮</option>
<option value="黑渦">黑色旋風(ブラックサイクロン)-黑 / 漂浮</option>
<option value="鐵渦">鋼鐵旋風(メタルサイクロン)-鋼鐵</option>
<option value="天渦">天使旋風(エンジェルサイクロン)-天使</option>
<option value="天使河馬">河馬將天使(天使カバちゃん)-天使</option>
<option value="天使狗">加百列天使(天使ガブリエル)-天使</option>
<option value="天使猩猩">落猩天使(天使ゴンザレス)-天使</option>
<option value="天使舉牌員">天使愛好家(天使愛好家)-鋼鐵</option>
<option value="白牆狗">壁犬(カベわんこ)-白</option>
<option value="紅蛇">紅髮扭扭蛇(赤毛のにょろ)-紅</option>
<option value="天使翻車魚">生化魚鈴木(マンボーグ鈴木)-天使</option>
<option value="白鴿">背負.和平.鴿(ド鳩・サブ・レー)-漂浮</option>
<option value="黑海獺">黑色破壞一族(ブラッコ)-黑</option>
<option value="天使馬">天使斯雷普尼爾(天使スレイプニール)-天使</option>
<option value="推人單小人">浪子漢(はぐれたヤツ)-白</option>
<option value="鋼鐵狗">鋼鐵狗仔(メタルわんこ)-鋼鐵</option>
<option value="小紅帝王">小戰士貓彈(コニャンダム)-紅</option>
<option value="黑大象">松黑藏(松 黒蔵)-黑</option>
<option value="天使雞">天使小雞(天使ヒオコエル)-天使</option>
<option value="異星狗">汪仔星人(エイリワン)-異星</option>
<option value="異星鱷魚">鱷星宅宅(ワーニック)-異星</option>
<option value="異星河馬">河馬星人(カーバチャン)-異星</option>
<option value="異星海豹">斑海星人(ゴマサーマン)-異星</option>
<option value="異星蛾">蛾星女神(レディ・ガ)-漂浮 / 異星</option>
<option value="異星眼鏡猴">眼猴星人(デカメガネル)-異星</option>
<option value="異星寄居蟹">寄居星人(ヤドカリー)-異星</option>
<option value="異星臉君">臉星人(カヲルさん)-漂浮 / 異星</option>
<option value="異星熊">熊星人(クマンチュー)-異星</option>
<option value="異星海蝶">破壞生物異星海蝶(破壊生物クオリネン)-漂浮 / 異星</option>
<option value="異星山豬">豬大哥(イノヴァルカン)-異星</option>
<option value="異星皇冠豬">伊麗莎白56世(エリザベス56世)-異星</option>
<option value="異星渦">異星旋風(スペースサイクロン)-漂浮 / 異星</option>
<option value="異星帝王">銀河貓彈(ギャラクシーニャンダム)-異星</option>
<option value="異星瓦爾騎">反叛的瓦爾基麗貓(反逆のヴァルキリー)-漂浮 / 異星</option>
<option value="異星袋鼠">異星袋拳(ハサミーマン)-異星</option>
<option value="異星犀牛">CyberX(サイバーX)-異星</option>
<option value="異星貴賓狗">異星貴賓(プドール夫人)-異星</option>
<option value="紅食蟻獸">豪豬教授(教授)-紅</option>
<option value="紅水豚">披著水豚皮的水豚(カルピンチョ)-紅</option>
<option value="紅無尾熊">戰鬥無尾熊球(バトルコアラッキョ)-紅</option>
<option value="燕子">燕梓南(ツバメンズ)-漂浮</option>
<option value="異星魷魚">小魷(よっちゃん)-漂浮 / 異星</option>
<option value="異星駱駝">羊駝背(アルパッカ)-異星</option>
<option value="異星樹懶">善哉(ヨキカナ)-異星</option>
<option value="異星噴噴">寄生噴噴(パラサイトブンブン)-漂浮 / 異星</option>
<option value="異星姆特">爆走姆特貓(暴走のネコムート)-漂浮 / 異星</option>
<option value="天使海蝶">斷罪天使海蝶(断罪天使クオリネル)-天使</option>
<option value="白土撥鼠">鼴小龍(ドリュウ)-白</option>
<option value="風暴渦">颶風旋風(ハリケーンサイクロン)-漂浮</option>
<option value="天使小人">天使先生(ミスターエンジェル)-天使</option>
<option value="黑大嘴鳥">大嘴武哥(キョセーヌ)-紅 / 黑</option>
<option value="黑海馬">必殺達也(タッちゃん)-黑</option>
<option value="小紅帽">狗仔紅帽咪娜(わんこずきんミーニャ)-白</option>
<option value="天使山豬">天使野豬(イノエンジェル)-天使</option>
<option value="白燈籠魚">小光(ヒカル)-白</option>
<option value="紅般若">般若我王(般若我王)-紅</option>
<option value="異星蜜蜂">森蜜子(森の蜜子)-漂浮 / 異星</option>
<option value="小白噴噴">小噴噴(ちびぶんぶん)-漂浮</option>
<option value="黑山豬">黑暗野豬(イノワール)-黑</option>
<option value="不死狗">不死狗仔(ゾンビワン)-不死</option>
<option value="不死蛇">不死扭扭蛇(にょろろん)-不死</option>
<option value="不死河馬">不死河馬將(カバタリアン)-不死</option>
<option value="不死豬">不死豬野郎(トンシー)-不死</option>
<option value="不死企鵝">不死企鵝大哥Z(ジャッキー・ペンZ)-不死</option>
<option value="不死猩猩">不死大猩猩(ゴリ・ンジュ)-不死</option>
<option value="不死海豹">不死斑海豹桑(ブチゴマさま)-不死</option>
<option value="不死袋鼠">不死袋拳(スカルボクサー)-不死</option>
<option value="不死鱷魚">不死鱷魚(ワニボン)-不死</option>
<option value="不死墓碑男">墓手太郎(墓手太郎)-不死</option>
<option value="不死渦">不死旋風(アンデッドサイクロン)-不死</option>
<option value="不死墓碑女">墓手花子(墓手花子)-不死</option>
<option value="不死大鯢">不死大鯢(オオさん)-不死</option>
<option value="不死牆狗">棺材狗(ヒツギイヌ)-不死</option>
<option value="不死駱駝">凹凸駝背(キャベロン)-不死</option>
<option value="不死宮木">犬武者武藏(犬武者 武蔵)-不死</option>
<option value="肥普">亡者肥普(亡者デブウ)-不死</option>
<option value="白海豚">海豚姑娘(イルカ娘)-白</option>
<option value="天使魷魚">炸烏賊天使(イカ天)-天使</option>
<option value="貓仙人">貓咪仙人(ネコ仙人)-白</option>
<option value="白青蛙">蛙助(ケロ助)-白</option>
<option value="宇宙狗">精英汪仔星人(エリートエイリワン)-異星戰士</option>
<option value="宇宙企鵝">宙星.企鵝大哥(スター・ペン)-異星戰士</option>
<option value="宇宙猩猩">宙猩將軍(グレゴリー将軍)-異星戰士</option>
<option value="宇宙大象">澎湃媽(ハハパオン)-異星戰士</option>
<option value="宇宙食蟻獸">巨匠(巨匠)-異星戰士</option>
<option value="宇宙土撥鼠">鼴鼠隊長(キャプテン・モグー)-異星戰士</option>
<option value="宇宙翻車魚">宙星生化魚(スペースマンボルグ)-漂浮 / 異星戰士</option>
<option value="貓神1">神(神さま)-漂浮</option>
<option value="天使大嘴鳥">大夢君(大夢くん)-天使 / 異星戰士</option>
<option value="宇宙渦">超級異星旋風(スーパースペースサイクロン)-異星戰士</option>
<option value="天使豬">飛天小豬(天使ブッタ)-天使</option>
<option value="吉娃娃">吉娃娃伯爵(謎仮面のウララー)-白</option>
<option value="烏拉拉">神秘假面烏拉拉(猫塚かりん)-白</option>
<option value="紅海馬">火馬(ヒウマ)-紅</option>
<option value="宇宙羊">激動咩咩(ウルトラメェメェ)-異星戰士</option>
<option value="機器噴噴">傳奇噴噴Ω(レジェンドブンブンΩ)-漂浮</option>
<option value="古代噴噴">真傳奇噴噴(真レジェンドブンブン)-古代種</option>
<option value="古代狗">古代狗仔(古代わんこ)-古代種</option>
<option value="古代松鼠">古代松鼠(イングリッス)-古代種</option>
<option value="古代犀牛">古代犀聖(コライノくん)-古代種</option>
<option value="古代蛾">古代蠶舌蛾(超町長)-漂浮 / 古代</option>
<option value="宇宙海豚">快豚星人(マナブくん)-異星戰士</option>
<option value="宇宙青蛙">食蛙星人(ゲコック)-異星戰士</option>
<option value="神2">神(認真)(神さま（本気）)-白</option>
<option value="古代眼鏡猴">古代眼鏡猴人(ダテメガネル)-古代種</option>
<option value="古代臉君">古頭王(古我王)-漂浮 / 古代</option>
<option value="古代鴕鳥">鴕鳥老黛麗(オールドリー)-古代種</option>
<option value="不死熊">科熊怪人(クマンケン)-不死</option>
<option value="不死山豬">玉米鬚豬(シノシシ)-不死</option>
<option value="黑白貘">愛作夢的貘(ユメミちゃん)-黑</option>
<option value="小宇宙渦">邪星小旋風(ミニスターサイクロン)-異星戰士</option>
<option value="宇宙鰻魚">花園鰻5兄弟(チンアナ5兄弟)-異星戰士</option>
<option value="宇宙大鯢">六角龍邪(ルーパールーパー)-異星戰士</option>
<option value="貓神3">終極千兆宏偉神(ファイナルギガグランド神さま)-白</option>
<option value="土偶">土偶喵丸(土偶戦士ドグ丸)-古代種</option>
<option value="不死雞">不死小雞(チキランラン)-不死</option>
<option value="不死三小人">驚悚幫(スリラーズ)-不死</option>
</datalist>
          <?php
$retval2 = mysqli_query( $mysqli, $sq2 );
if($enc1==NULL){}else{		
	while($row2 = mysqli_fetch_assoc($retval2))
		{
        echo '<table border="1"><tr><td>序號</td><td>日文名稱</td><td>中文名稱</td><td>種類</td><td>屬性</td></tr>';
   		 echo "<tr><td> {$row2['NO']}</td> ".
   		      "<td>{$row2['Japanese']}</td> ".
   		      "<td>{$row2['Chinese']}</td> ".
   		      "<td>{$row2['kind']}</td> ".
   		      "<td>{$row2['Attributes']}</td> ".
   		      "</tr>";
		}echo '</table>';}
?>
  </p>


  <label>敵人B：</label>
<input list="area" name="enc2" value="<?php echo $enc2 ?>" type="text" />
<datalist id="area">
<option value="白狗">狗仔(わんこ)-白</option>
<option value="白蛇">扭扭蛇(にょろ)-白</option>
<option value="白三小人">團結筷子幫(例のヤツ)-白</option>
<option value="白河馬">河馬將(カバちゃん)-白</option>
<option value="紅豬">豬野郎(ブタヤロウ)-紅</option>
<option value="白企鵝">企鵝大哥(ジャッキー・ペン)-白</option>
<option value="白猩猩">大猩猩(ゴリさん)-白</option>
<option value="白羊">咩咩(メェメェ)-白</option>
<option value="紅海豹">斑海豹桑(ゴマさま)-紅</option>
<option value="白大象">澎湃(パオン)-白</option>
<option value="白袋鼠">袋拳(カ・ンガリュ)-白</option>
<option value="紅犀牛">一角君(一角くん)-紅</option>
<option value="白熊">熊熊老師(クマ先生)-白</option>
<option value="白鱷魚">鱷魚宅宅(ワニック)-白</option>
<option value="紅兔">臭老兔(ウサ銀)-紅</option>
<option value="白松鼠">松鼠阿嘎(リッスントゥミー)-白</option>
<option value="黑熊">黑熊(ブラックマ)-黑</option>
<option value="紅臉君">赤面歌王(赤羅我王)-紅 / 漂浮</option>
<option value="白臉君">臉君(カオル君)-漂浮</option>
<option value="15分鐘舉牌">看板小妹(カンバン娘)-白</option>
<option value="白蛾">蛾蛾蛾蛾(ガガガガ)-漂浮</option>
<option value="紅帝王">惡之帝王貓彈(悪の帝王ニャンダム)-紅</option>
<option value="白噴噴">噴噴老師(ぶんぶん先生)-漂浮</option>
<option value="白食蟻獸">師匠(師匠)-白</option>
<option value="白鴕鳥">鴕鳥愛美惠(ダチョウ同好会)-白</option>
<option value="白海獺">破壞一族(フルぼっこ)-白</option>
<option value="白無尾熊">無尾熊球(コアラッキョ)-白</option>
<option value="白駱駝">駝背(こぶへい)-白</option>
<option value="白鴨子">鴨子倫倫(アヒルンルン)-白</option>
<option value="白貓頭鷹">眉毛鳥(まゆげどり)-漂浮</option>
<option value="紅山豬">獅豬(イノシャシ)-紅</option>
<option value="白樹懶">樹懶賴(ナマルケモルル)-白</option>
<option value="白馴鹿">粥董(ナカイくん)-白</option>
<option value="白臘腸狗">拉腸狗(だっふんど)-白</option>
<option value="白貴賓狗">貴賓(セレブ)-白</option>
<option value="白狼">鬣狼(ハイ・エナジー)-白</option>
<option value="杜賓狗">瀟灑哥(ダディ)-白</option>
<option value="狼妹">狼與少女(ウルフとウルルン)-白</option>
<option value="黑狗">殺意狗(殺意のわんこ)-黑</option>
<option value="鋼鐵河馬">鋼鐵河馬將(メタルカバちゃん)-鋼鐵</option>
<option value="紅皇冠豬">伊麗莎白2世(エリザベス2世)-紅</option>
<option value="紅噴噴">赤井噴太郎(赤井ブン太郎)-紅 / 漂浮</option>
<option value="黑噴噴">黑色噴噴(ブラックブンブン)-黑 / 漂浮</option>
<option value="黑猩猩">黑猩猩(ブラッゴリ)-黑</option>
<option value="黑袋鼠">影子拳擊手(シャドウボクサー)-黑</option>
<option value="黑帝王">黑澤導演(クロサワ監督)-黑</option>
<option value="紫電河馬">超級鋼鐵河馬將(超メタルカバちゃん)-鋼鐵</option>
<option value="鋼鐵犀牛">鋼鐵犀一角(メタルサイボーグ)-鋼鐵</option>
<option value="鋼鐵熊">鋼鐵熊(メタックマ)-鋼鐵</option>
<option value="鋼鐵臉君">鋼鐵臉君(メタルカオル君)-鋼鐵</option>
<option value="鋼鐵海豹">鋼鐵斑海豹桑(メタルゴマさま)-鋼鐵</option>
<option value="紅渦">赤色旋風(レッドサイクロン)-紅 / 漂浮</option>
<option value="白渦">白色旋風(ホワイトサイクロン)-漂浮</option>
<option value="黑渦">黑色旋風(ブラックサイクロン)-黑 / 漂浮</option>
<option value="鐵渦">鋼鐵旋風(メタルサイクロン)-鋼鐵</option>
<option value="天渦">天使旋風(エンジェルサイクロン)-天使</option>
<option value="天使河馬">河馬將天使(天使カバちゃん)-天使</option>
<option value="天使狗">加百列天使(天使ガブリエル)-天使</option>
<option value="天使猩猩">落猩天使(天使ゴンザレス)-天使</option>
<option value="天使舉牌員">天使愛好家(天使愛好家)-鋼鐵</option>
<option value="白牆狗">壁犬(カベわんこ)-白</option>
<option value="紅蛇">紅髮扭扭蛇(赤毛のにょろ)-紅</option>
<option value="天使翻車魚">生化魚鈴木(マンボーグ鈴木)-天使</option>
<option value="白鴿">背負.和平.鴿(ド鳩・サブ・レー)-漂浮</option>
<option value="黑海獺">黑色破壞一族(ブラッコ)-黑</option>
<option value="天使馬">天使斯雷普尼爾(天使スレイプニール)-天使</option>
<option value="推人單小人">浪子漢(はぐれたヤツ)-白</option>
<option value="鋼鐵狗">鋼鐵狗仔(メタルわんこ)-鋼鐵</option>
<option value="小紅帝王">小戰士貓彈(コニャンダム)-紅</option>
<option value="黑大象">松黑藏(松 黒蔵)-黑</option>
<option value="天使雞">天使小雞(天使ヒオコエル)-天使</option>
<option value="異星狗">汪仔星人(エイリワン)-異星</option>
<option value="異星鱷魚">鱷星宅宅(ワーニック)-異星</option>
<option value="異星河馬">河馬星人(カーバチャン)-異星</option>
<option value="異星海豹">斑海星人(ゴマサーマン)-異星</option>
<option value="異星蛾">蛾星女神(レディ・ガ)-漂浮 / 異星</option>
<option value="異星眼鏡猴">眼猴星人(デカメガネル)-異星</option>
<option value="異星寄居蟹">寄居星人(ヤドカリー)-異星</option>
<option value="異星臉君">臉星人(カヲルさん)-漂浮 / 異星</option>
<option value="異星熊">熊星人(クマンチュー)-異星</option>
<option value="異星海蝶">破壞生物異星海蝶(破壊生物クオリネン)-漂浮 / 異星</option>
<option value="異星山豬">豬大哥(イノヴァルカン)-異星</option>
<option value="異星皇冠豬">伊麗莎白56世(エリザベス56世)-異星</option>
<option value="異星渦">異星旋風(スペースサイクロン)-漂浮 / 異星</option>
<option value="異星帝王">銀河貓彈(ギャラクシーニャンダム)-異星</option>
<option value="異星瓦爾騎">反叛的瓦爾基麗貓(反逆のヴァルキリー)-漂浮 / 異星</option>
<option value="異星袋鼠">異星袋拳(ハサミーマン)-異星</option>
<option value="異星犀牛">CyberX(サイバーX)-異星</option>
<option value="異星貴賓狗">異星貴賓(プドール夫人)-異星</option>
<option value="紅食蟻獸">豪豬教授(教授)-紅</option>
<option value="紅水豚">披著水豚皮的水豚(カルピンチョ)-紅</option>
<option value="紅無尾熊">戰鬥無尾熊球(バトルコアラッキョ)-紅</option>
<option value="燕子">燕梓南(ツバメンズ)-漂浮</option>
<option value="異星魷魚">小魷(よっちゃん)-漂浮 / 異星</option>
<option value="異星駱駝">羊駝背(アルパッカ)-異星</option>
<option value="異星樹懶">善哉(ヨキカナ)-異星</option>
<option value="異星噴噴">寄生噴噴(パラサイトブンブン)-漂浮 / 異星</option>
<option value="異星姆特">爆走姆特貓(暴走のネコムート)-漂浮 / 異星</option>
<option value="天使海蝶">斷罪天使海蝶(断罪天使クオリネル)-天使</option>
<option value="白土撥鼠">鼴小龍(ドリュウ)-白</option>
<option value="風暴渦">颶風旋風(ハリケーンサイクロン)-漂浮</option>
<option value="天使小人">天使先生(ミスターエンジェル)-天使</option>
<option value="黑大嘴鳥">大嘴武哥(キョセーヌ)-紅 / 黑</option>
<option value="黑海馬">必殺達也(タッちゃん)-黑</option>
<option value="小紅帽">狗仔紅帽咪娜(わんこずきんミーニャ)-白</option>
<option value="天使山豬">天使野豬(イノエンジェル)-天使</option>
<option value="白燈籠魚">小光(ヒカル)-白</option>
<option value="紅般若">般若我王(般若我王)-紅</option>
<option value="異星蜜蜂">森蜜子(森の蜜子)-漂浮 / 異星</option>
<option value="小白噴噴">小噴噴(ちびぶんぶん)-漂浮</option>
<option value="黑山豬">黑暗野豬(イノワール)-黑</option>
<option value="不死狗">不死狗仔(ゾンビワン)-不死</option>
<option value="不死蛇">不死扭扭蛇(にょろろん)-不死</option>
<option value="不死河馬">不死河馬將(カバタリアン)-不死</option>
<option value="不死豬">不死豬野郎(トンシー)-不死</option>
<option value="不死企鵝">不死企鵝大哥Z(ジャッキー・ペンZ)-不死</option>
<option value="不死猩猩">不死大猩猩(ゴリ・ンジュ)-不死</option>
<option value="不死海豹">不死斑海豹桑(ブチゴマさま)-不死</option>
<option value="不死袋鼠">不死袋拳(スカルボクサー)-不死</option>
<option value="不死鱷魚">不死鱷魚(ワニボン)-不死</option>
<option value="不死墓碑男">墓手太郎(墓手太郎)-不死</option>
<option value="不死渦">不死旋風(アンデッドサイクロン)-不死</option>
<option value="不死墓碑女">墓手花子(墓手花子)-不死</option>
<option value="不死大鯢">不死大鯢(オオさん)-不死</option>
<option value="不死牆狗">棺材狗(ヒツギイヌ)-不死</option>
<option value="不死駱駝">凹凸駝背(キャベロン)-不死</option>
<option value="不死宮木">犬武者武藏(犬武者 武蔵)-不死</option>
<option value="肥普">亡者肥普(亡者デブウ)-不死</option>
<option value="白海豚">海豚姑娘(イルカ娘)-白</option>
<option value="天使魷魚">炸烏賊天使(イカ天)-天使</option>
<option value="貓仙人">貓咪仙人(ネコ仙人)-白</option>
<option value="白青蛙">蛙助(ケロ助)-白</option>
<option value="宇宙狗">精英汪仔星人(エリートエイリワン)-異星戰士</option>
<option value="宇宙企鵝">宙星.企鵝大哥(スター・ペン)-異星戰士</option>
<option value="宇宙猩猩">宙猩將軍(グレゴリー将軍)-異星戰士</option>
<option value="宇宙大象">澎湃媽(ハハパオン)-異星戰士</option>
<option value="宇宙食蟻獸">巨匠(巨匠)-異星戰士</option>
<option value="宇宙土撥鼠">鼴鼠隊長(キャプテン・モグー)-異星戰士</option>
<option value="宇宙翻車魚">宙星生化魚(スペースマンボルグ)-漂浮 / 異星戰士</option>
<option value="貓神1">神(神さま)-漂浮</option>
<option value="天使大嘴鳥">大夢君(大夢くん)-天使 / 異星戰士</option>
<option value="宇宙渦">超級異星旋風(スーパースペースサイクロン)-異星戰士</option>
<option value="天使豬">飛天小豬(天使ブッタ)-天使</option>
<option value="吉娃娃">吉娃娃伯爵(謎仮面のウララー)-白</option>
<option value="烏拉拉">神秘假面烏拉拉(猫塚かりん)-白</option>
<option value="紅海馬">火馬(ヒウマ)-紅</option>
<option value="宇宙羊">激動咩咩(ウルトラメェメェ)-異星戰士</option>
<option value="機器噴噴">傳奇噴噴Ω(レジェンドブンブンΩ)-漂浮</option>
<option value="古代噴噴">真傳奇噴噴(真レジェンドブンブン)-古代種</option>
<option value="古代狗">古代狗仔(古代わんこ)-古代種</option>
<option value="古代松鼠">古代松鼠(イングリッス)-古代種</option>
<option value="古代犀牛">古代犀聖(コライノくん)-古代種</option>
<option value="古代蛾">古代蠶舌蛾(超町長)-漂浮 / 古代</option>
<option value="宇宙海豚">快豚星人(マナブくん)-異星戰士</option>
<option value="宇宙青蛙">食蛙星人(ゲコック)-異星戰士</option>
<option value="神2">神(認真)(神さま（本気）)-白</option>
<option value="古代眼鏡猴">古代眼鏡猴人(ダテメガネル)-古代種</option>
<option value="古代臉君">古頭王(古我王)-漂浮 / 古代</option>
<option value="古代鴕鳥">鴕鳥老黛麗(オールドリー)-古代種</option>
<option value="不死熊">科熊怪人(クマンケン)-不死</option>
<option value="不死山豬">玉米鬚豬(シノシシ)-不死</option>
<option value="黑白貘">愛作夢的貘(ユメミちゃん)-黑</option>
<option value="小宇宙渦">邪星小旋風(ミニスターサイクロン)-異星戰士</option>
<option value="宇宙鰻魚">花園鰻5兄弟(チンアナ5兄弟)-異星戰士</option>
<option value="宇宙大鯢">六角龍邪(ルーパールーパー)-異星戰士</option>
<option value="貓神3">終極千兆宏偉神(ファイナルギガグランド神さま)-白</option>
<option value="土偶">土偶喵丸(土偶戦士ドグ丸)-古代種</option>
<option value="不死雞">不死小雞(チキランラン)-不死</option>
<option value="不死三小人">驚悚幫(スリラーズ)-不死</option>
</datalist>
          <?php
$retval3 = mysqli_query( $mysqli, $sq3 );
if($enc2==NULL){}else{
    echo '<table border="1"><tr><td>序號</td><td>日文名稱</td><td>中文名稱</td><td>種類</td><td>屬性</td></tr>';
	while($row3 = mysqli_fetch_assoc($retval3))
		{
   		 echo "<tr><td> {$row3['NO']}</td> ".
   		      "<td>{$row3['Japanese']}</td> ".
   		      "<td>{$row3['Chinese']}</td> ".
   		      "<td>{$row3['kind']}</td> ".
   		      "<td>{$row3['Attributes']}</td> ".
   		      "</tr>";
		}echo '</table>';}
?>
          </p>
  
 <label>敵人C：</label>
<input list="area" name="enc3" value="<?php echo $enc3 ?>" type="text" />
<datalist id="area">
<option value="白狗">狗仔(わんこ)-白</option>
<option value="白蛇">扭扭蛇(にょろ)-白</option>
<option value="白三小人">團結筷子幫(例のヤツ)-白</option>
<option value="白河馬">河馬將(カバちゃん)-白</option>
<option value="紅豬">豬野郎(ブタヤロウ)-紅</option>
<option value="白企鵝">企鵝大哥(ジャッキー・ペン)-白</option>
<option value="白猩猩">大猩猩(ゴリさん)-白</option>
<option value="白羊">咩咩(メェメェ)-白</option>
<option value="紅海豹">斑海豹桑(ゴマさま)-紅</option>
<option value="白大象">澎湃(パオン)-白</option>
<option value="白袋鼠">袋拳(カ・ンガリュ)-白</option>
<option value="紅犀牛">一角君(一角くん)-紅</option>
<option value="白熊">熊熊老師(クマ先生)-白</option>
<option value="白鱷魚">鱷魚宅宅(ワニック)-白</option>
<option value="紅兔">臭老兔(ウサ銀)-紅</option>
<option value="白松鼠">松鼠阿嘎(リッスントゥミー)-白</option>
<option value="黑熊">黑熊(ブラックマ)-黑</option>
<option value="紅臉君">赤面歌王(赤羅我王)-紅 / 漂浮</option>
<option value="白臉君">臉君(カオル君)-漂浮</option>
<option value="15分鐘舉牌">看板小妹(カンバン娘)-白</option>
<option value="白蛾">蛾蛾蛾蛾(ガガガガ)-漂浮</option>
<option value="紅帝王">惡之帝王貓彈(悪の帝王ニャンダム)-紅</option>
<option value="白噴噴">噴噴老師(ぶんぶん先生)-漂浮</option>
<option value="白食蟻獸">師匠(師匠)-白</option>
<option value="白鴕鳥">鴕鳥愛美惠(ダチョウ同好会)-白</option>
<option value="白海獺">破壞一族(フルぼっこ)-白</option>
<option value="白無尾熊">無尾熊球(コアラッキョ)-白</option>
<option value="白駱駝">駝背(こぶへい)-白</option>
<option value="白鴨子">鴨子倫倫(アヒルンルン)-白</option>
<option value="白貓頭鷹">眉毛鳥(まゆげどり)-漂浮</option>
<option value="紅山豬">獅豬(イノシャシ)-紅</option>
<option value="白樹懶">樹懶賴(ナマルケモルル)-白</option>
<option value="白馴鹿">粥董(ナカイくん)-白</option>
<option value="白臘腸狗">拉腸狗(だっふんど)-白</option>
<option value="白貴賓狗">貴賓(セレブ)-白</option>
<option value="白狼">鬣狼(ハイ・エナジー)-白</option>
<option value="杜賓狗">瀟灑哥(ダディ)-白</option>
<option value="狼妹">狼與少女(ウルフとウルルン)-白</option>
<option value="黑狗">殺意狗(殺意のわんこ)-黑</option>
<option value="鋼鐵河馬">鋼鐵河馬將(メタルカバちゃん)-鋼鐵</option>
<option value="紅皇冠豬">伊麗莎白2世(エリザベス2世)-紅</option>
<option value="紅噴噴">赤井噴太郎(赤井ブン太郎)-紅 / 漂浮</option>
<option value="黑噴噴">黑色噴噴(ブラックブンブン)-黑 / 漂浮</option>
<option value="黑猩猩">黑猩猩(ブラッゴリ)-黑</option>
<option value="黑袋鼠">影子拳擊手(シャドウボクサー)-黑</option>
<option value="黑帝王">黑澤導演(クロサワ監督)-黑</option>
<option value="紫電河馬">超級鋼鐵河馬將(超メタルカバちゃん)-鋼鐵</option>
<option value="鋼鐵犀牛">鋼鐵犀一角(メタルサイボーグ)-鋼鐵</option>
<option value="鋼鐵熊">鋼鐵熊(メタックマ)-鋼鐵</option>
<option value="鋼鐵臉君">鋼鐵臉君(メタルカオル君)-鋼鐵</option>
<option value="鋼鐵海豹">鋼鐵斑海豹桑(メタルゴマさま)-鋼鐵</option>
<option value="紅渦">赤色旋風(レッドサイクロン)-紅 / 漂浮</option>
<option value="白渦">白色旋風(ホワイトサイクロン)-漂浮</option>
<option value="黑渦">黑色旋風(ブラックサイクロン)-黑 / 漂浮</option>
<option value="鐵渦">鋼鐵旋風(メタルサイクロン)-鋼鐵</option>
<option value="天渦">天使旋風(エンジェルサイクロン)-天使</option>
<option value="天使河馬">河馬將天使(天使カバちゃん)-天使</option>
<option value="天使狗">加百列天使(天使ガブリエル)-天使</option>
<option value="天使猩猩">落猩天使(天使ゴンザレス)-天使</option>
<option value="天使舉牌員">天使愛好家(天使愛好家)-鋼鐵</option>
<option value="白牆狗">壁犬(カベわんこ)-白</option>
<option value="紅蛇">紅髮扭扭蛇(赤毛のにょろ)-紅</option>
<option value="天使翻車魚">生化魚鈴木(マンボーグ鈴木)-天使</option>
<option value="白鴿">背負.和平.鴿(ド鳩・サブ・レー)-漂浮</option>
<option value="黑海獺">黑色破壞一族(ブラッコ)-黑</option>
<option value="天使馬">天使斯雷普尼爾(天使スレイプニール)-天使</option>
<option value="推人單小人">浪子漢(はぐれたヤツ)-白</option>
<option value="鋼鐵狗">鋼鐵狗仔(メタルわんこ)-鋼鐵</option>
<option value="小紅帝王">小戰士貓彈(コニャンダム)-紅</option>
<option value="黑大象">松黑藏(松 黒蔵)-黑</option>
<option value="天使雞">天使小雞(天使ヒオコエル)-天使</option>
<option value="異星狗">汪仔星人(エイリワン)-異星</option>
<option value="異星鱷魚">鱷星宅宅(ワーニック)-異星</option>
<option value="異星河馬">河馬星人(カーバチャン)-異星</option>
<option value="異星海豹">斑海星人(ゴマサーマン)-異星</option>
<option value="異星蛾">蛾星女神(レディ・ガ)-漂浮 / 異星</option>
<option value="異星眼鏡猴">眼猴星人(デカメガネル)-異星</option>
<option value="異星寄居蟹">寄居星人(ヤドカリー)-異星</option>
<option value="異星臉君">臉星人(カヲルさん)-漂浮 / 異星</option>
<option value="異星熊">熊星人(クマンチュー)-異星</option>
<option value="異星海蝶">破壞生物異星海蝶(破壊生物クオリネン)-漂浮 / 異星</option>
<option value="異星山豬">豬大哥(イノヴァルカン)-異星</option>
<option value="異星皇冠豬">伊麗莎白56世(エリザベス56世)-異星</option>
<option value="異星渦">異星旋風(スペースサイクロン)-漂浮 / 異星</option>
<option value="異星帝王">銀河貓彈(ギャラクシーニャンダム)-異星</option>
<option value="異星瓦爾騎">反叛的瓦爾基麗貓(反逆のヴァルキリー)-漂浮 / 異星</option>
<option value="異星袋鼠">異星袋拳(ハサミーマン)-異星</option>
<option value="異星犀牛">CyberX(サイバーX)-異星</option>
<option value="異星貴賓狗">異星貴賓(プドール夫人)-異星</option>
<option value="紅食蟻獸">豪豬教授(教授)-紅</option>
<option value="紅水豚">披著水豚皮的水豚(カルピンチョ)-紅</option>
<option value="紅無尾熊">戰鬥無尾熊球(バトルコアラッキョ)-紅</option>
<option value="燕子">燕梓南(ツバメンズ)-漂浮</option>
<option value="異星魷魚">小魷(よっちゃん)-漂浮 / 異星</option>
<option value="異星駱駝">羊駝背(アルパッカ)-異星</option>
<option value="異星樹懶">善哉(ヨキカナ)-異星</option>
<option value="異星噴噴">寄生噴噴(パラサイトブンブン)-漂浮 / 異星</option>
<option value="異星姆特">爆走姆特貓(暴走のネコムート)-漂浮 / 異星</option>
<option value="天使海蝶">斷罪天使海蝶(断罪天使クオリネル)-天使</option>
<option value="白土撥鼠">鼴小龍(ドリュウ)-白</option>
<option value="風暴渦">颶風旋風(ハリケーンサイクロン)-漂浮</option>
<option value="天使小人">天使先生(ミスターエンジェル)-天使</option>
<option value="黑大嘴鳥">大嘴武哥(キョセーヌ)-紅 / 黑</option>
<option value="黑海馬">必殺達也(タッちゃん)-黑</option>
<option value="小紅帽">狗仔紅帽咪娜(わんこずきんミーニャ)-白</option>
<option value="天使山豬">天使野豬(イノエンジェル)-天使</option>
<option value="白燈籠魚">小光(ヒカル)-白</option>
<option value="紅般若">般若我王(般若我王)-紅</option>
<option value="異星蜜蜂">森蜜子(森の蜜子)-漂浮 / 異星</option>
<option value="小白噴噴">小噴噴(ちびぶんぶん)-漂浮</option>
<option value="黑山豬">黑暗野豬(イノワール)-黑</option>
<option value="不死狗">不死狗仔(ゾンビワン)-不死</option>
<option value="不死蛇">不死扭扭蛇(にょろろん)-不死</option>
<option value="不死河馬">不死河馬將(カバタリアン)-不死</option>
<option value="不死豬">不死豬野郎(トンシー)-不死</option>
<option value="不死企鵝">不死企鵝大哥Z(ジャッキー・ペンZ)-不死</option>
<option value="不死猩猩">不死大猩猩(ゴリ・ンジュ)-不死</option>
<option value="不死海豹">不死斑海豹桑(ブチゴマさま)-不死</option>
<option value="不死袋鼠">不死袋拳(スカルボクサー)-不死</option>
<option value="不死鱷魚">不死鱷魚(ワニボン)-不死</option>
<option value="不死墓碑男">墓手太郎(墓手太郎)-不死</option>
<option value="不死渦">不死旋風(アンデッドサイクロン)-不死</option>
<option value="不死墓碑女">墓手花子(墓手花子)-不死</option>
<option value="不死大鯢">不死大鯢(オオさん)-不死</option>
<option value="不死牆狗">棺材狗(ヒツギイヌ)-不死</option>
<option value="不死駱駝">凹凸駝背(キャベロン)-不死</option>
<option value="不死宮木">犬武者武藏(犬武者 武蔵)-不死</option>
<option value="肥普">亡者肥普(亡者デブウ)-不死</option>
<option value="白海豚">海豚姑娘(イルカ娘)-白</option>
<option value="天使魷魚">炸烏賊天使(イカ天)-天使</option>
<option value="貓仙人">貓咪仙人(ネコ仙人)-白</option>
<option value="白青蛙">蛙助(ケロ助)-白</option>
<option value="宇宙狗">精英汪仔星人(エリートエイリワン)-異星戰士</option>
<option value="宇宙企鵝">宙星.企鵝大哥(スター・ペン)-異星戰士</option>
<option value="宇宙猩猩">宙猩將軍(グレゴリー将軍)-異星戰士</option>
<option value="宇宙大象">澎湃媽(ハハパオン)-異星戰士</option>
<option value="宇宙食蟻獸">巨匠(巨匠)-異星戰士</option>
<option value="宇宙土撥鼠">鼴鼠隊長(キャプテン・モグー)-異星戰士</option>
<option value="宇宙翻車魚">宙星生化魚(スペースマンボルグ)-漂浮 / 異星戰士</option>
<option value="貓神1">神(神さま)-漂浮</option>
<option value="天使大嘴鳥">大夢君(大夢くん)-天使 / 異星戰士</option>
<option value="宇宙渦">超級異星旋風(スーパースペースサイクロン)-異星戰士</option>
<option value="天使豬">飛天小豬(天使ブッタ)-天使</option>
<option value="吉娃娃">吉娃娃伯爵(謎仮面のウララー)-白</option>
<option value="烏拉拉">神秘假面烏拉拉(猫塚かりん)-白</option>
<option value="紅海馬">火馬(ヒウマ)-紅</option>
<option value="宇宙羊">激動咩咩(ウルトラメェメェ)-異星戰士</option>
<option value="機器噴噴">傳奇噴噴Ω(レジェンドブンブンΩ)-漂浮</option>
<option value="古代噴噴">真傳奇噴噴(真レジェンドブンブン)-古代種</option>
<option value="古代狗">古代狗仔(古代わんこ)-古代種</option>
<option value="古代松鼠">古代松鼠(イングリッス)-古代種</option>
<option value="古代犀牛">古代犀聖(コライノくん)-古代種</option>
<option value="古代蛾">古代蠶舌蛾(超町長)-漂浮 / 古代</option>
<option value="宇宙海豚">快豚星人(マナブくん)-異星戰士</option>
<option value="宇宙青蛙">食蛙星人(ゲコック)-異星戰士</option>
<option value="神2">神(認真)(神さま（本気）)-白</option>
<option value="古代眼鏡猴">古代眼鏡猴人(ダテメガネル)-古代種</option>
<option value="古代臉君">古頭王(古我王)-漂浮 / 古代</option>
<option value="古代鴕鳥">鴕鳥老黛麗(オールドリー)-古代種</option>
<option value="不死熊">科熊怪人(クマンケン)-不死</option>
<option value="不死山豬">玉米鬚豬(シノシシ)-不死</option>
<option value="黑白貘">愛作夢的貘(ユメミちゃん)-黑</option>
<option value="小宇宙渦">邪星小旋風(ミニスターサイクロン)-異星戰士</option>
<option value="宇宙鰻魚">花園鰻5兄弟(チンアナ5兄弟)-異星戰士</option>
<option value="宇宙大鯢">六角龍邪(ルーパールーパー)-異星戰士</option>
<option value="貓神3">終極千兆宏偉神(ファイナルギガグランド神さま)-白</option>
<option value="土偶">土偶喵丸(土偶戦士ドグ丸)-古代種</option>
<option value="不死雞">不死小雞(チキランラン)-不死</option>
<option value="不死三小人">驚悚幫(スリラーズ)-不死</option>
</datalist>
<?php	
$retval4 = mysqli_query( $mysqli, $sq4 );
if($enc3==NULL){}else{
	echo '<table border="1"><tr><td>序號</td><td>日文名稱</td><td>中文名稱</td><td>種類</td><td>屬性</td></tr>';	
	while($row4 = mysqli_fetch_assoc($retval4))
		{
   		 echo "<tr><td> {$row4['NO']}</td> ".
   		      "<td>{$row4['Japanese']}</td> ".
   		      "<td>{$row4['Chinese']}</td> ".
   		      "<td>{$row4['kind']}</td> ".
   		      "<td>{$row4['Attributes']}</td> ".
   		      "</tr>";
		}echo '</table>';}	
?>	
  	<p><input type="submit" name="button" id="button" value="搜尋"></p>
</form>



<?php		
  
$retval = mysqli_query( $mysqli, $sql );
	if(! $retval )
		{
	    die('无法读取数据: ' . mysqli_error($mysqli));
		}
	if($enc1==NULL or $enc2==NULL or $enc3==NULL){}else{
			echo '<br>敵人： '; echo $enc1; echo'+'; echo $enc2; echo'+'; echo $enc3; echo'<br><table border="1"><tr><td>進度</td><td>順序</td><td>關卡</td><td>統率力</td><td>敵人總覽</td></tr>';
			while($row = mysqli_fetch_assoc($retval))
			{
 		   echo "<tr><td> {$row['schedule']}</td> ".
				"<td>{$row['number']} </td> ".
				"<td>{$row['name']} </td> ".
				"<td>{$row['HP']} </td> ".
				"<td>{$row['kide']} </td> ".
				"</tr>";
			}}
	echo '</table>';

    
$retval5 = mysqli_query( $mysqli, $sq5 );
	if($enc1==NULL or $enc2==NULL){}else{
		echo '<br>敵人： '; echo $enc1; echo'+'; echo $enc2; echo'<br><table border="1"><tr><td>進度</td><td>順序</td><td>關卡</td><td>統率力</td><td>敵人總覽</td></tr>';
		while($row5 = mysqli_fetch_assoc($retval5))
		{
  		  echo "<tr><td> {$row5['schedule']}</td> ".
				"<td>{$row5['number']} </td> ".
				"<td>{$row5['name']} </td> ".
				"<td>{$row5['HP']} </td> ".
				"<td>{$row5['kide']} </td> ".
				"</tr>";
		}}
	echo '</table>';
	
$retval6 = mysqli_query( $mysqli, $sq6 );
	if($enc1==NULL or $enc3==NULL){}else{
		echo '<br>敵人： '; echo $enc1; echo'+'; echo $enc3; echo'<br><table border="1"><tr><td>進度</td><td>順序</td><td>關卡</td><td>統率力</td><td>敵人總覽</td></tr>';
		while($row6 = mysqli_fetch_assoc($retval6))
		{
  		  echo "<tr><td> {$row6['schedule']}</td> ".
				"<td>{$row6['number']} </td> ".
				"<td>{$row6['name']} </td> ".
				"<td>{$row6['HP']} </td> ".
				"<td>{$row6['kide']} </td> ".
				"</tr>";
		}}
	echo '</table>';
	
$retval7 = mysqli_query( $mysqli, $sq7 );
	if($enc2==NULL or $enc3==NULL){}else{
		echo '<br>敵人： '; echo $enc2; echo'+'; echo $enc3; echo'<br><table border="1"><tr><td>進度</td><td>順序</td><td>關卡</td><td>統率力</td><td>敵人總覽</td></tr>';
		while($row7 = mysqli_fetch_assoc($retval7))
		{
  		  echo "<tr><td> {$row7['schedule']}</td> ".
				"<td>{$row7['number']} </td> ".
				"<td>{$row7['name']} </td> ".
				"<td>{$row7['HP']} </td> ".
				"<td>{$row7['kide']} </td> ".
				"</tr>";
		}}
	echo '</table>';
	
$retval8 = mysqli_query( $mysqli, $sq8 );
	if($enc1==NULL){}else{
		echo '<br>敵人A：' ;echo $enc1; echo '<br><table border="1"><tr><td>進度</td><td>順序</td><td>關卡</td><td>統率力</td><td>敵人總覽</td></tr>';
		while($row8 = mysqli_fetch_assoc($retval8))
		{
  		  echo "<tr><td> {$row8['schedule']}</td> ".
				"<td>{$row8['number']} </td> ".
				"<td>{$row8['name']} </td> ".
				"<td>{$row8['HP']} </td> ".
				"<td>{$row8['kide']} </td> ".
				"</tr>";
		}}
	echo '</table>';
	
$retval9 = mysqli_query( $mysqli, $sq9 );
	if($enc2==NULL){}else{
		echo '<br>敵人B：' ;echo $enc2; echo '<br><table border="1"><tr><td>進度</td><td>順序</td><td>關卡</td><td>統率力</td><td>敵人總覽</td></tr>';
		while($row9 = mysqli_fetch_assoc($retval9))
		{
  		  echo "<tr><td> {$row9['schedule']}</td> ".
				"<td>{$row9['number']} </td> ".
				"<td>{$row9['name']} </td> ".
				"<td>{$row9['HP']} </td> ".
				"<td>{$row9['kide']} </td> ".
				"</tr>";
		}}
	echo '</table>';

$retvalA = mysqli_query( $mysqli, $sqA );
	if($enc3==NULL){}else{
		echo '<br>敵人C：' ;echo $enc3; echo '<br><table border="1"><tr><td>進度</td><td>順序</td><td>關卡</td><td>統率力</td><td>敵人總覽</td></tr>';
		while($rowA = mysqli_fetch_assoc($retvalA))
		{
  		  echo "<tr><td> {$rowA['schedule']}</td> ".
				"<td>{$rowA['number']} </td> ".
				"<td>{$rowA['name']} </td> ".
				"<td>{$rowA['HP']} </td> ".
				"<td>{$rowA['kide']} </td> ".
				"</tr>";
		}}
	echo '</table>';

mysqli_free_result($retval);
mysqli_close($mysqli);
?>