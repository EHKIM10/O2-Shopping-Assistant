<head>
<meta http-equiv = "content-Type" content = "text/html" charset = "utf-8">
</head> <!-- �ѱ� ����(DB�� utf-8_unicode_ci�� ������ּ���)�� ���� �ҽ� -->
<?
$connect = mysql_connect("127.0.0.1", "badcode", "1234");
mysql_selectdb("o2");
mysql_query("set names utf8"); //���� ���������� utf8�� �����ϱ� ���� �ҽ�

$beacon_id = $_REQUEST['beacon_id'];
$qry = "select * from LOOKS_INFO_TB where beacon_id = '$beacon_id';";
$result = mysql_query($qry);

$xmlcode = "<?xml version = \"1.0\" encoding = \"utf-8\"?>\n";

while($obj = mysql_fetch_object($result))
{
	$look_id = $obj->look_id;
	$look_image = $obj->look_image;

	$xmlcode .= "<node>\n"; //xml���� �����ϱ� ������ node�� ����
	$xmlcode .= "<look_id>$look_id</look_id>\n";
	$xmlcode .= "<look_image>$look_image</look_image>\n";
	$xmlcode .= "</node>\n";
}

$dir = "C:/APM_Setup/htdocs"; //������ ���� ���丮
$filename = $dir."/src/look_search_result.xml"; //���� �̸�

file_put_contents($filename, $xmlcode);
?>