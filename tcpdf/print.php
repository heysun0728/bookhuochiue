<?php
session_start(); 

require_once('tcpdf.php');

// --------收集資料--------------- //
$school='';
$name='';
$date='';
$time=0;
$year='';
$month='';
$day='';
$vNumber='';
if(isset($_POST["confirm_btn"])){
	$vNumber=$_POST["confirm_btn"];
    get_info($vNumber);
    get_today();
    ChangeState($vNumber);
    make_pdf();
    
}
function get_info($vNumber){
	require "../DbConnect.php";
	global $school,$name,$date,$time;
    $sql="SELECT m.*,sch.schoolName,
                 SUM(s.ServiceHour) as sHours,
                 MIN(s.StartTime) as StartTime,
                 MAX(s.StartTime) as EndTime
          FROM member m,servicerecord s,school sch
          WHERE m.vNumber=:vNumber
          AND m.vNumber=s.vNumber
          AND s.ReserverState=:ReserverState
          AND m.School=sch.schoolid
          GROUP BY m.vNumber";
    $rs=$link->prepare($sql);
    $rs->bindValue(':ReserverState',"申請");
    $rs->bindValue(':vNumber',$vNumber);
    if($rs->execute()){
        $row=$rs->fetchall();
        $school=$row[0]["schoolName"];
        $name=$row[0]["Name"];
        $time=$row[0]["sHours"];
        //計算幾號到幾號
        $s=substr($row[0]["StartTime"],0,10);
        $e=substr($row[0]["EndTime"],0,10);
        //年月日放進陣列
        $s_date=explode("-",$s);
        $year=(int)$s_date[0]-1911;//民國
        $date="民國".$year."年".(int)$s_date[1]."月".(int)$s_date[2]."日";
        if($s!=$e){
            $e_date=explode("-",$e);
            $date.="至";
            if($s_date[0]!=$e_date[0]){//若間隔兩年不同年
                $year=(int)$e_date[0]-1911;
                $date.="民國".$year."年";
            }
            $date.=(int)$e_date[1]."月".(int)$e_date[2]."日";
        }
    }//取得申請人的相關資料 //取得申請人相關資料
}
function get_today(){//取得目前年月日
    global $year,$month,$day;//使用全域變數
    $today = getdate();
    date("Y/m/d H:i");  //日期格式化
    $year=$today["year"]; //年 
    $month=$today["mon"]; //月
    $day=$today["mday"];  //日
}

function make_pdf(){
	global $year,$month,$day,$school,$name,$date,$time,$vNumber;
    
    // ---------pdf初始設定------------ //
    //把舊有資料清掉
    ob_end_clean();

    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    //頁首頁尾控制
    $pdf->setPrintHeader(false); //不要頁首
    $pdf->setPrintFooter(false); //不要頁尾

    //設定邊界(使用預設)PDF_MARGIN_LEFTPDF_MARGIN_RIGHT
    $pdf->SetMargins(30, PDF_MARGIN_TOP,30 );

    //自動分頁設定
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    //設定語言相關字串
    if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	   require_once(dirname(__FILE__).'/lang/eng.php');
	   $pdf->setLanguageArray($l);
    }

    //設定好字體
    $kaiu = TCPDF_FONTS::addTTFfont('C:\Windows\Fonts\kaiu.ttf', 'TrueTypeUnicode', '', 32);

    // ---------正文------------ //

    // 新增頁面
    $pdf->AddPage();

    //標題
    $pdf->SetFont('kaiu', '', 32);
    $txt = '臺中市大墩文化中心圖書館';
    $pdf->Write(0, $txt, '', 0, 'C', true, 0, false, false, 0);
    $pdf->ln(7);//換行

    //二標題
    $pdf->SetFont('kaiu', '', 28);
    $txt = '學生志工服務證明';
    $pdf->Write(0, $txt, '', 0, 'C', true, 0, false, false, 0);
    $pdf->ln(7);//換行

    //因為要條行距改用html表示法
    //內容
    $pdf->SetFont('kaiu', '', 24);
    $html ='<div id="context" style="line-height:50px;font-size=24px;">'.$school.$name.'同學，於'.$date.'擔任圖書館志工，總計服務'.$time.'小時，熱心公益，積極奉獻，特此證明。</div>';
    $pdf->writeHTML($html, true, false, true, false, '');

    //下面日期標
    $pdf->SetXY(30.0,200.0);//設定xy位置
    $pdf->SetFont('kaiu', '', 28);
    $txt = "中華民國 ".((int)$year-1911)." 年 ".$month." 月 ".$day." 日";
    $pdf->Write(0, $txt, '', 0, 'C', true, 0, false, false, 0);

    //輸出
    $outputname="vNumber_".$vNumber.".pdf";
    $pdf->Output($outputname, 'I');//製作pdf檔案 //製作pdf檔
}

function ChangeState($vNumber){//將那人的狀態改為完成
    require "../DbConnect.php";
    $sql="UPDATE servicerecord
          SET ReserverState=:NewReserverState
          WHERE vNumber=:vNumber
          AND ReserverState=:ReserverState";
    $rs=$link->prepare($sql);
    $rs->bindValue(':ReserverState',"申請");
    $rs->bindValue(':NewReserverState',"完成");
    $rs->bindValue(':vNumber',$vNumber);
    $rs->execute();
}
?>
