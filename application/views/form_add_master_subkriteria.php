	<script type="text/javascript" src="<?php echo base_url() ?>js/niceforms.js"></script>
	<h2>Tambah Subkriteria untuk Kriteria <?=$nama_kriteria?></h2>
	<div>
	<?php echo validation_errors(); ?>
	<?php echo form_open('master_subkriteria/add_process/'.$kriteria_id, 'class ="niceform"');?>						
				<a href="<?php echo base_url()?>index.php/master_subkriteria/grid/<?php echo $kriteria_id;?>">Kembali ke master subkriteria</a>
                    <dl>
                        <dt><label for="role">Kriteria : </label></dt>
                        <dd>
                        <?php
							if(set_value('kriteria')!=0) $kriteria_dipilih = set_value('kriteria');
							echo form_dropdown('kriteria', $kriteria, $kriteria_id, 'size="1"');
						?>
						</dd>
                    </dl>
                    <dl>
                        <dt><label for="jabatan">Nama Subkriteria : </label></dt>
                        <?php $subkriteria = ''; if(set_value('subkriteria')!='') $subkriteria = set_value('subkriteria')?>
						<dd><?php echo form_input(array('name'=>'subkriteria', 'id'=>'subkriteria' ,'size'=>'54','type'=>'text', 'maxlength'=>'255', 'value'=>$subkriteria)); ?></dd>
                    </dl>
                    <dl class="submit">
						<dd><input type="submit" name="submit" id="submit" value="Submit" /></dd>
                    </dl>
		<?php echo form_close();?>
	</div>
