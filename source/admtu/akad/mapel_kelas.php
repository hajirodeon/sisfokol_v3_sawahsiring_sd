<?php
///////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////
/////// SISFOKOL_SD_v3.0_(sawah_siring)_FREE          ///////
/////// (Sistem Informasi Sekolah untuk SD)        ///////
///////////////////////////////////////////////////////////
/////// Dibuat oleh : 								///////
/////// Agus Muhajir, S.Kom 						///////
/////// URL 	: http://sisfokol.wordpress.com 	///////
/////// E-Mail	: 									///////
///////		* hajirodeon@yahoo.com 					///////
///////		* hajirodeon@gmail.com					///////
/////// HP/SMS	: 081-829-88-54 					///////
///////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////



session_start();

require("../../inc/config.php");
require("../../inc/fungsi.php");
require("../../inc/koneksi.php");
require("../../inc/cek/admtu.php");
$tpl = LoadTpl("../../template/index.html");

nocache;

//nilai
$filenya = "mapel_kelas.php";
$judul = "Mata Pelajaran Per Kelas";
$judulku = "[$tu_session : $nip5_session. $nm5_session] ==> $judul";
$judulx = $judul;
$kelkd = nosql($_REQUEST['kelkd']);
$ke = "$filenya?kelkd=$kelkd";



//focus...
if (empty($kelkd))
	{
	$diload = "document.formx.kelas.focus();";
	}






//PROSES ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//jika simpan
if ($_POST['btnSMP'])
	{
	//nilai
	$kelkd = nosql($_POST['kelkd']);
	$mapel = nosql($_POST['mapel']);

	//jika null
	if (empty($mapel))
		{
		//diskonek
		xfree($qbw);
		xclose($koneksi);

		//re-direct
		$pesan = "Input Tidak Lengkap. Harap Diulangi...!!";
		pekem($pesan,$ke);
		exit();
		}
	else
		{
		//cek
		$qcc = mysql_query("SELECT m_mapel_kelas.*, m_mapel.* ".
								"FROM m_mapel_kelas, m_mapel ".
								"WHERE m_mapel_kelas.kd_mapel = m_mapel.kd ".
								"AND m_mapel_kelas.kd_kelas = '$kelkd' ".
								"AND m_mapel_kelas.kd_mapel = '$mapel'");
		$rcc = mysql_fetch_assoc($qcc);
		$tcc = mysql_num_rows($qcc);
		$pel = balikin2($rcc['pel']);

		//not null
		if ($tcc != 0)
			{
			//re-direct
			$pesan = "Mata Pelajaran Sudah Ada. Silahkan Ganti Yang Lain...!!";
			pekem($pesan,$ke);
			exit();
			}
		else
			{
			//query
			mysql_query("INSERT INTO m_mapel_kelas(kd, kd_kelas, kd_mapel) VALUES ".
							"('$x', '$kelkd', '$mapel')");

			//diskonek
			xfree($qbw);
			xclose($koneksi);

			//re-direct
			xloc($ke);
			exit();
			}
		}
	}


//jika hapus
if ($_POST['btnHPS'])
	{
	//ambil nilai
	$jml = nosql($_POST['jml']);
	$kelkd = nosql($_POST['kelkd']);


	//ambil semua
	for ($i=1; $i<=$jml;$i++)
		{
		//ambil nilai
		$yuk = "item";
		$yuhu = "$yuk$i";
		$kd = nosql($_POST["$yuhu"]);

		//del
		mysql_query("DELETE FROM m_mapel_kelas ".
						"WHERE kd = '$kd'");
		}

	//diskonek
	xfree($qbw);
	xclose($koneksi);

	//auto-kembali
	xloc($ke);
	exit();
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



//isi *START
ob_start();

//js
require("../../inc/js/jumpmenu.js");
require("../../inc/js/checkall.js");
require("../../inc/js/swap.js");
require("../../inc/menu/admtu.php");
xheadline($judul);

//view //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo '<form action="'.$filenya.'" method="post" name="formx">
<table bgcolor="'.$warnaover.'" width="100%" border="0" cellspacing="0" cellpadding="3">
<tr>
<td>
Kelas : ';
echo "<select name=\"kelas\" onChange=\"MM_jumpMenu('self',this,0)\">";

//terpilih
$qbtx = mysql_query("SELECT * FROM m_kelas ".
						"WHERE kd = '$kelkd'");
$rowbtx = mysql_fetch_assoc($qbtx);

$btxkd = nosql($rowbtx['kd']);
$btxkelas = nosql($rowbtx['kelas']);

echo '<option value="'.$btxkd.'">'.$btxkelas.'</option>';

//kelas
$qbt = mysql_query("SELECT * FROM m_kelas ".
						"WHERE kd <> '$kelkd' ".
						"ORDER BY no ASC");
$rowbt = mysql_fetch_assoc($qbt);

do
	{
	$btkd = nosql($rowbt['kd']);
	$btkelas = nosql($rowbt['kelas']);

	echo '<option value="'.$filenya.'?kelkd='.$btkd.'">'.$btkelas.'</option>';
	}
while ($rowbt = mysql_fetch_assoc($qbt));

echo '</select>

<input name="kelkd" type="hidden" value="'.$kelkd.'">
</td>
</tr>
</table>
<br>';


//nek blm
if (empty($kelkd))
	{
	echo '<strong><font color="#FF0000">KELAS Belum Dipilih...!</font></strong>';
	}
else
	{
	//query
	$q = mysql_query("SELECT m_mapel_kelas.*, m_mapel_kelas.kd AS mmkd, ".
						"m_mapel.* ".
						"FROM m_mapel_kelas, m_mapel ".
						"WHERE m_mapel_kelas.kd_mapel = m_mapel.kd ".
						"AND m_mapel_kelas.kd_kelas = '$kelkd' ".
						"ORDER BY round(m_mapel.no, m_mapel.no_sub) ASC");
	$row = mysql_fetch_assoc($q);
	$total = mysql_num_rows($q);

	echo '<select name="mapel">
    	<option value="" selected>-TAMBAH MATA PELAJARAN-</option>';

	//mapel
	$qsp = mysql_query("SELECT * FROM m_mapel ".
							"ORDER BY round(no) ASC, ".
							"round(no_sub) ASC");
	$rowsp = mysql_fetch_assoc($qsp);

	do
		{
		$i_spkd = nosql($rowsp['kd']);
		$i_spaspek = balikin2($rowsp['pel']);
		$i_mulo = nosql($rowsp['mulo']);

		//jika muatan lokal
		if ($i_mulo == "true")
			{
			echo '<option value="'.$i_spkd.'">Muatan Lokal --> '.$i_spaspek.'</option>';
			}
		else
			{
			echo '<option value="'.$i_spkd.'">'.$i_spaspek.'</option>';
			}
        }
	while ($rowsp = mysql_fetch_assoc($qsp));

	echo '</select>
	<input name="btnSMP" type="submit" value="&gt;&gt;&gt;">';

	if ($total != 0)
		{
		echo '<table width="500" border="1" cellpadding="3" cellspacing="0">
		<tr valign="top" bgcolor="'.$warnaheader.'">
		<td>&nbsp;</td>
		<td><strong><font color="'.$warnatext.'">Nama Mata Pelajaran</font></strong></td>
	    </tr>';

		do
			{
			if ($warna_set ==0)
				{
				$warna = $warna01;
				$warna_set = 1;
				}
			else
				{
				$warna = $warna02;
				$warna_set = 0;
				}

			$nomer = $nomer + 1;
			$x_mmkd = nosql($row['mmkd']);
			$x_pel = balikin2($row['pel']);
			$x_mulo = nosql($row['mulo']);


			//jika muatan lokal
			if ($x_mulo == "true")
				{
				$x_pelx = "Muatan Lokal --> $x_pel";
				}
			else
				{
				$x_pelx = $x_pel;
				}


			echo "<tr valign=\"top\" bgcolor=\"$warna\" onmouseover=\"this.bgColor='$warnaover';\" onmouseout=\"this.bgColor='$warna';\">";
			echo '<td width="20">
			<input type="checkbox" name="item'.$nomer.'" value="'.$x_mmkd.'">
			</td>
			<td>'.$x_pelx.'</td>
			</tr>';
			}
		while ($row = mysql_fetch_assoc($q));

		echo '</table>
		<table width="500" border="0" cellspacing="0" cellpadding="3">
		<tr>
		<td width="326">
		<input name="btnALL" type="button" value="SEMUA" onClick="checkAll('.$total.')">
		<input name="btnBTL" type="reset" value="BATAL">
		<input name="btnHPS" type="submit" value="HAPUS">
		<input name="jml" type="hidden" value="'.$total.'">
		<input name="kelkd" type="hidden" value="'.$kelkd.'">
		</td>
		<td align="right">Total : <strong><font color="#FF0000">'.$total.'</font></strong> Data.</td>
		</tr>
		</table>';
		}
	else
		{
		echo '<p>
		<font color="red">
		<strong>TIDAK ADA DATA. Silahkan Entry Dahulu...!!</strong>
		</font>
		</p>';
		}
	}


echo '</form>';
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//isi
$isi = ob_get_contents();
ob_end_clean();


require("../../inc/niltpl.php");



//diskonek
xfree($qbw);
xclose($koneksi);
exit();
?>