<script type="text/javascript" src="<?php echo base_url() ?>js/niceforms.js"></script>
<h2>Prioritas Kriteria</h2>
<div>
	<?php 
		echo validation_errors(); 
		$i = 0;
		foreach($result_kriteria as $row)
		{
			$id_kriteria[$i] = $row->KRITERIA_ID;  
			$i++;
		}
	?>
	<?php echo form_open('ahp_kriteria/process1', 'class ="niceform"',$id_kriteria);?>						
	<table id="rounded-corner" summary="2007 Major IT Companies' Profit">
    <thead>
    	<tr>
			<th></th>
			<?php foreach($result_kriteria as $row) {?>
				<th><?php echo $row->NAMA_KRITERIA;?></th>
			<?}?>       	
        </tr>
    </thead>
    <tbody>
        <?php	
			//for($i=0;$i<$jumlah_kriteria;$i++)
			$i=0;
			$l = 0;
			$m = $jumlah_kriteria;
			$b=0;
			foreach($result_kriteria as $row)
			{
        
				echo '<tr>';
				echo '<td>';
				echo $row->NAMA_KRITERIA;
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
