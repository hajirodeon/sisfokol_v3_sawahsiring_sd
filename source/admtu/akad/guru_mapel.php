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
$filenya = "guru_mapel.php";
$judul = "Guru per Mata Pelajaran";
$judulku = "[$tu_session : $nip5_session.$nm5_session] ==> $judul";
$judulx = $judul;
$tapelkd = nosql($_REQUEST['tapelkd']);
$pelkd = nosql($_REQUEST['pelkd']);
$s = nosql($_REQUEST['s']);
$ke = "$filenya?tapelkd=$tapelkd&pelkd=$pelkd";



//focus...
if (empty($tapelkd))
	{
	$diload = "document.formx.tapel.focus();";
	}
else if (empty($pelkd))
	{
	$diload = "document.formx.progdi.focus();";
	}






//PROSES ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//jika simpan
if ($_POST['btnSMP'])
	{
	//nilai
	$tapelkd = nosql($_POST['tapelkd']);
	$kelkd = nosql($_POST['kelas']);
	$rukd = nosql($_POST['ruang']);
	$pelkd = nosql($_POST['pelkd']);
	$gurkd = nosql($_POST['gurkd']);

	//nek null
	if ((empty($gurkd)) OR (empty($kelkd)) OR (empty($rukd)))
		{
		//re-direct
		$pesan = "Input Tidak Lengkap. Harap Diulangi...!!";
		pekem($pesan,$ke);
		exit();
		}
	else
		{
		//cek
		$qc = mysql_query("SELECT m_guru_mapel.*, m_guru.*, m_pegawai.* ".
					"FROM m_guru_mapel, m_guru, m_pegawai ".
					"WHERE m_guru_mapel.kd_guru = m_guru.kd ".
					"AND m_guru.kd_pegawai = m_pegawai.kd ".
					"AND m_guru.kd_tapel = '$tapelkd' ".
					"AND m_guru.kd_kelas = '$kelkd' ".
					"AND m_guru_mapel.kd_ruang = '$rukd' ".
					"AND m_guru_mapel.kd_mapel = '$pelkd' ".
					"AND m_guru_mapel.kd_guru = '$gurkd'");
		$rc = mysql_fetch_assoc($qc);
		$tc = mysql_num_rows($qc);
		$c_nip = nosql($rc['nip']);
		$c_nama = balikin2($rc['nama']);


		//nek ada, msg
		if ($tc != 0)
			{
			//re-direct
			$pesan = "Mata Pelajaran dengan Guru : [$c_nip].$c_nama, SUDAH ADA. SILAHKAN GANTI...!";
			pekem($pesan,$ke);
			exit();
			}
		else
			{
			//query
			mysql_query("INSERT INTO m_guru_mapel(kd, kd_guru, kd_ruang, kd_mapel) VALUES ".
					"('$x', '$gurkd', '$rukd', '$pelkd')");

			//re-direct
			xloc($ke);
			exit();
			}
		}
	}



//jika hapus
if ($s == "hapus")
	{
	//nilai
	$tapelkd = nosql($_REQUEST['tapelkd']);
	$keakd = nosql($_REQUEST['keakd']);
	$mgkd = nosql($_REQUEST['mgkd']);
	$pkd = nosql($_REQUEST['pkd']);

	//query
	mysql_query("DELETE FROM m_guru_mapel ".
			"WHERE kd_guru = '$mgkd' ".
			"AND kd_mapel = '$pkd'");

	//re-direct
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

//VIEW //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo '<form name="formx" method="post" action="'.$ke.'">
<table bgcolor="'.$warnaover.'" width="100%" border="0" cellspacing="0" cellpadding="3">
<tr>
<td>
Tahun Pelajaran : ';
echo "<select name=\"tapel\" onChange=\"MM_jumpMenu('self',this,0)\">";

//terpilih
$qtpx = mysql_query("SELECT * FROM m_tapel ".
						"WHERE kd = '$tapelkd'");
$rowtpx = mysql_fetch_assoc($qtpx);
$tpx_kd = nosql($rowtpx['kd']);
$tpx_thn1 = nosql($rowtpx['tahun1']);
$tpx_thn2 = nosql($rowtpx['tahun2']);

echo '<option value="'.$tpx_kd.'">'.$tpx_thn1.'/'.$tpx_thn2.'</option>';

$qtp = mysql_query("SELECT * FROM m_tapel ".
						"WHERE kd <> '$tapelkd' ".
						"ORDER BY tahun1 ASC");
$rowtp = mysql_fetch_assoc($qtp);

do
	{
	$tpkd = nosql($rowtp['kd']);
	$tpth1 = nosql($rowtp['tahun1']);
	$tpth2 = nosql($rowtp['tahun2']);

	echo '<option value="'.$filenya.'?tapelkd='.$tpkd.'">'.$tpth1.'/'.$tpth2.'</option>';
	}
while ($rowtp = mysql_fetch_assoc($qtp));

echo '</select>
</td>
</tr>
</table>

<table bgcolor="'.$warna02.'" width="100%" border="0" cellspacing="0" cellpadding="3">
<tr>
Mata Pelajaran : ';
echo "<select name=\"progdi\" onChange=\"MM_jumpMenu('self',this,0)\">";
//terpilih
$qjnx = mysql_query("SELECT * FROM m_mapel ".
			"WHERE kd = '$pelkd'");
$rowjnx = mysql_fetch_assoc($qjnx);
$jnx_kd = nosql($rowjnx['kd']);
$jnx_pel = balikin($rowjnx['xpel']);

echo '<option value="'.$jnx_kd.'" selected>'.$jnx_pel.'</option>';

//daftar mapel
$qbs = mysql_query("SELECT DISTINCT(m_mapel.kd) AS mmkd ".
			"FROM m_mapel_kelas, m_mapel ".
			"WHERE m_mapel_kelas.kd_mapel = m_mapel.kd ".
			"AND m_mapel.kd <> '$pelkd' ".
			"ORDER BY round(m_mapel.no, m_mapel.no_sub) ASC");
$rbs = mysql_fetch_assoc($qbs);

do
	{
	$bskd = nosql($rbs['mmkd']);

	//detail
	$qprodi = mysql_query("SELECT * FROM m_mapel ".
				"WHERE kd = '$bskd'");
	$rprodi = mysql_fetch_assoc($qprodi);
	$bspel = balikin2($rprodi['xpel']);

	echo '<option value="'.$filenya.'?tapelkd='.$tapelkd.'&pelkd='.$bskd.'">'.$bspel.'</option>';
	}
while ($rbs = mysql_fetch_assoc($qbs));

echo '</select>

</td>
</tr>
</table>
<br>';

//nilai
$keakd = nosql($_REQUEST['keakd']);
$pelkd = nosql($_REQUEST['pelkd']);


//nek blm
if (empty($tapelkd))
	{
	echo '<strong><font color="#FF0000">TAHUN PELAJARAN Belum Dipilih...!</font></strong>';
	}
else if (empty($pelkd))
	{
	echo '<strong><font color="#FF0000">MATA PELAJARAN Belum Dipilih...!</font></strong>';
	}
else
	{
	echo '<select name="gurkd">
	<option value="" selected>-GURU-</option>';

	//daftar guru
	$qg = mysql_query("SELECT DISTINCT(m_pegawai.kd) AS mpkd ".
				"FROM m_guru, m_pegawai ".
				"WHERE m_guru.kd_pegawai = m_pegawai.kd ".
				"AND m_guru.kd_tapel = '$tapelkd' ".
				"ORDER BY round(m_pegawai.nip) ASC");
	$rg = mysql_fetch_assoc($qg);

	do
		{
		$x_mpkd = nosql($rg['mpkd']);

		//detail
		$qpgw = mysql_query("SELECT m_pegawai.*, m_guru.*, ".
					"m_guru.kd AS mgkd ".
					"FROM m_pegawai, m_guru ".
					"WHERE m_guru.kd_pegawai = m_pegawai.kd ".
					"AND m_guru.kd_tapel = '$tapelkd' ".
					"AND m_pegawai.kd = '$x_mpkd'");
		$rpgw = mysql_fetch_assoc($qpgw);
		$tpwg = mysql_num_rows($qpgw);
		$i_mgkd = nosql($rpgw['mgkd']);
		$i_nip = nosql($rpgw['nip']);
		$i_gnam = balikin2($rpgw['nama']);


		echo '<option value="'.$i_mgkd.'">'.$i_nip.'. '.$i_gnam.'</option>';
		}
	while ($rg = mysql_fetch_assoc($qg));

	echo '</select>,
	<select name="kelas">
	<option value="">-Kelas-</option>';

	$qbt = mysql_query("SELECT * FROM m_kelas ".
				"ORDER BY round(no) ASC");
	$rowbt = mysql_fetch_assoc($qbt);

	do
		{
		$btkd = nosql($rowbt['kd']);
		$btkelas = nosql($rowbt['kelas']);

		echo '<option value="'.$btkd.'">'.$btkelas.'</option>';
		}
	while ($rowbt = mysql_fetch_assoc($qbt));

	echo '</select>,
	<select name="ruang">';
	echo '<option value="">-Ruang-</option>';
	$qru = mysql_query("SELECT * FROM m_ruang ".
				"ORDER BY ruang ASC");
	$rowru = mysql_fetch_assoc($qru);

	do
		{
		$ru_kd = nosql($rowru['kd']);
		$ru_ru = balikin($rowru['ruang']);

		echo '<option value="'.$ru_kd.'">'.$ru_ru.'</option>';
		}
	while ($rowru = mysql_fetch_assoc($qru));

	echo '</select>
	<input name="tapelkd" type="hidden" value="'.nosql($_REQUEST['tapelkd']).'">
	<input name="pelkd" type="hidden" value="'.nosql($_REQUEST['pelkd']).'">
	<input name="btnSMP" type="submit" value="SIMPAN"></p>';


	//query
	$q = mysql_query("SELECT DISTINCT(m_pegawai.kd) AS mpkd ".
				"FROM m_pegawai, m_guru, m_guru_mapel ".
				"WHERE m_guru.kd_pegawai = m_pegawai.kd ".
				"AND m_guru_mapel.kd_guru = m_guru.kd ".
				"AND m_guru.kd_tapel = '$tapelkd' ".
				"AND m_guru_mapel.kd_mapel = '$pelkd'");
	$row = mysql_fetch_assoc($q);
	$total = mysql_num_rows($q);

	if ($total != 0)
		{
		echo '<table width="600" border="1" cellpadding="3" cellspacing="0">
		<tr bgcolor="'.$warnaheader.'">
		<td width="5" valign="top"><strong>No.</strong></td>
		<td width="1" valign="top">&nbsp;</td>
	    	<td valign="top"><strong><font color="'.$warnatext.'">Guru</font></strong></td>
		<td width="200" valign="top"><strong><font color="'.$warnatext.'">Kelas Ruang</font></strong></td>
	    	</tr>';

		do {
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

			$i_nomer = $i_nomer + 1;
			$i_mpkd = nosql($row['mpkd']);


			//detail
			$qpgw = mysql_query("SELECT m_pegawai.*, m_guru.*, m_guru_mapel.*, ".
						"m_guru_mapel.kd_guru AS mgkd, ".
						"m_guru_mapel.kd_mapel AS pkd ".
						"FROM m_pegawai, m_guru, m_guru_mapel ".
						"WHERE m_guru.kd_pegawai = m_pegawai.kd ".
						"AND m_guru_mapel.kd_guru = m_guru.kd ".
						"AND m_guru.kd_tapel = '$tapelkd' ".
						"AND m_guru_mapel.kd_mapel = '$pelkd' ".
						"AND m_pegawai.kd = '$i_mpkd'");
			$rpgw = mysql_fetch_assoc($qpgw);
			$tpwg = mysql_num_rows($qpgw);
			$i_mgkd = nosql($rpgw['mgkd']);
			$i_pkd = nosql($rpgw['pkd']);
			$i_gnam = balikin2($rpgw['nama']);


			echo "<tr bgcolor=\"$warna\" onmouseover=\"this.bgColor='$warnaover';\" onmouseout=\"this.bgColor='$warna';\">";
			echo '<td width="5">
			'.$i_nomer.'.
			</td>
			<td width="1">
			<a href="'.$ke.'&s=hapus&mgkd='.$i_mgkd.'&pkd='.$i_pkd.'" title="HAPUS --> '.$i_gnam.'"><img src="'.$sumber.'/img/delete.gif" width="16" height="16" border="0"></a>
			</td>
			<td>';

			//nek null
			if (empty($i_mgkd))
				{
				echo "-";
				}
			else
				{
				echo $i_gnam;
				}


			echo '</td>
			<td>';
			//ruang
			$qru1 = mysql_query("SELECT m_pegawai.*, m_guru.*, m_guru.kd AS mgkd, ".
						"m_guru_mapel.* ".
						"FROM m_pegawai, m_guru, m_guru_mapel ".
						"WHERE m_guru.kd_pegawai = m_pegawai.kd ".
						"AND m_guru_mapel.kd_guru = m_guru.kd ".
						"AND m_guru.kd_tapel = '$tapelkd' ".
						"AND m_pegawai.kd = '$i_mpkd' ".
						"AND m_guru_mapel.kd_mapel = '$pelkd'");
			$rru1 = mysql_fetch_assoc($qru1);
			$tru1 = mysql_num_rows($qru1);


			do
				{
				$ru1_rukd = nosql($rru1['kd_ruang']);
				$ru1_kelkd = nosql($rru1['kd_kelas']);

				//detail kelas
				$qkelx = mysql_query("SELECT * FROM m_kelas ".
							"WHERE kd = '$ru1_kelkd'");
				$rkelx = mysql_fetch_assoc($qkelx);
				$tkelx = mysql_num_rows($qkelx);
				$kelx_kelas = nosql($rkelx['kelas']);

				//detail ruang
				$qkelx2 = mysql_query("SELECT * FROM m_ruang ".
							"WHERE kd = '$ru1_rukd'");
				$rkelx2 = mysql_fetch_assoc($qkelx2);
				$tkelx2 = mysql_num_rows($qkelx2);
				$kelx2_ruang = nosql($rkelx2['ruang']);

				echo ''.$kelx_kelas.'/'.$kelx2_ruang.', ';
				}
			while ($rru1 = mysql_fetch_assoc($qru1));

			echo '</td>
			</tr>';
			}
		while ($row = mysql_fetch_assoc($q));

		echo '</table>
		<table width="600" border="0" cellspacing="0" cellpadding="3">
	    	<tr>
	    	<td align="right">Total : <strong><font color="#FF0000">'.$total.'</font></strong> Data.</td>
	    	</tr>
	  	</table>';
		}

	else
		{
		echo '<p>
		<b>
		<font color="red">
		TIDAK ADA DATA
		</font>
		</b>.
		</p>';
		}
	}

echo '</form>
<br>
<br>
<br>';
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//isi
$isi = ob_get_contents();
ob_end_clean();


require("../../inc/niltpl.php");
?>