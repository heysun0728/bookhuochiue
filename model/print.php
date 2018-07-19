<?php

require('../fpdf16/font/timesb.php');
require('../fpdf16/chinese-unicode.php');

ob_end_clean();
/*
$pdf = new FPDF();
$pdf->AddPage();

//$pdf->AddUniCNShwFont('uni'); 
//$pdf->SetFont('uni','',20); 

$pdf->SetFont('times','B',16);

$pdf->Cell(210,10,"abc",0,1,'C');
$pdf->Output();*/

class p extends PDF_Unicode
{
	
}

$pdf=new p();

$pdf->Open(); 
$pdf->AddPage(); 

$pdf->AddUniCNShwFont('uni'); 
$pdf->SetFont('uni','',20); 

$pdf->Write(10, "1234學生名字\n伃綉堃亘");
$pdf->Ln();
$pdf->MultiCell (120, 10, "服\n務\n單\n位");
$pdf->Cell (240, 10, "本文用UTF8做為中文字編碼, 在這裡還是呼叫同樣的FPDF函數");
$pdf->Ln();

$pdf->Output();


?>