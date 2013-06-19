<script type="text/javascript" src="<?php echo base_url() ?>js/niceforms.js"></script>
<h2>Prioritas Subkriteria</h2>
<div>
	<?php 
		echo validation_errors(); 
	?>
	<?php echo form_open('ahp_subkriteria/process0', 'class ="niceform"');?>	
	<dl>
       <dt><label for="role">Kriteria : </label></dt>
		<dd>
			<?php
				$kriteria_dipilih = 0; if(set_value('kriteria')!=0) $kriteria_dipilih = set_value('kriteria');
				echo form_dropdown('kriteria', $kriteria, $kriteria_dipilih, 'size="1"');
			?>
		</dd>
    </dl>					
	<dl class="submit">
		<dd><input type="submit" name="submit" id="submit" value="Proses" /></dd>
    </dl>
	<?php echo form_close();?>
</div>
