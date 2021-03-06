<?php
/**
 * @package Include/help/ja
 */
?>
<h1>データ保存倍率</h1>

データ保存倍率には、収集したデータをデータベースに保存する時の倍率を設定します。
例えば、収集データが 1024 であった場合に、データ保存倍率の設定が 1000 であれば、データベースには 1024000 が保存されます。
これは、データを標準化したい場合や特定の単位に変換して保存したい場合に便利です。
また、1未満の数値を設定することにより保存データを割ることもできます。
たとえば、0.001を設定すると、実際のデータを 1000 で割った値が保存されます。その他の例を以下に示します。
<li>timeticks(SNMP) を日に変換: 0.000000115740741
<li>Bytes を MBytes に変換: 0.00000095367432
<li>Bytes を GBytes に変換: 0.00000000093132
<li>Bbits を MBytes に変換: 0.125
<li>
<br /><br />
未設定もしくは 0 を設定すると、データ保存倍率の設定は無効になります。(デフォルト)
