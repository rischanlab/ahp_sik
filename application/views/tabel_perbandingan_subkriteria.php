<script type="text/javascript" src="<?php echo base_url() ?>js/niceforms.js"></script>
<h2>Prioritas Subkriteria dari Kriteria <?php echo $nama_kriteria;?></h2>


<div>
	<a href="<?php echo base_url()?>index.php/ahp_subkriteria">Kembali<br /></a>
	<?php 
		echo validation_errors(); 
		$i = 0;
		foreach($result_subkriteria as $row)
		{
			$id_subkriteria[$i] = $row->SUBKRITERIA_ID;  
			$i++;
		}
		$id_subkriteria['kriteria_id'] = $kriteria_id;
	?>
	<?php echo form_open('ahp_subkriteria/process1/'.$kriteria_id, 'class ="niceform"',$id_subkriteria);?>
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
			$i=0;
			$l = 0;
			$m = $jumlah_subkriteria;
			$b=0;
			foreach($result_subkriteria as $row)
			{
        
				echo '<tr>';
				echo '<td>';
				echo $row->NAMA_SUBKRITERIA;
				echo '</td>';
				
				for($k=0;$k<$l;$k++){
					echo '<td> - </td>';
				}
				for($j=0;$j<$m;$j++) {
					if($j==0){
						echo '<td>';
						echo '1';
						echo '</td>';
						
					}else{							
						$bobot_dipilih = 0; if(set_value('bobot'.$b)!=0) $bobot_dipilih = set_value('bobot'.$b);
						echo '<td>';
						echo form_dropdown('bobot'.$b, $bobot, $bobot_dipilih, 'size="0"');
						echo '</td>';
						$b++;
					}
				}
				$l++;
				$m--;
				echo '</tr>';
				$i++;
			} ?>
			<input type="hidden" name="max_bobot" value="<?php echo $b;?>" />             	
    </tbody>
</table>
	<dl class="submit">
		<dd><input type="submit" name="submit" id="submit" value="Hitung" /></dd>
    </dl>
	<?php echo form_close();?>
</div>
