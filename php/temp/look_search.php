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

mysqli_query($connect, "set names utf8"); //���� ���������� utf8�� �����ϱ� ���� �ҽ�

$beacon_id = $_GET["beacon_id"];
$qry = "select * from LOOKS_INFO_TB where beacon_id = '$beacon_id';";
echo $qry . PHP_EOL;

$result = mysqli_query($connect, $qry);

if(!$result) {
	echo mysqli_errno() . ": " . mysqli_error() . PHP_EOL;
}

printf("row number returned : %d\n", mysqli_num_rows($result));
$xmlcode = "<?xml version = \"1.0\" encoding = \"utf-8\"?>\n";

while($obj = mysqli_fetch_object($result))
{
	$look_id = $obj->look_id;
	$look_image = $obj->look_image;

	$xmlcode .= "<node>\n"; //xml���� �����ϱ� ������ node�� ����
	$xmlcode .= "<look_id>$look_id</look_id>\n";
	$xmlcode .= "<look_image>$look_image</look_image>\n";
	$xmlcode .= "</node>\n";
}

mysqli_free_result($result);

$dir = "/home/u366220461/"; //������ ���� ���丮
$filename = $dir."src/look_search_result.xml"; //���� �̸�
$valid = file_put_contents($filename, $xmlcode);

//file writing check
if($valid === FALSE)
	echo "file writing failed." . PHP_EOL;
?>