<!DOCTYPE html>
<html lang="en" dir="ltr">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<meta name="robots" content="noindex">
<title>Export: balk_brandtap - Adminer</title>
<link rel="stylesheet" type="text/css" href="adminer-4.1.0.php?file=default.css&amp;version=4.1.0">
<script type="text/javascript" src="adminer-4.1.0.php?file=functions.js&amp;version=4.1.0"></script>
<link rel="shortcut icon" type="image/x-icon" href="adminer-4.1.0.php?file=favicon.ico&amp;version=4.1.0">
<link rel="apple-touch-icon" href="adminer-4.1.0.php?file=favicon.ico&amp;version=4.1.0">

<body class="ltr nojs" onkeydown="bodyKeydown(event);" onclick="bodyClick(event);">
<script type="text/javascript">
document.body.className = document.body.className.replace(/ nojs/, ' js');
</script>

<div id="help" class="jush-sql jsonly hidden" onmouseover="helpOpen = 1;" onmouseout="helpMouseout(this, event);"></div>

<div id="content">
<p id="breadcrumb"><a href="adminer-4.1.0.php">MySQL</a> &raquo; <a href='adminer-4.1.0.php?username=balk_milos' accesskey='1' title='Alt+Shift+1'>Server</a> &raquo; <a href="adminer-4.1.0.php?username=balk_milos&amp;db=balk_brandtap">balk_brandtap</a> &raquo; Export
<h2>Export: balk_brandtap</h2>

<form action="" method="post">
<table cellspacing="0">
<tr><th>Output<td><label><input type='radio' name='output' value='text' checked>open</label><label><input type='radio' name='output' value='file'>save</label><label><input type='radio' name='output' value='gz'>gzip</label>
<tr><th>Format<td><label><input type='radio' name='format' value='sql' checked>SQL</label><label><input type='radio' name='format' value='csv'>CSV,</label><label><input type='radio' name='format' value='csv;'>CSV;</label><label><input type='radio' name='format' value='tsv'>TSV</label>
<tr><th>Database<td><select name='db_style'><option selected><option>USE<option>DROP+CREATE<option>CREATE</select><label><input type='checkbox' name='routines' value='1'>Routines</label><label><input type='checkbox' name='events' value='1'>Events</label><tr><th>Tables<td><select name='table_style'><option><option selected>DROP+CREATE<option>CREATE</select><label><input type='checkbox' name='auto_increment' value='1'>Auto Increment</label><label><input type='checkbox' name='triggers' value='1' checked>Triggers</label><tr><th>Data<td><select name='data_style'><option><option>TRUNCATE+INSERT<option selected>INSERT<option>INSERT+UPDATE</select></table>
<p><input type="submit" value="Export">
<input type="hidden" name="token" value="286582:919366">

<table cellspacing="0">
<thead><tr><th style='text-align: left;'><label class='block'><input type='checkbox' id='check-tables' onclick='formCheck(this, /^tables\[/);'>Tables</label><th style='text-align: right;'><label class='block'>Data<input type='checkbox' id='check-data' onclick='formCheck(this, /^data\[/);'></label></thead>
<tr><td><label class='block'><input type='checkbox' name='tables[]' value='access_token' onclick="checkboxClick(event, this); formUncheck(&#039;check-tables&#039;);">access_token</label><td align='right'><label class='block'><span id='Rows-access_token'></span><input type='checkbox' name='data[]' value='access_token' onclick="checkboxClick(event, this); formUncheck(&#039;check-data&#039;);"></label>
<tr><td><label class='block'><input type='checkbox' name='tables[]' value='activation' onclick="checkboxClick(event, this); formUncheck(&#039;check-tables&#039;);">activation</label><td align='right'><label class='block'><span id='Rows-activation'></span><input type='checkbox' name='data[]' value='activation' onclick="checkboxClick(event, this); formUncheck(&#039;check-data&#039;);"></label>
<tr><td><label class='block'><input type='checkbox' name='tables[]' value='logs' checked onclick="checkboxClick(event, this); formUncheck(&#039;check-tables&#039;);">logs</label><td align='right'><label class='block'><span id='Rows-logs'></span><input type='checkbox' name='data[]' value='logs' checked onclick="checkboxClick(event, this); formUncheck(&#039;check-data&#039;);"></label>
<tr><td><label class='block'><input type='checkbox' name='tables[]' value='post_winners' onclick="checkboxClick(event, this); formUncheck(&#039;check-tables&#039;);">post_winners</label><td align='right'><label class='block'><span id='Rows-post_winners'></span><input type='checkbox' name='data[]' value='post_winners' onclick="checkboxClick(event, this); formUncheck(&#039;check-data&#039;);"></label>
<tr><td><label class='block'><input type='checkbox' name='tables[]' value='users' onclick="checkboxClick(event, this); formUncheck(&#039;check-tables&#039;);">users</label><td align='right'><label class='block'><span id='Rows-users'></span><input type='checkbox' name='data[]' value='users' onclick="checkboxClick(event, this); formUncheck(&#039;check-data&#039;);"></label>
<script type='text/javascript'>ajaxSetHtml('adminer-4.1.0.php?username=balk_milos&db=balk_brandtap&script=db');</script>
</table>
</form>
</div>

<form action='' method='post'>
<div id='lang'>Language: <select name='lang' onchange="this.form.submit();"><option value="en" selected>English<option value="ar">العربية<option value="bn">বাংলা<option value="ca">Català<option value="cs">Čeština<option value="de">Deutsch<option value="es">Español<option value="et">Eesti<option value="fa">فارسی<option value="fr">Français<option value="hu">Magyar<option value="id">Bahasa Indonesia<option value="it">Italiano<option value="ja">日本語<option value="ko">한국어<option value="lt">Lietuvių<option value="nl">Nederlands<option value="no">Norsk<option value="pl">Polski<option value="pt">Português<option value="pt-br">Português (Brazil)<option value="ro">Limba Română<option value="ru">Русский язык<option value="sk">Slovenčina<option value="sl">Slovenski<option value="sr">Српски<option value="ta">த‌மிழ்<option value="th">ภาษาไทย<option value="tr">Türkçe<option value="uk">Українська<option value="vi">Tiếng Việt<option value="zh">简体中文<option value="zh-tw">繁體中文</select> <input type='submit' value='Use' class='hidden'>
<input type='hidden' name='token' value='945554:275874'>
</div>
</form>
<form action="" method="post">
<p class="logout">
<input type="submit" name="logout" value="Logout" id="logout">
<input type="hidden" name="token" value="286582:919366">
</p>
</form>
<div id="menu">
<h1>
<a href='http://www.adminer.org/' target='_blank' id='h1'>Adminer</a> <span class="version">4.1.0</span>
<a href="http://www.adminer.org/#download" target="_blank" id="version"></a>
</h1>
<script type="text/javascript" src="adminer-4.1.0.php?file=jush.js&amp;version=4.1.0"></script>
<script type="text/javascript">
var jushLinks = { sql: [ 'adminer-4.1.0.php?username=balk_milos&db=balk_brandtap&table=$&', /\b(access_token|activation|logs|post_winners|users)\b/g ] };
jushLinks.bac = jushLinks.sql;
jushLinks.bra = jushLinks.sql;
jushLinks.sqlite_quo = jushLinks.sql;
jushLinks.mssql_bra = jushLinks.sql;
bodyLoad('5.5');
</script>
<form action="">
<p id="dbs">
<input type="hidden" name="username" value="balk_milos"><span title='database'>DB</span>: <select name='db' onmousedown='dbMouseDown(event, this);' onchange='dbChange(this);'><option value=""><option>information_schema<option selected>balk_brandtap</select><input type='submit' value='Use' class='hidden'>
<input type="hidden" name="dump" value=""></p></form>
<p class='links'><a href='adminer-4.1.0.php?username=balk_milos&amp;db=balk_brandtap&amp;sql='>SQL command</a>
<a href='adminer-4.1.0.php?username=balk_milos&amp;db=balk_brandtap&amp;import='>Import</a>
<a href='adminer-4.1.0.php?username=balk_milos&amp;db=balk_brandtap&amp;dump=' id='dump' class='active '>Dump</a>
<a href="adminer-4.1.0.php?username=balk_milos&amp;db=balk_brandtap&amp;create=">Create table</a>
<p id='tables' onmouseover='menuOver(this, event);' onmouseout='menuOut(this);'>
<a href="adminer-4.1.0.php?username=balk_milos&amp;db=balk_brandtap&amp;select=access_token">select</a> <a href="adminer-4.1.0.php?username=balk_milos&amp;db=balk_brandtap&amp;table=access_token" title='Show structure'>access_token</a><br>
<a href="adminer-4.1.0.php?username=balk_milos&amp;db=balk_brandtap&amp;select=activation">select</a> <a href="adminer-4.1.0.php?username=balk_milos&amp;db=balk_brandtap&amp;table=activation" title='Show structure'>activation</a><br>
<a href="adminer-4.1.0.php?username=balk_milos&amp;db=balk_brandtap&amp;select=logs">select</a> <a href="adminer-4.1.0.php?username=balk_milos&amp;db=balk_brandtap&amp;table=logs" title='Show structure'>logs</a><br>
<a href="adminer-4.1.0.php?username=balk_milos&amp;db=balk_brandtap&amp;select=post_winners">select</a> <a href="adminer-4.1.0.php?username=balk_milos&amp;db=balk_brandtap&amp;table=post_winners" title='Show structure'>post_winners</a><br>
<a href="adminer-4.1.0.php?username=balk_milos&amp;db=balk_brandtap&amp;select=users">select</a> <a href="adminer-4.1.0.php?username=balk_milos&amp;db=balk_brandtap&amp;table=users" title='Show structure'>users</a><br>
</div>
<script type="text/javascript">setupSubmitHighlight(document);</script>
