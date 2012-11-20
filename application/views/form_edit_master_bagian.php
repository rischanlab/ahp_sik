	<script type="text/javascript" src="<?php echo base_url() ?>js/niceforms.js"></script>
	<h2>Edit Kriteria</h2>
	<div>
	<?php echo validation_errors(); ?>
	<?php echo form_open(uri_string(), 'class ="niceform"');?>						
				<a href="<?php echo base_url()?>index.php/master_kriteria">Kembali ke master kriteria</a>
                    <dl>
                        <dt><label for="jabatan">Nama Bagian : </label></dt>
                        <?php if(set_value('bagian')!='') $bagian = set_value('bagian')?>
						<dd><?php echo form_input(array('name'=>'bagian', 'id'=>'bagian' ,'size'=>'54','type'=>'text', 'maxlength'=>'255', 'value'=>$bagian)); ?></dd>
                    </dl>
					<dl>
                        <dt><label for="jabatan">Nilai Minimum : </label></dt>
                        <?php if(set_value('nilai_minimum')!='') $nilai_minimum = set_value('nilai_minimum')?>
						<dd><?php echo form_input(array('name'=>'nilai_minimum', 'id'=>'nilai_minimum' ,'size'=>'54','type'=>'text', 'maxlength'=>'255', 'value'=>$nilai_minimum)); ?></dd>
                    </dl>
                    <dl class="submit">
						<dd><input type="submit" name="submit" id="submit" value="Submit" /></dd>
                    </dl>
		<?php echo form_close();?>
	</div>
