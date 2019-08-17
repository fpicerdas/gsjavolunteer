<?php

/**
 * @author Abba Yosua <abbasiagian@gmail.com>
 * @copyright CreativeCode.Design 2019
 * @package gsja_volunteer_app
 * 
 * 
 * Created using Ionic App Builder
 * http://codecanyon.net/item/ionic-mobile-app-builder/15716727
 */


/** CONFIG:START **/
$config["host"] 		= "localhost" ; 		//host
$config["user"] 		= "root" ; 		//Username SQL
$config["pass"] 		= "root" ; 		//Password SQL
$config["dbase"] 		= "list_barang" ; 		//Database
$config["utf8"] 		= true ; 		//turkish charset set false
$config["timezone"] 		= "Asia/Jakarta" ; 		// check this site: http://php.net/manual/en/timezones.php
$config["abs_url_images"] 		= "http://localhost:81/output/gsja_volunteer_app/backend/php-sql//media/image/" ; 		//Absolute Images URL
$config["abs_url_videos"] 		= "http://localhost:81/output/gsja_volunteer_app/backend/php-sql//media/media/" ; 		//Absolute Videos URL
$config["abs_url_audios"] 		= "http://localhost:81/output/gsja_volunteer_app/backend/php-sql//media/media/" ; 		//Absolute Audio URL
$config["abs_url_files"] 		= "http://localhost:81/output/gsja_volunteer_app/backend/php-sql//media/file/" ; 		//Absolute Files URL
$config["image_allowed"][] 		= array("mimetype"=>"image/jpeg","ext"=>"jpg") ; 		//whitelist image
$config["image_allowed"][] 		= array("mimetype"=>"image/jpg","ext"=>"jpg") ; 		
$config["image_allowed"][] 		= array("mimetype"=>"image/png","ext"=>"png") ; 		
$config["file_allowed"][] 		= array("mimetype"=>"text/plain","ext"=>"txt") ; 		
$config["file_allowed"][] 		= array("mimetype"=>"","ext"=>"tmp") ; 		
/** CONFIG:END **/

date_default_timezone_set($config['timezone']);
if(isset($_SERVER["HTTP_X_AUTHORIZATION"])){
	list($_SERVER["PHP_AUTH_USER"],$_SERVER["PHP_AUTH_PW"]) = explode(":" , base64_decode(substr($_SERVER["HTTP_X_AUTHORIZATION"],6)));
}
$rest_api=array("data"=>array("status"=>404,"title"=>"Not found"),"title"=>"Error","message"=>"Routes not found");

/** connect to mysql **/
$mysql = new mysqli($config["host"], $config["user"], $config["pass"], $config["dbase"]);
if (mysqli_connect_errno()){
	die(mysqli_connect_error());
}


if(!isset($_GET["json"])){
	$_GET["json"]= "route";
}
if((!isset($_GET["form"])) && ($_GET["json"] == "submit")) {
	$_GET["json"]= "route";
}

if($config["utf8"]==true){
	$mysql->set_charset("utf8");
}

$get_dir = explode("/", $_SERVER["PHP_SELF"]);
unset($get_dir[count($get_dir)-1]);
$main_url = "http://" . $_SERVER["HTTP_HOST"] . implode("/",$get_dir)."/";


switch($_GET["json"]){	
	// TODO: -+- Listing : list_barang
	case "list_barang":
		$rest_api=array();
		$where = $_where = null;
		// TODO: -+----+- statement where
		if(isset($_GET["nama_barang"])){
			if($_GET["nama_barang"]!="-1"){
				$_where[] = "`nama_barang` LIKE '".$mysql->escape_string($_GET["nama_barang"])."'";
			}
		}
		if(isset($_GET["foto_barang"])){
			if($_GET["foto_barang"]!="-1"){
				$_where[] = "`foto_barang` LIKE '".$mysql->escape_string($_GET["foto_barang"])."'";
			}
		}
		if(isset($_GET["kondisi_posisi"])){
			if($_GET["kondisi_posisi"]!="-1"){
				$_where[] = "`kondisi_posisi` LIKE '".$mysql->escape_string($_GET["kondisi_posisi"])."'";
			}
		}
		if(isset($_GET["id"])){
			if($_GET["id"]!="-1"){
				$_where[] = "`id` = '".$mysql->escape_string($_GET["id"])."'";
			}
		}
		if(is_array($_where)){
			$where = " WHERE " . implode(" AND ",$_where);
		}
		// TODO: -+----+- orderby
		$order_by = "`id`";
		$sort_by = "DESC";
		if(!isset($_GET["order"])){
			$_GET["order"] = "`id`";
		}
		// TODO: -+----+- sort asc/desc
		if(!isset($_GET["sort"])){
			$_GET["sort"] = "desc";
		}
		if($_GET["sort"]=="asc"){
			$sort_by = "ASC";
		}else{
			$sort_by = "DESC";
		}
		if($_GET["order"]=="id"){
			$order_by = "`id`";
		}
		if($_GET["order"]=="nama_barang"){
			$order_by = "`nama_barang`";
		}
		if($_GET["order"]=="foto_barang"){
			$order_by = "`foto_barang`";
		}
		if($_GET["order"]=="kondisi_posisi"){
			$order_by = "`kondisi_posisi`";
		}
		if($_GET["order"]=="random"){
			$order_by = "RAND()";
		}
		$limit = 100;
		if(isset($_GET["limit"])){
			$limit = (int)$_GET["limit"] ;
		}
		// TODO: -+----+- SQL Query
		$sql = "SELECT * FROM `list_barang` ".$where."ORDER BY ".$order_by." ".$sort_by." LIMIT 0, ".$limit." " ;
		if($result = $mysql->query($sql)){
			$z=0;
			while ($data = $result->fetch_array()){
				if(isset($data['id'])){$rest_api[$z]['id'] = $data['id'];}; # id
				if(isset($data['nama_barang'])){$rest_api[$z]['nama_barang'] = $data['nama_barang'];}; # heading-1
				
				/** images**/
				$abs_url_images = $config['abs_url_images'].'/';
				$abs_url_videos = $config['abs_url_videos'].'/';
				$abs_url_audios = $config['abs_url_audios'].'/';
				if(!isset($data['foto_barang'])){$data['foto_barang']='undefined';}; # images
				if((substr($data['foto_barang'], 0, 7)=='http://')||(substr($data['foto_barang'], 0, 8)=='https://')){
					$abs_url_images = $abs_url_videos  = $abs_url_audios = '';
				}
				
				if(substr($data['foto_barang'], 0, 5)=='data:'){
					$abs_url_images = $abs_url_videos  = $abs_url_audios = '';
				}
				
				if($data['foto_barang'] != ''){
					$rest_api[$z]['foto_barang'] = $abs_url_images . $data['foto_barang']; # images
				}else{
					$rest_api[$z]['foto_barang'] = ''; # images
				}
				if(isset($data['kondisi_posisi'])){$rest_api[$z]['kondisi_posisi'] = $data['kondisi_posisi'];}; # to_trusted
				$z++;
			}
			$result->close();
			if(isset($_GET["id"])){
				if(isset($rest_api[0])){
					$rest_api = $rest_api[0];
				}else{
					$rest_api=array("data"=>array("status"=>404,"title"=>"Not found"),"title"=>"Error","message"=>"Invalid ID");
				}
			}
		}

		break;
	// TODO: -+- route
	case "route":		$rest_api=array();
		$rest_api["site"]["name"] = "GSJA Volunteer App" ;
		$rest_api["site"]["description"] = "App untuk memudahkan Volunteer Koordinasi dan Management" ;
		$rest_api["site"]["imabuilder"] = "rev18.12.10" ;

		$rest_api["routes"][0]["namespace"] = "list_barang";
		$rest_api["routes"][0]["tb_version"] = "Upd.1908170330";
		$rest_api["routes"][0]["methods"][] = "GET";
		$rest_api["routes"][0]["args"]["id"] = array("required"=>"false","description"=>"Selecting `list_barang` based `id`");
		$rest_api["routes"][0]["args"]["nama_barang"] = array("required"=>"false","description"=>"Selecting `list_barang` based `nama_barang`");
		$rest_api["routes"][0]["args"]["foto_barang"] = array("required"=>"false","description"=>"Selecting `list_barang` based `foto_barang`");
		$rest_api["routes"][0]["args"]["kondisi_posisi"] = array("required"=>"false","description"=>"Selecting `list_barang` based `kondisi_posisi`");
		$rest_api["routes"][0]["args"]["order"] = array("required"=>"false","description"=>"order by `random`, `id`, `nama_barang`, `foto_barang`, `kondisi_posisi`");
		$rest_api["routes"][0]["args"]["sort"] = array("required"=>"false","description"=>"sort by `asc` or `desc`");
		$rest_api["routes"][0]["args"]["limit"] = array("required"=>"false","description"=> "limit the items that appear","type"=>"number");
		$rest_api["routes"][0]["_links"]["self"] = "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"]."?json=list_barang";
		$rest_api["routes"][1]["namespace"] = "submit/list_barang";
		$rest_api["routes"][1]["tb_version"] = "Upd.1908170330";
		$rest_api["routes"][1]["methods"][] = "POST";
		$rest_api["routes"][1]["_links"]["self"] = "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"]."?json=submit&form=list_barang";
		$rest_api["routes"][1]["args"]["nama_barang"] = array("required"=>"true","description"=>"Insert data to field `nama_barang` in table `list_barang`");
		$rest_api["routes"][1]["args"]["foto_barang"] = array("required"=>"true","description"=>"Insert data to field `foto_barang` in table `list_barang`");
		$rest_api["routes"][1]["args"]["kondisi_posisi"] = array("required"=>"true","description"=>"Insert data to field `kondisi_posisi` in table `list_barang`");
		$rest_api["routes"][2]["namespace"] = "submit/list_barang";
		$rest_api["routes"][2]["tb_version"] = "Upd.1908170648";
		$rest_api["routes"][2]["methods"][] = "POST";
		$rest_api["routes"][2]["_links"]["self"] = "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"]."?json=submit&form=list_barang";
		$rest_api["routes"][2]["args"]["nama_barang"] = array("required"=>"true","description"=>"Insert data to field `nama_barang` in table `list_barang`");
		$rest_api["routes"][2]["args"]["foto_barang"] = array("required"=>"true","description"=>"Insert data to field `foto_barang` in table `list_barang`");
		$rest_api["routes"][2]["args"]["kondisi_posisi"] = array("required"=>"true","description"=>"Insert data to field `kondisi_posisi` in table `list_barang`");
		break;
	// TODO: -+- submit

	case "submit":
		$rest_api=array();

		$rest_api["methods"][0] = "POST";
		$rest_api["methods"][1] = "GET";
		switch($_GET["form"]){
			// TODO: -+----+- list_barang
			case "list_barang":


				$rest_api["auth"]["basic"] = false;

				$rest_api["args"]["nama_barang"] = array("required"=>"true","description"=>"Receiving data from the input `nama_barang`");
				$rest_api["args"]["foto_barang"] = array("required"=>"true","description"=>"Receiving data from the input `foto_barang`");
				$rest_api["args"]["kondisi_posisi"] = array("required"=>"true","description"=>"Receiving data from the input `kondisi_posisi`");
				if(!isset($_POST["nama_barang"])){
					$_POST["nama_barang"]="";
				}
				if(!isset($_POST["foto_barang"])){
					$_POST["foto_barang"]="";
				}
				if(!isset($_POST["kondisi_posisi"])){
					$_POST["kondisi_posisi"]="";
				}
				$rest_api["message"] = "Please! complete the form provided.";
				$rest_api["title"] = "Notice!";
				if(($_POST["nama_barang"] != "") || ($_POST["foto_barang"] != "") || ($_POST["kondisi_posisi"] != "")){
					// avoid undefined
					$input["nama_barang"] = "";
					$input["foto_barang"] = "";
					$input["kondisi_posisi"] = "";
					// variable post
					if(isset($_POST["nama_barang"])){
						$input["nama_barang"] = $mysql->escape_string($_POST["nama_barang"]);
					}

					$invalid_file = true;
					if(isset($_POST["foto_barang"])){
						if(!is_dir("media/image/")){
							mkdir("media/image/",0777,true);
						}
						if(!is_dir("media/media/")){
							mkdir("media/media/",0777,true);
						}
						if(!is_dir("media/file/")){
							mkdir("media/file/",0777,true);
						}
						foreach($config["image_allowed"] as $image_allowed){// whitelist mimetype
							$mimetype_image_allowed[] = $image_allowed["mimetype"];// create list
						}
						$parse_file = explode(";",substr($_POST["foto_barang"],5,strlen($_POST["foto_barang"])));// parsing file
						$file_foto_barang = base64_decode(str_replace("base64,","",$parse_file[1]));
						if(in_array(strtolower($parse_file[0]),$mimetype_image_allowed)){// whitelist image
							$ext = "tmp";
							foreach($config["image_allowed"] as $image_allowed){// searching extention
								if(strtolower($parse_file[0])==$image_allowed["mimetype"]){// filter
									$invalid_file = false;
									$ext = $image_allowed["ext"];
									$file_name = "foto_barang-" . sha1($file_foto_barang).".".$ext;
									file_put_contents("media/image/".$file_name,$file_foto_barang);
									$input["foto_barang"] = $main_url ."/media/image/".  $mysql->escape_string($file_name);
								}
							}
						}else{// whitelist files
							$invalid_file = true;
							$ext = "tmp";
							foreach($config["file_allowed"] as $file_allowed){
								if(strtolower($parse_file[0])==$file_allowed["mimetype"]){
									$invalid_file = false;
									$ext = $file_allowed["ext"];
									$file_name = "foto_barang-" . sha1($file_foto_barang).".".$ext;
									file_put_contents("media/file/".$file_name,$file_foto_barang);
									$input["foto_barang"] = $main_url ."/media/image/".  $mysql->escape_string($file_name);
								}
							}
						}
					}
					if(isset($_POST["kondisi_posisi"])){
						$input["kondisi_posisi"] = $mysql->escape_string($_POST["kondisi_posisi"]);
					}

					$sql_query = "INSERT INTO `list_barang` (`nama_barang`,`foto_barang`,`kondisi_posisi`) VALUES ('".$input["nama_barang"]."','".$input["foto_barang"]."','".$input["kondisi_posisi"]."' )";
					if($invalid_file ==false){
						if($query = $mysql->query($sql_query)){
							$rest_api["message"] = "Your request has been sent.";
							$rest_api["title"] = "Successfully";
						}else{
							$rest_api["message"] = "Form input and SQL Column do not match.";
							$rest_api["title"] = "Fatal Error!";
						}
					}else{
							$rest_api["message"] = "Please upload valid file";
							$rest_api["title"] = "File invalid!";
					}
				}else{
					$rest_api["message"] = "Please! complete the form provided.";
					$rest_api["title"] = "Notice!";
				}

				break;

			// TODO: -+----+- list_barang
			case "list_barang":


				$rest_api["auth"]["basic"] = false;

				$rest_api["args"]["nama_barang"] = array("required"=>"true","description"=>"Receiving data from the input `nama_barang`");
				$rest_api["args"]["foto_barang"] = array("required"=>"true","description"=>"Receiving data from the input `foto_barang`");
				$rest_api["args"]["kondisi_posisi"] = array("required"=>"true","description"=>"Receiving data from the input `kondisi_posisi`");
				if(!isset($_POST["nama_barang"])){
					$_POST["nama_barang"]="";
				}
				if(!isset($_POST["foto_barang"])){
					$_POST["foto_barang"]="";
				}
				if(!isset($_POST["kondisi_posisi"])){
					$_POST["kondisi_posisi"]="";
				}
				$rest_api["message"] = "Please! complete the form provided.";
				$rest_api["title"] = "Notice!";
				if(($_POST["nama_barang"] != "") || ($_POST["foto_barang"] != "") || ($_POST["kondisi_posisi"] != "")){
					// avoid undefined
					$input["nama_barang"] = "";
					$input["foto_barang"] = "";
					$input["kondisi_posisi"] = "";
					// variable post
					if(isset($_POST["nama_barang"])){
						$input["nama_barang"] = $mysql->escape_string($_POST["nama_barang"]);
					}

					$invalid_file = true;
					if(isset($_POST["foto_barang"])){
						if(!is_dir("media/image/")){
							mkdir("media/image/",0777,true);
						}
						if(!is_dir("media/media/")){
							mkdir("media/media/",0777,true);
						}
						if(!is_dir("media/file/")){
							mkdir("media/file/",0777,true);
						}
						foreach($config["image_allowed"] as $image_allowed){// whitelist mimetype
							$mimetype_image_allowed[] = $image_allowed["mimetype"];// create list
						}
						$parse_file = explode(";",substr($_POST["foto_barang"],5,strlen($_POST["foto_barang"])));// parsing file
						$file_foto_barang = base64_decode(str_replace("base64,","",$parse_file[1]));
						if(in_array(strtolower($parse_file[0]),$mimetype_image_allowed)){// whitelist image
							$ext = "tmp";
							foreach($config["image_allowed"] as $image_allowed){// searching extention
								if(strtolower($parse_file[0])==$image_allowed["mimetype"]){// filter
									$invalid_file = false;
									$ext = $image_allowed["ext"];
									$file_name = "foto_barang-" . sha1($file_foto_barang).".".$ext;
									file_put_contents("media/image/".$file_name,$file_foto_barang);
									$input["foto_barang"] = $main_url ."/media/image/".  $mysql->escape_string($file_name);
								}
							}
						}else{// whitelist files
							$invalid_file = true;
							$ext = "tmp";
							foreach($config["file_allowed"] as $file_allowed){
								if(strtolower($parse_file[0])==$file_allowed["mimetype"]){
									$invalid_file = false;
									$ext = $file_allowed["ext"];
									$file_name = "foto_barang-" . sha1($file_foto_barang).".".$ext;
									file_put_contents("media/file/".$file_name,$file_foto_barang);
									$input["foto_barang"] = $main_url ."/media/image/".  $mysql->escape_string($file_name);
								}
							}
						}
					}
					if(isset($_POST["kondisi_posisi"])){
						$input["kondisi_posisi"] = $mysql->escape_string($_POST["kondisi_posisi"]);
					}

					$sql_query = "INSERT INTO `list_barang` (`nama_barang`,`foto_barang`,`kondisi_posisi`) VALUES ('".$input["nama_barang"]."','".$input["foto_barang"]."','".$input["kondisi_posisi"]."' )";
					if($invalid_file ==false){
						if($query = $mysql->query($sql_query)){
							$rest_api["message"] = "Your request has been sent.";
							$rest_api["title"] = "Successfully";
						}else{
							$rest_api["message"] = "Form input and SQL Column do not match.";
							$rest_api["title"] = "Fatal Error!";
						}
					}else{
							$rest_api["message"] = "Please upload valid file";
							$rest_api["title"] = "File invalid!";
					}
				}else{
					$rest_api["message"] = "Please! complete the form provided.";
					$rest_api["title"] = "Notice!";
				}

				break;

		}


	break;

}


header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: GET,PUT,POST,DELETE,PATCH,OPTIONS');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization,X-Authorization');
if (!isset($_GET["callback"])){
	header('Content-type: application/json');
	if(defined("JSON_UNESCAPED_UNICODE")){
		echo json_encode($rest_api,JSON_UNESCAPED_UNICODE);
	}else{
		echo json_encode($rest_api);
	}

}else{
	if(defined("JSON_UNESCAPED_UNICODE")){
		echo strip_tags($_GET["callback"]) ."(". json_encode($rest_api,JSON_UNESCAPED_UNICODE). ");" ;
	}else{
		echo strip_tags($_GET["callback"]) ."(". json_encode($rest_api) . ");" ;
	}

}