<head>
<meta http-equiv = "content-Type" content = "text/html" charset = "utf-8">
</head> <!-- �ѱ� ����(DB�� utf-8_unicode_ci�� ������ּ���)�� ���� �ҽ� -->
<?
$connect = mysqli_connect("mysql.hostinger.kr", 'u366220461_o2', "badcode", "u366220461_o2");

//connection check
if (!$connect) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

echo "Success: A proper connection to MySQL was made!" . PHP_EOL;
echo "Host information: " . mysqli_get_host_info($connect) . PHP_EOL;

mysqli_query($connect, "set names utf8"); //���� ���������� utf8�� �����ϱ�

$look_id = $_GET['look_id'];
$qry1 = "select product_id from LOOKS_PRODUCT_TB where look_id = '$look_id';";
echo $qry1;

$result1 = mysqli_query($connect, $qry1);

if(!$result1) {
	echo mysqli_errno() . ": " . mysqli_error() . PHP_EOL;
}

printf("row number returned : %d\n", mysqli_num_rows($result1));
$xmlcode = "<?xml version = \"1.0\" encoding = \"utf-8\"?>\n";

while($obj = mysqli_fetch_object($result1))
{
	$product_id = $obj->product_id;
	
	$qry2 = "select * from PRODUCT_INFO_TB where product_id = '$product_id';";
	$result2 = mysqli_query($connect, $qry2);
	$row = mysqli_fetch_array($result2, MYSQLI_NUM);
	
	$xmlcode .= "<node>\n"; //xml���� �����ϱ� ������ node�� ����
	$xmlcode .= "<product_id>$row[0]</product_id>\n";
	$xmlcode .= "<name>$row[1]</name>\n";
	$xmlcode .= "<price>$row[2]</price>\n";
	$xmlcode .= "<comment>$row[3]</comment>\n";
	$xmlcode .= "<category>$row[4]</category>\n";
	$xmlcode .= "<product_image>$row[5]</product_image>\n";
	$xmlcode .= "<material>$row[6]</material>\n";	//���� �߰��� ����:����
	$xmlcode .= "<laundry>$row[7]</laundry>\n";	//���� �߰��� ����:��Ź ���ǻ���
	$xmlcode .= "</node>\n";
}

$dir = "/home/u366220461/public_html/"; //������ ���� ���丮
$filename = $dir."product_search_result.xml"; //���� �̸�

$valid = file_put_contents($filename, $xmlcode);

//file writing check
if($valid == FALSE)
	echo "file writing failed." . PHP_EOL;
?>