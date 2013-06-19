	<script type="text/javascript" src="<?php echo base_url() ?>js/niceforms.js"></script>
	<h2>Tambah Kriteria</h2>
	<div>
	<?php echo validation_errors(); ?>
	<?php echo form_open('master_kriteria/add_process', 'class ="niceform"');?>						
				<a href="<?php echo base_url()?>index.php/master_kriteria">Kembali ke master kriteria</a>
                    <dl>
                        <dt><label for="jabatan">Nama Kriteria : </label></dt>
                        <?php $kriteria = ''; if(set_value('kriteria')!='') $kriteria = set_value('kriteria')?>
						<dd><?php echo form_input(array('name'=>'kriteria', 'id'=>'kriteria' ,'size'=>'54','type'=>'text', 'maxlength'=>'255', 'value'=>$kriteria)); ?></dd>
                    </dl>
                    <dl class="submit">
						<dd><input type="submit" name="submit" id="submit" value="Submit" /></dd>
                    </dl>
		<?php echo form_close();?>
	</div>
