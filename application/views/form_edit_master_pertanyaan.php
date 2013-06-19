	<script type="text/javascript" src="<?php echo base_url() ?>js/niceforms.js"></script>
	<h2>Edit Pertanyaan</h2>
	<div>
	<?php echo validation_errors(); ?>
	<?php echo form_open(uri_string(), 'class ="niceform"');?>						
				<a href="<?php echo base_url()?>index.php/master_pertanyaan">Kembali ke master pertanyaan</a>
                    <dl>
                        <dt><label for="role">Bagian : </label></dt>
                        <dd>
                        <?php
							if(set_value('bagian')!=0) $bagian_dipilih = set_value('bagian');
							echo form_dropdown('bagian', $bagian, $bagian_dipilih, 'size="1"');
						?>
						</dd>
                    </dl>
					<dl>
                        <dt><label for="role">Kriteria : </label></dt>
                        <dd>
                        <?php
							if(set_value('kriteria')!=0) $kriteria_dipilih = set_value('kriteria');
							echo form_dropdown('kriteria', $kriteria, $kriteria_dipilih, 'size="1"');
						?>
						</dd>
                    </dl>
                    <dl>
                        <dt><label for="jabatan">Nama Pertanyaan : </label></dt>
                        <?php if(set_value('nama_pertanyaan')!='') $nama_pertanyaan = set_value('nama_pertanyaan')?>
						<dd><?php echo form_input(array('name'=>'nama_pertanyaan', 'id'=>'nama_pertanyaan' ,'size'=>'54','type'=>'text', 'maxlength'=>'255', 'value'=>$nama_pertanyaan)); ?></dd>
                    </dl>
                    <dl class="submit">
						<dd><input type="submit" name="submit" id="submit" value="Submit" /></dd>
                    </dl>
		<?php echo form_close();?>
	</div>
