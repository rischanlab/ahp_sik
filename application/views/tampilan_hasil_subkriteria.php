<script type="text/javascript" src="<?php echo base_url() ?>js/niceforms.js"></script>
<h2>Hasil Perhitungan</h2>
<a href="<?=base_url()?>master_subkriteria/grid/<?=$kriteria_id?>" class="bt_blue"><span class="bt_blue_lft"></span><strong>Daftar Subprioritas Subkriteria</strong><span class="bt_blue_r"></span></a>	
<br />
<div>
	<center><p><h4>Matriks Perbandingan</h4></p></center>
	<br />
	<table id="rounded-corner" summary="2007 Major IT Companies' Profit">
    <thead>
    	<tr>
			<th></th>
			<?php foreach($result_subkriteria as $row) {?>
				<th><?php echo $row->NAMA_SUBKRITERIA;?></th>
			<?}?>       	
			<th>Jumlah</th>
        </tr>
    </thead>
    <tbody>
        <?php	
			//for($i=0;$i<$jumlah_kriteria;$i++)
			$i = 0;
			foreach($result_subkriteria as $row)
			{
				//$j = 0;
				echo '<tr>';
				echo '<td>';
				echo $row->NAMA_SUBKRITERIA;
				echo '</td>';
				for($j=0;$j<$jumlah_subkriteria;$j++)
				{
					echo '<td>';
					echo $array1[$i][$j];
					echo '</td>';
				}
				echo '<td>'.$jumlah_per_baris[$i].'</td>';
				echo '</tr>';
				$i++;
			} ?>          	
    </tbody>
</table>
<center><p><h4>Matriks Nilai Kriteria</h4></p></center>
	<br />
	<table id="rounded-corner" summary="2007 Major IT Companies' Profit">
    <thead>
    	<tr>
			<th></th>
			<?php foreach($result_subkriteria as $row) {?>
				<th><?php echo $row->NAMA_SUBKRITERIA;?></th>
			<?}?>       	
        </tr>
    </thead>
    <tbody>
        <?php	
			//for($i=0;$i<$jumlah_kriteria;$i++)
			$i2 = 0;
			foreach($result_subkriteria as $row)
			{
				//$j = 0;
				echo '<tr>';
				echo '<td>';
				echo $row->NAMA_SUBKRITERIA;
				echo '</td>';
				for($j2=0;$j2<$jumlah_subkriteria;$j2++)
				{
					echo '<td>';
					echo $array2[$i2][$j2];
					echo '</td>';
				}
				echo '</tr>';
				$i2++;
			} 
			echo '<tr>';
			echo '<td>Jumlah</td>';
			for($c=0;$c<$jumlah_subkriteria;$c++)
			{
				echo '<td>'.$jumlah_per_baris2[$c].'</td>';
			}
			echo '</tr>';
			echo '<tr>';
			echo '<td>Prioritas</td>';
			for($c2=0;$c2<$jumlah_subkriteria;$c2++)
			{
				echo '<td>'.$prioritas[$c2].'</td>';
			}
			echo '</tr>';
			echo '<tr>';
			echo '<td>Subrioritas</td>';
			for($c2=0;$c2<$jumlah_subkriteria;$c2++)
			{
				echo '<td>'.$subprioritas[$c2].'</td>';
			}
			echo '</tr>';
			?>          	
    </tbody>
</table>
<center><p><h4>Matriks Penjumlahan Tiap Baris</h4></p></center>
	<br />
	<table id="rounded-corner" summary="2007 Major IT Companies' Profit">
    <thead>
    	<tr>
			<th></th>
			<?php foreach($result_subkriteria as $row) {?>
				<th><?php echo $row->NAMA_SUBKRITERIA;?></th>
			<?}?>       	
        </tr>
    </thead>
    <tbody>
        <?php	
			//for($i=0;$i<$jumlah_kriteria;$i++)
			$i3 = 0;
			foreach($result_subkriteria as $row)
			{
				//$j = 0;
				echo '<tr>';
				echo '<td>';
				echo $row->NAMA_SUBKRITERIA;
				echo '</td>';
				for($j3=0;$j3<$jumlah_subkriteria;$j3++)
				{
					echo '<td>';
					echo $array3[$i3][$j3];
					echo '</td>';
				}
				$i3++;
			} 
			echo '<tr>';
			echo '<td>Jumlah</td>';
			for($c3=0;$c3<$jumlah_subkriteria;$c3++)
			{
				echo '<td>'.$jumlah_per_baris3[$c3].'</td>';
			}
			echo '</tr>';
			?>          	
    </tbody>
</table>
<center><p><h4>Rasio Konsistensi</h4></p></center>
	<br />
	<table id="rounded-corner" summary="2007 Major IT Companies' Profit">
    <thead>
    	<tr>
			<th></th>
			<th>Jumlah</th>
			<th>Prioritas</th>  	
			<th>Hasil</th>  	
        </tr>
    </thead>
    <tbody>
        <?php	
			//for($i=0;$i<$jumlah_kriteria;$i++)
			$i4 = 0;
			foreach($result_subkriteria as $row)
			{
				//$j = 0;
				echo '<tr>';
				echo '<td>';
				echo $row->NAMA_SUBKRITERIA;
				echo '</td>';
				
				echo '<td>'.$jumlah_per_baris3[$i4].'</td>';
				echo '<td>'.$prioritas[$i4].'</td>';
				$sum = $jumlah_per_baris3[$i4] + $prioritas[$i4];
				echo '<td>'.$sum.'</td>';
				echo '</tr>';
				$i4++;
			} 
			
			?>          	
    </tbody>
</table>
<br />
<table id="rounded-corner" summary="2007 Major IT Companies' Profit">
    <tbody>
        <?php	
			echo '<tr>';
			echo '<td>Lamda Max :</td>';
			echo '<td>'.$alpha_max.'</td>';
			echo '</tr>';
			echo '<tr>';
			echo '<td>Rasio Index (CI) :</td>';
			echo '<td>'.$consistency_index.'</td>';
			echo '</tr>';
			echo '<tr>';
			echo '<td>Rasio Konsistensi (CR) :</td>';
			echo '<td>'.$consistency_ratio.'</td>';
			echo '</tr>';
			echo '<tr>';
			echo '<td>Keterangan :</td>';
			echo '<td>'.$keterangan.'</td>';
			echo '</tr>';
			?>          	
    </tbody>
</table>
<a href="<?=base_url()?>master_subkriteria/grid/<?=$kriteria_id?>" class="bt_blue"><span class="bt_blue_lft"></span><strong>Daftar Subprioritas Subkriteria</strong><span class="bt_blue_r"></span></a>     
</div>
