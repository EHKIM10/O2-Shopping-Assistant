<head>
<meta http-equiv = "content-Type" content = "text/html" charset = "utf-8">
</head> <!-- �ѱ� ����(DB�� utf-8_unicode_ci�� ������ּ���)�� ���� �ҽ� -->
<?
$connect = mysql_connect("127.0.0.1", "badcode", "1234");
mysql_selectdb("o2");
mysql_query("set names utf8"); //���� ���������� utf8�� �����ϱ� ���� �ҽ�

$look_id = $_REQUEST['look_id'];
$qry1 = "select product_id from LOOKS_PRODUCT_TB where look_id = '$look_id';";
$result1 = mysql_query($qry1);

$xmlcode = "<?xml version = \"1.0\" encoding = \"utf-8\"?>\n";

while($obj = mysql_fetch_object($result1))
{
	$product_id = $obj->product_id;
	
	$qry2 = "select * from PRODUCT_INFO_TB where product_id = '$product_id';";
	$result2 = mysql_query($qry2);
	$row=mysql_fetch_array($result2);
	
	$xmlcode .= "<node>\n"; //xml���� �����ϱ� ������ node�� ����
	$xmlcode .= "<product_id>$row[product_id]</product_id>\n";
	$xmlcode .= "<name>$row[name]</name>\n";
	$xmlcode .= "<price>$row[price]</price>\n";
	$xmlcode .= "<comment>$row[comment]</comment>\n";
	$xmlcode .= "<product_image>$row[product_image]</product_image>\n";
	$xmlcode .= "<material>$row[material]</material>\n";	//���� �߰��� ����:����
	$xmlcode .= "<laundry>$row[laundry]</laundry>\n";	//���� �߰��� ����:��Ź ���ǻ���
	$xmlcode .= "</node>\n";
}

$dir = "C:/APM_Setup/htdocs"; //������ ���� ���丮
$filename = $dir."/src/product_search_result.xml"; //���� �̸�

file_put_contents($filename, $xmlcode);
?>