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

//ambil nilai
require("../inc/config.php");
require("../inc/fungsi.php");
require("../inc/koneksi.php");
require("../inc/cek/admgr.php");
$tpl = LoadTpl("../template/index.html");

nocache;

//nilai
$filenya = "index.php";
$judul = "Daftar Mata Pelajaran";
$judulku = "[$guru_session : $nip1_session.$nm1_session] ==> $judul";
$juduli = $judul;




//isi *START
ob_start();

//js
require("../inc/js/swap.js");
require("../inc/menu/admgr.php");
xheadline($judul);

//view //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo '<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="3">
<tr valign="top">
<td valign="top">';


//data ne
$qdty = mysql_query("SELECT m_pegawai.*, m_guru.*, m_guru_mapel.*, m_guru_mapel.kd AS mgkd, ".
						"m_mapel.*, m_mapel.kd AS mpkd, ".
						"m_ruang.*, m_ruang.kd AS mrkd ".
						"FROM m_pegawai, m_guru, m_guru_mapel, m_mapel, m_ruang ".
						"WHERE m_guru_mapel.kd_mapel = m_mapel.kd ".
						"AND m_guru_mapel.kd_ruang = m_ruang.kd ".
						"AND m_guru_mapel.kd_guru = m_guru.kd ".
						"AND m_guru.kd_pegawai = m_pegawai.kd ".
						"AND m_pegawai.kd = '$kd1_session' ".
						"ORDER BY m_ruang.ruang ASC");
$rdty = mysql_fetch_assoc($qdty);
$tdty = mysql_num_rows($qdty);


echo '<table width="600" border="1" cellspacing="0" cellpadding="3">
<tr bgcolor="'.$warnaheader.'">
<td width="50"><strong>Tahun Pelajaran</strong></td>
<td width="10"><strong>Kelas</strong></td>
<td width="50"><strong>Ruang</strong></td>
<td><strong>Mata Pelajaran</strong></td>
<td width="50"><strong>Nilai</strong></td>
</tr>';

//nek gak null
if ($tdty != 0)
	{
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


		//nilai
		$dty_gurkd = nosql($rdty['mgkd']);
		$dty_kelkd = nosql($rdty['kd_kelas']);
		$dty_tapelkd = nosql($rdty['kd_tapel']);
		$dty_rukd = nosql($rdty['kd_ruang']);
		$dty_pelkd = nosql($rdty['kd_mapel']);
		$dty_ruang = balikin($rdty['ruang']);
		$dty_pel = balikin($rdty['xpel']);

		//tapel
		$qytapel = mysql_query("SELECT * FROM m_tapel ".
								"WHERE kd = '$dty_tapelkd'");
		$rytapel = mysql_fetch_assoc($qytapel);
		$ytapel_thn1 = nosql($rytapel['tahun1']);
		$ytapel_thn2 = nosql($rytapel['tahun2']);


		//kelas
		$qykel = mysql_query("SELECT * FROM m_kelas ".
								"WHERE kd = '$dty_kelkd'");
		$rykel = mysql_fetch_assoc($qykel);
		$ykel_kelas = nosql($rykel['kelas']);





		echo "<tr valign=\"top\" bgcolor=\"$warna\" onmouseover=\"this.bgColor='$warnaover';\" onmouseout=\"this.bgColor='$warna';\">";
		echo '<td>'.$ytapel_thn1.'/'.$ytapel_thn2.'</td>
		<td>'.$ykel_kelas.'</td>
		<td>'.$dty_ruang.'</td>
		<td>'.$dty_pel.'</td>
		<td>
		<a href="ajar/nilai.php?tapelkd='.$dty_tapelkd.'&kelkd='.$dty_kelkd.'&rukd='.$dty_rukd.'&mapelkd='.$dty_pelkd.'"
		title="Kelas = '.$ykel_kelas.', Ruang = '.$dty_ruang.', Pelajaran = '.$dty_pel.'">
		<img src="'.$sumber.'/img/edit.gif" width="16" height="16" border="0"></a>
		</td>
		</tr>';
		}
	while ($rdty = mysql_fetch_assoc($qdty));
	}

echo '</table>
<br><br><br>


<td valign="middle" align="center">
<p>
Anda Berada di <font color="blue"><strong>GURU AREA</strong></font>
</p>
<p><em>{Harap Dikelola Dengan Baik.)</em></p>
<p>
<img src="'.$sumber.'/img/linux.gif" width="300" height="130" title="Bravo Freedom Software based Open Source...!!">
</p>
<p>&nbsp;</p>
</td>
</tr>
</table>';
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//isi
$isi = ob_get_contents();
ob_end_clean();

require("../inc/niltpl.php");



//diskonek
xfree($qbw);
xclose($koneksi);
exit();
?>